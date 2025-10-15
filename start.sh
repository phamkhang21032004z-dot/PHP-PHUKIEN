#!/bin/bash
sed -i "s/newrelic.appname=.*/newrelic.appname=\"${NEW_RELIC_APP_NAME:-PHPweb_PHUKIEN}\"/" /usr/local/etc/php/conf.d/newrelic.ini
sed -i "s/newrelic.license=.*/newrelic.license=\"${NEW_RELIC_LICENSE_KEY}\"/" /usr/local/etc/php/conf.d/newrelic.ini
service nginx start
php-fpm
