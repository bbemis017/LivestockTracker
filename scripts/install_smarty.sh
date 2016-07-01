#download and extract Smarty
cd ~/Downloads
wget https://github.com/smarty-php/smarty/archive/v3.1.29.tar.gz
tar -xzf v3.1.29.tar.gz
mkdir /usr/lib/php/7.0/Smarty
cp -r smarty-3.1.29/libs/* /usr/lib/php/7.0/Smarty

#setup directories in LivestockTracker
#cd /var/www/LivestockTracker/src/private/views
#mkdir cache configs smarty templates
