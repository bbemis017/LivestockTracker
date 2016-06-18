add-apt-repository ppa:ondrej/php
apt-get update
apt-get install php7.0-fpm php7.0-common php7.0-mysql
apt-get install curl php7.0-cli
a2enmod proxy_fcgi setenvif
a2enconf php7.0-fpm
a2dismod php5
a2enmod php7.0
service apache2 restart
