GoTest Vagrant VM Setup
===========================

1. Install apache
2. Install php
3. Install php5-mcrypt
3. Install MySQL

Adding the database
-----------------------------
CREATE DATABASE clicker CHARACTER SET utf8 COLLATE utf8_bin;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON clicker.* TO 'clicker'@'localhost' IDENTIFIED BY 'JE928#J!($haoP!-3';

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

Setup cron
--------------
Setup cron for node to start on reboot - http://stackoverflow.com/questions/13385029/automatically-start-forever-node-on-system-restart
 - @reboot /vagrant/node/server.sh start dev