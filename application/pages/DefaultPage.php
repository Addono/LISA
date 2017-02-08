<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class DefaultPage extends PageFrame
{

    public function getHeader()
    {
        return [
            'default-header'
        ];
    }

    public function getBody()
    {
        return false;
    }

    public function isVisible()
    {
        return true;
    }

    protected function accessibleBy()
    {
        return [
            ROLE_VISITOR,
            ROLE_ADMIN,
            ROLE_VISITOR,
        ];
    }

    protected function getFormValidationRules()
    {
        return false;
    }
}