# Twizzle features for the speedsolving.com forum

Config URL: <https://www.speedsolving.com/admin.php?bb-code-media-sites/twizzle_link_encoded/edit>

Individual fields are separated in this document so they can be copied from e.g. GitHub easily.

## Media Site ID

```
twizzle
```

## Site title

```
Twizzle
```

## Site URL

```
https://twizzle.net/
```

## Match URLs

```
#https://(alpha|beta)\.twizzle\.net/(.*/)?\?(?P<id>.*)#i
```

(Check `Use 'Match URLs' as PCRE regular expressions` under "Advanced options")

## Embed Template

```html
<div class="bbMediaWrapper">
  <xf:js src="/misc/twizzle/twizzle-link.js" />
 Test 2
  <twizzle-forum-link><a href="{$link_url}">Twizzle link</a><pre style="margin: 0">{$html_alg}</pre></twizzle-forum-link>
</div>
```

## URL match callback

Class:

```
Twizzle\BbCode
```

Method:

```
matchCallback
```

## Embed HTML callback

Class:

```
Twizzle\BbCode
```

Method:

```
htmlCallback
```
