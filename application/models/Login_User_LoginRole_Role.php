<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Login_User_LoginRole_Role
 * @property CI_DB_query_builder    $db
 * @property Role_LoginRole         $Role_LoginRole
 */
class Login_User_LoginRole_Role extends ModelFrame
{
    protected function dependencies()
    {
        return [
            Login::class,
            User::class,
            LoginRole::class,
            Role::class,
            Role_LoginRole::class,
        ];
    }

    public function getAllUserData() {
        $users = $this->db
            ->select([
                field(Login::FIELD_LOGIN_ID, Login::name()),
                field(Login::FIELD_USERNAME, Login::name()),
                field(User::FIELD_FIRST_NAME, User::name()),
                field(User::FIELD_LAST_NAME, User::name()),
                field(User::FIELD_EMAIL, User::name()),
            ]) // Select on the login id, username, first name, last name, and email.
            ->join(User::name(),
                eq(
                    field(Login::FIELD_LOGIN_ID, Login::name()),
                    field(Login::FIELD_LOGIN_ID, User::name())
                )
            ) // Join the Login and User table on login id.
            ->get([Login::name()])
            ->result_array();

        // Add the roles to each user.
        foreach($users as $key => $user) {
            $loginId = $user[Login::FIELD_LOGIN_ID];
            $users[$key]['roles'] = $this->Role_LoginRole->getRolesFromLoginId($loginId);
        }

        return $users;
    }
}