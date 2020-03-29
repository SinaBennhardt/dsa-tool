help:                                                                           ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init:                                                                           ## initialize or update project
	composer install
	bin/console do:da:cr --if-not-exists
	bin/console do:mi:mi --no-interaction

logs:                                                                           ## display logs
	tail -f var/log/dev.logs

cache:                                                                          ## remove and warmup cache
	bin/console cache:clear && bin/console cache:warmup

php-cs-check:                                                                   ## run cs fixer (dry-run)
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes --diff --dry-run

php-cs-fix:                                                                     ## run cs fixer
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes

phpstan:                                                                        ## run phpstan static code analyser
	phpstan analyse -l max -c phpstan.neon src

psalm:                                                                          ## run psalm static code analyser
	psalm $(OPTIONS) --show-info=false

phpunit:                                                                        ## run phpunit tests
	php -dpcov.enabled=1 -dpcov.directory=. -dpcov.exclude="~vendor~" vendor/bin/phpunit --testdox --colors=always -v --coverage-html coverage/ $(OPTIONS)

ci-phpunit:                                                                     ## run phpunit tests with coverage for ci
	php -dpcov.enabled=1 -dpcov.directory=. -dpcov.exclude="~vendor~" vendor/bin/phpunit --testdox --colors=always -v --coverage-text $(OPTIONS)

static: php-cs-fix phpstan psalm                                                ## run static analyser
test: phpunit                                                                   ## run tests

dev: static test                                                                ## run dev tools

mysql:                                                                          ## go in mysql
	sudo docker exec -it mysql /usr/bin/mysql website
