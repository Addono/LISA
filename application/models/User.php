<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class User extends ModelFrame
{
    const INITIAL_USER_FIRST_NAME = 'admin';
    const INITIAL_USER_LAST_NAME = 'user';
    const INITIAL_USER_EMAIL = 'admin@email.x';

    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_EMAIL = 'email';

    protected function dependencies()
    {
        return [
            Login::class,
        ];
    }

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
        return $this->db->insert(
            $this->name(),
            [
                Login::FIELD_LOGIN_ID => $loginId,
                self::FIELD_FIRST_NAME => $firstName,
                self::FIELD_LAST_NAME => $lastName,
                self::FIELD_EMAIL => $email,
            ]
        );
    }

    /**
     * Gives an array for all names corresponding with each login id. The keys of the array are the login ids.
     *
     * @return array
     */
    public function getLoginIdToName() {
        $data = $this->db
            ->select([Login::FIELD_LOGIN_ID, self::FIELD_FIRST_NAME, self::FIELD_LAST_NAME])
            ->get(self::name())
            ->result_array();

        // Combine first and last name.
        foreach ($data as $key => $row) {
            $data[$key]['name'] = ucfirst($row[self::FIELD_FIRST_NAME]) . ' ' . $row[self::FIELD_LAST_NAME];
        }

        // Make the ID key and return.
        return array_column($data, 'name', Login::FIELD_LOGIN_ID);
    }

    /**
     * Checks if a user for this login id already exists.
     *
     * @param $loginId
     * @return bool True if the user exists
     */
    public function exists($loginId) {
        $result = $this->db
            ->where([Login::FIELD_LOGIN_ID => $loginId])
            ->count_all_results($this->name());

        return $result > 0;
    }

    /**
     * Gives the name of the user corresponding with a login id.
     *
     * @param $loginId
     * @return string
     */
    public function getName($loginId) {
        $row = $this->db
            ->where([Login::FIELD_LOGIN_ID => $loginId])
            ->get(self::name())
            ->row_array();

        $firstName = $row[self::FIELD_FIRST_NAME];
        $lastName = $row[self::FIELD_LAST_NAME];

        return ucfirst($firstName) . ' ' . $lastName;
    }

    /**
     * Updates data of one user.
     *
     * @param $loginId
     * @param $data
     * @return bool
     */
    public function update($loginId, $data) {
        return $this->db->update(self::name(), $data, [Login::FIELD_LOGIN_ID => $loginId]);
    }

    /**
     * Changes the name of one user.
     *
     * @param $loginId
     * @param $firstName
     * @param $lastName
     * @return bool
     */
    public function updateName($loginId, $firstName, $lastName) {
        $data = [
            self::FIELD_FIRST_NAME => $firstName,
            self::FIELD_LAST_NAME => $lastName,
        ];

        return $this->update($loginId, $data);
    }

    /**
     * Changes the email of one user.
     *
     * @param $loginId
     * @param $email
     * @return bool
     */
    public function updateEmail($loginId, $email) {
        $data = [
            self::FIELD_EMAIL => $email,
        ];

        return $this->update($loginId, $data);
    }

    //======================================

    public function v1() {
        return [
            'requires' => [
                Login::class => 1,
            ],
            'add' => [
                Login::FIELD_LOGIN_ID => [
                    'type' => 'primary|foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
                ],
                self::FIELD_FIRST_NAME => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ],
                self::FIELD_LAST_NAME => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ],
                self::FIELD_EMAIL => [
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