BIN_ROOT=vendor/bin
CONTAINER=1
PREFIX=

ifeq ($(CONTAINER), 1)
PREFIX=docker-compose exec app
endif


cs:
	$(PREFIX) $(BIN_ROOT)/php-cs-fixer fix --verbose --config=.php_cs --allow-risky yes src/ tests/

cs-check:
	$(PREFIX) $(BIN_ROOT)/php-cs-fixer fix --verbose --config=.php_cs --dry-run --allow-risky yes src/ tests/

test:
	$(PREFIX) $(BIN_ROOT)/phpunit tests/unit