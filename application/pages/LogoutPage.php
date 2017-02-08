<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class LogoutPage extends PageFrame
{

    public function __construct()
    {
        parent::addLibraries(['session']);
        parent::__construct();

        $this->ci->session->userId = null;
        redirect();
    }

    public function getHeader()
    {
        return [
            'logout-header'
        ];
    }

    public function getBody()
    {
        return false;
    }

    public function getData() {
        return false;
    }

    public function isVisible()
    {
        return false;
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    protected function accessibleBy()
    {
        return [
            ROLE_ADMIN,
            ROLE_USER,
        ];
    }
}