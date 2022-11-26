import { barelyServe } from "barely-a-dev-server";
import { build } from "esbuild";

build({
  format: "esm",
  target: "es2020",
  bundle: true,
  splitting: true,
  entryPoints: ["./src/js/index.ts"],
  outdir: "./dist/speedsolving.com/misc/twizzle/js",
});
