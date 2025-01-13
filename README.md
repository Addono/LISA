# LISA
[![Continuous Integration](https://github.com/Addono/LISA/actions/workflows/continuous-integration.yaml/badge.svg)](https://github.com/Addono/LISA/actions/workflows/continuous-integration.yaml)
[![All Contributors](https://img.shields.io/badge/all_contributors-3-orange.svg)](#contributors)
[![Project Status: Inactive – The project has reached a stable, usable state but is no longer being actively developed; support/maintenance will be provided as time allows.](https://www.repostatus.org/badges/latest/inactive.svg)](https://www.repostatus.org/#inactive)

![Screenshot of the application](https://i.imgur.com/Yc03EAW.png)

Lisa Is Super Awesome (LISA) is an application made to track "things" between friends/a group of people who trust each other. It offers a simple, informal and trust-based system to let users tally on each other. Although the system is designed with a non-exploitative user base in mind are all actions related to a user still completely transparent, as each user can see on whom they tallied and who tallied on their tap.

Many use-cases can be imagined, in general LISA gives each user an integer sized balance which each user can subtract from on a one-by-one basis. Imagined use cases include collectively buying goods and keeping track of whom used what.

## Installation

### Docker Compose (Recommended)

```bash
# Clone the repository
git clone https://github.com/Addono/lisa && cd lisa

# Install dependencies
npm install

# (Optional) Configure the database, the defaults are for a docker compose instance
vim application/config/database.php

# (Optional) Configure general settings, setting the hostname is mandatory
# by default it is set to http://localhost:8080, if your Docker instance
# is not at localhost, then you need to set $config['base_url'] manually 
# in application/config/config.php
vim application/config/config.php

# (Optional) Although you will get nasty errors in your logs whenever it
# tries to send email.
vim application/config/email.php

# Configuration is finished, let's deploy the application and all required services
docker compose up -d
```
One small last thing we need to do is to run all database migrations, either point your browser at [http://localhost:8080/install](http://localhost:8080/install) or run`curl http://localhost:8080/install`.

:rocket: And we are live at [http://localhost:8080](http://localhost:8080) :rocket:

Hint: The default admin credentials are `admin:admin312` :wink:

_Note: Depending on how you installed Docker it might be that Docker is not accessible at localhost, in that case replace localhost with the IP address of your Docker installation. E.g. for docker-machine users run `$(docker-machine ip)`._

### Manual
Requirements:
 * PHP 7* with the mysqli extension enabled.
 * MySQL database

 1. Download a copy/make a clone/make a fork of the project and move it to a folder in your web server.
 1. Rename or copy  `config.php-setup`, `database.php-setup` and `email.php-config` to `config.php`, `database.php` and `email.php` respectively, these files can be found in `application/config/`.
 1. Edit these config files to match your system.
    * Add your hostname in `config.php`.
    * Enter the settings of your database in `database.php`.
    * (Optional, but recommended) Configure an SMTP server in `email.php`.
 1. Navigate to __YOUR_HOSTNAME__/index.php/Install to initialize the database.

*The Docker Compose deploy method and the CI are using 7.4. The version used there is leading and the only one supported.

## Development Environment

For development purposes, an extended Docker Compose configuration is available in `docker-compose.dev.yml`. This configuration adds additional services useful for development:

### Additional Services
- **phpMyAdmin**: Web interface for database management
  - Available at: http://localhost:8081

### Starting Development Environment

To start all services including development tools:

```bash
docker compose -f docker-compose.dev.yml up -d
```

This will start all base services plus the development services.

## Usage

By default, the database is populated with one admin user with username `admin`and password `admin312`. Login as this user and change the password as soon as possible.

Afterwards, use the admin user to create new users, which can be done in the backend. When creating new users, make sure that the `user`role is enabled*, otherwise they won't be able to use the application.

*The admin user doesn't have the user role by default, hence the "Insufficient rights" message when you first logged in. Obviously you can add the admin user to the `user`group, however it is recommended to keep the admin user merely as an admin.

## Customisation

### Language
Currently, the language used by the application is hard-coded into `Handler.php`, for future releases it is planned to move configurations like these into the database. For now, one should edit hard-coded language or substitute all language files in the `nederlands` language folder with your language of preference. During development I tried to add translations for both English and Dutch, so the English translation files should be complete.

## Contributors✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center"><a href="https://aknapen.nl"><img src="https://avatars1.githubusercontent.com/u/15435678?v=4?s=100" width="100px;" alt="Adriaan Knapen"/><br /><sub><b>Adriaan Knapen</b></sub></a><br /><a href="#maintenance-Addono" title="Maintenance">🚧</a> <a href="#design-Addono" title="Design">🎨</a> <a href="https://github.com/Addono/LISA/issues?q=author%3AAddono" title="Bug reports">🐛</a> <a href="https://github.com/Addono/LISA/commits?author=Addono" title="Code">💻</a> <a href="#content-Addono" title="Content">🖋</a> <a href="#translation-Addono" title="Translation">🌍</a></td>
      <td align="center"><a href="https://www.sdhd.nl/"><img src="https://avatars1.githubusercontent.com/u/4325936?v=4?s=100" width="100px;" alt="Djamon Staal"/><br /><sub><b>Djamon Staal</b></sub></a><br /><a href="#security-SjamonDaal" title="Security">🛡️</a> <a href="#infra-SjamonDaal" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a></td>
      <td align="center"><a href="https://koenvw.nl"><img src="https://avatars0.githubusercontent.com/u/1337450?v=4?s=100" width="100px;" alt="Koen"/><br /><sub><b>Koen</b></sub></a><br /><a href="#infra-koen860" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="#ideas-koen860" title="Ideas, Planning, & Feedback">🤔</a> <a href="#userTesting-koen860" title="User Testing">📓</a> <a href="https://github.com/Addono/LISA/issues?q=author%3Akoen860" title="Bug reports">🐛</a></td>
      <td align="center"><a href="https://github.com/omit01"><img src="https://avatars.githubusercontent.com/u/62880858?v=4?s=100" width="100px;" alt="Timo"/><br /><sub><b>Timo</b></sub></a><br /><a href="https://github.com/Addono/LISA/commits?author=omit01" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/Synthetica9"><img src="https://avatars.githubusercontent.com/u/7075751?v=4?s=100" width="100px;" alt="Patrick Hilhorst"/><br /><sub><b>Patrick Hilhorst</b></sub></a><br /><a href="https://github.com/Addono/LISA/commits?author=Synthetica9" title="Code">💻</a></td>
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
