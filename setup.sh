#!/bin/bash
rm composer.lock
composer install &&\
mkdir -p vendor-bin/box/vendor/
rm vendor-bin/box/vendor/composer.json
echo "{\"minimum-stability\": \"dev\"}" > vendor-bin/box/composer.json &&\
composer bin box require --dev humbug/box

