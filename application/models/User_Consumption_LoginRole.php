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
 * @property User_Transaction		$User_Transaction
 */
class User_Consumption_LoginRole extends ModelFrame
{
    protected function dependencies()
    {
        return [
            Login::class,
            User::class,
            Consumption::class,
			Transaction::class,
            LoginRole::class,
            User_LoginRole::class,
			User_Transaction::class,
        ];
    }

    public function get($roleId) {
        $users = $this->User_LoginRole->getUsersWithRole($roleId);

        foreach ($users as $key => $user) {
            $users[$key][Consumption::FIELD_AMOUNT] = $this->Consumption->get($user[Login::FIELD_LOGIN_ID]);
            $users[$key][Transaction::FIELD_TIME] = $this->User_Transaction->getLatestForSubjectId($user[$this->Login::FIELD_LOGIN_ID]);
        }

        return $users;
    }
}
