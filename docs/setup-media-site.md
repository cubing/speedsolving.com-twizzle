
Config URL: <https://www.speedsolving.com/admin.php?bb-code-media-sites/twizzle_link_encoded/edit>

Individual fields are separated in this document so they can be copied from e.g. GitHub easily.

## Media Site ID

```text
twizzle
```

## Site title

```text
Twizzle
```

## Site URL

```text
https://twizzle.net/
```

## Match URLs

```text
#https://(alpha|beta)\.twizzle\.net/(.*/)?\?(?P<id>.*)#i
```

(Check `Use 'Match URLs' as PCRE regular expressions` under "Advanced options")

## Embed Template

```html
<xf:js>
  script = document.createElement("script");
  script.src = "/misc/twizzle/js/twizzle-forum-link.js";
  script.type = "module";
  document.body.appendChild(script);
</xf:js>
<twizzle-forum-link><fieldset><legend><a href="{$link_url}">&nbsp;Twizzle&nbsp;link&nbsp;</a></legend><pre style="margin: 0; white-space: pre-wrap;">{$html_alg}</pre></fieldset></twizzle-forum-link>
```

## URL match callback

Class:

```text
Twizzle\BbCode
```

Method:

```text
matchCallback
```

## Embed HTML callback

Class:

```text
Twizzle\BbCode
```

Method:

```text
htmlCallback
```

## BB Code tag

- Replacement mode: PHP callback
- Supports option parameter: optional
- PHP Callback: `Twizzle\BbCode` :: `twizzleTagCallback`
- Within this BB code
  - Disable smilies
  - Disable line break conversion
  - Check: Disable auto-linking
  - Check: Stop parsing BB code
