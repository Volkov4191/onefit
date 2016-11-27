php -r "readfile('https://getcomposer.org/installer');" | php
php -dmemory_limit=2G composer.phar install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force