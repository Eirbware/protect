src_files += $(shell find src/protect src/lib -type f) src/index.html
build_targets = $(patsubst src/%,dist/www/%,${src_files})

prod_src_files += $(shell find src/prod -type f)
prod_targets = $(patsubst src/prod/%,demo/%,${prod_src_files}) $(patsubst src/%,demo/nginx/www/%,${src_files})

PHONY += all
all: build

PHONY += build
build: download_deps ${build_targets} dist/php/vendor $(addprefix dist/php/,$(wildcard auth-config.php))

PHONY += prod
prod: build demo.tar.gz

demo.tar.gz: demo
	mv $@ $@.bak || true
	tar -cvz -f $@ $^
	${RM} $@.bak

demo: download_deps ${prod_targets} demo/nginx/php/vendor demo/nginx/php/auth-config.php
	@touch $@

demo/nginx/%: dist/%
	@mkdir -p $(dir $@)
	cp -r $^ $@

# dummy rule to prevent some make version to fail because vendor directory
# doesn't exist
vendor:
	mkdir -p $@

demo/nginx/php/auth-config.php: src/prod/nginx/php/auth-config.php
	@mkdir -p $(dir $@)
	cp -r $^ $@

demo/%: src/prod/%
	@mkdir -p $(dir $@)
	cp -r $^ $@

PHONY += download_deps
download_deps: composer.lock

composer.lock:
	composer install

dist/www/%: src/%
	@mkdir -p $(dir $@)
	cp -rf $^ $@

dist/php/%: %
	@mkdir -p $(dir $@)
	cp -r $^ $@

PHONY += install
install: build
	@mkdir -p nginx
	cp -r dist/* nginx

PHONY += dev
dev: install
	docker compose up -d

PHONY += clean
clean:
	docker compose down
	${RM} -r nginx dist composer.lock demo demo.tar.gz

PHONY += mrproper
mrproper: clean
	${RM} -r vendor

.phony: $(PHONY)
