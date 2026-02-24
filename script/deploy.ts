#!/usr/bin/env -S bun run --

import { PrintableShellCommand } from "printable-shell-command";
import { credentials } from "./credentials";

const { host, username, password, certFingerprint } = credentials.deploy;

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
