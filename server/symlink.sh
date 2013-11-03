sudo mv /etc/apache2/httpd.conf /etc/apache2/httpd.conf-old
ln -s `pwd`/etc/apache2/httpd.conf /etc/apache2/

sudo ln -s `pwd`/etc/apache2/sites-available/empty /etc/apache2/sites-available/
sudo ln -s `pwd`/etc/apache2/sites-available/gotest /etc/apache2/sites-available/

sudo mv /etc/mysql/my.cnf /etc/mysql/my.cnf-old
# Needs to be copied, otherwise mysql doesn't like
sudo cp `pwd`/etc/mysql/my.cnf /etc/mysql/

sudo mv /etc/php5/apache2/php.ini /etc/php5/apache2/php.ini-old
sudo ln -s `pwd`/etc/php5/apache2/php.ini /etc/php5/apache2/