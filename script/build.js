import { barelyServe } from "barely-a-dev-server";
import { build } from "esbuild";

import { cp } from "node:fs/promises";

await cp("./src/static", "dist/", { recursive: true });

build({
  format: "esm",
  target: "es2020",
  bundle: true,
  splitting: true,
  entryPoints: ["./src/js/index.ts"],
  outdir: "./dist/www.speedsolving.com/misc/twizzle/js/",
  minify: true,
  sourcemap: true,
});
