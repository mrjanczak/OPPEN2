Getting started
===============

The OppenProjectBundle is a Symfony2 bundle developed and tested for versions 2.4+. 

## Prerequisites

This bundle is tested using Symfony2 versions 2.4+. It uses other bundles and assets, which you have to download and integrate by yourself:

* PropelBundle
* UserBundle (by FriendsOfSymfony)
* UploaderBundle (by OneUp)
* JQuery & JQuery UI
* TintMCE editor

You can install Symfony using composer.phar. Run the following commands:

	$> curl -s https://getcomposer.org/installer | php
	$> php composer.phar create-project symfony/framework-standard-edition /Symfony 2.4.*
	$> cd Symfony
	
## Installation

Perform the following steps to install and use the basic functionality of the OppenProjectBundle:

* Download OppenProjectBundle and other assets using Composer
* Enable the bundles
* Configure the bundles
* Create MySQL database and user with proper privilages

### Step 1: Download the OppenProjectBundle and other Prerequisites

Add OppenProjectBundle to your composer.json using the following construct:

```js
{
	"require": {
		...
		"propel/propel-bundle": "1.4.*",
		"friendsofsymfony/user-bundle": "~2.0@dev",
		"willdurand/propel-typehintable-behavior": "~1.0",
		"oneup/uploader-bundle": "~1.3"       
	}
	"extra": {
		"symfony-app-dir": "app",
		"symfony-web-dir": "web",
		"symfony-assets-install": "symlink"
	}
}
```

Now tell composer to download the bundles by running the following command:

    $> curl -s https://getcomposer.org/installer | php
    $> php composer.phar update 

Composer will now fetch and install this bundle in the vendor directory ```vendor/Oppen```

Download assets:
* JQuery - jquery.min.js file to web/jquery [http://jquery.com/download/]
* JQuery-UI - all files of your theme to web/jquery/ui [http://jqueryui.com/download/]
* TintMCE for JQuery - all files to web/tinymce [http://www.tinymce.com/download/download.php]
* FileUpload - all files to web/fileupload [https://github.com/blueimp/jQuery-File-Upload/tags]

Execute:
	$> php app/console assetic:dump --env=prod --no-debug        
Then in asset .css file replace "../../" by "../"
        
        
### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
            new Propel\PropelBundle\PropelBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Oppen\ProjectBundle\OppenProjectBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle()                        
    );
}
```
### Step 3: Configure bundles

```yaml
# app/config/config.yml

	# Assetic Configuration
	assetic:
	    debug:          "%kernel.debug%"
	    use_controller: false
	    bundles:        [ OppenProjectBundle ]
	    
	# Propel Configuration
	propel:
	    dbal:
		driver:               %database_driver%
		user:                 %database_user%
		password:             %database_password%
		dsn:                  %database_driver%:host=%database_host%;dbname=%database_name%;charset=UTF8
		options:             {}
		attributes:          {}

	#FOS User    
	fos_user:
	    db_driver: propel
	    firewall_name: main
	    user_class: FOS\UserBundle\Propel\User 
    
    #One-Up Uploader       
	oneup_uploader:
		mappings:
			gallery:
				frontend: blueimp 
				enable_progress: true
				namer: oppen_project.cat_namer
```

Set routing
 
```yaml    
# app/config/routing.yml
    
	home:
	    path:  /
	    defaults: { _controller: OppenProjectBundle:Settings:home }

	oppen:
	    resource: "@OppenProjectBundle/Resources/config/routing.yml"
	    prefix: /oppen

	fos_user_security:
	    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

	fos_user_profile:
	    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
	    prefix: /profile

	fos_user_register:
	    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
	    prefix: /register

	fos_user_resetting:
	    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
	    prefix: /resetting

	fos_user_change_password:
	    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
	    prefix: /profile       
 
	oneup_uploader:
		resource: .
		type: uploader
```

... security rules:

```yaml       
# app/config/security.yml

	security:
	    encoders:
		FOS\UserBundle\Model\UserInterface: sha512    

	    role_hierarchy:
		ROLE_ADMIN:       ROLE_USER
		ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

	    providers:
		fos_userbundle:
		    id: fos_user.user_provider.username

	    firewalls:
		dev:
		    pattern:  ^/(_(profiler|wdt)|css|images|js)/
		    security: false

		main:
		    pattern: ^/
		    form_login:
		        provider: fos_userbundle
		        csrf_provider: form.csrf_provider
		    logout:       true
		    anonymous:    true

	    access_control:
		- { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
		- { path: ^/oppen, role: ROLE_USER }
		- { path: ^/oppen/admin/, role: ROLE_ADMIN }        
		- { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
		- { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

and parameters:

```yaml
# app/config/parameters.yml

parameters:
    database_driver: pdo_mysql
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

Copy views of UserBundle to app/Resources

	$> cp vendor/friendsofsymfony/user-bundle/FOS/UserBundle/Resources/views/* app/Resources/FOSUserBundle

and set new template in UserBundle layout:

``` twig
	# app/Resources/FOSUserBundle/layout.html.twig
	
	{% extends 'OppenProjectBundle::layout.html.twig' %}
```

Finally create model and warm up application:
		 
	$> php app/console propel:model:build
	$> php app/console cache:clear --env=prod --no-debug
	$> php app/console cache:warmup --env=prod --no-debug
	$> php app/console assetic:dump --env=prod --no-debug

### Step 4: Create database

