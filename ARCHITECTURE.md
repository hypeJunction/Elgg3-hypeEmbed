# hypeEmbed — Architecture

## What the plugin does

hypeEmbed adds media embedding to Elgg's longtext editors (CKEditor). It provides:

- A toolbar injected into `input/longtext` allowing users to embed files, external media, and custom players.
- An admin UI to embed arbitrary HTML buttons and code snippets via shortcodes.
- File upload and thumbnail management for embedded images.
- A lightbox layout/shell for embedded pages.
- Optional integration with `hypeShortcode` (shortcode rendering) and `hypeLists` (collection browsing tabs).

## Entity Types

| Type   | Subtype        | Class                           | Purpose                              |
|--------|----------------|---------------------------------|--------------------------------------|
| object | `embed_file`   | `hypeJunction\Embed\File`       | Uploaded file attached to an embed   |
| object | `ckeditor_file`| `hypeJunction\Embed\File`       | Legacy CKEditor-uploaded file        |
| object | `embed_code`   | `hypeJunction\Embed\EmbedCode`  | Admin-defined HTML code embed block  |

Both `File` and `EmbedCode` extend `\ElggFile`. The subtype is set in `initializeAttributes()`.

## Routes

| Route name                      | Path                           | Purpose                                    |
|---------------------------------|--------------------------------|--------------------------------------------|
| `collection:object:file:embed`  | `/file/embed/{guid}`           | File collection for a page owner           |
| `collection:object:all:embed`   | `/posts/embed/{guid}`          | All-objects collection for a page owner    |
| `embed:tab`                     | `/embed/{tab}`                 | Embed modal tab view                       |
| `ckeditor:image`                | `/ckeditor/image/{hash}`       | Redirect to CKEditor-uploaded image        |
| `ckeditor:asset`                | `/ckeditor/assets/{view}`      | Serve simplecache assets via CKEditor path |
| `embed:asset`                   | `/embed/asset/{view}`          | Serve simplecache assets via embed path    |

The `ckeditor:asset` and `embed:asset` routes both delegate to `embed/asset/view`, which redirects to a simplecache URL. `walled: false` allows access without login.

## Actions

| Action             | Access      | Purpose                              |
|--------------------|-------------|--------------------------------------|
| `embed/file/upload`| logged_in   | Upload a file for embedding          |
| `embed/player`     | logged_in   | Render an embed player               |
| `embed/buttons`    | admin       | Generate HTML button shortcode       |
| `embed/code`       | admin       | Generate HTML code shortcode         |

## Hook Handlers

| Hook                 | Type     | Handler                                  | Purpose                                     |
|----------------------|----------|------------------------------------------|---------------------------------------------|
| `register`           | `menu:embed`        | `EmbedMenu`               | Registers embed toolbar menu items          |
| `register`           | `menu:embed:entity` | `EntityEmbedMenu`         | Registers per-entity embed menu items       |
| `entity:icon:sizes`  | `object`            | `Uploads::setIconSizes`   | Custom icon sizes for embed_file objects    |
| `entity:icon:file`   | `object`            | `Uploads::setIconFile`    | Resolves thumbnail path for embed_file      |
| `layout`             | `page`              | `Views::filterLightboxLayout` | Switches layout to embed_lightbox when `?embed_lightbox=1` |
| `shell`              | `page`              | `Views::filterLightboxShell`  | Switches shell to embed_lightbox when `?embed_lightbox=1`  |

## Bootstrap

`hypeJunction\Embed\Bootstrap` extends `Elgg\DefaultPluginBootstrap`.

- **`boot()`** — Creates `{dataroot}/embed/` directory if it does not exist (required for asset serving).
- **`init()`** — Unregisters the legacy `embed_longtext_menu` hook handler, registers the Ajax view `embed/safe/entity`, and conditionally registers collections with `hypeLists` (guarded by `function_exists('elgg_register_collection')`). Conditionally registers shortcodes with `hypeShortcode` (guarded by `elgg()->has('shortcodes')`).

## Views

Key views:

- `embed/toolbar` — injected into `input/longtext` via `view_extensions`
- `embed/tab/{file,player,posts}` — tabs shown in the embed modal
- `embed/safe/{button,code,player,entity}` — safe-rendered embed outputs
- `embed/stylesheet.css` — extended into `elgg.css` and `admin.css`
- `page/embed_lightbox`, `page/layouts/embed_lightbox` — full lightbox page shell
- `shortcodes/{button,code,embed}` — shortcode renderers (used by hypeShortcode)

Static assets (JS/CSS) are served via simplecache through the `embed/` view namespace, backed by `{dataroot}/embed/`.

## Optional Dependencies

| Plugin         | Effect when absent                                         |
|----------------|------------------------------------------------------------|
| `hypeShortcode`| `embed/safe/button` and `embed/safe/code` views return empty |
| `hypeLists`    | Collection tabs in embed modal silently skip rendering     |
| `hypeScraper`  | URL preview scraping unavailable (suggested, not required) |

## Data Migration Notes

No schema changes required for 3.x → 4.x migration. No `serialize()`-stored data present — metadata is stored as plain strings/integers.
