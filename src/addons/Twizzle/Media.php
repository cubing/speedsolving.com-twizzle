<?php

namespace Twizzle;

class Media
{
	// TODO: remove this file once `Twizzle\BbCode` → `mediaMatchCallback` has proven itself.
	public static function matchCallback($url, $matchedId, \XF\Entity\BbCodeMediaSite $site, $siteId)
	{
		return urlencode($url);
	}

	// TODO: remove this file once `Twizzle\BbCode` → `mediaHtmlCallback` has proven itself.
	public static function htmlCallback($mediaKey, array $site, $siteId)
	{
		$url = urldecode($mediaKey);
		$alg = "";

		$url_components = parse_url($url);
		if (array_key_exists('query', $url_components)) {
			parse_str($url_components['query'], $url_params);
			if (array_key_exists('alg', $url_params)) {
				$alg = $url_params['alg'];
			}
		}

		return \XF::app()->templater()->renderTemplate('public:_media_site_embed_twizzle_link_encoded', [
			'id' => $url,
			'link_url' => $url,
			'alg' => $alg
		]);
	}
}

