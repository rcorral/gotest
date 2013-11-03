GoTest Vagrant VM Setup
===========================

1. Install apache
2. Install php
3. Install php5-mcrypt
3. Install MySQL

Enable rewrite
----------------------
sudo a2enmod rewrite

Adding the database
-----------------------------
CREATE DATABASE clicker CHARACTER SET utf8 COLLATE utf8_bin;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON clicker.* TO 'USER'@'localhost' IDENTIFIED BY 'PASS';

Remove test database
----------------------
sudo service mysql stop
sudo rm -r /var/lib/mysql/test
sudo service mysql start

Install apc
---------------
sudo apt-get install php-apc

Install git
-------------
sudo apt-get install git-core

Install node
-------------
Instal node and npm: http://stackoverflow.com/questions/16302436/install-nodejs-on-ubuntu-12-10

Required node.js modules
-----------------------------
> cd node
> npm install

Clone repo
------------------
mkdir /var/sites && cd /var/sites
git clone git@bitbucket.org:rcorral/clicker.git

Setup server configs
---------------------
cd clicker/server
./symlink.sh

Setup public dirs
-------------------
cd ..
sudo ln -s `pwd`/laravel/public /var/www/gotest.org
sudo mkdir /var/www/empty
sudo touch /var/www/empty/index.html

Setup database config in laravel
--------------------------------
cp laravel/app/config/database.php-sample laravel/app/config/database.php
vi laravel/app/config/database.php

Enable sites
----------------------------------
sudo a2dissite default && sudo a2ensite empty && sudo a2ensite gotest

Restart all for fun
--------------------
sudo service mysql restart
sudo service apache2 restart

Install composer
----------------------
http://getcomposer.org/download/

Install the rest
-----------------
./composer.phar install
php artisan migrate

Setup cron
--------------
Setup cron for node to start on reboot - http://stackoverflow.com/questions/13385029/automatically-start-forever-node-on-system-restart
 - @reboot /vagrant/node/server.sh start dev