<?php

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
        if (! array_key_exists(self::STATUS, $this->result)) {
            $this->result = [
                self::STATUS => self::STATUS_INTERNAL_SERVER_ERROR,
                self::STATUS_ERROR => 'statusNotSet',
            ];
        }

        echo json_encode($result);
    }
}