Getting started
===============

The OppenProject is an application for NGO to manage projects. 

## Prerequisites

This app is tested using Symfony2 versions 2.4+. It uses follwing bundles:

* [**PropelBundle**][1]
* [**User Bundle by FriendsOfSymfony**][2]
* [**Uploader Bundle by OneUp**][3]
* [**Jquery file upload by mrj**][4]
* [**JQuery & JQuery UI bundles by BMatzner**][5]
* [**TintMCE bundle by Stfalcon**][6]

[1]: https://packagist.org/packages/propel/propel-bundle
[2]: https://packagist.org/packages/friendsofsymfony/user-bundle
[3]: https://packagist.org/packages/oneup/uploader-bundle
[4]: https://packagist.org/packages/mrj/symfony-jquery-file-upload
[5]: https://packagist.org/packages/bmatzner/jquery-ui-bundle
[6]: https://packagist.org/packages/stfalcon/tinymce-bundle

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
```

Prepare production enviroment:

	$> php app/console cache:clear --env=prod --no-debug
	$> php app/console cache:warmup --env=prod --no-debug
	$> php app/console assets:install web
	$> php app/console assetic:dump --env=prod --no-debug
	$> chmod 777 -R app/cache app/logs
	
Prepare database:

	$> php app/console propel:model:build
	$> php app/console propel:sql:build
	$> php app/console propel:sql:insert
	$> php app/console propel:schema:create
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
