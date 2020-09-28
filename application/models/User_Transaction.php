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

    public function getAll($where = [], $limit = null) {
        // Retrieve all transactions
        $transactions = $this->db
            ->select('*, UNIX_TIMESTAMP('.Transaction::FIELD_TIME.') as '.Transaction::FIELD_TIME.'_unix')
            ->order_by(Transaction::FIELD_TIME, 'DESC')
            ->where($where)
            ->limit($limit)
            ->get(Transaction::name())
            ->result_array();

        $users = $this->User->getLoginIdToName();

        foreach ($transactions as $key => $t) {
            $transactions[$key]['author_name'] = $users[$t[Transaction::FIELD_AUTHOR_ID]];
            $transactions[$key]['subject_name'] = $users[$t[Transaction::FIELD_SUBJECT_ID]];
        }

        return $transactions;
    }

    public function getSumDeltaForAllSubjectId(bool $positive, $limit = null) {
        // Join the leaderboard on the user database.
        return $this->db
            ->from('(' . $this->ci->Transaction->getSumQuerySql($positive, $limit) . ') `' . $this->db->dbprefix('t') . '`')
            ->where('t.' . Transaction::FIELD_SUBJECT_ID . '=' . User::name() . '.' . Login::FIELD_LOGIN_ID)
            ->get(User::name())
            ->result_array();
    }

    public function getAllForSubjectId($subjectId, $limit = null) {
        return $this->getAll([Transaction::FIELD_SUBJECT_ID => $subjectId], $limit);
    }

    public function getAllForAuthorId($authorId, $limit = null) {
        return $this->getAll([Transaction::FIELD_AUTHOR_ID => $authorId], $limit);
    }
}