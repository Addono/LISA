<?php
namespace App\Models;


/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class User_LoginRole
 * @property CI_DB_query_builder    $db
 */
class User_LoginRole extends ModelFrame
{
    protected function dependencies()
    {
        return [
            User::class,
            Role::class,
            LoginRole::class,
        ];
    }

    public function getUsersWithRole($roleId) {
        return $this->join()
            ->orderBy(User::FIELD_FIRST_NAME)
            ->orderBy(User::FIELD_LAST_NAME)
            ->where([field(Role::FIELD_ROLE_ID, LoginRole::name()) => $roleId])
            ->get()
            ->getResultArray();
    }

    public function join() {
        return $this->db->table(LoginRole::name())
            ->join(
                User::name(),
                eq(
                    field(Login::FIELD_LOGIN_ID, User::name()),
                    field(Login::FIELD_LOGIN_ID, LoginRole::name())
                )
            );
    }
}