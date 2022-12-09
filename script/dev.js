import { build } from "esbuild";
import { createHash } from "node:crypto";
import { readdir, stat } from "node:fs/promises";
import { join } from "node:path";

import { cp, readFile, writeFile } from "node:fs/promises";
import { barelyServe } from "barely-a-dev-server";

// build({
//   format: "esm",
//   target: "es2020",
//   bundle: true,
//   splitting: true,
//   entryPoints: ["./src/js/index.ts"],
//   outdir: "./dist/www.speedsolving.com/misc/twizzle/js/",
//   minify: true,
//   sourcemap: true,
// });

barelyServe({
  entryRoot: "./src/js/",
  setHeaders: (_, response) =>
    response.setHeader("Access-Control-Allow-Origin", "*"),
});
