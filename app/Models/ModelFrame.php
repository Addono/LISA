<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelFrame extends Model
{
    public static function name(): string
    {
        $name = (new \ReflectionClass(static::class))->getShortName();

        return db_connect()->getPrefix() . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
    }
}