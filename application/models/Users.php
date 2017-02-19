<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Users extends CI_Model {

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
                    'type' => 'ENUM("'.ROLE_USER.'","'.ROLE_ADMIN.'")',
                    'default' => 'user',
                ],
            ]
        ];
    }

    public function r2() {
        $this->addUser('admin', 'banana', 'admin');
    }

    /**
     * Adds a new user to the database.
     * @param $username
     * @param $password
     * @param $role
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     */
    public function addUser($username, $password, $role = 'user') {
        return $this->db->insert(
            $this->tableName,
            [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
            ]
        );
    }

    /**
     * Checks if a user matching the credentials is present in the database.
     * @param $username
     * @param $password
     * @return bool
     */
    public function checkUsernamePasswordCredentials($username, $password) {
        $userId = $this->getUserId($username);

        return $this->checkUserIdPasswordCredentials($userId, $password);
    }

    /**
     * Checks if a user matching the credentials is present in the database.
     * @param $userId
     * @param $password
     * @return bool
     */
    public function checkUserIdPasswordCredentials($userId, $password) {
        $result = $this->db
            ->where(['id' => $userId])
            ->get($this->tableName)
            ->row();
        if(isset($result->password)) {
            return password_verify($password, $result->password);
        } else {
            return false;
        }
    }

    public function usernameExists($username) {
        $result = $this->db
            ->where(['username' => $username])
            ->count_all_results($this->tableName);
        return $result !== 0;
    }

    public function userRole($userId) {
        return $this->db
            ->where(['id' => $userId])
            ->get($this->tableName)
            ->row()
            ->role;
    }

    public function getUsernames() {
        return $this->db
            ->select(['username'])
            ->where(['role' => 'user'])
            ->get($this->tableName)
            ->result();
    }

    public function getUsername($userId) {
        return $this->db
            ->where(['id' => $userId])
            ->get($this->tableName)
            ->row()
            ->username;
    }

    private function getUserId($username) {
        return $this->db
            ->where(['username' => $username])
            ->get($this->tableName)
            ->row()
            ->id;
    }
}