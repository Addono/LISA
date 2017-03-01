<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

/**
 * Class Consumption
 * @property CI_DB_query_builder $db
 */
class Consumption extends ModelFrame
{
    const FIELD_AMOUNT = 'amount';

    public function add($loginId) {
        return $this->db
            ->insert(self::name(), [
                Login::FIELD_LOGIN_ID => $loginId,
            ]);
    }

    public function get($loginId) {
        return $this->db
            ->where([Login::FIELD_LOGIN_ID => $loginId])
            ->get(self::name())
            ->row_array()[self::FIELD_AMOUNT];
    }

    public function change($loginId, $delta) {
        $this->db->trans_begin();
            $oldAmount = $this->get($loginId);
            $newAmount = $oldAmount + $delta;

            $success = $this->set($loginId, $newAmount);
        $this->db->trans_complete();

        return $this->db->trans_status() && $success;
    }

    public function set($loginId, $amount) {
        return $this->db
            ->update(
                self::name(),
                [self::FIELD_AMOUNT => $amount],
                [Login::FIELD_LOGIN_ID => $loginId]
            );
    }

    public function v1() {
        return [
            'add' => [
                Login::FIELD_LOGIN_ID => [
                    'type' => 'foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
                ],
                self::FIELD_AMOUNT => [
                    'type' => 'INT',
                    'constraint' => 9,
                    'default' => 0,
                ],
            ],
        ];
    }
}