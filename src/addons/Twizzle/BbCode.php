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
  public static function renderURL($url, $force_url = false) {
    $alg_only = true;

    $escaped_title_fieldset = '';
    $escaped_setup_alg_fieldset = '';
    $escaped_alg_pre_tag = "";

    $url_components = parse_url($url);
    $href_url = "https://" . $url_components["host"] . $url_components["path"];
    if (array_key_exists("query", $url_components)) {
      parse_str($url_components["query"], $url_params);
      if (array_key_exists("title", $url_params)) {
        $escaped_title_fieldset = self::fieldset("Title", nl2br(htmlentities($url_params["title"])), true);
        $alg_only = false;
      }
      if (array_key_exists("setup-alg", $url_params)) {
        $escaped_setup_alg_fieldset = self::fieldset("Setup", self::pre(htmlentities($url_params["setup-alg"])), true);
        $alg_only = false;
      }
      if (array_key_exists("alg", $url_params)) {
        $escaped_alg_pre_tag = self::pre(htmlentities($url_params["alg"]));
      }
      $href_url .= "?" . http_build_query($url_params);
    }

    $anchor_escaped_html = '<a href="' . $href_url . '">Twizzle&nbsp;link</a>';

    if ($alg_only) {
      return self::twizzle_forum_link(
          self::fieldset($anchor_escaped_html, $escaped_alg_pre_tag)
      ) . self::addBoilerplate($force_url);
    }

    return self::twizzle_forum_link(
      self::fieldset($anchor_escaped_html,
        $escaped_title_fieldset . $escaped_setup_alg_fieldset . self::fieldset("Moves", $escaped_alg_pre_tag)
      )
    ) . self::addBoilerplate($force_url);
  }

  private static function pre($escaped_inner_html) {
    return '<pre style="margin: 0; white-space: pre-wrap;">' . $escaped_inner_html . '</pre>';
  }

  private static function fieldset($escaped_legend_inner_html, $escaped_inner_html, $use_margin_bottom = false) {
    $style_attribute = '';
    if ($use_margin_bottom) {
      $style_attribute = ' style="margin-bottom: 0.5em;"';
    }
    return '<fieldset' . $style_attribute . '>' . '<legend>&nbsp;' . $escaped_legend_inner_html . '&nbsp;</legend>' . $escaped_inner_html . '</fieldset>';
  }

  private static function twizzle_forum_link($escaped_inner_html) {
    return '<twizzle-forum-link>' . $escaped_inner_html . '</twizzle-forum-link>';
  }

  static $haveAddedBoilerplate = false;
  static function addBoilerplate($force_url = false) {
    if (self::$haveAddedBoilerplate && !$force_url) {
      return '';
    }
    self::$haveAddedBoilerplate = true;
    return '<script src="/misc/twizzle/js/twizzle-forum-link.js" type="module"></script>
<style>
  @font-face { font-family: "Ubuntu"; src: url("/misc/twizzle/font/ubuntu/Ubuntu-Regular.ttf"); }
  twizzle-forum-link { font-family: Ubuntu, Verdana, sans-serif; }
</style>';
  }

	public static function mediaMatchCallback($url, $matchedId, \XF\Entity\BbCodeMediaSite $site, $siteId)
	{
		return urlencode($url);
	}

	public static function mediaHtmlCallback($mediaKey, array $site, $siteId)
	{
		$url = urldecode($mediaKey);
		return \XF::app()->templater()->renderTemplate('public:_media_site_embed_twizzle_link_encoded', [
			'id' => "UNUSED_ID",
		]) . self::renderURL($url, true);
	}
}
