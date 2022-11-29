#!/bin/bash

set -e

##let the container build completely.
#sleep 15

chmod -R 777 /var/www/html/laravelMS/ezKartMailService
chown -R www-data:www-data /var/www/html/laravelMS/ezKartMailService
mkdir -p /var/www/html/laravelMS/ezKartMailService/bootstrap/cache
find /var/www/html/laravelMS/ezKartMailService -type f -exec chmod 644 {} \;
find /var/www/html/laravelMS/ezKartMailService -type d -exec chmod 755 {} \;
/etc/init.d/apache2 restart
cd /var/www/html/laravelMS/ezKartMailService && chgrp -R www-data storage bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache
cd /var/www/html/laravelMS/ezKartMailService && php artisan cache:clear &&
php artisan config:clear && php artisan migrate && php artisan serve


echo "Account API start"
exec "$@"