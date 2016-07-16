#!/usr/bin/env bash

echo ">>> Installing xDebug"

mkdir /var/log/xdebug
chown www-data:www-data /var/log/xdebug
sudo pecl install xdebug

echo ';;;;;;;;;;;;;;;;;;;;;;;;;;' >> /etc/php/7.0/fpm/php.ini	
echo '; Added to enable Xdebug ;' >> /etc/php/7.0/fpm/php.ini
echo ';;;;;;;;;;;;;;;;;;;;;;;;;;' >> /etc/php/7.0/fpm/php.ini
echo '' >> /etc/php/7.0/fpm/php.ini
echo 'zend_extension="'$(find / -name 'xdebug.so' 2> /dev/null)'"' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.default_enable = 1' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.idekey = "vagrant"' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.remote_enable = 1' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.remote_autostart = 0' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.remote_port = 9000' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.remote_handler=dbgp' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.remote_log="/var/log/xdebug/xdebug.log"' >> /etc/php/7.0/fpm/php.ini
echo 'xdebug.remote_host=192.168.68.8 ; IDE-Environments IP, from vagrant box.' >> /etc/php/7.0/fpm/php.ini

cd /etc/php/7.0/cli/
sudo mv php.ini php.ini.original
sudo mv conf.d conf.d.original
sudo ln -s /etc/php/7.0/fpm/php.ini
sudo ln -s /etc/php/7.0/fpm/conf.d

sudo service php7.0-fpm restart

echo ">>> Installed xDebug"