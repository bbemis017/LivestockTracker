add-apt-repository ppa:ondrej/php
apt-get update
apt-get install php7.0 php7.0-fpm php7.0-common php7.0-mysql
apt-get install curl php7.0-cli php7.0-fpm
apt-get install libapache2-mod-php7.0 
#a2enmod proxy_fcgi setenvif
#a2enconf php7.0-fpm
#a2dismod php5
#a2enmod php7.0
#service apache2 restart
