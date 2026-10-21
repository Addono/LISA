<?php
namespace App\Models;


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

        $this->db->transStart();
            // Create a new login entry.
            model(Login::class)->add($username, $password);
            $loginId = model(Login::class)->getLoginIdFromUsername($username);

            // Create a new user profile.
            model(User::class)->add($loginId, $firstName, $lastName, $email);

            // Add all roles to the new account.
            foreach ($roles as $role) {
                model(LoginRole::class)->add($loginId, $role);
            }
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}