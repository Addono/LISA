<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */

/**
 * Class LoginRole
 * @property  CI_DB_query_builder   $db
 */
class LoginRole extends ModelFrame
{

    protected function dependencies()
    {
        return [
            Role::class,
            Login::class,
        ];
    }

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
                self::name(),
                [
                    Login::FIELD_LOGIN_ID => $loginId,
                    Role::FIELD_ROLE_ID => $roleId,
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
     * @return bool True if it exists, else false.
     */
    public function exists($loginId, $roleId) {
        $result = $this->db
            ->where([
                Login::FIELD_LOGIN_ID => $loginId,
                Role::FIELD_ROLE_ID => $roleId,
            ])
            ->count_all_results(self::name());

        return $result > 0;
    }

    /**
     * Get all roles associated with a login id.
     *
     * @param $loginId
     * @return array
     */
    public function getFromLoginId($loginId) {
        return $this->db
            ->where([Login::FIELD_LOGIN_ID => $loginId])
            ->get(self::name())
            ->result_array();
    }

    /**
     * Get all roles associated with a role id.
     *
     * @param $roleId
     * @return array
     */
    public function getFromRoleId($roleId) {
        return $this->db
            ->where([Role::FIELD_ROLE_ID => $roleId])
            ->get(self::name())
            ->result_array();
    }

    /**
     * Sets the roles linked to a login id. NOTE: Removes all others.
     *
     * @param int $loginId
     * @param array $roleIds
     * @return bool True on success, else false.
     */
    public function setRolesForLoginId($loginId, array $roleIds) {
        $this->db->trans_start();
            $this->deleteRolesForLoginId($loginId);

            foreach ($roleIds as $roleId) {
                $this->add($loginId, $roleId);
            }
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Unlinkes all roles from a login id.
     *
     * @param int $loginId
     */
    private function deleteRolesForLoginId($loginId) {
        $this->db->delete(self::name(), [Login::FIELD_LOGIN_ID => $loginId]);
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
     * Ensure all dependencies of v3.
     *
     * @return array
     */
    public function v2() {
        return [
            'requires' => [
                User::class => 3,
                Role::class => 2,
            ],
        ];
    }

    public function v3() {
        $loginId = $this->Login->getLoginIdFromUsername(Login::INITIAL_LOGIN_USERNAME);
        $roleIdAdmin = $this->Role->getRoleIdFromRoleName(Role::ROLE_ADMIN);
        $roleIdUser = $this->Role->getRoleIdFromRoleName(Role::ROLE_USER);

        $this->add($loginId, $roleIdAdmin);
        $this->add($loginId, $roleIdUser);
    }
}