BIN_ROOT=vendor/bin
CONTAINER=1
PREFIX=

ifeq ($(CONTAINER), 1)
PREFIX=docker-compose exec app
endif

cs:
	$(PREFIX) $(BIN_ROOT)/php-cs-fixer fix src/ --rules=@PSR2

test:
	$(PREFIX) $(BIN_ROOT)/phpunit tests/unit