<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property    CI_Form_validation  $form_validation
 * @property    Users               $Users
 * @property    Receipt             $Receipt
 * @property    CI_Session          $session
 * @property    CI_DB_query_builder $db
 */
class Page extends CI_Controller {
    const DefaultValue = 'default';

    private $data = ['errors' => []];

    public function index($page = self::DefaultValue, $subPage = null)
    {
        // Import all helpers and libraries.
        $this->load->helper([
            'url',
            'form',
            'language',
        ]);
        $this->load->model([
            'Users',
        ]);
        $this->load->library([
            'session',
            'form_validation'
        ]);
        $this->lang->load('default', 'english');
        $this->lang->load('application', 'english');

        // Check if the user is logged in
        $this->data['loggedIn'] = $this->session->username !== NULL;
        $this->data['username'] = $this->session->username;
        if($this->data['loggedIn']) {
            $this->data['role'] = $this->Users->userRole($this->data['username']);
        }

        $pageType = 'page';
        $headerPage = "page/$page-header";
        $bodyPage = "page/$page-body";

        $hasHeader = file_exists('./application/views/'.$headerPage.'.php');
        $hasBody = file_exists('./application/views/'.$bodyPage.'.php');

        if(!$hasHeader && !$hasBody) { // Check if the page exists
            $pageType = 'error';
            $page = 'pageNotFound';
        }

        // Show the page.
        $this->load->view('templates/header', $this->data);
        switch($pageType) {
            case 'page':
                $this->handlePage($page, $subPage);
                if($hasHeader) {
                    $this->load->view($headerPage, $this->data);
                }
                $this->load->view('templates/intersection');
                if($hasBody) {
                    $this->load->view($bodyPage, $this->data);
                }
            break;
            case 'error':
                $this->load->view('error/'.$page);
                $this->load->view('templates/intersection');
            break;
        }
        $this->load->view('templates/footer');
    }

    /**
     * Handles some actions specific for certain pages.
     * @param $page
     */
    private function handlePage($page, $subPage) {
        switch($page) {
            case 'login':
                // Check if the user is already logged in.
                if($this->data['loggedIn']) {
                    redirect();
                    exit;
                }
                $rules = [
                    [
                        'field' => 'username',
                        'label' => lang('login_username'),
                        'rules' => [
                            'required',
                        ],
                        'errors' => [
                            'required' => lang('login_error_field_required'),
                        ],
                    ],
                    [
                        'field' => 'password',
                        'label' => lang('login_password'),
                        'rules' => 'required',
                        'errors' => [
                            'required' => lang('login_error_field_required'),
                        ],
                    ],
                    [
                        'field' => 'username',
                        'label' => lang('login_username'),
                        'rules' => [
                            ['usernameExists', [$this->Users, 'userExists']],
                        ],
                        'errors' => [
                            'usernameExists' => lang('login_error_username_does_not_exist'),
                        ],
                    ],
                ];
                // Parse all rules individually to enforce the order.
                $hasError = false;
                foreach($rules as $rule) {
                    $this->form_validation->set_rules([$rule]);
                    if(!$this->form_validation->run()) {
                        $hasError = true;
                        break;
                    }
                    $this->form_validation->reset_validation()->set_data($_POST);
                }
                if($hasError) {
                    break;
                } elseif($this->Users->checkCredentials(set_value('username'), set_value('password'))) { // Check if the credentials are valid
                    $this->session->username = set_value('username');
                    redirect('');
                    break;
                } else {
                    $this->data['errors'][] = lang('login_error_invalid_credentials');
                    break;
                }
            case 'logout':
                $this->session->sess_destroy();
                redirect();
                exit;
            default:
                break;
        }
    }
}
