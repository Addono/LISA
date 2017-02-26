<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class UserRole extends ModelFrame
{

    /**
     * Adds a role-user relation if it did not exist already.
     *
     * @param $loginId
     * @param $roleId
     * @return bool True on success, else false.
     */
    public function add($loginId, $roleId) {
        $exists = $this->exists($loginId, $roleId);
        if (!$exists) {
            return $this->db->insert(
                $this->name(),
                [
                    'login_id' => $loginId,
                    'role_id' => $roleId,
                ]
            );
        } else {
            return false;
        }
    }

    /**
     * Checks if a role-user relation exists.
     *
     * @param $loginId
     * @param $roleId
     * @return bool
     */
    public function exists($loginId, $roleId) {
        $result = $this->db
            ->where([
                'login_id' => $loginId,
                'role_id' => $roleId,
            ])
            ->count_all_results($this->name());

        return $result > 0;
    }

    /**
     * Checks if a user has a certain role.
     *
     * @param $loginId
     * @param $roleName
     * @return bool True if the user has this role, else false.
     */
    public function userHasRole($loginId, $roleName) {
        $count = $this->db
            ->where([
                Role::name().'.role_name' => $roleName,
                UserRole::name().'.login_id' => $loginId,
                Role::name().'.role_id = '.UserRole::name().'.role_id',
            ])
            ->count_all_results([UserRole::name(), Role::name()]);

        return $count > 0;
    }

    //======================================

    /**
     * Create the table.
     *
     * @return array
     */
    public function v1() {
        return [
            'requires' => [
                Login::class => 1,
                Role::class => 1,
            ],
            'add' => [
                Login::FIELD_LOGIN_ID => [
                    'type' => 'foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
                ],
                Role::FIELD_ROLE_ID => [
                    'type' => 'foreign',
                    'table' => Role::name(),
                    'field' => Role::FIELD_ROLE_ID,
                ],
            ],
        ];
    }

    /**
     * Ensures that login_id and role_id is a primary key.
     * todo implement this
     *
     * @return array
     */
    public function v2() {
        return []; // todo add role_id and login_id as a primary key
    }

    /**
     * Ensure all dependencies of v4.
     *
     * @return array
     */
    public function v3() {
        return [
            'requires' => [
                User::class => 4,
                Role::class => 3,
            ],
        ];
    }

    public function v4() {
        $loginId = $this->Login->getLoginIdFromUsername(LOGIN::INITIAL_LOGIN_USERNAME);
        $roleId = $this->Role->getRoleIdFromRoleName(Role::ROLE_SUPERADMIN);

        $this->add($loginId, $roleId);
    }
}