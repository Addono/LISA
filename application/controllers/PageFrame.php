<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class PageFrame
 * @property    CI_Form_validation  $form_validation
 */
abstract class PageFrame extends CI_Controller
{

    protected $params;
    protected $hasError = false;
    protected $data = [];
    protected $ci;
    protected $accessibleBy;
    private   $models = [];
    private   $libraries = [];
    private   $helpers = ['tables'];

    public function  __construct() {
        $this->models += $this->getModels();
        $this->libraries += $this->getLibraries();
        $this->helpers += $this->getHeader();

        $this->ci = self::get_instance();
        $this->ci->load->model($this->models);
        $this->ci->load->library($this->libraries);
        $this->ci->load->helper($this->helpers);

        $this->hasError = $this->formValidate();
    }

    /**
     * The views to be shown as header.
     *
     * @return array|null
     */
    abstract public function getHeader();

    /**
     * The views to be shown as body.
     *
     * @return array|null
     */
    abstract public function getBody();

    /**
     * If the page should be visible in the menu.
     *
     * @return boolean
     */
    abstract public function isVisible();

    /**
     * Function which is called after construction and before the views are rendered.
     */
    abstract public function beforeView();

    /**
     * Function which is called after the views are rendered.
     */
    abstract public function afterView();

    /**
     * Setter for parameters from the page handler.
     *
     * @param array
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * If the current user has access to this page.
     *
     * @return boolean
     */
    abstract public function hasAccess();

    /**
     * The form validation rules.
     *
     * @return array
     */
    abstract protected function getFormValidationRules();

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    abstract protected function getModels();

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    abstract protected function getLibraries();

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    abstract protected function getHelpers();

    /**
     * Adds a data pair which is accessible for all views.
     *
     * @param $key
     * @param $value
     */
    protected function setData($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * Appends an entry to the data, which is accessible for all views.
     *
     * @param $key
     * @param $value
     */
    protected function appendData($key, $value) {
        $this->data[$key][] = $value;
    }

    /**
     * Getter for the data to be accessible to views.
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Validates a form given the rules of the class.
     *
     * @return bool
     */
    private function formValidate() {
        $rules = $this->getFormValidationRules();
        if(!$rules) {
            return true;
        }

        $hasError = false;
        foreach($rules as $rule) {
            $this->ci->form_validation->set_rules([$rule]);
            if(!$this->ci->form_validation->run()) {
                $hasError = true;
                break;
            }
            $this->ci->form_validation->reset_validation()->set_data($_POST);
        }

        return $hasError;
    }
}