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
            new MenuPage(lang('application_menu_dashboard'), DefaultPage::class, ' fa fa-dashboard fa-fw'),
            [
                new MenuItem(lang('application_menu_manage_users'), 'fa fa-bar-chart-o'),
                new MenuPage(lang('application_menu_overview'), UserOverviewPage::class, ' fa fa-th-list fa-fw'),
                new MenuPage(lang('application_menu_new_user'), NewUserPage::class, ' fa fa-plus-circle fa-fw'),
            ],
        ];
    }

    function getListItemHtml($title, $link, $icon)
    {
        $iconHtml = $this->getIconHtml($icon);
        $titleHtml = $title===null?'':$title;
        $linkHtml = $link===null?$titleHtml:'<a href="'.$link.'">'.$iconHtml.$titleHtml.'</a>';

        return '<li>'.$linkHtml.'</li>';
    }

    function getSubMenuHtml($title, $icon, $content, $level)
    {
        $iconHtml = $this->getIconHtml($icon);
        switch ($level) {
            case 0:
                return "<ul class=\"nav\" id=\"side-menu\">\n" . $content . "</ul>\n";
                break;
            case 1:
            default:
                return '<li>'
                        . '<a href="#">' . $iconHtml . $title
                            . '<span class="fa arrow">'
                        . '</a>'
                        . '<ul class="nav nav-second-level">'.$content.'</ul>'
                    . '</li>';
                break;
        }
    }

    private function getIconHtml($icon) {
        if ($icon===null) {
            return '';
        } else {
            return '<i class="' . $icon . '"></i> ';
        }
    }
}