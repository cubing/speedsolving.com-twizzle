import { TwizzleLink } from "cubing/twisty";

const LIGHT = "light";
const DARK = "dark";
type Mode = typeof LIGHT | typeof DARK;

// TODO: do this purely in CSS once `<twisty-player>` handles it.
function darkMode(): Mode | null {
  const attribute = document.documentElement.getAttribute("data-color-scheme");
  if (attribute === LIGHT || attribute === DARK) {
    return attribute;
  }
  if (globalThis.window.matchMedia("(prefers-color-scheme: dark)").matches) {
    return "dark";
  }
  if (globalThis.window.matchMedia("(prefers-color-scheme: light)").matches) {
    return "light";
  }
  if (document.documentElement.classList.contains("style-dark")) {
    // TODO: does this case still exist?
    return "dark";
  }
  return null;
}

export class TwizzleForumLink extends TwizzleLink {
  constructor() {
    super({
      cdnForumTweaks: true,
      colorScheme: darkMode() ?? "light",
    });
  }
}

customElements.define("twizzle-forum-link", TwizzleForumLink);
declare global {
  interface HTMLElementTagNameMap {
    "twizzle-forum-link": TwizzleForumLink;
  }
}
