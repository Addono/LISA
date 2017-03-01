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
    /**
     * All columns whom can be selected without sensitive data like passwords.
     *
     * @return array
     */
    private function selectNonCritical() {
        return [
            field(Login::FIELD_LOGIN_ID, Login::name()),
            field(Login::FIELD_USERNAME, Login::name()),
            field(User::FIELD_FIRST_NAME, User::name()),
            field(User::FIELD_LAST_NAME, User::name()),
            field(User::FIELD_EMAIL, User::name()),
        ];
    }

    protected function dependencies()
    {
        return [
            Login::class,
            User::class,
            Role_LoginRole::class,
            Login_User::class,
        ];
    }

    public function getAllUserData() {
        $users = $this->Login_User->joinUser()
            ->select($this->selectNonCritical()) // Select on the login id, username, first name, last name, and email.
            ->get([Login::name()])
            ->result_array();

        // Add the roles to each user.
        foreach($users as $key => $user) {
            $loginId = $user[Login::FIELD_LOGIN_ID];
            $users[$key]['roles'] = $this->Role_LoginRole->getRolesFromLoginId($loginId);
        }

        return $users;
    }

    public function getUserData($loginId) {
        $user = $this->Login_User->joinUser()
            ->select($this->selectNonCritical())
            ->where([field(Login::FIELD_LOGIN_ID, Login::name()) => $loginId])
            ->get([Login::name()])
            ->row_array();

        $user['roles'] = $this->Role_LoginRole->getRolesFromLoginId($loginId);

        return $user;
    }
}