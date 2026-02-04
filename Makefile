.PHONY: build
build: clean-for-build setup
	bun run -- script/build.ts

.PHONY: check
check: lint build

.PHONY: dev
dev: setup
	bun run -- script/dev.ts

.PHONY: lint
lint: lint-biome lint-tsc

.PHONY: lint-biome
lint-biome: setup
	bun x -- bun-dx --package @biomejs/biome biome -- check

.PHONY: lint-tsc
lint-tsc: setup
	bun x -- bun-dx --package typescript tsc -- --project .

.PHONY: format
format: setup
	bun x -- bun-dx --package @biomejs/biome biome -- check --write

.PHONY: setup
setup:
	bun install --frozen-lockfile

RM_RF = bun -e 'process.argv.slice(1).map(p => process.getBuiltinModule("node:fs").rmSync(p, {recursive: true, force: true, maxRetries: 5}))' --

.PHONY: clean
clean:
	${RM_RF} ./dist/

.PHONY: clean-for-build
clean-for-build:
	${RM_RF} ./dist/www.speedsolving.com/src/addons/Twizzle/* ./dist/www.speedsolving.com/misc/twizzle/*

.PHONY: reset
reset: clean
	${RM_RF} ./node_modules/

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
	bun run -- ./script/deploy.ts
