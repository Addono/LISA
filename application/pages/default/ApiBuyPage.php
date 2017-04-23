<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */

/**
 * Class ApiBuyPage
 */
class ApiBuyPage extends ApiFrame
{

    function call()
    {
        $id = set_value('id');

        if ($id === '' || ! is_numeric($id)) {
            $this->setResult('error', 'invalidArgument');
        } else {
            if ($this->ci->Role_LoginRole->userHasRole($id)) {


                $authorId = getLoggedInLoginId($this->ci->session);
                $this->ci->Consumption->change($id, $authorId, -1);
            } else {
                // error invalid user specified.
            }
        }
    }

    function hasAccess(): bool
    {
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
}