<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Login_User
 * @property CI_DB_query_builder $db
 */
class LoginReset_User extends ModelFrame {
    protected function dependencies()
    {
        return [
            LoginReset::class,
            User::class,
        ];
    }

    public function updatePassword($key, $password) {
        $this->db->trans_start();
            $this->LoginReset->exists($key);

        $this->db->trans_complete();
    }
}