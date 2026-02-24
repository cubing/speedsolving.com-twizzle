import assert from "node:assert";
import { credentials } from "./credentials";

const { cache_token, zone } = credentials.cloudflare;

const url = new URL(
  `https://api.cloudflare.com/client/v4/zones/${zone}/purge_cache`,
);

const response = await fetch(url, {
  method: "POST",
  headers: {
    Authorization: `Bearer ${cache_token}`,
    "Content-Type": "application/json",
  },
  body: JSON.stringify({ purge_everything: true }),
});

const responseJSON = await response.json();
console.log(responseJSON);
assert.ok(responseJSON.success);
