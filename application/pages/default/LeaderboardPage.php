<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class LeaderboardPage extends PageFrame
{

    const LEADERBOARD_SIZE = 10;
    private static $hasAccess = null;

    public function getViews(): array
    {
        return [
            'leaderboard-header',
        ];
    }

    public function hasAccess(): bool
    {
        if (static::$hasAccess == null) {
            if (! isLoggedInAndHasRole($this->ci, Role::ROLE_USER)) {
                static::$hasAccess = false;
            } else {
                $loginId = getLoggedInLoginId($this->ci->session);
                static::$hasAccess = $this->ci->Transaction->getSumDeltaSubjectIdWithinTop($loginId, self::LEADERBOARD_SIZE);
            }
        }

        return  static::$hasAccess;
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
        // Define all models
        /** @var Transaction $transactionModel */
        $transactionModel = $this->ci->Transaction;
        /** @var User_Transaction $userTransactionModel */
        $userTransactionModel = $this->ci->User_Transaction;

        // Load the transactions Javascript
        /** @var Transactions $transactionsLibrary */
        $transactionsLibrary = $this->ci->transactions;
        $this->addScript($transactionsLibrary->getJavascript($this->data['group']));
        $this->setData('transactionsLibrary', $transactionsLibrary);

        // Retrieve the data of all users on the leaderboard
        $leaderboard = $userTransactionModel->getSumDeltaForAllSubjectId(false, self::LEADERBOARD_SIZE);
        $this->setData('entries', $leaderboard);

        $fields = [
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'sum' => 'sum',
            'login_id' => Login::FIELD_LOGIN_ID,
        ];
        $this->setData('fields', $fields);

        $sumByWeek = $transactionModel->getSumDeltaByWeek();

        // Load the transactions graph Javascript
        /** @var Graph $graphLibrary */
        $graphLibrary = $this->ci->graph;
        $this->addScript($graphLibrary->includeJsLibrary());
        $this->addScript($graphLibrary->getGraphForTransactions($sumByWeek));
        $this->addScript('<script>updateData();</script>');
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
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
    protected function getLibraries(): array
    {
        return [
            'Transactions',
            'Graph',
        ];
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