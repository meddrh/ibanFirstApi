# ibanFirstApi

Installation Development Environment

Requirement
Do not have PHP 7.2 ? instructions to setup bellow
# remove previous PHP versions on the host
$ sudo apt-get --purge remove php-common
# install required packages 
$ sudo apt-get install software-properties-common python-software-properties
# add the ondrej PPA and update your sources
$ sudo add-apt-repository -y ppa:ondrej/php
$ sudo apt-get update
# install PHP 7.2
$ sudo apt-get install -y php7.2 php7.2-cli php7.2-common
# install the most commonly used PHP extensions
$ sudo apt-get install -y php7.2-curl php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-xml php7.2-zip php7.2-amqp
# check current PHP version is PHP 7.2
$ php -v
# install gRPC and protobuf extension to allow installation of google/cloud-firestore PHP pachage (https://cloud.google.com/firestore/docs/quickstart-servers)
$ apt-get install -y autoconf libz-dev php7.2-dev php-pear
$ pecl install grpc; pecl install protobuf
$ echo "extension=grpc.so" >> /etc/php/7.2/cli/php.ini
$ echo "extension=protobuf.so" >> /etc/php/7.2/cli/php.ini
The library used to connect to the API : http_client
https://symfony.com/doc/current/components/http_client.html

#Setup
```bash
$ composer install
$ yarn install
$ yarn build
$ php bin/console server:start
Assets Development Workflow
I use the library Webpack Encore to build assets.
# compile assets once
$ yarn encore dev
# or, recompile assets automatically when files change
$ yarn encore dev --watch

