<?php
namespace App\Models;


/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 2-3-2017
 */

/**
 * Class Transaction
 * @property CI_DB_query_builder $db
 */
class Transaction extends ModelFrame
{
    const FIELD_TRANSACTION_ID = 'transaction_id';
    const FIELD_AUTHOR_ID = 'author_id';
    const FIELD_SUBJECT_ID = 'subject_id';
    const FIELD_TIME = 'time';
    const FIELD_DELTA = 'delta';
    const FIELD_TYPE = 'type';

    const TYPES = [
        self::TYPE_CONSUME,
        self::TYPE_UPGRADE,
    ];

    const TYPE_CONSUME = 'c';
    const TYPE_UPGRADE = 'u';

    protected function dependencies()
    {
        return [
            Login::class,
            Consumption::class,
        ];
    }

    public function add($subjectId, $authorId, $amount, $delta, $type = self::TYPE_CONSUME)
    {
        $this->db->table(self::name())->insert([
                self::FIELD_SUBJECT_ID => $subjectId,
                self::FIELD_AUTHOR_ID => $authorId,
                Consumption::FIELD_AMOUNT => $amount,
                self::FIELD_DELTA => $delta,
                self::FIELD_TYPE => $type,
            ]
        );
    }

    public function getAllFromSubject($subjectId)
    {
        return $this->db->table(self::name())
            ->where([self::FIELD_SUBJECT_ID => $subjectId])
            ->get()
            ->getResultArray();
    }

    public function getAllFromAuthor($authorId)
    {
        return $this->db->table(self::name())
            ->where([self::FIELD_AUTHOR_ID => $authorId])
            ->get()
            ->getResultArray();
    }

    public function getAll()
    {
        return $this->db->table(self::name())
            ->get()
            ->getResultArray();
    }

    public function getConsumeCountForSubject(int $subjectId)
    {
        $amount = $this->db->table(self::name())
            ->selectSum(self::FIELD_DELTA, 'sum')
            ->where([
                self::FIELD_SUBJECT_ID => $subjectId,
                self::FIELD_TYPE => self::TYPE_CONSUME,
                ])
            ->get()
            ->getFirstRow()
            ->sum;

        return abs($amount);
    }

    /**
     * SQL query which sorts the top amount of consumptions made by a user.
     * @param bool $positive True if only positive consumptions should be counter, false if only negative consumptions should be counted.
     * @param int $limit The amount of users retrieved to compute the top score over.
     * @return string SQL for this query.
     */
    public function getSumQuerySql(bool $positive, ?int $limit) {
        $sumQuery = $this->db->table(self::name())
            ->select(self::FIELD_SUBJECT_ID)
            ->selectSum(self::FIELD_DELTA, 'sum')
            ->where(self::FIELD_DELTA . ($positive?'>':'<') . ' 0')
            ->groupBy(self::FIELD_SUBJECT_ID)
            ->limit($limit ?? 0)
            ->orderBy('sum ' . ($positive?'DESC':'ASC'));

        return $sumQuery->getCompiledSelect();
    }

    /**
     * Checks if a subject user is on the leaderboard
     *
     * @param int $subjectId The login ID of the subject.
     * @param int $position The amount of spots on the leaderboard.
     * @return bool True if the user is on the leaderboard, false otherwise.
     */
    public function getSumDeltaSubjectIdWithinTop(int $subjectId, int $position): bool
    {
        // Check if the specified subject is within the earlier retrieved leaderboard.
        $alias = $this->db->prefixTable('t');
        $result = $this->db->newQuery()
            ->fromSubquery($this->db->table(self::name())
                ->select(self::FIELD_SUBJECT_ID)
                ->selectSum(self::FIELD_DELTA, 'sum')
                ->where(self::FIELD_DELTA . ' <', 0)
                ->groupBy(self::FIELD_SUBJECT_ID)
                ->orderBy('sum', 'ASC')
                ->limit($position), $alias)
            ->where($alias . '.' . self::FIELD_SUBJECT_ID, $subjectId)
            ->get();

        // If a row is returned, then the user is on the leaderboard.
        return $result->getNumRows() > 0;
    }

    /**
     * Retrieves the sum of negative transactions grouped by week.
     *
     * @param int $subjectId The id of the subject user.
     * @return array
     */
    public function getSumDeltaSubjectIdByWeek(int $subjectId): array
    {
        return $this->db->table(self::name())
            ->select('YEAR(' . self::FIELD_TIME . ') as year, WEEKOFYEAR(' . self::FIELD_TIME . ') as week')
            ->selectSum(self::FIELD_DELTA, 'sum')
            ->where(self::FIELD_SUBJECT_ID, $subjectId)
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->groupBy('YEAR(' . self::FIELD_TIME . '), WEEKOFYEAR(' . self::FIELD_TIME . ')')
            ->orderBy(self::FIELD_TIME, 'asc')
            ->get()
            ->getResultArray();
    }

    /**
     * Retrieves the sum of negative transactions grouped by week.
     *
     * @return array
     */
    public function getSumDeltaByWeek(): array
    {
        return $this->db->table(self::name())
            ->select('YEAR(' . self::FIELD_TIME . ') as year, WEEKOFYEAR(' . self::FIELD_TIME . ') as week')
            ->selectSum(self::FIELD_DELTA, 'sum')
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->groupBy('YEAR(' . self::FIELD_TIME . '), WEEKOFYEAR(' . self::FIELD_TIME . ')')
            ->orderBy(self::FIELD_TIME, 'asc')
            ->get()
            ->getResultArray();
    }


    public function getConsumedWeeksForLeaderboard(int $position): array
    {
        $sumQuery = $this->db->table(self::name())
            ->select(self::FIELD_SUBJECT_ID)
            ->selectSum(self::FIELD_DELTA, 'sum')
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->groupBy(self::FIELD_SUBJECT_ID)
            ->limit($position)
            ->orderBy('sum ' . 'ASC')
            ->getCompiledSelect()
        ;

        $result = $this->db->table(self::name())
            ->select([
                self::FIELD_SUBJECT_ID,
                'YEAR('.self::FIELD_TIME.') * 52 + WEEKOFYEAR('.self::FIELD_TIME.') as weekyear',
            ])
            ->where(self::FIELD_TYPE, self::TYPE_CONSUME)
            ->whereIn(self::FIELD_SUBJECT_ID, $this->db->newQuery()
                ->select(self::FIELD_SUBJECT_ID)
                ->from('(' . $sumQuery . ') ' . $this->db->prefixTable('t')))
            ->groupBy(['weekyear', self::FIELD_SUBJECT_ID])
            ->orderBy('time', 'ASC')
            ->get()
            ->getResultArray()
        ;

        return $result;
    }

    public function v1()
    {
        return [
            'requires' => [
                Login::class => 1,
            ],
            'add' => [
                self::FIELD_TRANSACTION_ID => [
                    'type' => 'primary',
                ],
                self::FIELD_AUTHOR_ID => [
                    'type' => 'foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
                ],
                self::FIELD_SUBJECT_ID => [
                    'type' => 'foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
                ],
                Consumption::FIELD_AMOUNT => [
                    'type' => 'INT',
                    'constraint' => 9,
                ],
                self::FIELD_DELTA => [
                    'type' => 'INT',
                    'constraint' => 9,
                ],
                self::FIELD_TIME => [
                    'type' => 'TIMESTAMP'
                ],
            ],
        ];
    }

    public function v2()
    {
        return [
            'add' => [
                self::FIELD_TYPE => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'default' => self::TYPE_CONSUME,
                    'null' => false,
                ],
            ],
        ];
    }

    public function v3()
    {
        $this->db->query('ALTER TABLE '.self::name().' CHANGE `'.self::FIELD_TIME.'` `'.self::FIELD_TIME.'` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;');
        $this->db->query('UPDATE '.self::name().' SET '.self::FIELD_TYPE.' = IF(delta = -1, \''.self::TYPE_CONSUME.'\', \''.self::TYPE_UPGRADE.'\')');
        return [];
    }
}
