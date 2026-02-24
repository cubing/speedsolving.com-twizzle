import { Path } from "path-class";
import { object, string } from "zod";

const CREDENTIALS_PATH = Path.homedir.join(
  "./.local/secrets/speedsolving.com-twizzle/credentials.json",
);

const Credentials = object({
  cloudflare: object({
    cache_token: string(),
    zone: string(),
  }),
  deploy: object({
    host: string(),
    username: string(),
    password: string(),
    certFingerprint: string(),
  }),
});

export const credentials = Credentials.parse(await CREDENTIALS_PATH.readJSON());
