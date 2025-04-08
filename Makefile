src_files += $(wildcard src/* src/**/*)
build_targets = $(patsubst src/%,dist/www/%,${src_files})

.PHONY: all
all: build

.PHONY: build
build: download_deps ${build_targets} dist/php/vendor dist/php/auth-config.php

.PHONY: download_deps
download_deps: composer.lock

composer.lock:
	composer install

dist/www/%: src/%
	@mkdir -p $(dir $@)
	cp -r $^ $@

dist/php/%: %
	@mkdir -p $(dir $@)
	cp -r $^ $@

.PHONY: install
install: build
	@mkdir -p nginx
	cp -r dist/* nginx

.PHONY: demo
demo: build install
	docker compose up -d

.PHONY: dev
dev: demo

.PHONY: clean
clean:
	docker compose down
	${RM} -r nginx dist vendor composer.lock
