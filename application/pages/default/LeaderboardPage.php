<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class LeaderboardPage extends PageFrame
{

    const LEADERBOARD_SIZE = 10;

    public function getViews(): array
    {
        return [
            'leaderboard-header',
            'intersection',
        ];
    }

    public function hasAccess(): bool
    {
        if (! isLoggedInAndHasRole($this->ci, Role::ROLE_USER)) {
            return false;
        }

        $loginId = getLoggedInLoginId($this->ci->session);
        $isOnTheLeaderboard = $this->ci->Transaction->getSumDeltaSubjectIdWithinTop($loginId, false, self::LEADERBOARD_SIZE);

        return  $isOnTheLeaderboard;
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
        $leaderboard = $this->ci->User_Transaction->getSumDeltaForAllSubjectId(false, self::LEADERBOARD_SIZE);

        $this->setData('entries', $leaderboard);

        $fields = [
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'sum' => 'sum',
        ];
        $this->setData('fields', $fields);
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