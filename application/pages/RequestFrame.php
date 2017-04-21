<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 21-4-2017
 */
abstract class RequestFrame extends CI_Controller implements RequestInterface
{
    /** @var array  */
    private   $models = [];

    /** @var array  */
    private   $libraries = [];

    /** @var array */
    private   $helpers = ['tables'];

    /** @var CI_Controller */
    protected $ci;

    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->models += $this->getModels();
        $this->libraries += $this->getLibraries();
        $this->helpers += $this->getHelpers();

        $this->ci = self::get_instance();
        $this->ci->load->model($this->models);
        $this->ci->load->library($this->libraries);
        $this->ci->load->helper($this->helpers);
    }


    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    abstract protected function getModels(): array;

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    abstract protected function getLibraries(): array;

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    abstract protected function getHelpers(): array;
}