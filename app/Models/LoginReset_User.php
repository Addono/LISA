<?php
namespace App\Models;

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Login_User
 * @property CI_DB_query_builder    $db
 * @property User                   $User
 * @property LoginReset             $LoginReset
 * @property Login                  $Login
 */
class LoginReset_User extends ModelFrame {
    protected function dependencies()
    {
        return [
            LoginReset::class,
            User::class,
            Login::class,
        ];
    }

    public function updatePassword($key, $password) {
        if (!model(LoginReset::class)->exists($key)) {
            return false;
        }

        $this->db->transStart();
            // Get the all data accompanied by this key.
            $res = model(LoginReset::class)->get($key);

            // Change the password of the user.
            model(Login::class)->updatePassword($res[Login::FIELD_LOGIN_ID], $password);

            // Remove the key since it now is used.
            $success = model(LoginReset::class)->remove($key);
            if (!$success) {
                $this->db->transRollback();
                return false;
            }
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}