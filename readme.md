# Getting started
 1. Download a copy/make a clone/make a fork of the project and move it to a folder in your web server.
 1. Rename/duplicate config.php-setup and database.php-setup to config.php and database.php respectively.
 1. Edit these config files to match your system. Most important is:
    * Add your hostname.
    * Enter the settings of your database.
 1. Navigate to __YOUR_HOSTNAME__/index.php/Install to initialise the database.

# Requirements
 * PHP 7.1, although it might also work on PHP7 (untested). The mysqli extension of PHP should be enabled.
 * MySQL database, although it might also work on other database types supported by CodeIgniter, but non of them are tested nor are focus during development.

# Licence
This project is released under the MIT licence, except for all other sources which are included.
For these their own licence will still be in place. Projects included are:
 - [CodeIgniter3](https://codeigniter.com) (MIT)
 - [CodeIgniter3 Translations](https://github.com/bcit-ci/codeigniter3-translations) (MIT)
 - [Font Awsome](https://github.com/FortAwesome/Font-Awesome) (MIT and SIL OFL 1.1)
 - [Material Kit](https://github.com/creativetimofficial/material-kit/blob/master/LICENSE.md) (MIT)
 - [SB Admin 2](https://github.com/BlackrockDigital/startbootstrap-sb-admin-2) (MIT)
 
# Language
Currently the language used by the application is hard-coded into Handler.php, for future releases it is planned to move configurations like these into database. For now one should edit hard-coded language or substitute all language files in the 'nederlands' language folder with your language of preference. During development I tried to add translations for both English and Dutch, so the English translation files should be complete.

# Example
An example implementation is [Lisa](https://github.com/Addono/lisa). You might want to look through the code to get a feel how you can use this project.