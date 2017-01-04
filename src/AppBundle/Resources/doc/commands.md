Useful commands for console:
----------------------------

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

heroku config:set SWIFTMAILER_URL=smtp://mail.latempesta.pl:vespers1@mail.latempesta.pl

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



### PostgreSQL

CREATE ROLE root WITH PASSWORD '123';
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA oppen TO root;

Migration to a remote server:

	pg_dump -C -h localhost -U oppen_admin oppen | psql -h ec2-54-195-252-202.eu-west-1.compute.amazonaws.com -U fmwywibshtmelk d5ll5jdith257o

ewud-jlGTJpGYXMTVxFxIE_-eU

Migration from MySQL:

	https://github.com/AnatolyUss/FromMySqlToPostgreSql

Install PostgreSQL server:

	$> apt-get install postgresql postgresql-client
	$> apt-get install php5-pgsql
	$> sudo apt-get install pgadmin3
	
To start off, we need to set the password of the PostgreSQL user (role) called "postgres"; we will not be able to access the server externally otherwise. As the local “postgres” Linux user, we are allowed to connect and manipulate the server using the psql command.	
	
	$> sudo -u postgres psql postgres

This connects as a role with same name as the local user, i.e. "postgres", to the database called "postgres" (1st argument to psql).

Set a password for the "postgres" database role using the command:

		\password postgres

and give your password when prompted. The password text will be hidden from the console for security purposes.
Type Control+D or \q to exit the posgreSQL prompt. 

	sudo -u postgres createuser -D -A -P oppen_admin
	sudo -u postgres createdb -O oppen_admin oppen