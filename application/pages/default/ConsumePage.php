<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
class ConsumePage extends PageFrame
{

    /**
     * The views to be shown.
     *
     * @return array|null Array with the names of the views inbetween the header and footer, null if no views should be shown.
     */
    public function getViews()
    {
        return [
            'consume-header',
            'intersection'
        ];
    }

    /**
     * If the page should be visible in the menu.
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->hasAccess();
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
    }

    /**
     * If the current user has access to this page.
     *
     * @return boolean
     */
    public function hasAccess()
    {
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_USER]);
    }

    /**
     * The form validation rules.
     *
     * @return array|bool
     */
    protected function getFormValidationRules()
    {
        return false;
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