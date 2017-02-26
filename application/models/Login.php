<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Login extends ModelFrame {

    const INITIAL_LOGIN_USERNAME = 'Lisa';
    const INITIAL_LOGIN_PASSWORD = 'is super awsome';

    const FIELD_LOGIN_ID = 'login_id';
    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    /**
     * Adds a new user to the database.
     * @param $username
     * @param $password
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     */
    public function addLogin($username, $password) {
        return $this->db->insert(
            $this->name(),
            [
                self::FIELD_USERNAME => $username,
                self::FIELD_PASSWORD => password_hash($password, PASSWORD_DEFAULT),
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
        $userId = $this->getLoginIdFromUsername($username);

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
            ->where([self::FIELD_LOGIN_ID => $userId])
            ->get($this->name())
            ->row();
        if(isset($result->password)) {
            return password_verify($password, $result->password);
        } else {
            return false;
        }
    }

    public function usernameExists($username) {
        $result = $this->db
            ->where([self::FIELD_USERNAME => $username])
            ->count_all_results($this->name());
        return $result > 0;
    }

    public function getUsernames() {
        return $this->db
            ->select([self::FIELD_USERNAME])
            ->get($this->name())
            ->result();
    }

    public function getUsername($loginId) {
        return $this->db
            ->where([self::FIELD_LOGIN_ID => $loginId])
            ->get($this->name())
            ->row()
            ->username;
    }

    public function getLoginIdFromUsername($username) {
        $row = $this->db
            ->where([self::FIELD_USERNAME => $username])
            ->get($this->name())
            ->row_array();

        if ($row === null) {
            throw new Exception('Username "'.$username.'" was not found in the login table.');
        }

        return $row[self::FIELD_LOGIN_ID];
    }

    //======================================

    /**
     * Setups the table.
     *
     * @return array
     */
    public function v1() {
        return [
            'add' => [
                self::FIELD_LOGIN_ID => [
                    'type' => 'primary',
                ],
                self::FIELD_USERNAME => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                    'unique' => TRUE,
                ],
                self::FIELD_PASSWORD => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ],
            ]
        ];
    }

    /**
     * Ensures that login_id is a primary key.
     * todo implement this
     *
     * @return array
     */
    public function v2() {
        return []; // todo add login_id as a primary key
    }

    /**
     * Creates a login for the admin user.
     */
    public function v3() {
        $this->addLogin(self::INITIAL_LOGIN_USERNAME, self::INITIAL_LOGIN_PASSWORD);
    }
}