<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class DefaultPage extends PageFrame
{

    public function getViews()
    {
        if (isLoggedIn($this->ci->session)) {
            return [
                'not-user-header',
                'intersection',
            ];
        } else {
            return [
                'default-header',
                'intersection',
            ];
        }
    }

    public function isVisible()
    {
        return true;
    }

    public function hasAccess()
    {
        return true;
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
        if (isLoggedInAndHasRole($this->ci, Role::ROLE_USER)) {
            redirect('consume');
        }
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