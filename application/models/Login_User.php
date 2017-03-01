<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Login_User
 * @property CI_DB_query_builder $db
 */
class Login_User extends ModelFrame {
    protected function dependencies()
    {
        return [
            Login::class,
            User::class,
        ];
    }

    /**
     * Checks if a login id exists and has a user tuple connected to it.
     *
     * @param $loginId
     * @return bool
     */
    public function exists($loginId) {
        return $this->joinUser()
            ->where([field(Login::FIELD_LOGIN_ID, Login::name()) => $loginId])
            ->get(Login::name())
            ->num_rows() > 0;
    }

    /**
     * Prepares a join between the users and login model, joins on user so a get on login should still be added.
     *
     * @return CI_DB_query_builder
     */
    public function joinUser() {
        return $this->db->join(User::name(),
            eq(
                field(Login::FIELD_LOGIN_ID, Login::name()),
                field(Login::FIELD_LOGIN_ID, User::name())
            )
        ); // Join the Login and User table on login id.
    }
}