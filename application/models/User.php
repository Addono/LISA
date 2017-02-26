<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class User extends ModelFrame
{
    const INITIAL_USER_FIRST_NAME = 'Lisa';
    const INITIAL_USER_LAST_NAME = 'is super awsome';
    const INITIAL_USER_EMAIL = 'invalid@email.x';

    /**
     * Adds a new user.
     * todo check if a valid email is parsed.
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
                    'type' => 'foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
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

    public function v2() {
        return [
            'requires' => [
                Login::class => 2,
            ],
        ];
    }

    public function v3() {
        $loginId = $this->Login->getLoginIdFromUsername(Login::INITIAL_LOGIN_USERNAME);

        $this->add($loginId, self::INITIAL_USER_FIRST_NAME, self::INITIAL_USER_LAST_NAME, self::INITIAL_USER_EMAIL);
    }
}