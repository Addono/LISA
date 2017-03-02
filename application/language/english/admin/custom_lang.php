<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 2-3-2017
 */

// Menu
$lang['application_menu_transactions'] = 'Transactions';

// Give user
$lang['application_give_user_title'] = 'Give consumptions to %s';
$lang['application_give_user_subtitle'] = 'Give %s consumptions';

$lang['application_give_user_amount'] = 'Amount';
$lang['application_give_user_amount_placeholder'] = 'Currently: %s';
$lang['application_give_user_amount_help'] = 'The amount of consumptions the user should receive.';

$lang['application_give_user_success'] = 'Successfully gave %s %s consumption(s).';

$lang['application_give_user_error_amount_required'] = 'The amount is required.';
$lang['application_give_user_error_not_integer'] = 'Invalid amount given, only whole (integer) numbers are supported.';
$lang['application_give_user_error_amount_not_positive'] = 'Only positive amounts can be given.';

$lang['application_give_user_submit'] = 'Give';

$lang['application_user_overview_tooltip_give_user'] = 'Give user consumptions';

// Transactions
$lang['application_transactions_title'] = 'Transaction overview';
$lang['application_transactions_table_title'] = 'All transactions';

$lang['application_transactions_table_header_author'] = 'Author';
$lang['application_transactions_table_header_subject'] = 'Subject';
$lang['application_transactions_table_header_amount'] = 'New amount';
$lang['application_transactions_table_header_delta'] = 'Difference';
$lang['application_transactions_table_header_time'] = 'Time';

//@Overwrite
$lang['application_name'] = 'Lisa Backend';
$lang['application_title'] = 'Lisa Backend';

$lang['application_dashboard_body'] = 'One day this might contain widgets, but for now it will just give you a quick intro. You can add users under new user Manage Users > Add User.<p>To view all users go to Manage Users > Overview, here you can also edit each user or give them consumption credits.<p><b>Note:</b> Only users with the <i>User</i>-role are able to spend them.';