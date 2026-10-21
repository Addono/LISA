<?php

namespace App\Controllers;

use App\Models\Login;
use CodeIgniter\Exceptions\PageNotFoundException;

class Handler extends BaseController
{
    public function index(string $group = 'default', string $page = 'default', ?string $subPage = null)
    {
        if (! in_array($group, ['default', 'admin'], true)) {
            $subPage = $page === 'default' ? null : $page;
            $page = $group;
            $group = 'default';
        }

        helper(['url', 'form', 'tables', 'mdbt_model', 'mdbt_form', 'login_state']);
        $namespace = $group === 'admin' ? 'App\\Pages\\Admin\\' : 'App\\Pages\\Frontend\\';
        $pageClass = $namespace . ucfirst($page) . 'Page';

        if (! class_exists($pageClass)) {
            throw PageNotFoundException::forPageNotFound();
        }

        service('language')->setLocale(config('App')->defaultLocale);
        $menuClass = $namespace . 'Menu';
        $loggedIn = isLoggedIn(session());
        $data = [
            'group' => $group,
            'page' => $page,
            'subpage' => $subPage,
            'loggedIn' => $loggedIn,
            'messages' => ['success' => [], 'info' => [], 'warning' => [], 'danger' => []],
            'scripts' => [],
            'menu' => (new $menuClass($group))->getMenu(),
        ];

        if ($loggedIn) {
            $data['username'] = model(Login::class)->getUsername(getLoggedInLoginId(session()));
        }

        return (new $pageClass($data))->show();
    }
}