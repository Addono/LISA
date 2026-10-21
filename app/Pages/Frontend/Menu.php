<?php
namespace App\Pages\Frontend;

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
use App\Pages\{PageFrame, ApiFrame, MenuFrame, MenuItem, MenuPage, MenuLink};

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 3-3-2017
 */
class Menu extends MenuFrame
{

    protected function menuElements() {
        return [
            new MenuPage(lang('Lisa.menu_consume'), ConsumePage::class, 'fa fa-glass fa-fw'),
            new MenuPage(lang('Lisa.menu_transactions'), TransactionsPage::class, 'fa fa-history fa-fw'),
            new MenuPage(lang('Lisa.menu_leaderboard'), LeaderboardPage::class, 'fa fa-trophy fa-fw'),
            new MenuPage(lang('Lisa.menu_admin'), AdminPage::class, 'fa fa-dashboard fa-fw'),
            new MenuPage(lang('Lisa.menu_reset_password'), ResetPasswordPage::class, 'fa fa-cogs fa-fw'),
            // Since only the pages which the user has access rights for will be shown, only one of these will be displayed.
            new MenuPage(lang('Lisa.login_login'), LoginPage::class, 'fa fa-sign-in fa-fw'),
            new MenuPage(lang('Lisa.logout_logout'), LogoutPage::class, 'fa fa-sign-out fa-fw'),
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