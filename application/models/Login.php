<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Login extends ModelFrame {

    const INITIAL_LOGIN_USERNAME = 'admin';
    const INITIAL_LOGIN_PASSWORD = 'admin312';

    const FIELD_LOGIN_ID = 'login_id';
    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    /**
     * Adds a new user to the database.
     * @param $username
     * @param $password
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     */
    public function add($username, $password) {
        return $this->db->insert(
            $this->name(),
            [
                self::FIELD_USERNAME => $username,
                self::FIELD_PASSWORD => password_hash($password, PASSWORD_DEFAULT),
            ]
        );
    }

    /**
     * Executes an update statement on one tuple in Login.
     *
     * @param $loginId
     * @param $data
     * @return bool
     */
    public function update($loginId, $data) {
        return $this->db->update(self::name(), $data, [self::FIELD_LOGIN_ID => $loginId]);
    }

    /**
     * Updates the password of one login.
     *
     * @param $loginId
     * @param $password
     * @return bool
     */
    public function updatePassword($loginId, $password) {
        $data = [
            self::FIELD_PASSWORD => password_hash($password, PASSWORD_DEFAULT),
        ];

        return $this->update($loginId, $data);
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
            ->row_array();
        if (key_exists(self::FIELD_PASSWORD, $result)) {
            return password_verify($password, $result[self::FIELD_PASSWORD]);
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

    public function usernameNotExists($username) {
        return !$this->usernameExists($username);
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
            ->row_array()[self::FIELD_USERNAME];
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
     * Creates a login for the admin user.
     */
    public function v2() {
        $this->add(self::INITIAL_LOGIN_USERNAME, self::INITIAL_LOGIN_PASSWORD);
    }
}