<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

// Menu
$lang['menu_transactions'] = 'My transactions';
$lang['menu_consume'] = 'Consume';
$lang['menu_leaderboard'] = 'Leaderboard';

// Consume
$lang['consume_title'] = 'Consume!';
$lang['consume_description'] = '<b>Double click</b> on the button to make a purchase.';
$lang['consume_description_self'] = 'Consume one yourself!';

$lang['consume_table_head_name'] = 'Name';
$lang['consume_table_head_credit'] = 'Credit';
$lang['consume_table_head_consumptions'] = '#Consumptions';

$lang['consume_group_first_name'] = 'First name';
$lang['consume_group_last_name'] = 'Last name';
$lang['consume_group_amount_name'] = 'Amount';
$lang['consume_group_recent_name'] = 'Recent';

$lang['consume_submit'] = 'Buy';

$lang['consume_form_invalid_amount'] = 'Something went wrong with the given amounts, therefore the transaction is aborted. Please try again, if the problem persists please contact the system administrator.';
$lang['consume_form_invalid_user'] = 'An invalid user has been selected, therefore the transaction is aborted. Please try again, if the problem persist please contact the system administrator.';
$lang['consume_form_user_success'] = 'Successfully purchased %s consumption(s) for %s.';
$lang['consume_form_user_failure'] = 'Failed purchasing %s consumptions for %s. Please try again, if the problem persists please contact the system administrator.';

// Transactions
$lang['transactions_title'] = 'My transactions';
$lang['leaderboard_subtext'] = 'This list is only visible to users within this list.';

$lang['transactions_subtitle_author'] = 'As author';
$lang['transactions_subtitle_subject'] = 'As subject';

$lang['transactions_table_limit_description'] = 'At most [limit] transactions will be shown.';
$lang['transactions_table_limit_show_all'] = 'Show all';

$lang['transactions_table_header_author'] = 'Author';
$lang['transactions_table_header_subject'] = 'Subject';
$lang['transactions_table_header_amount'] = 'New amount';
$lang['transactions_table_header_delta'] = 'Difference';
$lang['transactions_table_header_time'] = 'Time';

$lang['transactions_ajax_message_success'] = 'Purchase for [name] successful, [newAmount] remaining';
$lang['transactions_ajax_message_unknown_error'] = 'Something went wrong, refresh the page and try again.';
$lang['transactions_ajax_message_internal_server_error'] = 'The request caused an internal server error.';
$lang['transactions_ajax_message_access_denied'] = 'Not enough rights, you might be logged out.';
$lang['transactions_ajax_message_invalid_request'] = 'Invalid request was sent, refresh the page and try again.';
$lang['transactions_ajax_message_database_error'] = 'The request caused an internal database error, please contact the system administrator.';
$lang['transactions_ajax_message_timed_out'] = 'Because of security risks is it required to refresh the page, when you dismiss this message should the page be automatically reloaded.';
$lang['transactions_ajax_message_celebrate'] = 'That was the [count]th 🎉🎉🎇! Let\'s go for the next [next_amount]!';

// Leaderboard
$lang['leaderboard_title'] = $lang['menu_leaderboard'];

$lang['leaderboard_table_header_name'] = $lang['consume_table_head_name'];
$lang['leaderboard_table_header_sum'] = 'Sum';

// Graph
$lang['graph_week_number'] = 'Week number';
$lang['graph_amount_of_consumptions'] = 'Amount of consumptions';

// Not user
$lang['not_user_page_header_title'] = 'Eeuhm... sorry :(';
$lang['not_user_page_header_body'] = 'You currently do not have the rights to use this application, if you think you should please contact the administrator.';

// Email
$lang['email_low_credits_subject'] = 'O no, your credits are depleted!';
$lang['email_low_credits_preview'] = 'Hi, just a friendly reminder that your credits are depleted!';
$lang['email_low_credits_greeting'] = 'Hi [name],';
$lang['email_low_credits_message'] = 'You currently have <b>[credits] credits</b>, that\'s a <i>tiny bit</i> to little, so would you mind fixing that?';
$lang['email_low_credits_signature'] = '[name]';

// Footer
$lang['footer_hosted_by'] = 'Hosted by';
$lang['footer_source'] = 'Source';

// @Overwrite
$lang['application_name'] = 'Lisa';
$lang['application_title'] = 'Lisa is super awesome';
$lang['application_version'] = 'v2.2.1';

$lang['default_page_header_title'] = 'Hello, I am Lisa!';
$lang['default_page_header_body'] = 'Login to get started';
