<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Users extends CI_Model {

    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->database();
        $this->load->helper('tables');
    }

    /**
     * Adds a new user to the database.
     * @param $username
     * @param $password
     * @param $role
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     */
    public function insertUser($username, $password, $role = 'user') {
        return $this->db->insert(
            USERS_TABLE,
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
    public function checkCredentials($username, $password) {
        $result = $this->db
            ->where(['username' => $username])
            ->get(USERS_TABLE)
            ->row();
        if(isset($result->password)) {
            return password_verify($password, $result->password);
        } else {
            return false;
        }
    }

    public function userExists($username) {
        $result = $this->db
            ->where(['username' => $username])
            ->count_all_results(USERS_TABLE);
        return $result !== 0;
    }

    public function userRole($username) {
        return $this->db
            ->where(['username' => $username])
            ->get(USERS_TABLE)
            ->row()
            ->role;
    }

    public function getUsernames() {
        return $this->db
            ->select(['username'])
            ->where(['role' => 'user'])
            ->get(USERS_TABLE)
            ->result();
    }

    public function getId($username) {
        return $this->db
            ->where(['username' => $username])
            ->get(USERS_TABLE)
            ->row()
            ->id;
    }
}