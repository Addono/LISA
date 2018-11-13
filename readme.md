[![Codacy Badge](https://api.codacy.com/project/badge/Grade/609d0cc095eb48b18305a6cc3e178f4d)](https://app.codacy.com/app/Addono/lisa?utm_source=github.com&utm_medium=referral&utm_content=Addono/lisa&utm_campaign=badger)

# Getting started

 1. Download a copy/make a clone/make a fork of the project and move it to a folder in your web server.
 1. Rename/duplicate config.php-setup, database.php-setup and email.php-config to config.php, database.php and email.php respectively.
 1. Edit these config files to match your system. Most important is:
    * Add your hostname.
    * Enter the settings of your database.
 1. Navigate to __YOUR_HOSTNAME__/index.php/Install to initialise the database.

# Requirements
 * PHP 7.1 with the mysqli extension enabled.
 * MySQL database
	
# Licence
This project is released under the MIT licence, except for all other sources which are included.
For these their own licence will still be in place. Projects included are:
 - [CodeIgniter](https://codeigniter.com) (MIT)
 - [CodeIgniter3 Translations](https://github.com/bcit-ci/codeigniter3-translations) (MIT)
 - [Font Awsome](https://github.com/FortAwesome/Font-Awesome) (MIT and SIL OFL 1.1)
 - [Material Kit](https://github.com/creativetimofficial/material-kit/blob/master/LICENSE.md) (MIT)
 - [SB Admin 2](https://github.com/BlackrockDigital/startbootstrap-sb-admin-2) (MIT)
 
# Language
Currently the language used by the application is hard-coded into Handler.php, for future releases it is planned to move configurations like these into database. For now one should edit hard-coded language or substitute all language files in the 'nederlands' language folder with your language of preference. During development I tried to add translations for both English and Dutch, so the English translation files should be complete.
