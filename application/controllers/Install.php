<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-1-2017
 */

class Install extends CI_Controller {

    public function index() {
        $this->load->database();
        $this->load->dbforge();
        $this->load->helper('tables');

        $this->addTable(USERS_TABLE, $this->getUsersTableFields());
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

    private function getUsersTableFields() {
        return [
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE,
            ],
            'password' => [
                'type' => 'TEXT',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'ENUM("user","admin")',
                'default' => 'user',
            ],
        ];
    }
}