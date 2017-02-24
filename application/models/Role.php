<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class Role extends ModelFrame
{

    /**
     * Adds a new role.
     *
     * @param $roleName
     * @return bool True on success, else false.
     */
    public function add($roleName) {
        $roleExists = $this->getRoleNameExists($roleName);
        if (!$roleExists) {
            return $this->db->insert($this->name(), ['name' => $roleName]);
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
            ->where(['role_name' => $roleName])
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
            ->where(['role_name' => $roleName])
            ->get($this->name())
            ->row()
            ->role_id;
    }

    //======================================

    public function r1() {
        return [
            'add' => [
                'role_id' => [
                    'type' => 'INT',
                    'constraint' => ID_LENGTH,
                    'unsigned' => TRUE,
                ],
                'role_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                    'unique' => TRUE,
                ],
            ],
        ];
    }
}