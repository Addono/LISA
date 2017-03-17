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
    protected $formSuccess = false;
    protected $data = [];
    protected $ci;
    protected $accessibleBy;
    private   $models = [];
    private   $libraries = [];
    private   $helpers = ['tables'];

    public function  __construct($validateForm = true) {
        $this->models += $this->getModels();
        $this->libraries += $this->getLibraries();
        $this->helpers += $this->getHelpers();

        $this->ci = self::get_instance();
        $this->ci->load->model($this->models);
        $this->ci->load->library($this->libraries);
        $this->ci->load->helper($this->helpers);

        if ($validateForm) {
            $this->formSuccess = $this->formValidate();
        } else {
            $this->formSuccess = true;
        }
    }

    /**
     * The views to be shown.
     *
     * @return array|null Array with the names of the views inbetween the header and footer, null if no views should be shown.
     */
    abstract public function getViews();

    /**
     * Function which is called after construction and before the views are rendered.
     */
    abstract public function beforeView();

    /**
     * Will be called when the form is successfully submitted. Overwrite to use it.
     */
    public function onFormSuccess() {}

    /**
     * If the form input was valid.
     *
     * @return bool
     */
    public function getFormSuccess() {
        return $this->formSuccess;
    }

    /**
     * Setter for parameters from the page handler.
     *
     * @param array
     */
    public function setParams($params) {
        $this->params = $params;
    }

    public function getParams($key) {
        return $this->params[$key];
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
     * @return array|bool
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
     * @param string $key
     * @param mixed $value
     */
    protected final function setData($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * Appends an entry to the data, which is accessible for all views.
     *
     * @param string $key
     * @param mixed $value
     */
    protected final function appendData($key, $value) {
        $this->data[$key][] = $value;
    }

    protected final function appendDataArray($key, $arrayName, $value) {
        $this->data[$key][$arrayName][] = $value;
    }

    protected function addMessage($message, $type) {
        $this->appendDataArray('messages', $type, $message);
    }

    protected function addSuccessMessage($message) {
        $this->addMessage($message, 'success');
    }

    protected function addInfoMessage($message) {
        $this->addMessage($message, 'info');
    }

    protected function addWarningMessage($message) {
        $this->addMessage($message, 'warning');
    }

    protected function addDangerMessage($message) {
        $this->addMessage($message, 'danger');
    }

    protected function addScript($script) {
        $this->appendData('scripts', $script);
    }

    /**
     * Getter for the data to be accessible to views.
     *
     * @return array
     */
    public final function getData() {
        return $this->data;
    }

    public final function getDataKey($key) {
        return $this->data[$key];
    }

    /**
     * Validates a form given the rules of the class.
     *
     * @return bool
     */
    private function formValidate() {
        // Retrieve all form validation rules and check if any of them where set.
        $rules = $this->getFormValidationRules();
        if (is_bool($rules)) {
            return $rules;
        } elseif ($rules === []) {
            return false;
        }

        // Check if anything was posted, else no need to validate the form since it wasn't submitted.
        if (!$this->hasPostContent()) {
            return false;
        }

        // Check for each rule if it holds.
        $success = true;
        foreach ($rules as $rule) {
            if (is_array($rule['rules'])) {
                foreach($rule['rules'] as $requirement) {
                    // Convert the requirement to an individual rule.
                    $requirementAsRule = $this->convertRequirementToRule($requirement, $rule);

                    $successRule = $this->evaluateRequirementAsRule($requirementAsRule, $_POST);

                    if (!$successRule) {
                        $success = false;
                        break;
                    }
                }
            } else {
                $successRule = $this->evaluateRequirementAsRule($rule, $_POST);

                if (!$successRule) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Checks if a requirement holds.
     * NOTE: For each field each individual rule it has is called a requirement.
     *
     * @param array $requirement
     * @param array $formInput The input data of the form.
     * @return bool
     */
    private function evaluateRequirementAsRule($requirement, array $formInput) {
        // Reset the rules and insert the data.
        $this->ci->form_validation->reset_validation()->set_data($formInput);

        $rule = [];

        // Check if the requirement is of the type matches, if so it is required to also validate the form matches refers to.
        $requirementName = $this->getRequirementName($requirement['rules']);
        if (stripos($requirementName, 'matches') === 0) {
            preg_match('/matches\[([^\]]*?)\]/', $requirementName, $matches);

            $rule[] = [
                'field' => $matches[1],
                'rules' => 'returnTrue', // Give it some dummy rule.
            ];
        }
        $rule[] = $requirement;

        // Initialise the rule to be evaluated.
        $this->ci->form_validation->set_rules($rule);

        // Evaluate the rule.
        $success = $this->ci->form_validation->run();

        if (!$success) {
            // Generate the error message.
            $requirementName = $this->getRequirementName($requirement['rules']);

            if (array_key_exists('errors', $requirement) && is_array($requirement['errors']) && array_key_exists($requirementName, $requirement['errors'])) {
                $rawErrorMessage = $requirement['errors'][$requirementName];
                $errorMessage = sprintf($rawErrorMessage, $requirement['label']);
            } else {
                $errorMessage = 'Rule <i>' . $requirementName . '</i> of field <i>' . $requirement['field'] . '</i> failed.';
            }

            // Add the error message to the data for the view.
            $this->addDangerMessage($errorMessage);
        }

        return $success;
    }

    /**
     * Extracts the name of a requirement from a requirement.
     *
     * @param array|string $requirement
     * @return string
     */
    private function getRequirementName($requirement) {
        if (!is_array($requirement)) {
            return $requirement;
        } elseif(!is_array($requirement[0])) {
            return $requirement[0];
        } else {
            return $requirement[0][0];
        }
    }

    /**
     * @param $requirement
     * @param $rule
     * @return array
     */
    private function convertRequirementToRule($requirement, $rule)
    {
        $requirementName = $this->getRequirementName($requirement);

        $requirementAsRule = [
            'field' => $rule['field'],
            'label' => $rule['label'],
            'rules' => [$requirement],
        ];
        if (array_key_exists($requirementName, $rule['errors'])) {
            $requirementAsRule['errors'] = [
                $requirementName => $rule['errors'][$requirementName],
            ];
        }

        return $requirementAsRule;
    }

    /**
     * Checks whether the page was loaded with POST data.
     *
     * @return bool
     */
    private function hasPostContent() {
        return count($_POST) > 0;
    }
}