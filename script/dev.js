import { build } from "esbuild";
import { createHash } from "node:crypto";
import { readdir, stat } from "node:fs/promises";
import { join } from "node:path";

import { cp, readFile, writeFile } from "node:fs/promises";
import { barelyServe } from "barely-a-dev-server";

barelyServe({
  entryRoot: "./src/js/",
  port: 3344,
  devDomain: "speedsolving-twizzle.localhost",
  setHeaders: (_, response) =>
    response.setHeader("Access-Control-Allow-Origin", "*"),
});
