<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 2-3-2017
 */

/**
 * Class User_Transaction
 * @property CI_DB_query_builder $db
 */
class User_Transaction extends ModelFrame
{

    protected function dependencies()
    {
        return [
            User::class,
            Transaction::class
        ];
    }

    public function getAll() {
        $transactions = $this->db
            ->get(Transaction::name())
            ->result_array();

        $users = $this->User
    }
}