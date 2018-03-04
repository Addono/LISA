<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */

/**
 * Class TransactionApiPage
 */
class TransactionApiPage extends ApiFrame
{

    const USER_NOT_FOUND = 'userNotFound';
    const INVALID_ARGUMENT = 'invalidArgument';
    const DATABASE_ERROR = 'databaseError';

    function call()
    {
        $action = set_value('action');
        switch ($action) {
            case 'buy':
                $this->actionBuy();
                break;
            case 'updateData':
                $this->actionUpdateData();
                break;
            default:
                $this->setError(self::INVALID_ARGUMENT);
                break;
        }
    }

    function hasAccess(): bool
    {
        // Ensure that the request originates from a user.
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_USER]);
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            Login::class,
            Consumption::class,
            User::class,
            Role::class,
            LoginRole::class,
            Role_LoginRole::class,
            User_Consumption_LoginRole::class,
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

    private function actionUpdateData(): void
    {
        $result = $this->ci->Consumption->getAll();

        if ($result !== null) {
            $this->setStatus(ApiFrame::STATUS_SUCCESS);
            $this->setResult('updated_data', $result);
        } else {
            $this->setError(self::DATABASE_ERROR);
        }
    }

    private function actionBuy(): void
    {
        $id = set_value('id');

        // Check that id is set.
        if ($id === '' || !is_numeric($id)) {
            $this->setError(self::INVALID_ARGUMENT);
            $this->setResult('id', $id);
            return;
        }

        // Check that a valid id is passed.
        if (!$this->ci->Role_LoginRole->userHasRole($id, Role::ROLE_USER)) {
            // error invalid user specified.
            $this->setError(self::USER_NOT_FOUND);
            return;
        }

        $authorId = getLoggedInLoginId($this->ci->session);

        $amount = 1;
        $result = $this->ci->Consumption->change($id, $authorId, -$amount);

        if ($result) {
            $this->setStatus(ApiFrame::STATUS_SUCCESS);
            $this->setResult('name', $this->ci->User->getName($id));
            $this->setResult('new_amount', $this->ci->Consumption->get($id));
            $this->setResult('amount', $amount);
            $this->setResult('updated_data', $this->ci->Consumption->getAll());
        } else {
            $this->setError(self::DATABASE_ERROR);
        }
    }
}