<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class ModelFrame extends CI_Model
{
    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->database();
        $this->load->helper('tables');
    }

    public static function name() {
        return Install::getTableName(static::class);
    }
}