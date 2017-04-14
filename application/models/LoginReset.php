<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 11-3-2017
 */

/**
 * Class LoginReset
 * @property CI_DB_query_builder    $db
 */
class LoginReset extends ModelFrame
{
    const FIELD_DATE = 'date';
    const FIELD_RESET_KEY = 'reset_key';

    protected function dependencies()
    {
        return [
            Login::class,
        ];
    }

    public function add($loginId) {
        do {
            $key = sha1(random_bytes(30));
        } while ($this->exists($key));

        $this->db->replace(
            self::name(),
            [
                self::FIELD_RESET_KEY => $key,
                Login::FIELD_LOGIN_ID => $loginId,
            ]
        );
    }

    public function exists($key) {
        $res = $this->db
            ->where([self::FIELD_RESET_KEY => $key])
            ->get(self::name())
            ->row_array();

        if ($res===null) {
            return false;
        } else {
            return $res[Login::FIELD_LOGIN_ID];
        }
    }

    public function get($key) {
        return $this->db
            ->where([self::FIELD_RESET_KEY => $key])
            ->get(self::name())
            ->row_array();
    }

    public function remove($key) {
        return $this->db
            ->delete(self::name(), [self::FIELD_RESET_KEY => $key]);
    }

    public function v1() {
        return [
            'requires' => [
                Login::class => 1,
            ],
            'add' => [
                Login::FIELD_LOGIN_ID => [
                    'type' => 'primary|foreign',
                    'table' => Login::name(),
                    'field' => Login::FIELD_LOGIN_ID,
                ],
                self::FIELD_RESET_KEY => [
                    'type' => 'varchar',
                    'constraint' => 255,
                    'unique' => TRUE,
                ],
                self::FIELD_DATE => [
                    'type' => 'TIMESTAMP',
                ],
            ],
        ];
    }
}