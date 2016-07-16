#!/usr/bin/env bash

export LANG=C.UTF-8

PHP_TIMEZONE=$1
HHVM=$2
PHP_VERSION=$3

if [[ $HHVM == "true" ]]; then

    echo ">>> Installing HHVM"

    # Get key and add to sources
    wget --quiet -O - http://dl.hhvm.com/conf/hhvm.gpg.key | sudo apt-key add -
    echo deb http://dl.hhvm.com/ubuntu trusty main | sudo tee /etc/apt/sources.list.d/hhvm.list

    # Update
    sudo apt-get update

    # Install HHVM
    # -qq implies -y --force-yes
    sudo apt-get install -qq hhvm

    # Start on system boot
    sudo update-rc.d hhvm defaults

    # Replace PHP with HHVM via symlinking
    sudo /usr/bin/update-alternatives --install /usr/bin/php php /usr/bin/hhvm 60

    sudo service hhvm restart
else
    sudo add-apt-repository -y ppa:ondrej/php
    sudo apt-key update
    sudo apt-get update
    
    sudo apt-get install -qq php7.0 php7.0-cli php7.0-fpm php7.0-mysql php7.0-pgsql php7.0-sqlite php7.0-curl php7.0-dev php7.0-gd php7.0-intl php7.0-imap php7.0-mbstring php7.0-opcache php7.0-soap php7.0-tidy php7.0-xmlrpc
    sudo apt-get install -qq php-pear php-xdebug

    # Set PHP FPM to listen on TCP instead of Socket
    sudo sed -i "s/listen =.*/listen = 127.0.0.1:9000/" /etc/php/7.0/fpm/pool.d/www.conf

    # Set PHP FPM allowed clients IP address
    sudo sed -i "s/;listen.allowed_clients/listen.allowed_clients/" /etc/php/7.0/fpm/pool.d/www.conf

    # Set run-as user for PHP/7.0-FPM processes to user/group "vagrant"
    # to avoid permission errors from apps writing to files
    sudo sed -i "s/user = www-data/user = vagrant/" /etc/php/7.0/fpm/pool.d/www.conf
    sudo sed -i "s/group = www-data/group = vagrant/" /etc/php/7.0/fpm/pool.d/www.conf

    sudo sed -i "s/listen\.owner.*/listen.owner = vagrant/" /etc/php/7.0/fpm/pool.d/www.conf
    sudo sed -i "s/listen\.group.*/listen.group = vagrant/" /etc/php/7.0/fpm/pool.d/www.conf
    sudo sed -i "s/listen\.mode.*/listen.mode = 0666/" /etc/php/7.0/fpm/pool.d/www.conf

    sudo echo ';;;;;;;;;;;;;;;;;;;;;;;;;;' >> /etc/php/7.0/fpm/php.ini
    sudo echo '; Added to enable Xdebug ;' >> /etc/php/7.0/fpm/php.ini
    sudo echo ';;;;;;;;;;;;;;;;;;;;;;;;;;' >> /etc/php/7.0/fpm/php.ini
    sudo echo '' >> /etc/php/7.0/fpm/php.ini
    # sudo echo 'zend_extension="'$(find / -name 'xdebug.so' 2> /dev/null)'"' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.default_enable = 1' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.idekey = "vagrant"' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.remote_enable = 1' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.remote_autostart = 0' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.remote_port = 9000' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.remote_handler=dbgp' >> /etc/php/7.0/fpm/php.ini
    sudo echo 'xdebug.remote_log="/var/log/xdebug/xdebug.log"' >> /etc/php/7.0/fpm/php.ini
	
	sudo sed -i "s/error_reporting = E_ALL &/error_reporting = E_ALL ;&/" /etc/php/7.0/fpm/php.ini
	sudo sed -i "s/display_errors = Off/display_errors = On/" /etc/php/7.0/fpm/php.ini
	sudo sed -i "s/display_startup_errors = Off/display_startup_errors = On/" /etc/php/7.0/fpm/php.ini
	sudo sed -i "s/track_errors = Off/track_errors = On/" /etc/php/7.0/fpm/php.ini
	sudo sed -i "s/;date.timezone =/date.timezone = Europe/Warsaw/" /etc/php/7.0/fpm/php.ini

    cd /etc/php/7.0/cli/
    sudo mv php.ini php.ini.original
    sudo mv conf.d conf.d.original
    sudo mv ./pool.d/www.conf ./pool.d/www.conf.original
    sudo ln -s /etc/php/7.0/fpm/php.ini
    sudo ln -s /etc/php/7.0/fpm/conf.d
    cd pool.d/
    sudo ln -s /etc/php/7.0/fpm/pool.d/www.conf

    sudo service php7.0-fpm restart
fi
