Useful commands for console:
----------------------------
service apache2 restart 

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
