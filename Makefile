
.PHONY: build
build: clean-for-build
	bun run script/build.ts

.PHONY: dev
dev: setup
	bun run script/dev.ts

.PHONY: lint
lint: setup
	bun x @biomejs/biome check
	bun x tsc --noEmit --project .

.PHONY: format
format: setup
	bun x @biomejs/biome check --write

.PHONY: setup
setup:
	bun install --frozen-lockfile

.PHONY: clean
clean:
	rm -rf ./dist/

.PHONY: clean
clean-for-build:
	rm -rf ./dist/www.speedsolving.com/src/addons/Twizzle/* ./dist/www.speedsolving.com/misc/twizzle/*

.PHONY: reset
reset: clean
	rm -rf ./node_modules

.PHONY: serve
serve-dist:
	make build
	cd ./dist/www.speedsolving.com
	open http://localhost:3344/misc/twizzle/test.html
	caddy file-server --listen :3344 --browse

.PHONY: cache-purge
cache-purge:
	@echo "To purge the cache once, sudo auth now."
	@echo "Ctrl-C to cancel"
	@curl -X POST \
		"https://api.cloudflare.com/client/v4/zones/$(shell sudo cat ~/.ssh/secrets/CLOUDFLARE_SPEEDSOLVING_COM_ZONE.txt)/purge_cache" \
		-H "Authorization: Bearer $(shell sudo cat ~/.ssh/secrets/CLOUDFLARE_SPEEDSOLVING_COM_CACHE_TOKEN.txt)" \
		-H "Content-Type:application/json" \
		--data '{"purge_everything":true}' # purge cubing.net cache

.PHONY: deploy
deploy: deploy-dist cache-purge

.PHONY: deploy-dist
deploy-dist: setup clean build
	./script/deploy.ts
