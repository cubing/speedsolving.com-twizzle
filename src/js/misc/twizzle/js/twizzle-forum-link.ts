import { TwizzleLink } from "cubing/twisty";

export class TwizzleForumLink extends TwizzleLink {
  constructor() {
    super({
      cdnForumTweaks: true,
      colorScheme: document.documentElement.classList.contains("style-dark")
        ? "dark"
        : "light",
    });
  }
}

customElements.define("twizzle-forum-link", TwizzleForumLink);
declare global {
  interface HTMLElementTagNameMap {
    "twizzle-forum-link": TwizzleForumLink;
  }
}
