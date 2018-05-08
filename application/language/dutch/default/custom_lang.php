<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

// Menu
$lang['menu_transactions'] = 'Mijn transacties';
$lang['menu_consume'] = 'Consumeer';
$lang['menu_leaderboard'] = 'Leaderboard';

// Consume
$lang['consume_title'] = 'Consumeer!';
$lang['consume_description'] = '<b>Dubbel klik</b> op een knop om een consumptie te kopen.';
$lang['consume_description_self'] = 'Consumeer zelf!';

$lang['consume_table_head_name'] = 'Naam';
$lang['consume_table_head_credit'] = 'Credit';
$lang['consume_table_head_consumptions'] = '#Consumpties';

$lang['consume_group_first_name'] = 'Voornaam';
$lang['consume_group_last_name'] = 'Achternaam';
$lang['consume_group_amount_name'] = 'Hoeveelheid';

$lang['consume_submit'] = 'Koop';

$lang['consume_form_invalid_amount'] = 'Er is iets mis gegaan met de opgegeven hoeveelheden, daarom is de volledige transactie afgebroken. Probeer het alsjeblieft opnieuw, als dit probleem blijft neem dan contact op met uw systeembeheerder.';
$lang['consume_form_invalid_user'] = 'Een ongeldige (verouderd?) pagina is gebruikt, daarom is de volledige transactie afgebroken. Probeer het alsjeblieft opnieuw, als dit probleem blijft neem dan contact op met uw systeembeheerder.';
$lang['consume_form_user_success'] = 'Succesvol %s consumptie(s) voor %s gekocht.';
$lang['consume_form_user_failure'] = 'Er is iets mis gegaan bij het kopen van %s consumpties voor %s. Probeer het alsjeblieft opnieuw, als dit probleem blijft neem dan contact op met uw systeembeheerder';

// Transactions
$lang['transactions_title'] = 'Mijn transacties';

$lang['transactions_subtitle_author'] = 'Als auteur';
$lang['transactions_subtitle_subject'] = 'Als gebruiker';

$lang['transactions_table_header_author'] = 'Auteur';
$lang['transactions_table_header_subject'] = 'Gebruiker';
$lang['transactions_table_header_amount'] = 'Nieuwe hoeveelheid';
$lang['transactions_table_header_delta'] = 'Verschil';
$lang['transactions_table_header_time'] = 'Datum';

$lang['transactions_ajax_message_success'] = 'Aankoop voor [name] gelukt! Nieuwe hoeveelheid [newAmount].';
$lang['transactions_ajax_message_unknown_error'] = 'Sorry, er ging iets fout. Ververs de pagina en probeer opnieuw.';
$lang['transactions_ajax_message_internal_server_error'] = 'Ongeldige aanvraag was verstuurd, als het probleem aanhoud neem dan contact op met de systeembeheerder.';
$lang['transactions_ajax_message_access_denied'] = 'Je hebt niet genoeg rechten voor deze actie, mogelijk ben je niet ingelogd.';
$lang['transactions_ajax_message_invalid_request'] = 'Ongeldige aanvraag was verstuurd, als het probleem aanhoud neem dan contact op met de systeembeheerder.';
$lang['transactions_ajax_message_database_error'] = 'Ongeldige aanvraag was verstuurd, als het probleem aanhoud neem dan contact op met de systeembeheerder.';
$lang['transactions_ajax_message_timed_out'] = 'Om veiligheidsredenen is het nodig om de pagina te verversen, klik op oke en probeer opnieuw.';

// Leaderboard
$lang['leaderboard_title'] = $lang['menu_leaderboard'];
$lang['leaderboard_subtext'] = 'De som van alle negatieve transacties wordt hier weergeven. Deze pagina is alleen zichtbaar voor gebruikers die in deze exclusieve lijst staan.';

$lang['leaderboard_table_header_name'] = $lang['consume_table_head_name'];
$lang['leaderboard_table_header_sum'] = 'Totaal';

// Not user
$lang['not_user_page_header_title'] = 'Eeuhm... sorry :(';
$lang['not_user_page_header_body'] = 'Je hebt niet de rechten om deze applicatie te mogen gebruiker, als je denkt dat je dit wel zou mogen neem dan contact op met de systeem beheerder.';

// Email
$lang['email_low_credits_subject'] = 'Oww nee, je credits zijn op!';
$lang['email_low_credits_preview'] = 'Hoi, ik wil je er even aan herinneren dat je door je tegoed heen bent!';
$lang['email_low_credits_greeting'] = 'Hoi [name],';
$lang['email_low_credits_message'] = 'Je tegoed staat op het moment op <b>[credits] credits</b>, dat is <i>"iets"</i> aan de lage kant, zou je daar alsjeblieft iets aan willen doen?';
$lang['email_low_credits_signature'] = 'Groetjes [name]!';

// Footer
$lang['footer_hosted_by'] = 'Gehost door';
$lang['footer_source'] = 'Broncode';

// @Overwrite
$lang['application_name'] = 'Lisa';
$lang['application_title'] = 'Lisa is super awesome';
$lang['copyright'] = '2018, Adriaan Knapen';
$lang['application_version'] = 'Core v1.6.0';

$lang['default_page_header_title'] = 'Hoi ik ben Lisa!';
$lang['default_page_header_body'] = 'Log in om te beginnen';