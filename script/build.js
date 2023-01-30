import { build } from "esbuild";
import { createHash } from "node:crypto";
import { readdir, stat } from "node:fs/promises";
import { join } from "node:path";

import { cp, readFile, writeFile } from "node:fs/promises";

const ADDON_SERVER_PATH = "src/addons/Twizzle";
const ADDON_DIST_DIR = `./dist/www.speedsolving.com/${ADDON_SERVER_PATH}/`;

await cp("./src/static", "./dist/", { recursive: true });

build({
  format: "esm",
  target: "es2020",
  bundle: true,
  splitting: true,
  entryPoints: ["./src/js/misc/twizzle/js/twizzle-forum-link.ts"],
  outdir: "./dist/www.speedsolving.com/misc/twizzle/js/",
  minify: true,
  sourcemap: true,
});

await cp("./src/addons/Twizzle", ADDON_DIST_DIR, { recursive: true });

export async function listFiles(folderPath, filter, relativePath) {
  let childNames = await readdir(
    relativePath ? join(folderPath, relativePath) : folderPath,
  );

  let ownMatches = [];
  let recursiveMatches = [];
  for (const childName of childNames) {
    const newRelativePath = relativePath
      ? join(relativePath, childName)
      : childName;
    if ((await stat(join(folderPath, newRelativePath))).isDirectory()) {
      recursiveMatches = recursiveMatches.concat(
        await listFiles(folderPath, filter, newRelativePath),
      );
    } else if (filter(newRelativePath)) {
      ownMatches.push(newRelativePath);
    }
  }
  return ownMatches.concat(recursiveMatches);
}

const hashes = {};
for (const path of await listFiles(
  ADDON_DIST_DIR,
  (name) => name !== ".DS_Store" && !name.endsWith("/.DS_Store"),
)) {
  const sha256HashInstance = createHash("sha256");

  // // wat
  // let contents = await readFile(join(ADDON_DIST_DIR, fileName), "ascii");
  // contents = contents.replaceAll("\r", "");
  // sha256HashInstance.update(contents, "ascii");

  let contents = await readFile(join(ADDON_DIST_DIR, path));
  sha256HashInstance.update(contents);
  hashes[`${ADDON_SERVER_PATH}/${path}`] = sha256HashInstance.digest("hex");
}
await writeFile(
  join(ADDON_DIST_DIR, "hashes.json"),
  JSON.stringify(hashes, null, "  "),
);
