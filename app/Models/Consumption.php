<?php
namespace App\Models;


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

    const CONSUMPTION_DEFAULT_AMOUNT = 0;

    protected function dependencies()
    {
        return [
            Transaction::class,
        ];
    }

    private function add($loginId) {
        return $this->db->table(self::name())->insert([
                Login::FIELD_LOGIN_ID => $loginId,
            ]);
    }

    /**
     * Retrieves the amount of consumptions a user has.
     *
     * @param $loginId
     * @return int
     */
    public function get($loginId) {
        $result = $this->db->table(self::name())
            ->where([Login::FIELD_LOGIN_ID => $loginId])
            ->get()
            ->getRowArray()[self::FIELD_AMOUNT] ?? null;

        // Check if the user was present.
        if ($result === null) {
            $this->add($loginId);

            return $this->get($loginId);
        } else {
            return $result;
        }
    }

    public function getAll() {
        return $this->db->table(self::name())
            ->get()
            ->getResultArray()
        ;
    }

    public function change($loginId, $authorId, $delta, $type) {
        $this->db->transStart();
            $oldAmount = $this->get($loginId);

            // Check if the tuple even exists.
            if ($oldAmount === null) {
                $newAmount = self::CONSUMPTION_DEFAULT_AMOUNT + $delta;
            } else {
                $newAmount = $oldAmount + $delta;
            }

            $success = $this->setBalance($loginId, $authorId, $newAmount, $delta, $type);
        $this->db->transComplete();

        return $this->db->transStatus() && $success;
    }

    /**
     * Sets the amount for a login id. NOTE: Creates a new tuple if the login id didn't have one already.
     *
     * @param $loginId
     * @param $authorId
     * @param $amount
     * @param $delta
     * @return bool
     */
    private function setBalance($loginId, $authorId, $amount, $delta, $type) {
        $this->db->transStart();
            $result = $this->db->table(self::name())->replace([
                        self::FIELD_AMOUNT => $amount,
                        Login::FIELD_LOGIN_ID => $loginId
                    ]
                );

            // Log this action as a new transaction.
            model(Transaction::class)->add($loginId, $authorId, $amount, $delta, $type);
        $this->db->transComplete();

        return $result;
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
                    'unique' => true,
                ],
                self::FIELD_AMOUNT => [
                    'type' => 'INT',
                    'constraint' => 9,
                    'default' => self::CONSUMPTION_DEFAULT_AMOUNT,
                ],
            ],
        ];
    }
}