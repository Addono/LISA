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
    public function r1() {
        return [
            'add' => [
                'login_id' => [
                    'type' => 'INT',
                    'constraint' => ID_LENGTH,
                    'unsigned' => TRUE,
                ],
                'role_id' => [
                    'type' => 'INT',
                    'constraint' => ID_LENGTH,
                    'unsigned' => TRUE,
                ],
            ],
        ];
    }
}