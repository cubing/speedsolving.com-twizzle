# Twizzle features for the speedsolving.com forum

See the [announcement post](https://www.speedsolving.com/threads/embed-algs-and-solves-using-twizzle.88543/).

<img src="./docs/example.png" width="400" alt="Example embedded alg">

## Setup

- [Media site](./docs/setup-media-site.md)

## JS Dev

- 1. Block `https://www.speedsolving.com/misc/twizzle/js/twizzle-forum-link.js` in DevTools
- 2. `make dev`
- 3. Visit a speedsolving.com thread with an embedded player.
- 4. Paste this in the JS console after every page load you want to test:

```js
const script = document.createElement("script");
script.type = "module";
script.src = "http://speedsolving-twizzle.localhost:3344/misc/twizzle/js/twizzle-forum-link.js";
document.body.appendChild(script)
```

## Dependencies

- `lftp` (for deployment)
