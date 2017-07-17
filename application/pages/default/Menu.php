<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 3-3-2017
 */
class Menu extends MenuFrame
{

    protected function menuElements() {
        return [
            [
                new MenuItem('Sublevel'),
                new MenuLink('Github', 'https://github.com/Addono', 'fa fa-github fa-fw'),
                new MenuLink('Another link', 'https://w3.org'),
                new MenuPage('Menu params!', ResetPage::class, null, ['abc']),
            ],
            new MenuPage(lang('menu_admin'), AdminPage::class, 'fa fa-dashboard fa-fw'),
            new MenuPage(lang('menu_reset_password'), ResetPasswordPage::class, 'fa fa-cogs fa-fw'),
            // Since only the pages which the user has access rights for will be shown, only one of these will be displayed.
            new MenuPage(lang('login_login'), LoginPage::class, 'fa fa-sign-in fa-fw'),
            new MenuPage(lang('logout_logout'), LogoutPage::class, 'fa fa-sign-out fa-fw'),
        ];
    }

    function getListItemHtml($title, $link, $icon) {
        $iconHtml = $this->getIconHtml($icon);
        $linkHtml = $link===null?$title:'<a href="'.$link.'">'.$iconHtml.$title.'</a>';

        return '<li>'.$linkHtml.'</li>';
    }

    function getSubMenuHtml($title, $icon, $content, $level) {
        $iconHtml = $this->getIconHtml($icon);
        switch ($level) {
            case 0:
                return "<ul class=\"nav navbar-nav\">\n" . $content . "</ul>\n";
                break;
            default:
                return '<li class="dropdown">'
                        . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $iconHtml . $title
                            . '<b class="caret"></b>'
                        . '</a>'
                        . '<ul class="dropdown-menu dropdown-menu-right">'.$content.'</ul>'
                    . '</li>';
                break;
        }
    }

    private function getIconHtml($icon) {
        if ($icon === null) {
            return '';
        } else {
            return '<i class="' . $icon . '"></i> ';
        }
    }
}