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
    $unescaped_setup_alg = NULL;
    $unescaped_alg = "";
    $url_components = parse_url($url);
    $href_url = "https://" . $url_components["host"] . $url_components["path"];
    if (array_key_exists("query", $url_components)) {
      parse_str($url_components["query"], $url_params);
      if (array_key_exists("setup-alg", $url_params)) {
        $unescaped_setup_alg = $url_params["setup-alg"];
      }
      if (array_key_exists("alg", $url_params)) {
        $unescaped_alg = $url_params["alg"];
      }
      $href_url .= "?" . http_build_query($url_params);
    }
    $anchor_escaped_html = '<a href="' . $href_url . '">Twizzle&nbsp;link</a>';
    if (is_null($unescaped_setup_alg)) {
      return '<twizzle-forum-link>' . self::fieldset($anchor_escaped_html, self::pre(htmlentities($unescaped_alg))) . '</twizzle-forum-link>' . self::addBoilerplate();
    }
    return '<twizzle-forum-link>' . self::fieldset($anchor_escaped_html,
               self::fieldset("Setup", self::pre(htmlentities($unescaped_setup_alg))) . self::fieldset("Moves", self::pre(htmlentities($unescaped_alg)), true)
           ) . '</twizzle-forum-link>' . self::addBoilerplate();
  }

  private static function pre($escaped_inner_html) {
    return '<pre style="margin: 0; white-space: pre-wrap;">' . $escaped_inner_html . '</pre>';
  }

  private static function fieldset($escaped_legend_inner_html, $escaped_inner_html, $use_margin_top = false) {
    $style_attribute = '';
    if ($use_margin_top) {
      $style_attribute = ' style="margin-top: 0.5em;"';
    }
    return '<fieldset' . $style_attribute . '>' . '<legend>&nbsp;' . $escaped_legend_inner_html . '&nbsp;</legend>' . $escaped_inner_html . '</fieldset>';
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
