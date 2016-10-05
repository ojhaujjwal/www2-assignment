WWW2 Assignment based on Zend Expressive and vue 
==================================================================

##Requirements
* PHP7
* composer
* mysql/mariadb/postgresql/sqlite

## Installation instructions
Makesure php7 and composer are installed. To install composer, [click here](https://getcomposer.org/download/)
* Create database tv_shows
* Open your terminal and run the following commands

```sh
git clone https://github.com/ojhaujjwal/www2-assignment
cd www2-assignment
composer install
./vendor/bin/doctrine orm:schema-tool:update --force
cp config/autoload/local.php.dist config/autoload/local.php
#Edit config/autoload/local.php to put appropriate database credentials

php -S 0.0.0.0:8080 -t public/ public/index.php
```

Now, go to localhost:8080 and the application should be running.
