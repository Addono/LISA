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
        $this->ci = self::get_instance();
        $this->ci->load->model($this->models);
        $this->ci->load->library($this->libraries);
        $this->ci->load->helper($this->helpers);

        $this->hasError = $this->formValidate();
    }

    abstract public function getHeader();

    abstract public function getBody();

    abstract public function isVisible();

    abstract protected function accessibleBy();

    public function setParams($params) {
        $this->params = $params;
    }

    public function hasAccess($role) {
        $rolesWithAccess = $this->accessibleBy();

        foreach($rolesWithAccess as $accessRole) {
            if($accessRole === $role) {
                return true;
            }
        }

        return false;
    }

    abstract protected function getFormValidationRules();

    protected function addModels($models) {
        $this->models += $models;
    }

    protected function addLibraries($libraries) {
        $this->libraries += $libraries;
    }

    protected function addHelpers($helpers) {

    }

    protected function dataAdd($key, $value) {
        $this->data[$key] = $value;
    }

    protected function dataAppend($key, $value) {
        $this->data[$key][] = $value;
    }

    public function getData() {
        return $this->data;
    }

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