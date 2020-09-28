<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 24-2-2017
 */

/**
 * todo make abstract, to achieve this another way of loading this model has te be used.
 *
 * Class ModelFrame
 */
class ModelFrame extends CI_Model
{
    private static $tablePrefix;
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $dependencies = $this->dependencies();

        $this->ci->load->database();
        $this->load->model($dependencies);
        $this->load->helper([
            'tables',
            'mdbt_model',
        ]);

        self::$tablePrefix = $this->db->dbprefix(null);
    }

    public static function name() {
        return Install::getTableName(static::class, self::$tablePrefix);
    }

    protected function dependencies() {
        return [];
    }
}