<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Login extends ModelFrame {

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
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
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
            ->where(['login_id' => $userId])
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
            ->where(['username' => $username])
            ->count_all_results($this->name());
        return $result > 0;
    }

    public function getUsernames() {
        return $this->db
            ->select(['username'])
            ->get($this->name())
            ->result();
    }

    public function getUsername($loginId) {
        return $this->db
            ->where(['login_id' => $loginId])
            ->get($this->name())
            ->row()
            ->username;
    }

    private function getLoginIdFromUsername($username) {
        return $this->db
            ->where(['username' => $username])
            ->get($this->name())
            ->row()
            ->login_id;
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
                'login_id' => [
                    'type' => 'INT',
                    'constraint' => ID_LENGTH,
                    'unsigned' => TRUE,
                ],
                'username' => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                    'unique' => TRUE,
                ],
                'password' => [
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
        return [
            'requires' => [
                User::class => 1,
            ],
        ]; // todo add login_id as a primary key
    }

    /**
     * Creates a login for the admin user.
     */
    public function v3() {
        $this->addLogin('admin', 'banana');
    }
}