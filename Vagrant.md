GoTest Vagrant VM Setup
===========================

1. Install apache
2. Install php
3. Install php5-mcrypt
3. Install MySQL

Install apc: sudo apt-get install php-apc
Instal node and npm: http://stackoverflow.com/questions/16302436/install-nodejs-on-ubuntu-12-10
Setup cron for node to start on reboot - http://stackoverflow.com/questions/13385029/automatically-start-forever-node-on-system-restart
 - @reboot /vagrant/node/server.sh start dev