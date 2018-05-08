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
        // Retrieve all transactions
        $transactions = $this->db
            ->select('*, UNIX_TIMESTAMP('.Transaction::FIELD_TIME.') as '.Transaction::FIELD_TIME.'_unix')
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

    public function getSumDeltaForAllSubjectId(bool $positive, $limit = null) {
        // Retrieve the sum of the delta of all negative or positive transactions, ordered and limited to retrieve only highest values.
        $sumQuery = $this->db
            ->select(Transaction::FIELD_SUBJECT_ID)
            ->select_sum(Transaction::FIELD_DELTA, 'sum')
            ->where(Transaction::FIELD_DELTA . ($positive?'>':'<') . ' 0')
            ->group_by(Transaction::FIELD_SUBJECT_ID)
            ->limit($limit)
            ->order_by('sum ' . ($positive?'DESC':'ASC'))
            ->get_compiled_select(Transaction::name());

        // Join the leaderboard on the user database.
        return $this->db
            ->from('(' . $sumQuery . ') `' . $this->db->dbprefix('t') . '`')
            ->where('t.' . Transaction::FIELD_SUBJECT_ID . '=' . User::name() . '.' . Login::FIELD_LOGIN_ID)
            ->get(User::name())
            ->result_array();
    }

    public function getAllForSubjectId($subjectId) {
        return $this->getAll([Transaction::FIELD_SUBJECT_ID => $subjectId]);
    }

    public function getAllForAuthorId($authorId) {
        return $this->getAll([Transaction::FIELD_AUTHOR_ID => $authorId]);
    }
}