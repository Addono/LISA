<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-1-2017
 */

class Install extends CI_Controller {

    private $usersTable = 'users_table';
    private $receiptsEntries = 'receipts_entries';

    public function index() {
        $this->load->database();
        $this->load->dbforge();

        $this->addTable($this->usersTable, $this->getUsersTableFields());
        $this->addTable($this->receiptsEntries, $this->getReceiptEntriesTable());
    }

    private function addTable($name, $fields, $attr = ['ENGINE' => 'InnoDB']) {
        if($this->db->table_exists($name)) {
            echo "Table '$name' already exists.<br>";
        } else {
            $this->dbforge->add_field($fields);
            $this->dbforge->add_field('id');
            if($this->dbforge->create_table($name, TRUE, $attr)) {
                echo "Succesfully added table '$name'<br>";
            } else {
                echo "Failed adding table '$name'<br>";
                exit;
            }
        }
    }

    private function getReceiptEntriesTable() {
        return [
            'group_id' => [
                'type' => 'INT',
                'constraint' => '9',
            ],
            'resource0' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'resource1' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'resource2' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'specialty' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'score' => [
                'type' => 'FLOAT',
            ],
            'country' => [
                'type' => 'ENUM("netherlands","belgium","france","germany")',
            ],
            'FOREIGN KEY (group_id) REFERENCES '.$this->usersTable.'(id)',
        ];
    }

    private function getUsersTableFields() {
        return [
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE,
            ],
            'pin' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
            ],
            'role' => [
                'type' => 'ENUM("user","admin")',
                'default' => 'user',
            ],
        ];
    }
}