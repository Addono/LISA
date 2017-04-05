<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */
class ApiPage extends ApiFrame
{

    function call()
    {
        $this->setResult('test', 'abc');
    }

    function hasAccess(): bool
    {
        return $this->getData('loggedIn');
    }
}