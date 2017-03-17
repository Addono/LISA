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

    public function getViews()
    {
        return [
            'logout-header',
            'intersection',
        ];
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    public function hasAccess()
    {
        return isLoggedIn($this->ci->session);
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        setLoggedOut($this->ci->session);
        redirect();
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