<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 3-3-2017
 */
class Menu extends MenuFrame
{

    protected function menuElements()
    {
        return [
            [
                new MenuItem('Sublevel'),
                new MenuLink('Github', 'https://github.com/Addono', 'fa fa-github fa-fw'),
                new MenuLink('Another link', 'https://w3.org'),
            ],
            // Since only the pages which the user has access rights for will be shown, only one of these will be displayed.
            new MenuPage('Login', LoginPage::class, 'fa fa-sign-in fa-fw'),
            new MenuPage('Logout', LogoutPage::class, 'fa fa-sign-out fa-fw'),
        ];
    }

    function getListItemHtml($title, $link, $icon)
    {
        $iconHtml = $icon===null?'':'<i class="' . $icon . '"></i> ';
        $linkHtml = $link===null?$title:'<a href="'.$link.'">'.$iconHtml.$title.'</a>';

        return '<li>'.$linkHtml.'</li>';
    }

    function getSubMenuHtml($title, $content, $level)
    {
        switch ($level) {
            case 0:
                return "<ul class=\"nav navbar-nav\">\n" . $content . "</ul>\n";
                break;
            default:
                return '<li class="dropdown">'
                        . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $title
                            . '<b class="caret"></b>'
                        . '</a>'
                        . '<ul class="dropdown-menu dropdown-menu-right">'.$content.'</ul>'
                    . '</li>';
                break;
        }
    }
}