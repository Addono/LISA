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

        $this->addTable(MODEL_VERSIONS_TABLE, $this->getModelVersionTable());

        $models = new RecursiveDirectoryIterator('./application/models', FilesystemIterator::SKIP_DOTS);

        $models->rewind();
        while($models->valid()) {
            $fileName = $models->getFilename();
            $len = strlen(MODELS_FILE_EXTENTION);
            if(
                !substr_compare($fileName, MODELS_FILE_EXTENTION, -$len, $len, TRUE)
                    &&
                $fileName !== 'Install.php'
            ) {
                $modelName = substr($fileName, 0, -$len);
                $tableName = static::getTableName($modelName);
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

                echo "Current version of " . $modelName . " is " . $version . ".<br>";

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
                        echo ' - ' . $modelName . ' is up-to-date.';
                        break;
                    }
                }
            }

            $models->next();
        }
    }

    static function getTableName($modelName) {
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
        if($this->db->table_exists($name)) {
            echo "Table '$name' already exists.<br>";
            if (array_key_exists('add', $fields)) {
                $this->dbforge->add_column($name, $fields['add']);
            }
            if (array_key_exists('delete', $fields)) {
                if (is_array($fields['delete'])) {
                    $this->dbforge->drop_table($name);
                } else {
                    $this->dbforge->drop_column($name, $fields['delete']);
                }
            }
        } else {
            $this->dbforge->add_field($fields['add']);
            $this->dbforge->add_field('id');
            if($this->dbforge->create_table($name, TRUE, $attr)) {
                echo "Succesfully added table '$name'<br>";
            } else {
                echo "Failed adding table '$name'<br>";
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