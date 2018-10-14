<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-1-2017
 */

/**
 * Class Install
 * @property  CI_DB_query_builder   $db
 * @property  CI_DB_forge           $dbforge
 */
class Install extends CI_Controller {

    const INITIAL_MODEL_VERSION = 0;
    const MODEL_FRAME_FILE_NAME = 'ModelFrame.php';

    const ID_TYPE = 'INT';
    const ID_CONSTRAINT = 11;

    public function index() {
        $this->load->database();
        $this->load->dbforge();
        $this->load->helper('tables');

        $this->load->model('ModelFrame');


        $this->addTable(MODEL_VERSIONS_TABLE, $this->getModelVersionTable());

        // Build the model queue.
        $models = new RecursiveDirectoryIterator('./application/models', FilesystemIterator::SKIP_DOTS);
        $queue = $this->buildModelQueue($models);

        // Attempt to update all models.
        $this->updateModels($queue);
    }

    /**
     * Creates a queue of all models.
     *
     * @param $models RecursiveDirectoryIterator The models to be added to the queue.
     * @return SplQueue A queue containing all models.
     */
    private function buildModelQueue($models)
    {
        $queue = new SplQueue();

        // Add each model and its version to the queue.
        $models->rewind();
        while ($models->valid()) {
            $fileName = $models->getFilename();
            $len = strlen(MODELS_FILE_EXTENTION);
            if (
                !substr_compare($fileName, MODELS_FILE_EXTENTION, -$len, $len, TRUE) // Check if the model has the right file type.
                &&
                $fileName !== self::MODEL_FRAME_FILE_NAME // Check if the model isn't the ModelFrame.
            ) {
                // Extract the model name from the file name and load this model.
                $modelName = substr($fileName, 0, -$len);
                $this->load->model($modelName);
                $model = $this->$modelName;

                $version = $this->getModelVersion($model);

                // Add new model entry in the version database, if it didn't exist yet.
                if ($version === null) {
                    $this->addModelVersion($model, self::INITIAL_MODEL_VERSION);

                    echo 'New model ' . $modelName . ' found.<br>';

                    $version = self::INITIAL_MODEL_VERSION;
                }

                // Add the model to the queue.
                $queue->enqueue($model);

                echo "<i>Current version of " . $modelName . " is " . $version . ".</i><br>";
            }

            $models->next();
        }

        return $queue;
    }

    /**
     * Tries to update all models in the queue.
     *
     * @param $queue SplQueue The queue of all models whom should be updated.
     * @return bool False if an unsolvable dependency occurred, else true.
     */
    private function updateModels($queue)
    {
        /*
         * If a model fails to update it will be added to the end of the queue. If non of the models in the queue
         * manages to update any further while there still exists an model which isn't at the last version, then an
         * unsolvable dependency problem has occurred.
         */
        $unchanged = 0; // Tracks how many models consequently tried to update without making any improvement.
        while (!$queue->isEmpty() && $unchanged <= $queue->count()) {
            $model = $queue->dequeue();

            echo 'Updating ' . $model->name() . '.<br>';

            $oldVersion = $this->getModelVersion($model);

            // Install all versions currently not yet installed.
            $finished = $this->installUpdate($model);

            $newVersion = $this->getModelVersion($model);

            // Add the model to the back of the queue while it isn't finished updating.
            if ($finished !== TRUE) {
                $queue->enqueue($model);
            }

            // Reset unchanged if the model managed to progress (either finished or increased in version), else increase
            // the unchanged counter.
            if ($oldVersion != $newVersion || $finished) {
                $unchanged = 0;
            } else {
                $unchanged++;
            }
        }

        // Check if all models in the queue where successfully updated. If not this means that an (dependency) issue occurred.
        if ($queue->isEmpty()) {
            echo '<b>Update successful</b><br>';
            return true;
        } else {
            echo '<b>DEPENDENCY CANNOT BE SOLVED!</b><br>Unfinished models are:';

            // Show the names of all remaining models.
            while (!$queue->isEmpty()) {
                $model = $queue->dequeue();

                echo ' - ' . $model->name() . '<br>';
            }

            return false;
        }
    }

    /**
     * Attempts to update a model as far as possible.
     *
     * @param $model
     * @return bool True if it reached or is on the latest version, false otherwise.
     */
    private function installUpdate($model) {
        $version = $this->getModelVersion($model) + 1;
        $functionName = 'v'.$version;
        $tableName = $model->name();

        if(method_exists($model, $functionName)) {
            $alterations = $model->$functionName();

            // Check if it has any dependencies.
            if ($alterations !== null && key_exists('requires', $alterations)) {
                $canProceed = $this->areDependenciesMet($alterations['requires']);

                if (!$canProceed) {
                    return false;
                }
            }

            $this->db->trans_start();
                if (is_array($alterations)) {
                    $this->alterTable($tableName, $alterations);
                }

                $this->updateModelVersion($model, $version);
            $this->db->trans_complete();

            echo ' - Installed v' . $version . '.<br>';

            return $this->installUpdate($model); // Recursively try to update.
        } else {
            echo '<i> - ' . get_class($model) . ' is up-to-date.</i><br>';

            return true;
        }
    }

    /**
     * Checks is all dependencies are met.
     *
     * @param $dependencies array The dependencies to be met.
     * @return bool True if all dependencies are met, false otherwise.
     */
    private function areDependenciesMet(array $dependencies) {
        $success = true;

        foreach ($dependencies as $modelName => $version) {
            $currentVersion = $this->getModelVersion($modelName);

            if ($version > $currentVersion) {
                echo '<i> - Dependency on '.$modelName.' (v'.$version.') not met!</i><br>';

                $success = false;
            }
        }

        return $success;
    }

    /**
     * Updates the model version in the database.
     *
     * @param $model ModelFrame
     * @param $version int
     * @return True on success, else false.
     */
    private function addModelVersion($model, $version) {
        return $this->db->insert(
            MODEL_VERSIONS_TABLE, [
            'model_name' => get_class($model),
            'table_name' => $model->name(),
            'version' => $version,
        ]);
    }

    /**
     * Gets the current version of the model from the database.
     *
     * @param ModelFrame|string Either an instance of the model class or the name of the model itself.
     * @return int|null The version of the model if it exists, else null.
     */
    private function getModelVersion($model) {
        if (is_string($model)) {
            $modelName = $model;
        } else {
            $modelName = get_class($model);
        }

        $row = $this->db
            ->where(['model_name' => $modelName])
            ->get(MODEL_VERSIONS_TABLE)
            ->row();

        if ($row === null) {
            return null;
        } else {
            return $row->version;
        }
    }

    /**
     * Updates the version of a model in the database.
     *
     * @param $model ModelFrame
     * @param $version int
     * @return bool True on success, else false.
     */
    private function updateModelVersion($model, $version) {
        return $this->db
            ->where(['model_name' => get_class($model)])
            ->update(MODEL_VERSIONS_TABLE, ['version' => $version]);
    }

    /**
     * Generates the name of the table corresponding with a model.
     *
     * @param $modelName string
     * @return string
     */
    public static function getTableName($modelName, $tablePrefix) {
        // Check if the model name already contains the prefix.
        if (stripos($modelName, $tablePrefix) === 0) {
            $tablePrefix = '';
        }

        return $tablePrefix . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelName));
    }

    /**
     * Adds a table to the database.
     *
     * @param $name string The name of the array.
     * @param array $fields
     * @return bool False if the table already existed, true otherwise.
     */
    private function addTable($name, $fields) {
        if ($this->db->table_exists($name)) {
            echo 'Table ' . $name . ' already exists.<br>';
            return false;
        } else {
            $addedFields['add'] = $fields;
            $this->alterTable($name, $addedFields);
            return true;
        }
    }


    /**
     * Makes changes to a table.
     *
     * @param string $tableName The name of the table.
     * @param array $actions The actions to be executed.
     * @param array $attr
     */
    private function alterTable($tableName, array $actions, array $attr = ['ENGINE' => 'InnoDB']) {
        foreach ($actions as $type => $action) {
            switch ($type) {
                case 'add':
                    if($this->db->table_exists($tableName)) {
                        $tableNameWithoutPrefix = substr($tableName, strlen($this->db->dbprefix(null)));
                        $this->dbforge->add_column($tableNameWithoutPrefix, $action);
                    } else {
                        $keyType = [
                            'type' => self::ID_TYPE,
                            'constraint' => self::ID_CONSTRAINT,
                            'unsigned' => true,
                            'auto_increment' => true,
                        ];

                        // Check if there are any keys which should be primary key.
                        foreach ($action as $name => $properties) {

                            switch ($properties['type']) {
                                case 'primary':
                                    $this->dbforge->add_key($name, true);
                                    $this->dbforge->add_field([$name => $keyType]);
                                    $keyType['auto_increment'] = false; // Ensure that only the first field will auto increment
                                    break;
                                case 'foreign':
                                    // Add a new key as foreign key.
                                    $this->dbforge->add_field([$name => $keyType]);
                                    $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (' . $name . ') REFERENCES ' . $properties['table'] . '(' . $properties['field'] . ')');
                                    $keyType['auto_increment'] = false; // Ensure that only the first field will auto increment.
                                    break;
                                case 'foreign|primary':
                                case 'primary|foreign':
                                    $this->dbforge->add_key($name, true);
                                    $this->dbforge->add_field([$name => $keyType]);
                                    $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (' . $name . ') REFERENCES ' . $properties['table'] . '(' . $properties['field'] . ')');
                                    $keyType['auto_increment'] = false; // Ensure that only the first field will auto increment.
                                    break;
                                default:
                                    $this->dbforge->add_field([$name => $properties]);
                                    break;
                            }
                        }

                        // Remove the table prefix if it has one, since create_table will add it.
                        $createTableName = substr(self::getTableName($tableName, $this->db->dbprefix), strlen($this->db->dbprefix));

                        if ($this->dbforge->create_table($createTableName, TRUE, $attr)) {
                            echo " - Added table '$tableName'<br>";
                        } else {
                            echo "<b> - Failed adding table '$tableName'</b><br>";
                            exit;
                        }
                    }
                    break;
                case 'delete':
                    // Check if we should drop a table or column(s) of a table.
                    if (is_array($action)) {
                        $this->dbforge->drop_column($tableName, $actions['delete']);
                    } else {
                        $this->dbforge->drop_table($tableName);
                    }
                    break;
                case 'requires':
                    // Ignore these
                    break;
                default:
                    echo "<b>Invalid database forge command parsed</b>";
                    exit;

            }
        }
    }

    /**
     * Returns the structure of the model version table.
     *
     * @return array The structure of the model version table.
     */
    private function getModelVersionTable() {
        return [
            'model_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE,
            ],
            'version' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0,
            ],
            'table_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE,
            ],
        ];
    }
}