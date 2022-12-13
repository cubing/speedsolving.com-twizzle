<?php

namespace Twizzle;

class PostSave
{
	public static function threadEntityPostSave(\XF\Mvc\Entity\Entity $entity)
	{
		self::postSave($entity);
	}

	public static function conversationEntityPostSave(\XF\Mvc\Entity\Entity $entity)
	{
		self::postSave($entity);
	}

	public static function postSave($entity)
	{
		if ($entity->isChanged('message')) {
			self::updateMessage($entity);
		}
	}

  // TODO: share implementation with `Media.php`
  static function sanitizeTwizzleURL($url) {
    $url_components = parse_url($url);
    $sanitized_url = "https://" . $url_components["host"] . $url_components["path"];
    if (array_key_exists("query", $url_components)) {
      parse_str($url_components["query"], $url_params);
      $sanitized_url .= "?" . http_build_query($url_params);
    }
    return $sanitized_url;
  }

	protected static function updateMessage($entity)
	{
    $message = $entity->message;
    $updated = false;
    while (preg_match("/(\[MEDIA=twizzle_link_encoded\]([^\[]*)\[\/MEDIA\])/i", $message, $match) === 1) {
      $url_encoded = self::sanitizeTwizzleURL(urldecode($match[2]));
      $message = str_replace($match[1], "[twizzle]" . $url_encoded . "[/twizzle]", $message);
      $updated = true;
    }
    if ($updated) {
      $entity->fastUpdate([
        "message" => $message
      ]);
    }
  }
}
