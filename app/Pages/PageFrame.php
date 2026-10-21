<?php
namespace App\Pages;

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
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class PageFrame
 * @property    CI_Form_validation  $form_validation
 */
abstract class PageFrame extends RequestFrame
{

    protected $formSuccess = false;
    protected $data = [];
    protected $accessibleBy;
    private $validateForm;

    public function  __construct(array $data, $validateForm = true) {
        parent::__construct($data);

        $this->validateForm = $validateForm;
    }

    public function show() {
        $group = $this->getDataKey('group');

        // Check if the user has access.
        if (!$this->hasAccess()) {
            return redirect()->to(site_url(isLoggedIn(session()) ? 'InsufficientRights' : $group . '/login'));
        }

        $this->formSuccess = $this->validateForm && $this->formValidate();

        // Call the form success if a valid form was submitted.
        if ($this->getFormSuccess()) {
            $response = $this->onFormSuccess();
            if ($response instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $response;
            }
        }

        $response = $this->beforeView();
        if ($response instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $response;
        }

        $views = $this->getViews();

        if ($views === null) {
            return '';
        } else {
            $html = view('templates/'.$group.'/header', $this->data);
            foreach ($views as $v) {
                $html .= view('page/'.$group.'/' . $v, $this->data);
            }
            return $html . view('templates/'.$group.'/footer', $this->data);
        }
    }
    /**
     * The views to be shown.
     *
     * @return array|null Array with the names of the views inbetween the header and footer, null if no views should be shown.
     */
    abstract public function getViews(): array;

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
     * The form validation rules.
     *
     * @return array|bool
     */
    abstract protected function getFormValidationRules();

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
        $requirementName = $this->getRequirementName($requirement['rules']);
        if (in_array($requirementName, ['trim', 'returnTrue'], true)) {
            return true;
        }
        $rule = $requirement['rules'];
        if (is_array($rule)) {
            $rule = $rule[0];
            if (is_array($rule)) {
                $callback = $rule[1];
                $rule = static fn ($value) => $callback($value);
            }
        }
        $validation = service('validation');
        $validation->reset();
        $validation->setRules([
            $requirement['field'] => [
                'label' => $requirement['label'] ?? $requirement['field'],
                'rules' => is_string($rule) ? $rule : [$rule],
            ],
        ]);
        $success = $validation->run($formInput);

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
        if (array_key_exists($requirementName, $rule['errors'] ?? [])) {
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