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

        $models->rewind();
        while($models->valid()) {
            $fileName = $models->getFilename();
            $len = strlen(MODELS_FILE_EXTENTION);
            if(
                !substr_compare($fileName, MODELS_FILE_EXTENTION, -$len, $len, TRUE)
                    &&
                $fileName !== 'ModelFrame'
            ) { // Check if the model has the right extention.
                $modelName = substr($fileName, 0, -$len);
                $tableName = getTableName($modelName);
                $this->load->model($modelName);

                $where = [
                    'model_name' => $modelName,
                    'table_name' => $tableName,
                ];

                $res = $this->db
                    ->where($where)
                    ->get(MODEL_VERSIONS_TABLE);

                // Add new model entry in the version database, if it didn't exist yet.
                if ($res->num_rows() == 0) {
                    $this->db
                        ->insert(
                            MODEL_VERSIONS_TABLE,
                            $where
                        );

                    echo 'New model ' . $modelName . ' found.<br>';

                    $version = 0;
                } else {
                    $version = $res->row()->version;
                }

                echo "<i>Current version of " . $modelName . " is " . $version . ".</i><br>";

                // Install all versions currently not yet installed.
                while (TRUE) {
                    $version++;
                    $functionName = 'r'.$version;

                    if(method_exists($this->$modelName, $functionName)) {
                        $this->db->trans_start();
                            $alterations = $this->$modelName->$functionName();
                            if (is_array($alterations)) {
                                $this->alterTable($tableName, $alterations);
                            }

                            $replace = array_merge($where, ['version' => $version]);

                            $this->db->replace(MODEL_VERSIONS_TABLE, $replace);
                        $this->db->trans_complete();

                        echo ' - Installed r' . $version . '.<br>';
                    } else {
                        echo '<b> - ' . $modelName . ' is up-to-date.</b><br>';
                        break;
                    }
                }
            }

            $models->next();
        }
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