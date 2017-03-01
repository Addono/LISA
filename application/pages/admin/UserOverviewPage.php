<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-2-2017
 */
class UserOverviewPage extends PageFrame
{

    /**
     * The views to be shown as header.
     *
     * @return array|null
     */
    public function getViews()
    {
        return [];
    }

    /**
     * If the page should be visible in the menu.
     *
     * @return boolean
     */
    public function isVisible()
    {
        return true;
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
    }

    /**
     * Function which is called after the views are rendered.
     */
    public function afterView()
    {
    }

    /**
     * If the current user has access to this page.
     *
     * @return boolean
     */
    public function hasAccess()
    {
        return isLoggedInAndHasRole($this->ci->session, $this->ci->LoginRole, [Role::ROLE_ADMIN]);
    }

    /**
     * The form validation rules.
     *
     * @return array
     */
    protected function getFormValidationRules()
    {
        return null;
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels()
    {
        return [
            LoginRole::class,
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