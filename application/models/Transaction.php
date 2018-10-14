<?php

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
        $this->db->insert(
            self::name(),
            [
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
        return $this->db
            ->where([self::FIELD_SUBJECT_ID => $subjectId])
            ->get(self::name())
            ->result_array();
    }

    public function getAllFromAuthor($authorId)
    {
        return $this->db
            ->where([self::FIELD_AUTHOR_ID => $authorId])
            ->get(self::name())
            ->result_array();
    }

    public function getAll()
    {
        return $this->db
            ->get(self::name())
            ->result_array();
    }

    public function getCountForSubject($subjectId)
    {
        return $this->db
            ->where([self::FIELD_SUBJECT_ID => $subjectId])
            ->count_all(self::name());
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
        // Retrieve the sum of the delta of consumption transactions, ordered and limited to retrieve only highest values.
        $sumQuery = $this->db
            ->select(self::FIELD_SUBJECT_ID)
            ->select_sum(self::FIELD_DELTA, 'sum')
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->group_by(self::FIELD_SUBJECT_ID)
            ->limit($position)
            ->order_by('sum ' . 'ASC')
            ->get_compiled_select(self::name());

        // Check if the specified subject is within the earlier retrieved leaderboard.
        $result = $this->db
            ->from('(' . $sumQuery . ') `' . $this->db->dbprefix('t') . '`')
            ->where('t.' . self::FIELD_SUBJECT_ID . '=' . $subjectId)
            ->get();

        // If a row is returned, then the user is on the leaderboard.
        return $result->num_rows() > 0;
    }

    /**
     * Retrieves the sum of negative transactions grouped by week.
     *
     * @param int $subjectId The id of the subject user.
     * @return array
     */
    public function getSumDeltaSubjectIdByWeek(int $subjectId): array
    {
        return $this->db
            ->select('YEAR(' . self::FIELD_TIME . ') as year, WEEKOFYEAR(' . self::FIELD_TIME . ') as week')
            ->select_sum(self::FIELD_DELTA, 'sum')
            ->where(self::FIELD_SUBJECT_ID, $subjectId)
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->group_by('YEAR(' . self::FIELD_TIME . '), WEEKOFYEAR(' . self::FIELD_TIME . ')')
            ->order_by(self::FIELD_TIME, 'asc')
            ->get(self::name())
            ->result_array();
    }

    /**
     * Retrieves the sum of negative transactions grouped by week.
     *
     * @return array
     */
    public function getSumDeltaByWeek(): array
    {
        return $this->db
            ->select('YEAR(' . self::FIELD_TIME . ') as year, WEEKOFYEAR(' . self::FIELD_TIME . ') as week')
            ->select_sum(self::FIELD_DELTA, 'sum')
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->group_by('YEAR(' . self::FIELD_TIME . '), WEEKOFYEAR(' . self::FIELD_TIME . ')')
            ->order_by(self::FIELD_TIME, 'asc')
            ->get(self::name())
            ->result_array();
    }


    public function getConsumedWeeksForLeaderboard(int $position): array
    {
        $sumQuery = $this->db
            ->select(self::FIELD_SUBJECT_ID)
            ->select_sum(self::FIELD_DELTA, 'sum')
            ->where([self::FIELD_TYPE => self::TYPE_CONSUME])
            ->group_by(self::FIELD_SUBJECT_ID)
            ->limit($position)
            ->order_by('sum ' . 'ASC')
            ->get_compiled_select(self::name())
        ;

        $result = $this->db
            ->select([
                self::FIELD_SUBJECT_ID,
                'YEAR('.self::FIELD_TIME.') * 52 + WEEKOFYEAR('.self::FIELD_TIME.') as weekyear',
            ])
            ->from(self::name())
            ->where(self::FIELD_TYPE, self::TYPE_CONSUME)
            ->where_in(self::FIELD_SUBJECT_ID, 'SELECT `subject_id` FROM ('
                . $sumQuery . ') `' . $this->db->dbprefix('t') . '`',
                false
            )
            ->group_by(['weekyear', self::FIELD_SUBJECT_ID])
            ->order_by('time', 'ASC')
            ->get()
            ->result_array()
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
            'query' => [
                'UPDATE '.self::name().' SET type = IF(delta = -1, \'c\', \'u\')',
            ],
        ];
    }
}