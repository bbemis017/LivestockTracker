#!/bin/bash

apt-get install php7.0-xml
apt-get install unzip

#download and unzip aws sdk
mkdir aws
cd aws
wget "http://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.zip"
unzip aws.zip
rm aws.zip
cd ..

#make changes take effect
service apache2 restart
