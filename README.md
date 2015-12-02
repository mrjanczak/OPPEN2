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

Then install all vendors with Composer and build model

	$> curl -s https://getcomposer.org/installer | php
	$> composer install
	
Copy FOS/UserBundle views to app/
	
	$> cp vendor/friendsofsymfony/user-bundle/FOS/UserBundle/Resources/views/* app/Resources/FOSUserBundle

Relaod your project:

	$> php bin/vendors update
	$> php app/console assets:install web
	$> php app/console propel:model:build
	$> php app/console propel:schema:create
	$> php app/console propel:fixtures:load
	$> chmod 777 -R app/cache app/logs

	$> heroku config:set SYMFONY__DATABASE_DRIVER=pdo_mysql
	$> heroku config:set SYMFONY__DATABASE_HOST=127.0.0.1
	$> heroku config:set SYMFONY__DATABASE_PORT=null
	$> heroku config:set SYMFONY__DATABASE_NAME=xxxxxxxxxxxxxx
	$> heroku config:set SYMFONY__DATABASE_USER=xxxxxxxxxxxxxx
	$> heroku config:set SYMFONY__DATABASE_PASSWORD='xxxxxxxxx'
	$> heroku config:set SYMFONY__LOCALE=pl
	$> heroku config:set SYMFONY__SECRET=xxxxxxxxxxxxxxxxxxxxx


Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.
