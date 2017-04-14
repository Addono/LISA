<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class ResetPage
 */
class ResetPage extends PageFrame
{

    private $showForm;

    const SUCCESS_STATUS = self::SHOW_SUCCESS;
    private $success = false;
    private $show;

    const SHOW_KEY_NOT_FOUND = 'KeyNotFound';
    const SHOW_SUCCESS = 'success';
    const SHOW_FORM = 'form';

    public function getViews()
    {
        switch ($this->show) {
            case self::SHOW_SUCCESS:
                $this->addSuccessMessage(lang('reset_success'));
                return [
                    'reset-success-header',
                    'intersection',
                ];
            case self::SHOW_KEY_NOT_FOUND:
                $this->addWarningMessage(lang('reset_error_key_not_found'));
                return [
                    '404-header',
                    'intersection',
                ];
            case self::SHOW_FORM:
                return [
                    'reset-header',
                    'intersection',
                ];
        }
    }

    public function hasAccess()
    {
        return !isLoggedIn($this->ci->session);
    }

    protected function getFormValidationRules()
    {
        return [
            [
                'field' => 'password',
                'label' => lang('form_field_password'),
                'rules' => [
                    'required',
                    'min_length[8]',
                    'matches[confirm-password]',
                ],
                'errors' => [
                    'required' => lang('form_error_required'),
                    'min_length[8]' => lang('form_error_password_not_strong_enough'),
                    'matches[confirm-password]' => lang('form_error_password_not_equal'),
                ],
            ],
            [
                'field' => 'confirm-password',
                'label' => lang('form_field_confirm_password'),
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => lang('form_error_required'),
                ],
            ],
        ];
    }

    public function onFormSuccess()
    {
        $key = $this->params['subpage'];
        $password = set_value('password');

        $this->success = $this->ci->LoginReset_User->updatePassword($key, $password);
        if (!$this->success) {
            $this->addDangerMessage(lang('reset_error_update_password_failed'));
        }
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        if ($this->success) {
            $this->showForm = false;
            $this->show = self::SHOW_SUCCESS;
        } else {
            $key = $this->params['subpage'];
            $exists = $this->ci->LoginReset->exists($key);
            if ($exists) {
                $this->show = self::SHOW_FORM;
            } else {
                $this->show = self::SHOW_KEY_NOT_FOUND;
            }
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
            LoginReset_User::class,
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