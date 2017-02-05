<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Users extends CI_Model {
    private $usersTable = 'users_table';
    private $receiptsTable = 'receipts_entries';

    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->database();
    }

    /**
     * Adds a new user to the database.
     * @param $username
     * @param $role
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     */
    public function insertUser($username, $role) {
        $data = [
            'username' => $username,
            'pin' => generatePin(),
            'role' => $role,
        ];
        if($this->db->insert_string($this->usersTable, $data)) {
            return $data['pin'];
        } else {
            return false;
        }
    }

    /**
     * Checks if a user matching the credentials is present in the database.
     * @param $username
     * @param $pin
     * @return bool
     */
    public function checkCredentials($username, $pin) {
        $this->db->where([
            'username' => $username,
            'pin' => $pin,
        ]);
        return $this->db->count_all_results($this->usersTable) !== 0;
    }

    public function userExists($username) {
        $this->db->where(['username' => $username]);
        return $this->db->count_all_results($this->usersTable) !== 0;
    }

    public function userRole($username) {
        return $this->db
            ->where(['username' => $username])
            ->get($this->usersTable)
            ->row()
            ->role;
    }

    public function getUsernames() {
        return $this->db
            ->select(['username'])
            ->where(['role' => 'user'])
            ->get($this->usersTable)
            ->result();
    }

    public function getId($username) {
        return $this->db
            ->where(['username' => $username])
            ->get($this->usersTable)
            ->row()
            ->id;
    }

    /**
     * Resets the pin of a user.
     * @param $username
     * @return bool|void
     */
    public function resetPin($username) {
        $pin = $this->generatePin();
        $success = $this->db->update(
            $this->usersTable,
            ['pin' => $pin],
            ['username' => $username]
        );
        if($success) {
            return $pin;
        } else {
            return false;
        }
    }

    /**
     * Generates a random pin of 6 numbers as a string.
     * @return string
     */
    private function generatePin() {
        $pin = "";
        for($i = 0; $i < 6; $i++) {
            $pin .= strval(random_int(0,9));
        }
        return $pin;
    }

    public function topUsers($region, $amount = 20) {
        return $this->db
            ->select($this->usersTable.'.username')
            ->select_max($this->receiptsTable.'.score')
            ->where([$this->receiptsTable.'.country' => $region])
            ->where($this->usersTable.'.id = '.$this->receiptsTable.'.group_id')
            ->group_by($this->usersTable.'.username')
            ->limit($amount)
            ->order_by('score', 'DESC')
            ->get([$this->usersTable, $this->receiptsTable])
            ->result();
    }
}