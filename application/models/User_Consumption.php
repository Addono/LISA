<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 2-3-2017
 */

/**
 * Class User_Consumption
 * @property CI_DB_query_builder    $db
 */
class User_Consumption extends ModelFrame
{
    protected function dependencies()
    {
        return [
            Login::class,
            User::class,
            Consumption::class,
        ];
    }

    public function join($type = '') {
        return $this->db
            ->join(
                User::name(),
                eq(
                    field(Login::FIELD_LOGIN_ID, User::name()),
                    field(Login::FIELD_LOGIN_ID, Consumption::name())
                ),
                $type
            );
    }
}