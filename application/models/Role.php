<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class Role extends ModelFrame
{
    const ROLE_SUPERADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    const FIELD_ROLE_ID = 'role_id';
    const FIELD_ROLE_NAME = 'role_name';

    /**
     * Adds a new role.
     *
     * @param $roleName
     * @return bool True on success, else false.
     */
    public function add($roleName) {
        $roleExists = $this->getRoleNameExists($roleName);
        if (!$roleExists) {
            return $this->db->insert($this->name(), [self::FIELD_ROLE_NAME => $roleName]);
        } else {
            return false;
        }
    }

    /**
     * Checks whether a role already exists.
     *
     * @param $roleName
     * @return bool
     */
    public function getRoleNameExists($roleName) {
        $result = $this->db
            ->where([self::FIELD_ROLE_NAME => $roleName])
            ->count_all_results($this->name());

        return $result > 0;
    }

    /**
     * Gets the role id corresponding with the name of a role.
     *
     * @param $roleName
     * @return bool|int False on failure, else the id as an int.
     */
    public function getRoleIdFromRoleName($roleName) {
        if (!$this->getRoleNameExists($roleName)) {
            return false;
        }

        return $this->db
            ->where([self::FIELD_ROLE_NAME => $roleName])
            ->get($this->name())
            ->row_array()[self::FIELD_ROLE_ID];
    }

    //======================================

    public function v1() {
        return [
            'add' => [
                self::FIELD_ROLE_ID => [
                    'type' => 'primary',
                ],
                self::FIELD_ROLE_NAME => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                    'unique' => TRUE,
                ],
            ],
        ];
    }

    /**
     * Ensures that role_id is a primary key.
     * todo implement this
     *
     * @return array
     */
    public function v2() {
        return []; // todo add role_id as a primary key
    }

    /**
     * Adds the user, admin, and super admin role.
     */
    public function v3() {
        $this->add(self::ROLE_SUPERADMIN);
        $this->add(self::ROLE_ADMIN);
        $this->add(self::ROLE_USER);
    }
}