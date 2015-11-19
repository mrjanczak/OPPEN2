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
php app/console cache:clear
php app/console cache:clear --env=prod --no-debug
php app/console assetic:dump --env=prod --no-debug
php app/console cache:warmup --env=prod --no-debug
php app/console assets:install web --symlink

git clone https://github.com/musicahumana/Oppen.git
git config --global user.name "mrjanczak"
git config --global user.email mrjanczak@gmail.com
git remote add mh https://github.com/musicahumana/Oppen.git
git add *
git commit -a -m 'xxx'
git push mh 2.0

### PostgreSQL

CREATE ROLE root WITH PASSWORD '123';
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA oppen TO root;

Migration to a remote server:

	pg_dump -C -h localhost -U oppen_admin oppen | psql -h ec2-54-195-252-202.eu-west-1.compute.amazonaws.com -U fmwywibshtmelk d5ll5jdith257o

ewud-jlGTJpGYXMTVxFxIE_-eU

Migration from MySQL:

	https://github.com/AnatolyUss/FromMySqlToPostgreSql

### ClearDB

heroku addons:create cleardb:ignite --fork=mysql://root:123@localhost/oppen

mysql://b39453a823acea:94b8b6ef@eu-cdbr-west-01.cleardb.com/heroku_09a29fcafd1c0c7?reconnect=true
host: eu-cdbr-west-01.cleardb.com
b39453a823acea:94b8b6ef

# Heroku
heroku git:remote -a 

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
