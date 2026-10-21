<?php

namespace App\Pages;

abstract class RequestFrame implements RequestInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        helper(array_merge(['tables', 'mdbt_model', 'mdbt_form', 'login_state', 'form'], $this->getHelpers()));
    }

    abstract public function hasAccess(): bool;

    abstract protected function getModels(): array;

    abstract protected function getLibraries(): array;

    abstract protected function getHelpers(): array;
}