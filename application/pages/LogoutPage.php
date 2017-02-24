<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class LogoutPage
 * todo remove username from header
 */
class LogoutPage extends PageFrame
{

    public function getHeader()
    {
        return [
            'logout-header'
        ];
    }

    public function getBody()
    {
        return false;
    }

    public function getData() {
        return false;
    }

    public function isVisible()
    {
        return false;
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    public function hasAccess()
    {
        return $this->ci->session->userId !== null;
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $this->ci->session->userId = null;
        redirect();
    }

    /**
     * Function which is called after the views are rendered.
     */
    public function afterView()
    {
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels()
    {
        return [];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries()
    {
        return ['session'];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers()
    {
        return [];
    }
}