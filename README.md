Getting started
===============

The OppenProject is an application for NGO to manage projects. 

## Manual
https://github.com/mrjanczak/OPPEN2/blob/master/src/AppBundle/Resources/doc/manual.pdf

## Prerequisites

This app is tested using Symfony2 versions 2.4+. It uses follwing bundles and assets:

* [**PropelBundle**][1]
* [**User Bundle by FriendsOfSymfony**][2]
* [**Uploader Bundle by OneUp**][3]
* [**JQuery UI**][4]
* [**TintMCE**][5]

[1]: https://packagist.org/packages/propel/propel-bundle
[2]: https://packagist.org/packages/friendsofsymfony/user-bundle
[3]: https://packagist.org/packages/oneup/uploader-bundle
[4]: http://jqueryui.com/
[5]: https://www.tinymce.com/

## Installation

You can download OppenProject from GitHub:

    $> git clone https://github.com/musicahumana/Oppen.git

Install all vendors with Composer

	$> curl -s https://getcomposer.org/installer | php
	$> composer install

Update parameters.yml file

```yaml
# app/config/parameters.yml

parameters:
    database_driver: pdo_pgsql
    database_host: 127.0.0.1
    database_port: null
    database_name: xxxxxxx
    database_user: xxxxxxx
    database_password: xxxxxxxxx
    
    mailer_transport: smtp
    mailer_host: xxxx.xxx
    mailer_user: xxxx@xxxxxx.xxx
    mailer_password: xxxxxxx
    locale: pl
    secret: xxxxxxxxxxxxxxxxxx
```

Prepare production enviroment:

	$> php app/console cache:clear --env=prod --no-debug
	$> php app/console cache:warmup --env=prod --no-debug
	$> php app/console assets:install web
	$> php app/console assetic:dump --env=prod --no-debug
	$> sudo chmod 777 -R app/cache app/logs
	
Prepare database:

	$> php app/console propel:sql:insert --force
	$> php app/console propel:fixtures:load @AppBundle

And create user:

	$> php app/console fos:user:create
	$> user_name
	$> user_email
	$> user_pass
	
	$> php app/console fos:user:promote
	$> user_name
	$> ROLE_SUPER_ADMIN

Open the `config.php`page:

    http:///.../web/config.php

If you get any warnings or recommendations, fix them before moving on.

Now application is ready to use:

    http://.../web/app.php

## Instalation on Heroku


