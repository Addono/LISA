<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */

/**
 * Class TransactionApiPage
 */
class ApiResetPage extends ApiFrame
{

    const USER_NOT_FOUND = 'userNotFound';
    const INVALID_ARGUMENT = 'invalidArgument';
    const DATABASE_ERROR = 'databaseError';


    function call()
    {
        $id = set_value('id');

        // Check that id is set.
        if ($id === '' || ! is_numeric($id)) {
            $this->setError(self::INVALID_ARGUMENT);
            $this->setResult('id', $id);
            return;
        }

        // Check that an valid id is passed.
        if (!$this->ci->Role_LoginRole->userHasRole($id, Role::ROLE_USER)) {
            // error invalid user specified.
            $this->setError(self::USER_NOT_FOUND);
            return;
        }

        $result = $this->ci->LoginReset->add($id);

        if ($result) {
            $this->setStatus(ApiFrame::STATUS_SUCCESS);
            $this->setResult('link', site_url('Reset/'.$result));
        } else {
            $this->setError(self::DATABASE_ERROR);
        }
    }

    function hasAccess(): bool
    {
        // Ensure that the request originates from a admin.
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_ADMIN]);
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
            Role::class,
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