Useful commands for console:
----------------------------
service apache2 restart 
sudo /etc/init.d/apache2 restart

php app/console propel:migration:generate-diff
php app/console propel:migration:migrate
php app/console propel:model:build

php app/console router:debug --env=prod
php composer.phar install --no-dev --optimize-autoloader

sudo chmod -R 777 app
php app/console assets:install web

php app/console cache:clear
php app/console cache:clear --env=prod --no-debug
php app/console assetic:dump --env=prod --no-debug
php app/console cache:warmup --env=prod --no-debug

git clone https://github.com/musicahumana/Oppen.git
git config --global user.name "mrjanczak"
git config --global user.email mrjanczak@gmail.com
git remote add mh https://github.com/musicahumana/Oppen.git
git add *
git commit -a -m 'xxx'
git push mh 2.0

$loader->add('FOS', __DIR__.'/../vendor/friendsofsymfony/user-bundle');
$loader->add('Sensio', __DIR__.'/../vendor/sensio/generator-bundle');

# Heroku
	
	$> heroku config:set SYMFONY__DATABASE_DRIVER=pdo_mysql
	$> heroku config:set SYMFONY__DATABASE_HOST=127.0.0.1
	$> heroku config:set SYMFONY__DATABASE_PORT=null
	$> heroku config:set SYMFONY__DATABASE_NAME=xxxxxxxxxxxxxx
	$> heroku config:set SYMFONY__DATABASE_USER=xxxxxxxxxxxxxx
	$> heroku config:set SYMFONY__DATABASE_PASSWORD='xxxxxxxxx'
	$> heroku config:set SYMFONY__LOCALE=pl
	$> heroku config:set SYMFONY__SECRET=xxxxxxxxxxxxxxxxxxxxx

heroku login
heroku git:clone -a oppen-project
git add *
git commit -a -m 'xxx'
git push heroku master

heroku git:remote -a 
heroku logs --tail
heroku run php app/console assets:install web
heroku run php app/console propel:model:build

heroku run composer install -dev --optimize-autoloader

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
            
# AppKernel.php
	public function getRootDir()
	{
		if (isset($_ENV['SYMFONY_ENV']) && $_ENV['SYMFONY_ENV'] == 'prod') {
			// Workaround to avoid problem with the slug of heroku
			return '/app/app';
		}
		return parent::getRootDir();
	} 


### ClearDB

heroku addons:create cleardb:ignite --fork=mysql://root:123@localhost/oppen
heroku run php app/console propel:schema:create

mysql://b39453a823acea:94b8b6ef@eu-cdbr-west-01.cleardb.com/heroku_09a29fcafd1c0c7?reconnect=true
host: eu-cdbr-west-01.cleardb.com
db:heroku_09a29fcafd1c0c7
user:b39453a823acea
password:94b8b6ef

/*
	$container->setParameter('database_driver', 'pdo_mysql'); 
	$container->setParameter('database_host', 'localhost'); 
	$container->setParameter('database_port', '~');
	$container->setParameter('database_name', 'heroku_09a29fcafd1c0c7'); 
	$container->setParameter('database_user', 'root'); 
	$container->setParameter('database_password', '123');

	$container->setParameter('mailer_transport', 'smtp');
	$container->setParameter('mailer_host', null);
	$container->setParameter('mailer_user', null);
	$container->setParameter('mailer_password', null);
	
	$container->setParameter('locale', 'pl');
	$container->setParameter('secret', 'c5bd9c14be810cd74439e07bdbc933049e092c5c');
*/


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
