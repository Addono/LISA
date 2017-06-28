<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class ResetPasswordPage
 */
class ResetPasswordPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'intersection',
        ];
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    public function hasAccess(): bool
    {
        return isLoggedIn($this->ci->session);
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $id = getLoggedInLoginId($this->ci->session);

        $key = $this->ci->LoginReset->add($id);

        if ($key) {
            redirect('Reset/' . $key);
        } else {
            redirect('PageNotFound'); // @todo redirect to 500 page instead of 404.
        }
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            LoginReset::class,
        ];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries(): array
    {
        return ['session'];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers(): array
    {
        return [];
    }
}