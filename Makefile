composer-update:
	composer install
	composer update

cc:
	php bin/console cache:clear --env=prod
	php bin/console cache:clear --env=dev

entities:
	php bin/console doctrine:generate:entities BackendBundle -vvv

diff:
	php bin/console doctrine:migrations:diff

migrate:
	php bin/console doctrine:migrations:migrate

fixtures:
	php bin/console doctrine:fixtures:load

services_avail:
	php bin/console debug:container
