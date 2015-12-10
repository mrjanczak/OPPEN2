Manual installation step by step
===============================

### Step 1: Add to composer.json following lines:

```js
{
    "require": {
        ...
        "propel/propel-bundle": "1.4.*",
        "friendsofsymfony/user-bundle": "dev-master",
        "willdurand/propel-typehintable-behavior": "~1.0",        
        "oneup/uploader-bundle": "^1.3",
        "bmatzner/jquery-bundle": "~1.9",
        "bmatzner/jquery-ui-bundle": "*",
        "stfalcon/tinymce-bundle": "dev-master",
        "blueimp/jquery-file-upload": "^9.11"    
    }
    "extra": {
        ...
        "symfony-assets-install": "symlink",
        "incenteev-parameters": {
            "file": "app/config/parameters.yml"           
        },
        ...
        "heroku": {
            "compile": [
                "chmod 777 /app/app/cache"
            ],
            "document-root": "web",
            "php-config": [
                "date.timezone=Europe/Warsaw",
                "display_errors=off",
                "short_open_tag=off"
            ]                
        }
    }
}
```
        
### Step 2: Enable the bundles in AppKernel.php

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        ...
        new Propel\PropelBundle\PropelBundle(),
        new FOS\UserBundle\FOSUserBundle(),          
        new Bmatzner\JQueryBundle\BmatznerJQueryBundle(),
        new Bmatzner\JQueryUIBundle\BmatznerJQueryUIBundle(),
        new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
        new Oneup\UploaderBundle\OneupUploaderBundle(),
        new AppBundle(),                        
    );
}
```
### Step 3: Update config.yml

```yaml
# app/config/config.yml

    # Assetic Configuration
    assetic:
        debug:          "%kernel.debug%"
        use_controller: false
        bundles:        [ AppBundle ]
        
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

### Step 4: Update routing.yml
 
```yaml    
# app/config/routing.yml
    
    home:
        path:  /
        defaults: { _controller: AppBundle:Settings:home }

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
        
    ...
```

### Step 5: Update security.yml

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

### Step 6: Update parameters.yml

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

### Step 7: Use app/console to configure application
    
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


