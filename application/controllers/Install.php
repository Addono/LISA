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

    public function index() {
        $this->load->database();
        $this->load->dbforge();
        $this->load->helper('tables');
        $this->load->model('ModelFrame');

        $this->addTable(MODEL_VERSIONS_TABLE, $this->getModelVersionTable());

        $models = new RecursiveDirectoryIterator('./application/models', FilesystemIterator::SKIP_DOTS);
        $queue = new SplQueue();

        // Add each model and its version to the queue.
        $models->rewind();
        while($models->valid()) {
            $fileName = $models->getFilename();
            $len = strlen(MODELS_FILE_EXTENTION);
            if(
                !substr_compare($fileName, MODELS_FILE_EXTENTION, -$len, $len, TRUE) // Check if the model has the right file type.
                    &&
                $fileName !== 'ModelFrame.php'
            ) {
                $modelName = substr($fileName, 0, -$len);
                $this->load->model($modelName);
                $model = $this->$modelName;

                $res = $this->db
                    ->where(['model_name' => $modelName])
                    ->get(MODEL_VERSIONS_TABLE);

                // Add new model entry in the version database, if it didn't exist yet.
                if ($res->num_rows() == 0) {
                    $this->db->insert(
                        MODEL_VERSIONS_TABLE, [
                        'model_name' => $modelName,
                        'table_name' => $model->name(),
                    ]);

                    echo 'New model ' . $modelName . ' found.<br>';

                    $version = 0;
                } else {
                    $version = $res->row()->version;
                }

                $queue->enqueue($model);

                echo "<i>Current version of " . $modelName . " is " . $version . ".</i><br>";
            }

            $models->next();
        }

        /*
         * Tries to update all models in the queue. If a model fails to update it will be added to the end of the
         * queue. If non of the models in the queue manages to update any further while there still exists an model
         * which isn't at the last version, then a dependency problem has occurred.
         */
        $unchanged = 0; // Tracks how many models consequently tried to update without making any improvement.
        while(!$queue->isEmpty() && $unchanged < $queue->count()) {
            $model = $queue->dequeue();

            echo 'Updating '.$model->name().'.<br>';

            $oldVersion = $this->getModelVersion($model);

            // Install all versions currently not yet installed.
            $finished = $this->installUpdate($model);

            $newVersion = $this->getModelVersion($model);

            if ($finished !== TRUE) {
                $queue->enqueue($model);

                if ($oldVersion == $newVersion) {
                    $unchanged++;
                } else {
                    $unchanged = 0;
                }
            }
        }

        if (!$queue->isEmpty()) {
            echo '<b>DEPENDENCY CANNOT BE SOLVED!</b><br>';
        } else {
            echo '<b>Update successful</b><br>';
        }
    }

    private function installUpdate($model) {
        $version = $this->getModelVersion($model) + 1;
        $functionName = 'r'.$version;
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

            echo ' - Installed r' . $version . '.<br>';

            return $this->installUpdate($model);
        } else {
            echo '<i> - ' . get_class($model) . ' is up-to-date.</i><br>';

            return true;
        }
    }

    private function areDependenciesMet($dependencies) {
        $success = true;

        foreach ($dependencies as $modelName => $version) {
            $currentVersion = $this->getModelVersion($modelName);

            if ($version > $currentVersion) {
                echo '<i> - Dependency on '.$modelName.' version '.$version.' not met!</i><br>';

                $success = false;
            }
        }

        return $success;
    }

    /**
     * Gets the current version of the model from the database.
     *
     * @param ModelFrame|string Either an instance of the model class or the name of the model itself.
     * @return int The version of the model.
     */
    private function getModelVersion($model) {
        if (is_string($model)) {
            $modelName = $model;
        } else {
            $modelName = get_class($model);
        }

        return $this->db
            ->where(['model_name' => $modelName])
            ->get(MODEL_VERSIONS_TABLE)
            ->row()
            ->version;
    }

    private function updateModelVersion($model, $version) {
        return $this->db
            ->where(['model_name' => get_class($model)])
            ->update(MODEL_VERSIONS_TABLE, ['version' => $version]);
    }

    public static function getTableName($modelName) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelName));
    }

    private function addTable($name, $fields) {
        if ($this->db->table_exists($name)) {
            echo 'Table ' . $name . ' already exists.<br>';
        } else {
            $addedFields['add'] = $fields;
            $this->alterTable($name, $addedFields);
        }
    }

    private function alterTable($name, $fields, $attr = ['ENGINE' => 'InnoDB']) {
        // Manage all types.
        foreach ($fields as $type => $field) {
            switch ($type) {
                case 'add':
                    if(!$this->db->table_exists($name)) {
                        $this->dbforge->add_field($field);
                        if ($this->dbforge->create_table($name, TRUE, $attr)) {
                            echo "Successfully added table '$name'<br>";
                        } else {
                            echo "Failed adding table '$name'<br>";
                            exit;
                        }
                    } else {
                        $this->dbforge->add_column($name, $field);
                    }
                    break;
                case 'delete':
                    // Check if we should drop a table or column(s) of a table.
                    if (is_array($field)) {
                        $this->dbforge->drop_column($name, $fields['delete']);
                    } else {
                        $this->dbforge->drop_table($name);
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