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
    const DefaultValue = 'default';

    private $data = [
        'errors' => [],
    ];

    public function index($page = self::DefaultValue, $subPage = null)
    {
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
            'Role',
            'UserRole',
        ]);
        $this->load->library([
            'session',
            'form_validation'
        ]);
        $this->lang->load('default', 'english');
        $this->lang->load('application', 'english');

        // Check if the user is logged in
        $this->data['loggedIn'] = isLoggedIn($this->session);
        if($this->data['loggedIn']) {
            $this->data['username'] = $this->Login->getUsername(getLoggedInLoginId($this->session));
        }

        $this->showPage($page, $subPage);
    }

    /**
     * @param string $page
     * @param string $subPage
     */
    private function showPage($page, $subPage)
    {
        $pageControllerName = ucfirst($page) . 'Page';
        $pageControllerFile = './application/pages/' . $pageControllerName . '.php';
        if (file_exists($pageControllerFile)) {
            require_once($pageControllerFile);

            /** @var PageFrame $pageController */
            $pageController = new $pageControllerName();

            if (!$pageController->hasAccess()) {
                redirect(); // todo add insufficient rights page
                exit;
            }
            $pageController->setParams([$page, $subPage]);

            $pageController->beforeView();

            $header = $pageController->getHeader();
            $header = $header ? $header : [];
            $body = $pageController->getBody();
            $body = $body ? $body : [];
            $data = $pageController->getData();
            $data = $data ? $data : [];


            $data = array_merge($this->data, $data);

            if (!$header && !$body) {
                redirect('pageNotFound');
            } else {
                $this->load->view('templates/header', $data);
                foreach ($header as $h) {
                    $this->load->view('page/' . $h, $data);
                }
                $this->load->view('templates/intersection', $data);
                foreach ($body as $b) {
                    $this->load->view('page/' . $b, $data);
                }
                $this->load->view('templates/footer', $data);
            }

            $pageController->afterView();
        } else {
            if ($page !== 'PageNotFound') {
                redirect('PageNotFound');
            } else {
                show_404();
            }
        }
    }
}
