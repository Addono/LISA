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

    protected function dependencies()
    {
        return [
            Login::class,
            Consumption::class,
        ];
    }

    public function add($subjectId, $authorId, $amount, $delta) {
        $this->db->insert(
            self::name(),
            [
                self::FIELD_SUBJECT_ID => $subjectId,
                self::FIELD_AUTHOR_ID => $authorId,
                Consumption::FIELD_AMOUNT => $amount,
                self::FIELD_DELTA => $delta,
            ]
        );
    }

    public function v1() {
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
}