# LISA
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/609d0cc095eb48b18305a6cc3e178f4d)](https://app.codacy.com/app/Addono/lisa?utm_source=github.com&utm_content=Addono/lisa&utm_campaign=badger)

![Screenshot of the application](https://i.imgur.com/Yc03EAW.png)

Lisa Is Super Awesome (LISA) is an application made to track "things" between friends/a group of people who trust each other. It offers a simple, informal and trust-based system to let users tally on each other. Although the system is designed with a non-exploitative user base in mind are all actions related to a user still completely transparent, as each user can see on whom they tallied and who tallied on their tap.

Many use-cases can be imagined, in general LISA gives each user an integer sized balance which each user can subtract from on a one-by-one basis. Imagined use cases include collectively buying goods and keeping track of whom used what.

## Getting started

 1. Download a copy/make a clone/make a fork of the project and move it to a folder in your web server.
 1. Rename/duplicate `config.php-setup`, `database.php-setup` and `email.php-config` to `config.php`, `database.php` and `email.php` respectively, these files can be found in `application/config/`.
 1. Edit these config files to match your system. Most important is:
    * Add your hostname.
    * Enter the settings of your database.
 1. Navigate to __YOUR_HOSTNAME__/index.php/Install to initialize the database.

## Requirements
 * PHP 7.1 with the mysqli extension enabled.
 * MySQL database
    
## Licence
This project is released under the MIT license, except for all other sources which are included.
For these their own licence will still be in place. Projects included are:
 - [CodeIgniter](https://codeigniter.com) (MIT)
 - [CodeIgniter3 Translations](https://github.com/bcit-ci/codeigniter3-translations) (MIT)
 - [Font Awsome](https://github.com/FortAwesome/Font-Awesome) (MIT and SIL OFL 1.1)
 - [Material Kit](https://github.com/creativetimofficial/material-kit/blob/master/LICENSE.md) (MIT)
 - [SB Admin 2](https://github.com/BlackrockDigital/startbootstrap-sb-admin-2) (MIT)
 
## Customisation
### Language
Currently, the language used by the application is hard-coded into `Handler.php`, for future releases it is planned to move configurations like these into the database. For now, one should edit hard-coded language or substitute all language files in the `nederlands` language folder with your language of preference. During development I tried to add translations for both English and Dutch, so the English translation files should be complete.
