<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Install.php');
require_once(APPPATH . 'pages/RequestFrame.php');
require_once(APPPATH . 'pages/RequestInterface.php');
require_once(APPPATH . 'pages/MenuFrame.php');

/**
 * @property    CI_Form_validation  $form_validation
 * @property    Login               $Login
 * @property    CI_Session          $session
 * @property    CI_DB_query_builder $db
 */
class Handler extends CI_Controller {
    const DEFAULT_PAGE = 'default';

    const GROUP_FRONTEND = 'default';

    private $data = [
        'messages' => [
            'success' => [],
            'info' => [],
            'warning' => [],
            'danger' => [],
        ],
        'scripts' => [],
    ];

    public function index($group = self::GROUP_FRONTEND, $page = self::DEFAULT_PAGE, $subPage = null)
    {
        if (!file_exists(APPPATH . 'pages/'.$group)) {
            $subPage = $page;
            $page = $group;
            $group = self::GROUP_FRONTEND;
        }

        // Add the group to data such that views can generate urls accordingly.
        $this->data['group'] = $group;

        $this->data['ci'] = $this;

        // Import all helpers and libraries.
        $this->load->helper([
            'url',
            'form',
            'language',
            'tables',
            'login_state',
        ]);
        $this->load->model([
            'ModelFrame', // Ensure to load model frame first, since other models might depend on it.
            'Login',
        ]);
        $this->load->library([
            'session',
            'form_validation'
        ]);
        $language = 'nederlands';
        $this->lang->load('default', $language);
        $this->lang->load($group.'/application', $language);
        if (file_exists('./application/language/'.$language.'/'.$group.'/custom_lang.php')) {
            $this->lang->load($group.'/custom', $language);
        }

        // Check if the user is logged in
        $this->data['loggedIn'] = isLoggedIn($this->session);
        if($this->data['loggedIn']) {
            $this->data['username'] = $this->Login->getUsername(getLoggedInLoginId($this->session));
        }

        $this->buildMenu($group);
        $this->showPage($group, $page, $subPage);
    }

    /**
     * @param string $page
     * @param string $subPage
     * @param string $group
     */
    private function showPage($group, $page, $subPage)
    {
        $pageControllerName = ucfirst($page) . 'Page';
        $pageControllerFile = './application/pages/'.$group.'/' . $pageControllerName . '.php';
        if (file_exists($pageControllerFile)) {
            require_once($pageControllerFile);

            /** @var RequestInterface $pageController */
            $pageController = new $pageControllerName($this->data);

            $pageController->show();
        } else {
            if ($page !== 'PageNotFound' && $page !== 'login') {
                redirect($group.'/PageNotFound');
            } else {
                show_404();
            }
        }
    }

    private function buildMenu($group) {
        require_once(APPPATH.'/pages/'.$group.'/Menu.php');
        $menu = new Menu($group);

        $this->data['menu'] = $menu->getMenu();
    }
}
