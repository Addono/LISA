<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 2-3-2017
 */

/**
 * Class User_Consumption
 * @property CI_DB_query_builder    $db
 * @property User_LoginRole         $User_LoginRole
 * @property Login                  $Login
 * @property Consumption            $Consumption
 */
class User_Consumption_LoginRole extends ModelFrame
{
    protected function dependencies()
    {
        return [
            Login::class,
            User::class,
            Consumption::class,
            LoginRole::class,
            User_LoginRole::class,
        ];
    }

    public function get($role) {
        $users = $this->User_LoginRole->getUsersWithRole($role);

        foreach ($users as $key => $user) {
            $users[$key][Consumption::FIELD_AMOUNT] = $this->Consumption->get($user[Login::FIELD_LOGIN_ID]);
        }

        return $users;
    }
}