<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Install.php');
require_once('./application/pages/PageFrame.php');

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
    ];

    public function index($group = self::GROUP_FRONTEND, $page = self::DEFAULT_PAGE, $subPage = null)
    {
        if (!file_exists('./application/pages/'.$group)) {
            $subPage = $page;
            $page = $group;
            $group = self::GROUP_FRONTEND;
        }

        // Add the group to data such that views can generate urls accordingly.
        $this->data['group'] = $group;

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
        $this->lang->load('default', 'english');
        $this->lang->load($group.'/application', 'english');

        // Check if the user is logged in
        $this->data['loggedIn'] = isLoggedIn($this->session);
        if($this->data['loggedIn']) {
            $this->data['username'] = $this->Login->getUsername(getLoggedInLoginId($this->session));
        }

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

            /** @var PageFrame $pageController */
            $pageController = new $pageControllerName();

            if (!$pageController->hasAccess()) {
                redirect($group.'/login'); // todo add insufficient rights page
                exit;
            }
            $pageController->setParams([$page, $subPage]);

            // Call the form success if a valid form was submitted.
            if ($pageController->getFormSuccess()) {
                $pageController->onFormSuccess();
            }

            $pageController->beforeView();

            $views = $pageController->getViews();
            $views = $views ? $views : [];
            $data = $pageController->getData();
            $data = $data ? $data : [];

            $data = array_merge($this->data, $data);

            if ($views === null) {
                exit;
            } else {
                $this->load->view('templates/'.$group.'/header', $data);
                foreach ($views as $v) {
                    $this->load->view('page/'.$group.'/' . $v, $data);
                }
                $this->load->view('templates/'.$group.'/footer', $data);
            }

            $pageController->afterView();
        } else {
            if ($page !== 'PageNotFound' && $page !== 'login') {
                redirect($group.'/PageNotFound');
            } else {
                show_404();
            }
        }
    }
}
