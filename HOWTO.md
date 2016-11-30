http://symfony.com/doc/current/setup.html
http://symfony.com/doc/current/setup/file_permissions.html

php -r "readfile('https://getcomposer.org/installer');" | php
php -dmemory_limit=2G composer.phar install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console assetic:dump
php bin/console assets:install



rm -rf var/*
php -dmemory_limit=2G composer.phar update
chmod 777 -R var/cache
chmod 777 -R var/logs
php bin/console assetic:dump
php bin/console assets:install