hello:
	@echo "Hello! use other targets!"

init:
	php app/console doctrine:phpcr:repository:init
	make fixtures

dump_phpcr:
	php app/console doctrine:phpcr:node:dump

dump_phpcr_props:
	php app/console doctrine:phpcr:node:dump --props

create:
	php app/console doctrine:database:create
	php app/console doctrine:phpcr:init:dbal --force
	make init
	make cache

drop_and_create:
	php app/console doctrine:database:drop --force
	make create

fixtures:
	php app/console doctrine:phpcr:fixtures:load --no-interaction

cache:
	php app/console cache:clear