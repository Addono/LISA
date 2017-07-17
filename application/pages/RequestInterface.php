<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */
interface RequestInterface
{

    public function __construct(array $data);

    /**
     * Show the page.
     *
     * @return null
     */
    public function show();
}