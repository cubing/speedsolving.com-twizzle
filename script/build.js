import { barelyServe } from "barely-a-dev-server";
import { build } from "esbuild";
import { createHash } from "node:crypto";

import { cp, readdir, readFile, writeFile } from "node:fs/promises";
import { basename, join } from "node:path";

const ADDON_SERVER_PATH = "src/addons/Twizzle";
const ADDON_DIST_DIR = `./dist/www.speedsolving.com/${ADDON_SERVER_PATH}`;

await cp("./src/static", "./dist/", { recursive: true });

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

await cp("./src/addons/Twizzle", ADDON_DIST_DIR, { recursive: true });

const dir = await readdir(ADDON_DIST_DIR);
const hashes = {};
for (const fileName of dir) {
  if ([".DS_Store"].includes(fileName)) {
    continue;
  }
  const sha256HashInstance = createHash("sha256");

  // // wat
  // let contents = await readFile(join(ADDON_DIST_DIR, fileName), "ascii");
  // contents = contents.replaceAll("\r", "");
  // sha256HashInstance.update(contents, "ascii");

  let contents = await readFile(join(ADDON_DIST_DIR, fileName));
  sha256HashInstance.update(contents);
  hashes[`${ADDON_SERVER_PATH}/${fileName}`] = sha256HashInstance.digest("hex");
}
await writeFile(
  join(ADDON_DIST_DIR, "hashes.json"),
  JSON.stringify(hashes, null, "  "),
);
