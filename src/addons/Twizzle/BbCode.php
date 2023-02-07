<?php

namespace Twizzle;

class BbCode {
  static function editorURL($params) {
    return "https://alpha.twizzle.net/edit/?" . http_build_query($params);
  }

  public static function twizzleTagCallback($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
  {
      $rendered = $renderer->renderSubTreePlain($tagChildren, $options);
      if (preg_match("/(https:\/\/(alpha|beta)\.twizzle\.net\/.*)/i", $rendered, $match) === 1) {
        return self::renderURL($match[1]);
      } else {
          $puzzle = $tag["option"];
          $contents = $tag["children"][0];
          $params = array();
          if ($puzzle) {
              $params["puzzle"] = $puzzle;
          }
          if (is_string($contents) && $contents) {
            $params["alg"] = $contents;
          }
          return self::renderURL(self::editorURL($params));
      }
  }

  public static function algTagCallback($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
  {
    $stickering = $tag["option"];
    $contents = $tag["children"][0];
    $params = array(
      "setup-anchor" => "end"
    );
    if ($stickering) {
      $params["stickering"] = $stickering;
      $params["title"] = $stickering . " Alg";
    }
    if (is_string($contents) && $contents) {
      $params["alg"] = $contents;
    }
    return self::renderURL(self::editorURL($params));
  }

  // Sanitizes and renders the given URL as a `<twizzle-forum-link>` element.
  public static function renderURL($url) {
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

    return '<twizzle-forum-link><a href="' . $href_url . '">Twizzle link</a><pre style="margin: 0">' . htmlentities($html_alg) . '</pre></twizzle-forum-link>' . self::addBoilerplate();
  }

  static $haveAddedBoilerplate = false;
  static function addBoilerplate() {
    if (self::$haveAddedBoilerplate) {
      return '';
    }
    self::$haveAddedBoilerplate = true;
    return '<script src="/misc/twizzle/js/twizzle-forum-link.js" type="module"></script>
<style>
  @font-face { font-family: "Ubuntu"; src: url("/misc/twizzle/font/ubuntu/Ubuntu-Regular.ttf"); }
  twizzle-forum-link { font-family: Ubuntu, Verdana, sans-serif; }
</style>';
  }
}
