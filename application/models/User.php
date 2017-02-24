<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */

class User extends CI_Model {

    private $tableName;

    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->database();
        $this->load->helper('tables');

        $this->tableName = Install::getTableName(self::class);
    }

    public function r1() {
        return [
            'add' => [
                'role' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE,
            ],
            ]
        ];
    }
}