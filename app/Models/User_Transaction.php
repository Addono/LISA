<?php
namespace App\Models;


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

    const FIELD_TIME = 'time';

    protected function dependencies()
    {
        return [
            User::class,
            Transaction::class
        ];
    }

    public function getAll($where = [], $limit = null)
    {
        // Retrieve all transactions
        $transactions = $this->db->table(Transaction::name())
            ->select('*, UNIX_TIMESTAMP(' . Transaction::FIELD_TIME . ') as ' . Transaction::FIELD_TIME . '_unix')
            ->orderBy(Transaction::FIELD_TIME, 'DESC')
            ->where($where)
            ->limit($limit ?? 0)
            ->get()
            ->getResultArray();

        $users = model(User::class)->getLoginIdToName();

        foreach ($transactions as $key => $t) {
            $transactions[$key]['author_name'] = $users[$t[Transaction::FIELD_AUTHOR_ID]];
            $transactions[$key]['subject_name'] = $users[$t[Transaction::FIELD_SUBJECT_ID]];
        }

        return $transactions;
    }

    public function getSumDeltaForAllSubjectId(bool $positive, $limit = null)
    {
        // Join the leaderboard on the user database.
        $alias = $this->db->prefixTable('t');
        return $this->db->table(User::name())
            ->join('(' . model(Transaction::class)->getSumQuerySql($positive, $limit) . ') ' . $alias,
                $alias . '.' . Transaction::FIELD_SUBJECT_ID . '=' . User::name() . '.' . Login::FIELD_LOGIN_ID)
            ->get()
            ->getResultArray();
    }

    public function getAllForSubjectId($subjectId, $limit = null)
    {
        return $this->getAll([Transaction::FIELD_SUBJECT_ID => $subjectId], $limit);
    }

    public function getAllForAuthorId($authorId, $limit = null)
    {
        return $this->getAll([Transaction::FIELD_AUTHOR_ID => $authorId], $limit);
    }

    public function getLatestForSubjectId($subjectId, $limit = 1)
    {
        $row = $this->db->table(Transaction::name())
            ->where(Transaction::FIELD_SUBJECT_ID . '=' . $subjectId)
            ->orderBy(Transaction::FIELD_TIME, 'DESC')
            ->limit($limit)
            ->get()
            ->getRowArray();

		// Handle the case when no data was returned iin case the user has no transactions.
        if (!isset($row[self::FIELD_TIME])) {
			return '2000-01-01 00:00:00';
		}

        return $row[self::FIELD_TIME];
    }
}
