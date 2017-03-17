<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class ResetPage
 * @property CI_DB_query_builder    $db
 */
class ResetPage extends PageFrame
{

    public function getViews()
    {
        return [
            'reset-header',
            'intersection',
        ];
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
        return [
            [
                'field' => 'password',
                'label' => lang('reset_field_password'),
                'rules' => [
                    'required',
                    'min_length[8]',
                    'matches[confirm-password]',
                ],
                'errors' => [
                    'required' => lang('reset_error_required'),
                    'min_length[8]' => lang('reset_error_password_not_strong_enough'),
                    'matches[confirm-password]' => lang('reset_error_password_not_equal'),
                ],
            ],
            [
                'field' => 'confirm-password',
                'label' => lang('reset_field_confirm_password'),
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => lang('reset_error_required'),
                ],
            ],
        ];
    }

    public function onFormSuccess()
    {
        $key = $this->params['subpage'];

        if ($this->ci->LoginReset->exists($key)) {
            $password = set_value('password');


        } else {
            $this->addWarningMessage('key not found');
        }
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $key = $this->params['subpage'];

        $exists = $this->ci->LoginReset->exists($key);
        if (!$exists) {
            redirect();
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
            LoginReset::class,
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