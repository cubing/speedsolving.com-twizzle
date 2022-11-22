<?php

namespace Twizzle;

class BbCode
{
	public static function matchCallback($url, $matchedId, \XF\Entity\BbCodeMediaSite $site, $siteId)
	{
		return urlencode($url);
	}

	public static function htmlCallback($mediaKey, array $site, $siteId)
	{
		$url = urldecode($mediaKey);
		$html_alg = "";

		$url_components = parse_url($url);
		if (array_key_exists('query', $url_components)) {
			parse_str($url_components['query'], $url_params);
			if (array_key_exists('alg', $url_params)) {
				$html_alg = $url_params['alg'];
			}
		}

		return \XF::app()->templater()->renderTemplate('public:_media_site_embed_twizzle', [
			'id' => $url,
			'link_url' => $url,
			'html_alg' => $html_alg
		]);
	}
}

