import { TwizzleLink } from "cubing/twisty";

export class TwizzleForumLink extends TwizzleLink {
  constructor() {
    super({
      cdnForumTweaks: true,
      darkMode: document.documentElement.classList.contains("style-dark"),
    });
  }
}

customElements.define("twizzle-forum-link", TwizzleForumLink);
declare global {
  interface HTMLElementTagNameMap {
    "twizzle-forum-link": TwizzleForumLink;
  }
}
