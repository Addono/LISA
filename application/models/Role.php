<?php

/**
 * @property  CI_DB_query_builder   $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class Role extends ModelFrame
{

    public function r1() {
        return [
            'add' => [
                'role_id' => [
                    'type' => 'INT',
                    'constraint' => ID_LENGTH,
                    'unsigned' => TRUE,
                ],
                'role_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => NAME_LENGTH,
                ],
            ],
        ];
    }
}