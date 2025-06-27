import { type BinaryLike, createHash } from "node:crypto";
import { cp, readdir, readFile, stat, writeFile } from "node:fs/promises";
import { join } from "node:path";
import { build } from "esbuild";

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

export async function listFiles(
  folderPath: string,
  filter: (path: string) => boolean,
  relativePath?: string,
) {
  const childNames = await readdir(
    relativePath ? join(folderPath, relativePath) : folderPath,
  );

  const ownMatches = [];
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

  const contents = await readFile(join(ADDON_DIST_DIR, path));
  // TODO: why don't `bun`'s types work out of the box?
  sha256HashInstance.update(contents as unknown as BinaryLike);
  hashes[`${ADDON_SERVER_PATH}/${path}`] = sha256HashInstance.digest("hex");
}
await writeFile(
  join(ADDON_DIST_DIR, "hashes.json"),
  JSON.stringify(hashes, null, "  "),
);
