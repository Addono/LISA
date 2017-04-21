<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */
abstract class ApiFrame extends RequestFrame implements RequestInterface
{
    protected $result = [];

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
            $result = ['state' => '401'];
        }

        echo json_encode($result);
    }
}