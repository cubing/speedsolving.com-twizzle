import { barelyServe } from "barely-a-dev-server";

barelyServe({
  entryRoot: "./src/js/",
  port: 3344,
  devDomain: "speedsolving-twizzle.localhost",
  setHeaders: async (_, response) =>
    // TODO: why doesn't this pass type checking without hax?
    // biome-ignore lint/suspicious/noExplicitAny: TODO
    (response as unknown as any).setHeader("Access-Control-Allow-Origin", "*"),
});
