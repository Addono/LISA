<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */
class ModelFrame extends CI_Model
{
    protected  $tableName;

    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->database();
        $this->load->helper('tables');

        $class = get_class($this);
        $this->tableName = Install::getTableName($class);
    }

}