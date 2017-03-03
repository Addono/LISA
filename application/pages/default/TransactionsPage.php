<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class TransactionsPage extends PageFrame
{

    public function getViews()
    {
        return [
            'transactions-header',
            'intersection',
        ];
    }

    public function isVisible()
    {
        return true;
    }

    public function hasAccess()
    {
        return isLoggedInAndHasRole($this->ci, Role::ROLE_USER);
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
        $loginId = getLoggedInLoginId($this->ci->session);

        $transactions = $this->ci->User_Transaction->getAllForSubjectId($loginId);
        $this->setData('transactions', $transactions);

        $fields = [
            'author' => 'author_name',
            'subject' => 'subject_name',
            'amount' => Consumption::FIELD_AMOUNT,
            'delta' => Transaction::FIELD_DELTA,
            'time' => Transaction::FIELD_TIME,
        ];
        $this->setData('fields', $fields);
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
            User_Transaction::class,
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