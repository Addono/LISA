<?php


/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class User extends ModelFrame
{

    /**
     * Adds a new user.
     *
     * @param $loginId
     * @param $firstName
     * @param $lastName
     * @param $email
     * @return bool True on success, else false.
     */
    public function add($loginId, $firstName, $lastName, $email) {
        $exists = $this->exists($loginId);
        if (!$exists) {
            return $this->db->insert(
                $this->name(),
                [
                    'login_id' => $loginId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                ]
            );
        } else {
            return false;
        }
    }

    /**
     * Checks if a user for this login id already exists.
     *
     * @param $loginId
     * @return bool True if the user exists
     */
    public function exists($loginId) {
        $result = $this->db
            ->where(['login_id' => $loginId])
            ->count_all_results($this->name());

        return $result > 0;
    }

    //======================================

    public function v1() {
        return [
            'add' => [
                'login_id' => [
                    'type' => 'INT',
                    'constraint' => ID_LENGTH,
                    'unsigned' => TRUE,
                ],
                'first_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ],
                'last_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ]
            ],

        ];
    }
}