Useful commands for console:
----------------------------

# MySQL

DELETE FROM `bookk_entry` USING `bookk_entry` ,
`bookk` WHERE `bookk_entry`.`bookk_id` = `bookk`.`id` AND `bookk`.`doc_id` IS NULL 

# PHP

service apache2 restart 
sudo /etc/init.d/apache2 restart

# Composer

php composer.phar install --no-dev --optimize-autoloader

# Console

php app/console cache:clear --env=prod --no-debug
php app/console cache:warmup --env=prod --no-debug
php app/console assets:install web
php app/console assetic:dump --env=prod --no-debug

php app/console propel:model:build
php app/console propel:fixtures:load @AppBundle

php app/console propel:migration:generate-diff
php app/console propel:migration:migrate
php app/console debug:router --env=prod

# Linux

sudo chmod -R 777 app

# Github

git clone https://github.com/musicahumana/Oppen.git
git config --global user.name "mrjanczak"
git config --global user.email mrjanczak@gmail.com
git remote add mh https://github.com/musicahumana/Oppen.git

git add *
git commit -a -m 'xxx'
git push mh 2.0
git push heroku master



# Heroku

heroku login

heroku config:set SWIFTMAILER_URL=gmail://mail@latempesta.pl:***@smtp.gmail.com

heroku run php app/console cache:clear --env=prod --no-debug
heroku run php app/console cache:warmup --env=prod --no-debug

heroku git:clone -a oppen-project

heroku git:remote -a 
heroku logs --tail
heroku run php app/console assets:install web
heroku run php app/console propel:model:build

heroku run composer install -dev --optimize-autoloader
heroku run composer update -prod 

php app/console cache:clear
php app/console cache:warmup --no-optional-warmers

### Changes

# composer.json
    "extra": {
        "heroku": {
            "compile": [
                "chmod 777 /app/app/cache"
            ]
        }
    }

# config_prod.yml
monolog:
    handlers:
        nested:
            path:  "php://stderr"   

### ClearDB

heroku addons:create cleardb:ignite
heroku run php app/console propel:schema:create

heroku config:get CLEARDB_DATABASE_URL

mysql://b39453a823acea:94b8b6ef@eu-cdbr-west-01.cleardb.com/heroku_09a29fcafd1c0c7?reconnect=true

# MySQL Workbench + SSL

Download following files from ClearDB console:
- b39453a823acea-cert.pem
- b39453a823acea-key.pem
- cleardb-ca.pem

Run in terminal to remove password:
openssl rsa -in b39453a823acea-key.pem -out b39453a823acea-key-no-password.pem

Configure MySQL Workbench with above files.

