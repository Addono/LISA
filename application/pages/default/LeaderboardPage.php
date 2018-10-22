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

        return static::$hasAccess;
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

        // Retrieve the streak data
        $this->setData('streakLength', $this->queryStreakLength());

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

    /**
     * Retrieve the streak length for all users.
     * @return array int => int Key value pair where the keys are all user ids on the leader board and the values are the amount
     *      of consecutive weeks since this week where each user has been consuming.
     */
    private function queryStreakLength(): array
    {
        /** @var Transaction $transactionModel */
        $transactionModel = $this->ci->Transaction;

        // Retrieve all week user pairs for which there has been consumptions by leader board members.
        $consumedWeeks = $transactionModel->getConsumedWeeksForLeaderboard(self::LEADERBOARD_SIZE);

        // Group the consumed week data by user
        $consumedWeeksByUser = [];
        foreach ($consumedWeeks as $week) {
            if (!key_exists(Transaction::FIELD_SUBJECT_ID, $week)) {
                $consumedWeeksByUser[Transaction::FIELD_SUBJECT_ID] = [];
            }
            $consumedWeeksByUser[$week[Transaction::FIELD_SUBJECT_ID]][] = $week["weekyear"];
        }

        // Query the database for the current week, the DB is used instead of PHP since their interpretation of time might differ.
        $currentWeek = (int)$this->ci->db->query('SELECT YEAR(NOW()) * 52 + WEEKOFYEAR(NOW()) as `currentWeek`')->row()->currentWeek;

        // Count the amount of consecutive weeks since this week
        $streakLength = [];
        foreach ($consumedWeeksByUser as $userId => $weeks) {
            // Check if the user has consumed during the current week. If so then this increases the streak length
            // already counts towards the streak length.
            if (end($weeks) === $currentWeek) {
                $streakLength[$userId] = 1;
                array_pop($weeks); // Remove the last week, since we already counted it.
            } else {
                $streakLength[$userId] = 0;
            }


            for ($i = count($weeks) - 1, $weekCounter = $currentWeek - 1; key_exists($i, $weeks) && $weeks[$i--] == $weekCounter--;) {
                $streakLength[$userId]++;
            }
        }

        return $streakLength;
    }
}