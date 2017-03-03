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

    public function getAll($where = []) {
        $transactions = $this->db
            ->order_by(Transaction::FIELD_TIME, 'DESC')
            ->where($where)
            ->get(Transaction::name())
            ->result_array();

        $users = $this->User->getLoginIdToName();

        foreach ($transactions as $key => $t) {
            $transactions[$key]['author_name'] = $users[$t[Transaction::FIELD_AUTHOR_ID]];
            $transactions[$key]['subject_name'] = $users[$t[Transaction::FIELD_SUBJECT_ID]];
        }

        return $transactions;
    }

    public function getAllForSubjectId($subjectId) {
        return $this->getAll([Transaction::FIELD_SUBJECT_ID => $subjectId]);
    }

    public function getAllForAuthorId($authorId) {
        return $this->getAll([Transaction::FIELD_AUTHOR_ID => $authorId]);
    }
}