<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-2-2017
 */

class InsufficientRightsPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            '../../error/insufficientRights',
            'intersection',
        ];
    }

    public function hasAccess(): bool
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