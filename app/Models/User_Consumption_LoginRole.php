<?php
namespace App\Models;


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
        $users = model(User_LoginRole::class)->getUsersWithRole($roleId);

        foreach ($users as $key => $user) {
            $users[$key][Consumption::FIELD_AMOUNT] = model(Consumption::class)->get($user[Login::FIELD_LOGIN_ID]);
            $users[$key][Transaction::FIELD_TIME] = model(User_Transaction::class)->getLatestForSubjectId($user[Login::FIELD_LOGIN_ID]);
        }

        return $users;
    }
}
