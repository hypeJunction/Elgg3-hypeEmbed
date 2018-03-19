hypeEmbed
=========
![Elgg 3.0](https://img.shields.io/badge/Elgg-3.0.x-orange.svg?style=flat-square)

## Features

* Search, upload and embed files on the spot
* Search and embed all other registered object types on the spot
* Embed URL previews and rich-media players
* [admin] Embed buttons that match the site styles
* [admin] Embed "insecure" HTML embeds (forms, calendars etc)

![Embed Popup](https://raw.github.com/hypeJunction/hypeEmbed/master/screenshots/embed.png "Embed Popup")
![Editor](https://raw.github.com/hypeJunction/hypeEmbed/master/screenshots/editor.png "Editor")
![Player](https://raw.github.com/hypeJunction/hypeEmbed/master/screenshots/player.png "Player")

## Shortcodes

The plugin supports the following shortcodes:

`ebmed` shortcode:

 * `guid` - GUID of an entity to embed

`button` shortcode:

 * `text` - call to action
 * `type` - One of the following types `action`, `submit`, `delete`, `cancel` (these values only affect styling and do not carry any functional value)
 * `url` - URL to link to
 * `target` - Default `self`, `blank` or `lightbox` 

Examples:

```
[embed guid="555"]
[button type="action" text="Read Terms" url="/terms" target="lightbox"]
```

Unlisted shortcode attributes will be parsed and passed to the view after sanitization, so extending plugins can add additional options.

By default, only shortcodes passed to `output/longtext` view will be expanded automatically.

### Static assets

If you are using the same images across multiple posts, you may way to use static assets,
as they allow you to take advantage of simplecache, thus offering better performance than
file entities.

Create a folder in your dataroot `/embed/` and place your image files in there, flush the caches,
and you will see your images in the Assets tab of the embed lightbox window.

## Acknowledgements

* Upgrade for Elgg 2.3 has been sponsored by ApostleTree, LLC