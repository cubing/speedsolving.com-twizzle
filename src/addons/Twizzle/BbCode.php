<?php

namespace Twizzle;

class BbCode
{
    public static function bbcodeCallback($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
    {
        $rendered = $renderer->renderSubTreePlain($tagChildren, $options);
        if (preg_match("/\[URL[^\]]*\](https:\/\/(alpha|beta)\.twizzle\.net\/.*)\[\/URL\]/i", $rendered, $match) === 1) {
          $url = $match[1];
        } else if (preg_match('/\[MEDIA=twizzle_link_encoded\](https%3A%2F%2F(alpha|beta)\.twizzle\.net%2F.*)\[\/MEDIA\]/i', $rendered, $match) === 1) {
          $url = urldecode($match[1]);
        } else {
            $puzzle = $tag["option"];
            $contents = $tag["children"][0];
            $url = "https://alpha.twizzle.net/edit/?";
            if ($puzzle) {
                $url .= "puzzle=" . urlencode($puzzle) . "&";
            }
            if (is_string($contents) && $contents) {
                $url .= "alg=" . urlencode($contents);
            }
        }

        $html_alg = "";
        $url_components = parse_url($url);
        $href_url = "https://" . $url_components["host"] . $url_components["path"];
        if (array_key_exists("query", $url_components)) {
          parse_str($url_components["query"], $url_params);
          if (array_key_exists("alg", $url_params)) {
            $html_alg = $url_params["alg"];
          }
          $href_url .= "?" . http_build_query($url_params);
        }

        return '<div class="bbMediaWrapper">
<twizzle-forum-link><a href="' . $href_url . '">Twizzle link</a><pre style="margin: 0">' . $html_alg . '</pre></twizzle-forum-link>
<script>
if (!globalThis.twizzleLinkScript) {
  var script = document.createElement("script");
  globalThis.twizzleLinkScript = script;
  script.src = "/misc/twizzle/js/index.js";
  script.type = "module";

  function append() {
    document.body.appendChild(script);
    const style = document.createElement("style");
    style.textContent = `
@font-face {
  font-family: "Ubuntu";
  src: url("/misc/twizzle/font/ubuntu/Ubuntu-Regular.ttf");
}
twizzle-forum-link {
  font-family: Ubuntu, -apple-system, Tahoma, sans-serif;
}
`;
    document.body.appendChild(style);
  }

  if (document.body) {
    append();
  } else {
    window.addEventListener("DOMContentLoaded", append);
  }
}
</script>
</div>';
    }
}
