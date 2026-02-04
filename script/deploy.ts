#!/usr/bin/env -S bun run --

import { homedir } from "node:os";
import { join } from "node:path";
import { file } from "bun";
import { PrintableShellCommand } from "printable-shell-command";

// TODO: validation?
interface Credentials {
  host: string;
  username: string;
  password: string;
  certFingerprint: string;
}

const { host, username, password, certFingerprint } = (await file(
  join(
    homedir(),
    ".ssh/secrets/ftps/speedsolving.com/speedsolving-forum.credentials.json",
  ),
).json()) as Credentials;

const commands = `set ftp:ssl-force true
set ftp:ssl-protect-data true
set ssl:verify-certificate/${certFingerprint} no
mirror --verbose --reverse --ignore-time --exclude=.DS_Store ./dist/www.speedsolving.com/misc/twizzle /misc/twizzle
mirror --verbose --reverse --ignore-time --exclude=.DS_Store ./dist/www.speedsolving.com/src/addons/Twizzle /src/addons/Twizzle
bye
EOF`;

const command = new PrintableShellCommand("lftp", [
  ["--user", username],
  ["--password", password],
  host,
])
  .stdin({ text: commands })
  .spawnTransparently();

await command.success;
