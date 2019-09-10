<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-4-2017
 */

/**
 * Class TransactionApiPage
 */
class TransactionApiPage extends ApiFrame
{

    const USER_NOT_FOUND = 'userNotFound';
    const INVALID_ARGUMENT = 'invalidArgument';
    const DATABASE_ERROR = 'databaseError';

    function call()
    {
        $action = set_value('action');
        switch ($action) {
            case 'buy':
                $this->actionBuy();
                break;
            case 'updateData':
                $this->actionUpdateData();
                break;
            default:
                $this->setError(self::INVALID_ARGUMENT);
                break;
        }
    }

    function hasAccess(): bool
    {
        // Ensure that the request originates from a user.
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_USER]);
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            Transaction::class,
            Login::class,
            Consumption::class,
            User::class,
            Role::class,
            LoginRole::class,
            Role_LoginRole::class,
            User_Consumption_LoginRole::class,
        ];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries(): array
    {
        return [];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers(): array
    {
        return [];
    }

    private function actionUpdateData(): void
    {
        $result = $this->ci->Consumption->getAll();

        if ($result !== null) {
            $this->setStatus(ApiFrame::STATUS_SUCCESS);
            $this->setResult('updated_data', $result);
        } else {
            $this->setError(self::DATABASE_ERROR);
        }
    }

    private function actionBuy(): void
    {
        $id = set_value('id');

        // Check that id is set.
        if ($id === '' || !is_numeric($id)) {
            $this->setError(self::INVALID_ARGUMENT);
            $this->setResult('id', $id);
            return;
        }

        // Check that a valid id is passed.
        if (!$this->ci->Role_LoginRole->userHasRole($id, Role::ROLE_USER)) {
            // error invalid user specified.
            $this->setError(self::USER_NOT_FOUND);
            return;
        }

        $authorId = getLoggedInLoginId($this->ci->session);

        $amount = 1;
        $result = $this->ci->Consumption->change($id, $authorId, -$amount, Transaction::TYPE_CONSUME);

        if ($result) {
            $newAmount = $this->ci->Consumption->get($id);
            $this->setStatus(ApiFrame::STATUS_SUCCESS);
            $this->setResult('name', $this->ci->User->getName($id));
            $this->setResult('new_amount', $newAmount);
            $this->setResult('amount', $amount);
            $this->setResult('updated_data', $this->ci->Consumption->getAll());
            $this->setResult('consume_count', $this->ci->Transaction->getConsumeCountForSubject($id));

            if ($newAmount < 0) {
                $this->sendEmail($id, $newAmount);
            }
        } else {
            $this->setError(self::DATABASE_ERROR);
        }
    }

    private function sendEmail(int $id, int $credits)
    {
        $this->ci->config->load('email');

        /** @var User $userModel */
        $userModel = $this->ci->User;
        $userData = $userModel->get($id);

        $this->ci->load->library('email');
        /** @var CI_Email $emailLibrary */
        $emailLibrary =& $this->ci->email;
        $emailSuccess = false;
        try {
            $emailLibrary
                ->from($this->ci->config->item('from_email'), $this->ci->config->item('from_name'))
                ->to($userData[User::FIELD_EMAIL])
                ->set_mailtype('html')
                ->subject(lang('email_low_credits_subject'))
                ->message($this->getEmailBody($userData[User::FIELD_FIRST_NAME], $credits))
                ->send()
            ;
            $emailSuccess = true;
        } catch (Exception $e) { }

        if ($emailSuccess) {
            log_message('info', 'Email send to '.$userData[User::FIELD_EMAIL].' to notify about a lack of credits '.$credits.'.');
        } else {
            log_message('error', 'Failed sending email message for '.$id.'.');
            log_message('debug', 'Email debug info:'.PHP_EOL.$emailLibrary->print_debugger());
        }
    }

    private function getEmailBody(string $name, int $credits)
    {
        ob_start(); ?>
        <!doctype html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>Simple Transactional Email</title>
            <style>
                /* -------------------------------------
                    INLINED WITH htmlemail.io/inline
                ------------------------------------- */
                /* -------------------------------------
                    RESPONSIVE AND MOBILE FRIENDLY STYLES
                ------------------------------------- */
                @media only screen and (max-width: 620px) {
                    table[class=body] h1 {
                        font-size: 28px !important;
                        margin-bottom: 10px !important;
                    }
                    table[class=body] p,
                    table[class=body] ul,
                    table[class=body] ol,
                    table[class=body] td,
                    table[class=body] span,
                    table[class=body] a {
                        font-size: 16px !important;
                    }
                    table[class=body] .wrapper,
                    table[class=body] .article {
                        padding: 10px !important;
                    }
                    table[class=body] .content {
                        padding: 0 !important;
                    }
                    table[class=body] .container {
                        padding: 0 !important;
                        width: 100% !important;
                    }
                    table[class=body] .main {
                        border-left-width: 0 !important;
                        border-radius: 0 !important;
                        border-right-width: 0 !important;
                    }
                    table[class=body] .btn table {
                        width: 100% !important;
                    }
                    table[class=body] .btn a {
                        width: 100% !important;
                    }
                    table[class=body] .img-responsive {
                        height: auto !important;
                        max-width: 100% !important;
                        width: auto !important;
                    }
                }
                /* -------------------------------------
                    PRESERVE THESE STYLES IN THE HEAD
                ------------------------------------- */
                @media all {
                    .ExternalClass {
                        width: 100%;
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                        line-height: 100%;
                    }
                    .apple-link a {
                        color: inherit !important;
                        font-family: inherit !important;
                        font-size: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                        text-decoration: none !important;
                    }
                    .btn-primary table td:hover {
                        background-color: #34495e !important;
                    }
                    .btn-primary a:hover {
                        background-color: #34495e !important;
                        border-color: #34495e !important;
                    }
                }
            </style>
        </head>
        <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
        <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
            <tr>
                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
                <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                    <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                        <!-- START CENTERED WHITE CONTAINER -->
                        <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;"><?=lang('email_low_credits_preview')?></span>
                        <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                            <!-- START MAIN CONTENT AREA -->
                            <tr>
                                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                        <tr>
                                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><?=str_replace('[name]', $name, lang('email_low_credits_greeting'))?></p>
                                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><?=str_replace('[credits]', $credits, lang('email_low_credits_message'))?></p>
                                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><?=str_replace('[name]', lang('application_name'), lang('email_low_credits_signature'))?></p>
                                                <img style="width: 100%; height: 100%; max-width: 100%; max-height: 5em" src="<?=base_url('public/img/email-signature.png')?>">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- END MAIN CONTENT AREA -->
                        </table>

                        <!-- END CENTERED WHITE CONTAINER -->
                    </div>
                </td>
                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            </tr>
        </table>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
