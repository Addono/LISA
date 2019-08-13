# LISA
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/609d0cc095eb48b18305a6cc3e178f4d)](https://app.codacy.com/app/Addono/lisa?utm_source=github.com&utm_content=Addono/lisa&utm_campaign=badger)
[![Project Status: Inactive – The project has reached a stable, usable state but is no longer being actively developed; support/maintenance will be provided as time allows.](https://www.repostatus.org/badges/latest/inactive.svg)](https://www.repostatus.org/#inactive)

![Screenshot of the application](https://i.imgur.com/Yc03EAW.png)

Lisa Is Super Awesome (LISA) is an application made to track "things" between friends/a group of people who trust each other. It offers a simple, informal and trust-based system to let users tally on each other. Although the system is designed with a non-exploitative user base in mind are all actions related to a user still completely transparent, as each user can see on whom they tallied and who tallied on their tap.

Many use-cases can be imagined, in general LISA gives each user an integer sized balance which each user can subtract from on a one-by-one basis. Imagined use cases include collectively buying goods and keeping track of whom used what.

## Installation

### docker-compose (Recommended)

```bash
# Clone the repository
git clone https://github.com/Addono/lisa && cd lisa

# Configure the database, the defaults are for a docker-compose instance
cp application/config/database.php-setup application/config/database.php

# Configure general settings, setting the hostname is mandatory
# by default it is set to http://localhost:8080, if your Docker instance
# is not at localhost, then you need to set $config['base_url'] manually 
# in application/config/config.php
cp application/config/config.php-setup application/config/config.php

# Optional, although you will get nasty errors without configuring this,
# configure outgoing email.
cp application/config/email.php-setup application/config/email.php
vim application/config/email.php

# Setup if finished, it's time to launch
docker-compose up -d
```
One small last thing we need to do is to run all database migrations, either point your browser at [http://localhost:8080/install](http://localhost:8080/install) or run`curl http://localhost:8080/install`.

:rocket: And we are live at [http://localhost:8080](http://localhost:8080) :rocket:

Hint: The default user's credentials `admin:admin312` :wink:

_Note: Depending on how you installed Docker it might be that Docker is not accessible at localhost, in that case replace localhost with the IP address of your Docker installation. E.g. for docker-machine users run `$(docker-machine ip)`._

### Manual
Requirements:
 * PHP 7.1 with the mysqli extension enabled.
 * MySQL database

 1. Download a copy/make a clone/make a fork of the project and move it to a folder in your web server.
 1. Rename or copy  `config.php-setup`, `database.php-setup` and `email.php-config` to `config.php`, `database.php` and `email.php` respectively, these files can be found in `application/config/`.
 1. Edit these config files to match your system.
    * Add your hostname in `config.php`.
    * Enter the settings of your database in `database.php`.
    * (Optional, but recommended) Configure an SMTP server in `email.php`.
 1. Navigate to __YOUR_HOSTNAME__/index.php/Install to initialize the database.

## Usage

By default, the database is populated with one admin user with username `admin`and password `admin312`. Login as this user and change the password as soon as possible.

Afterwards, use the admin user to create new users, which can be done in the backend. When creating new users, make sure that the `user`role is enabled*, otherwise they won't be able to use the application.

*The admin user doesn't have the user role by default, hence the "Insufficient rights" message when you first logged in. Obviously you can add the admin user to the `user`group, however it is recommended to keep the admin user merely as an admin.

## Customisation

### Language
Currently, the language used by the application is hard-coded into `Handler.php`, for future releases it is planned to move configurations like these into the database. For now, one should edit hard-coded language or substitute all language files in the `nederlands` language folder with your language of preference. During development I tried to add translations for both English and Dutch, so the English translation files should be complete.
