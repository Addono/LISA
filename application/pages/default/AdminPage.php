<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 3-3-2017
 */

class AdminPage extends PageFrame
{

    public function getViews()
    {
        return [
        ];
    }

    public function hasAccess()
    {
        return isLoggedInAndHasRole($this->ci, Role::ROLE_ADMIN);
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        redirect('admin');
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels()
    {
        return [
            Role::class,
        ];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries()
    {
        return [];
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