<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Role_LoginRole
 * @property  CI_DB_query_builder   $db
 */
class Role_LoginRole extends ModelFrame
{
    protected function dependencies()
    {
        return [
            Role::class,
            LoginRole::class,
        ];
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
                Role::name().'.'.Role::FIELD_ROLE_NAME => $roleName,
                LoginRole::name().'.'.Login::FIELD_LOGIN_ID => $loginId,
            ])
            ->where(Role::name().'.'.Role::FIELD_ROLE_ID.' = '.LoginRole::name().'.'.Role::FIELD_ROLE_ID)
            ->count_all_results([LoginRole::name(), Role::name()]);

        return $count > 0;
    }

    /**
     * Returns all roles corresponding to one login id.
     *
     * @param int $loginId
     * @return array
     */
    public function getRolesFromLoginId($loginId) {
        return $this->db
            ->where([Login::FIELD_LOGIN_ID => $loginId])
            ->join(Role::name(),
                eq(
                    field(Role::FIELD_ROLE_ID, Role::name()),
                    field(Role::FIELD_ROLE_ID, LoginRole::name())
                )
            )
            ->get(LoginRole::name())
            ->result_array();
    }
}