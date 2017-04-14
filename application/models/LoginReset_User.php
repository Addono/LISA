<?php
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
        if (!$this->LoginReset->exists($key)) {
            return false;
        }

        $this->db->trans_start();
            // Get the all data accompanied by this key.
            $res = $this->LoginReset->get($key);

            // Change the password of the user.
            $this->Login->updatePassword($res[Login::FIELD_LOGIN_ID], $password);

            // Remove the key since it now is used.
            $success = $this->LoginReset->remove($key);
            if (!$success) {
                $this->db->trans_rollback();
                return false;
            }
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}