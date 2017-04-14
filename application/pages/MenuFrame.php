<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 3-3-2017
 */
abstract class MenuFrame extends CI_Controller
{
    private $ci;

    public function __construct($group) {
        $this->ci = get_instance();
        $this->group = $group;
    }

    public function getMenu() {
        $menuElements = $this->menuElements();

        return $this->buildMenu($menuElements, 0);
    }

    private function buildMenu($elements, $level) {
        if (is_array($elements)) {
            $html = '';
            $title = null;
            $icon = null;

            foreach ($elements as $key => $element) {
                if ($key === 0 && !is_array($element) && get_class($element) == MenuItem::class) {
                    /** @var MenuItem $element */
                    $title = $element->getTitle();
                    $icon = $element->getIcon();
                } else {
                    $html .= $this->buildMenu($element, $level + 1);
                }
            }

            return $this->getSubMenuHtml($title, $icon, $html, $level);
        } else {
            return $this->buildListItem($elements);
        }
    }

    /**
     * @param MenuItem $menuItem
     * @return string
     */
    private function buildListItem($menuItem) {
        // If this is a page, check if the user has the rights to even visit it, else hide it.
        if (get_class($menuItem) == MenuPage::class) {
            /** @var MenuPage $menuItem*/
            $pageClass = $menuItem->getPage();

            require_once(APPPATH.'pages/'.$this->group.'/'.$pageClass.'.php');

            /** @var PageFrame $page */
            $page = new $pageClass(false);

            if (!$page->hasAccess()) {
                return '';
            }
        }

        $title = $menuItem->getTitle();
        $link = $this->getLink($menuItem);
        $icon = $menuItem->getIcon();

        return $this->getListItemHtml($title, $link, $icon) . "\n";
    }

    abstract function getListItemHtml($title, $link, $icon);

    abstract function getSubMenuHtml($title, $icon, $content, $level);

    private function getLink($menuItem) {
        switch (get_class($menuItem)) {
            case MenuLink::class:
                /** @var MenuLink $menuItem */
                return $menuItem->getLink();
            case MenuPage::class:
                /** @var MenuPage $menuItem */
                $page = $menuItem->getPage();
                $pageName = substr($page, 0, -strlen('Page'));

                $pageParams = $menuItem->getParams();
                if (empty($pageParams)) {
                    $linkParams = '';
                } else {
                    $linkParams = '/' . implode('/', $pageParams);
                }

                return site_url($this->group . '/' . $pageName . $linkParams);
            case MenuItem::class:
                return null;
            default:
                throw new Exception('Invalid menu item parsed.');
        }
    }

    protected  abstract function menuElements();
}

class MenuItem {
    protected $title;
    protected $icon;

    public function __construct($title, $icon = null) {
        $this->title = $title;
        $this->icon = $icon;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getIcon() {
        return $this->icon;
    }
}

class MenuLink extends MenuItem {
    protected $link;

    public function __construct($title, $link, $icon = null)
    {
        parent::__construct($title, $icon);

        $this->link = $link;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function getLink() {
        return $this->link;
    }
}

class MenuPage extends MenuItem {
    protected $page;
    protected $params;

    public function __construct($title, $page, $icon = null, $params = []) {
        parent::__construct($title, $icon);

        $this->page = $page;
        $this->params = $params;
    }

    public function setPage($page) {
        $this->page = $page;
    }

    public function getPage() {
        return $this->page;
    }

    public function getParams() {
        return $this->params;
    }
}