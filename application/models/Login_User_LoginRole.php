<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Login_User_Role
 * @property  CI_DB_query_builder   $db
 * @property  Login                 $Login
 * @property  User                  $User
 * @property  LoginRole             $LoginRole
 */
class Login_User_LoginRole extends ModelFrame
{
    protected function dependencies()
    {
        return [
            User::class,
            Login::class,
            LoginRole::class,
        ];
    }

    public function add($username, $password, $firstName, $lastName, $email, array $roles) {

        $this->db->trans_start();
            // Create a new login entry.
            $this->Login->add($username, $password);
            $loginId = $this->Login->getLoginIdFromUsername($username);

            // Create a new user profile.
            $this->User->add($loginId, $firstName, $lastName, $email);

            // Add all roles to the new account.
            foreach ($roles as $role) {
                $this->LoginRole->add($loginId, $role);
            }
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}