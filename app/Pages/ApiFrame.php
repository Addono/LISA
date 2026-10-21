<?php
namespace App\Pages;

use App\Models\Consumption;
use App\Models\Login;
use App\Models\LoginReset;
use App\Models\LoginReset_User;
use App\Models\LoginRole;
use App\Models\Login_User;
use App\Models\Login_User_LoginRole;
use App\Models\Login_User_LoginRole_Role;
use App\Models\ModelFrame;
use App\Models\Role;
use App\Models\Role_LoginRole;
use App\Models\Transaction;
use App\Models\User;
use App\Models\User_Consumption;
use App\Models\User_Consumption_LoginRole;
use App\Models\User_LoginRole;
use App\Models\User_Transaction;

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */
abstract class ApiFrame extends RequestFrame implements RequestInterface
{
    protected $result = [];

    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_ACCESS_DENIED = 'accessDenied';
    const STATUS_INTERNAL_SERVER_ERROR = 'internalServerError';

    const STATUS = 'status';

    abstract function call();

    final function setResult(string $key, $value){
        $this->result[$key] = $value;
    }

    final function appendResult(string $key, $value) {
        $this->result[$key][] = $value();
    }

    final function getData(string $key) {
        return $this->data[$key];
    }

    final function setError(string $message) {
        $this->setResult(self::STATUS_ERROR, $message);
        $this->setResult(self::STATUS, self::STATUS_ERROR);
    }

    final function setStatus(string $status) {
        $this->setResult(self::STATUS, $status);
    }

    /**
     * Show the page.
     *
     * @return null
     */
    public function show()
    {
        if ($this->hasAccess()) {
            $this->call();
            $result = $this->result;
        } else {
            $result = [self::STATUS => self::STATUS_ACCESS_DENIED];
        }

        // Check if the status is set, else return an internal server error instead.
        if (! array_key_exists(self::STATUS, $result)) {
            $result = [
                self::STATUS => self::STATUS_INTERNAL_SERVER_ERROR,
                self::STATUS_ERROR => 'statusNotSet',
            ];
        }

        return service('response')->setJSON($result);
    }
}