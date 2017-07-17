<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 5-2-2017
 */

$lang['admin_application_name'] = 'Jouw beheerspaneel naam hier';
$lang['admin_application_title'] = 'Jouw beheerspaneel titel hier';
$lang['copyright'] = '2017, Adriaan Knapen';

// Side menu
$lang['application_menu_dashboard'] = 'Dashboard';
$lang['application_menu_manage_users'] = 'Beheer gebruikers';
$lang['application_menu_overview'] = 'Overzicht';
$lang['application_menu_new_user'] = 'Nieuwe gebruiker';
$lang['application_menu_main_environment'] = 'Hoofdapplicatie';

// Dashboard
$lang['application_dashboard_title'] = 'Dashboard';
$lang['application_dashboard_body'] = 'Dit is jouw nieuwe dashboard!';

// User
$lang['application_user_title'] = 'Bewerk gebruiker %s';
$lang['application_user_change_name'] = 'Verander naam';
$lang['application_user_change_password'] = 'Verander wachtwoord';
$lang['application_user_change_email'] = 'Verander email';
$lang['application_user_change_roles'] = 'Verander de rollen';
$lang['application_user_submit'] = 'Aanpassen';

$lang['application_user_first_name'] = 'Voornaam';
$lang['application_user_first_name_help'] = 'De voornaam van de gebruiker.';

$lang['application_user_last_name'] = 'Achternaam';
$lang['application_user_last_name_help'] = 'De achternaam van de gebruiker.';

$lang['application_user_password'] = 'Wachtwoord';
$lang['application_user_password_help'] = 'Het nieuwe wachtwoord van de gebruiker.';

$lang['application_user_confirm_password'] = 'Bevestig wachtwoord';
$lang['application_user_confirm_password_help'] = 'Voer het wachtwoord opnieuw in.';

$lang['application_user_email'] = 'Email';
$lang['application_user_email_help'] = 'Het nieuwe email address van deze gebruiker.';

$lang['application_user_roles'] = 'Rollen';

$lang['application_user_error_required'] = '%s is vereist, vul dit aljeblieft in.';
$lang['application_user_error_password_not_strong_enough'] = 'Het opgegeven wachtwoord voldoet niet aan de veiligheids eisen.';
$lang['application_user_error_password_not_equal'] = 'De opgegeven wachtwoorden kwamen niet overeen.';
$lang['application_user_error_valid_email'] = 'Het opgegeven email address is ongeldig.';

$lang['application_user_name_change_success'] = 'Naam is aangepast.';
$lang['application_user_password_change_success'] = 'Aanpassen wachtwoord gelukt.';
$lang['application_user_email_change_success'] = 'Email aanpassen is gelukt.';
$lang['application_user_roles_change_success'] = 'Rollen aanpassen is gelukt.';
$lang['application_server_error'] = 'Er is iets fout gegaan, probeer het opnieuw. Als dit het niet oplos neem dan contact op met de systeembeheerder.';

// User overview
$lang['application_user_overview_title'] = 'Gebruikers overzicht';
$lang['application_user_overview_table_title'] = 'Alle gebruikers';
$lang['application_user_overview_table_header_username'] = 'Gebruikersnaam';
$lang['application_user_overview_table_header_first_name'] = 'Voornaam';
$lang['application_user_overview_table_header_last_name'] = 'Achternaam';
$lang['application_user_overview_table_header_email'] = 'Email';
$lang['application_user_overview_table_header_roles'] = 'Rollen';
$lang['application_user_overview_table_header_actions'] = 'Acties';

$lang['application_user_overview_tooltip_edit_user'] = 'Gebruiker wijzigen';

// Add users
$lang['application_new_user_title'] = 'Nieuwe gebruiker toevoegen';
$lang['application_new_user_form_title'] = 'Alle velden zijn verplicht.';

$lang['application_new_user_username'] = 'Gebruikersnaam';
$lang['application_new_user_username_help'] = 'De gebruikersnaam moet uniek zijn.';

$lang['application_new_user_email'] = 'Email';
$lang['application_new_user_email_help'] = '';

$lang['application_new_user_password'] = 'Wachtwoord';
$lang['application_new_user_password_help'] = 'Kies een sterk wachtwoord van minstens 8 tekens.';

$lang['application_new_user_confirm_password'] = 'Bevestig wachtwoord';
$lang['application_new_user_confirm_password_help'] = 'Vul het wachtwoord opnieuw in.';

$lang['application_new_user_first_name'] = 'Voornaam';
$lang['application_new_user_first_name_help'] = 'De voornaam van de gebruiker.';

$lang['application_new_user_last_name'] = 'Achternaam';
$lang['application_new_user_last_name_help'] = 'De achternaam van de gebruiker.';

$lang['application_name_user_roles'] = 'Rollen';

$lang['application_new_user_submit'] = 'Toevoegen';

$lang['application_new_user_error_required'] = '%s is verplicht, vul dit alstublieft in.';
$lang['application_new_user_error_username_exists'] = 'Deze gebruikersnaam is al in gebruik.';
$lang['application_new_user_error_valid_email'] = 'Het opgegeven email address is ongeldig.';
$lang['application_new_user_error_password_not_equal'] = 'De opgegeven wachtwoorden komen niet overeen.';
$lang['application_new_user_error_password_not_strong_enough'] = 'Het wachtwoord voldoet niet aan de veiligheidseisen.';
$lang['application_new_user_error_invalid_username'] = 'Ongeldige gebruikersnaam.';
$lang['application_new_user_error_short_username'] = 'Gebruikersnaam is te kort.';
$lang['application_new_user_error_unknown'] = 'Er is iets foutgegaan, als dit de eerste keer is dat je dit ziet probeer het dan nog een keer. Zo niet, neem dan alsjeblieft contact op met de systeem beheerder.';

$lang['application_new_user_success'] = 'Nieuwe gebruiker \'%s\' toegevoegd.';
