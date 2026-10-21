<?php
namespace App\Pages\Frontend;

use App\Models\Consumption;
use App\Models\Login;
use App\Models\LoginReset;
use App\Models\LoginReset_User;
use App\Models\LoginRole;
use App\Models\Login_User;
use App\Models\Login_User_LoginRole;
use App\Models\Login_User_LoginRole_Role;
use App\Models\ModelFrame;
use App\Models\Role;
use App\Models\Role_LoginRole;
use App\Models\Transaction;
use App\Models\User;
use App\Models\User_Consumption;
use App\Models\User_Consumption_LoginRole;
use App\Models\User_LoginRole;
use App\Models\User_Transaction;
use App\Pages\{PageFrame, ApiFrame, MenuFrame, MenuItem, MenuPage, MenuLink};


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

    public function getViews(): array
    {
        switch ($this->show) {
            case self::SHOW_SUCCESS:
                $this->addSuccessMessage(lang('Lisa.reset_success'));
                return [
                    'reset-success-header',
                ];
            case self::SHOW_KEY_NOT_FOUND:
                $this->addWarningMessage(lang('Lisa.reset_error_key_not_found'));
                return [
                    '404-header',
                ];
            case self::SHOW_FORM:
                return [
                    'reset-header',
                ];
        }
    }

    public function hasAccess(): bool
    {
        return true;
    }

    protected function getFormValidationRules()
    {
        return [
            [
                'field' => 'password',
                'label' => lang('Lisa.form_field_password'),
                'rules' => [
                    'required',
                    'min_length[6]',
                    'matches[confirm-password]',
                ],
                'errors' => [
                    'required' => lang('Lisa.form_error_required'),
                    'min_length[6]' => lang('Lisa.form_error_password_not_strong_enough'),
                    'matches[confirm-password]' => lang('Lisa.form_error_password_not_equal'),
                ],
            ],
            [
                'field' => 'confirm-password',
                'label' => lang('Lisa.form_field_confirm_password'),
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => lang('Lisa.form_error_required'),
                ],
            ],
        ];
    }

    public function onFormSuccess()
    {
        $key = $this->getDataKey('subpage');
        $password = set_value('password');

        $this->success = model(LoginReset_User::class)->updatePassword($key, $password);
        if (!$this->success) {
            $this->addDangerMessage(lang('Lisa.reset_error_update_password_failed'));
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
            $key = $this->getDataKey('subpage');
            $exists = model(LoginReset::class)->exists($key);
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
    protected function getModels(): array
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
    protected function getLibraries(): array
    {
        return [];
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