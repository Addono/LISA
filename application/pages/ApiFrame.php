<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */
abstract class ApiFrame extends RequestFrame implements RequestInterface
{
    protected $result = [];

    const STATUS_ERROR = 'error';
    const STATUS_ACCESS_DENIED = 'accessDenied';

    abstract function call();

    final function setResult(string $key, $value) {
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
        $this->setResult('status', self::STATUS_ERROR);
    }

    /**
     * Show the page.
     *
     * @return null
     */
    public function show()
    {
        $this->call();

        if ($this->hasAccess()) {
            $result = $this->result;
        } else {
            $result = ['status' => self::STATUS_ACCESS_DENIED];
        }

        echo json_encode($result);
    }
}