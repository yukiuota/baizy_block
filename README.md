# baizy_block — ブロック中心のハイブリッドテーマ

クラシックテーマ [baizy](../baizy/) をベースに、**「見た目を作る部分」をブロックへ寄せた**オリジナルテーマ開発用テンプレートです。WordPress の用語でいう**ハイブリッドテーマ**（クラシックテーマ + ブロック機能）にあたります。

| | 担当 |
|---|---|
| ルーティング / データ取得 | PHP（`resources/layouts/index.php` のルーター + `app/` の MVC） |
| ヘッダー / フッター | **ブロックテンプレートパーツ**（`parts/*.html`、サイトエディターで編集可能） |
| ページ本文 | **ブロックパターン**（`patterns/*.php`、クライアントがブロックで組み立て） |
| デザイントークン | **`theme.json`**（色・フォント・余白・レイアウト幅） |
| カスタムブロック | **baizy-custom-blocks プラグイン**（テーマには含まれません） |

その他の前提：

- 共通スタイルは SCSS で管理し、Sass CLI でコンパイルします（`resources/common/scss/` → `resources/common/css/`）
- PHP は名前空間付きクラスで構成し、Composer オートロードで管理します（`BaizyBlock\` 名前空間）

### baizy との違い

| | baizy | baizy_block |
|---|---|---|
| ヘッダー / フッター | `resources/include/` の PHP | `parts/header.html` / `parts/footer.html` |
| ページのレイアウト | `resources/pages/{slug}.php` に直書き | パターンを起点にブロックで組む |
| `theme.json` | ほぼ空（プリセット未定義） | 色・フォント・余白・レイアウトを定義 |
| `alignwide` / `alignfull` | 効かない | 効く（`.wp-site-blocks` ラッパーを出力） |
| ウィジェットエリア / クラシックメニュー | あり | なし（ブロックで代替） |

## 必要環境

- WordPress 6.6 以上（`register_block_style()` の `style_data` を使用）
- PHP 8.0 以上（`composer.json` に準拠）
- Node.js 18 以上（`package.json` の `engines` に準拠）
- pnpm 10.0 以上（パッケージマネージャー）

## セットアップ

### PHP（オートロード）

Composer のオートロードを利用しています。

```bash
composer install
composer dump-autoload
```

### コード品質チェック（phpcs）

WordPress コーディング規約に沿ったスタティック解析を実行できます。

```bash
# チェック実行
composer phpcs

# 自動修正
composer phpcbf
```

ルールセットは [`phpcs.xml`](phpcs.xml) で管理しています。

### カスタムブロック（Gutenberg）

カスタムブロックは **baizy-custom-blocks プラグイン**として分離しました。
`wp-content/plugins/baizy-custom-blocks/` に配置して有効化してください。ブロックの追加・ビルド方法はプラグイン側の README を参照してください。

### 共通 SCSS

`resources/common/scss/` 以下の SCSS を `resources/common/css/` へコンパイルします。

1) 一回だけコンパイル

```bash
pnpm sass:build
```

2) 監視（変更を検知して自動コンパイル）

```bash
pnpm sass:watch
```

#### コンパイル対象

| 入力 | 出力 | 適用先 |
|------|------|------|
| `resources/common/scss/common.scss` | `resources/common/css/common.css` | フロントのみ |
| `resources/common/scss/blocks.scss` | `resources/common/css/blocks.css` | **フロント + エディター** |
| `resources/common/scss/editor-style.scss` | `resources/common/css/editor-style.css` | エディターのみ |
| `resources/common/scss/pages/home.scss` | `resources/common/css/home.css` | body class `home` のページ |

> `blocks.scss` とディレクトリ `blocks/` は名前が同じため、`blocks.scss` 内で `@use "blocks"` と書くと Sass が自分自身を読もうとして Module loop になります。パーシャルは `@use "blocks/core"` のように直接指定してください。

## 主な pnpm スクリプト

```bash
# SCSS の一回コンパイル
pnpm sass:build

# SCSS の監視（変更を検知して自動コンパイル）
pnpm sass:watch

# 本番アップ用の dist/baizy_block/ を生成（SCSS → dist 作成を一括実行）
pnpm dist

# Puppeteer によるパフォーマンス計測（URL省略時は localhost を計測）
pnpm perf:check
pnpm perf:localhost
```

## ディレクトリ構成（抜粋）

```
baizy_block/
├── app/
│   ├── functions/      フック登録・グローバル関数（Composer の files で読み込み）
│   ├── helpers/        ユーティリティクラス（ImageHelper, TemplateHelper）
│   ├── models/         データ取得クラス（PostModel, TaxonomyModel）
│   ├── plugins/        プラグイン連携用 CSS / JS
│   ├── services/       サービスクラス（ExternalLinksManager）
│   └── setup/          テーマ初期化クラス（ThemeSetup, Scripts, Customizer）
├── data/
│   └── field-groups/   ACF / SCF フィールドグループの JSON 同期ファイル
├── parts/              ★ ブロックテンプレートパーツ（header.html / footer.html）
├── patterns/           ★ ブロックパターン（ページ雛形 + セクション）
├── resources/
│   ├── archives/       アーカイブテンプレート
│   ├── common/
│   │   ├── scss/       共通スタイルの SCSS ソース
│   │   ├── css/        コンパイル後の CSS（Sass CLI 出力）
│   │   └── js/         script.js（フロント）/ editor.js（エディター）
│   ├── include/        コンポーネント・タグ・検索フォームなどの分割テンプレート
│   ├── layouts/        ルーター（index.php）
│   ├── pages/          固定ページテンプレート
│   ├── settings/       links.json（外部リンク管理）
│   └── single/         投稿詳細テンプレート
├── sample/             参照用サンプル（dist には含まれない）
│   ├── include/        PHP 版ヘッダー / フッター（ブロック版に戻したくない場合の参照）
│   └── patterns/       旧サンプルパターン
├── vendor/             Composer オートロード
├── phpcs.xml           PHP CodeSniffer 設定（WordPress 規約）
├── functions.php       エントリポイント（require のみ）
├── style.css           テーマ情報（ヘッダー必須）
└── theme.json          ★ デザイントークン + templateParts 宣言
```

## テーマ仕様

### theme.json（デザイントークン）

色・フォント・余白・レイアウト幅は [theme.json](theme.json) に集約しています。案件ごとに**まずここを差し替える**のが基本の流れです。

| 設定 | 内容 |
|---|---|
| `settings.color.palette` | `base` / `base-2` / `contrast` / `contrast-2` / `accent` |
| `settings.typography.fontFamilies` | `system-font`（和文ゴシック）/ `serif-font` / `monospace-font` |
| `settings.typography.fontSizes` | `small` 〜 `xx-large`（`large` 以上は fluid） |
| `settings.spacing.spacingSizes` | `20`〜`80`（`50` 以上は `clamp()`） |
| `settings.layout` | `contentSize: 1100px` / `wideSize: 1140px` |
| `templateParts` | `header` / `footer` の表示名とエリア |

コアのデフォルトパレット・グラデーション・デュオトーン・フォントサイズは無効化して、インスペクターの選択肢をテーマ定義だけに絞っています。

> **注意**: `customTemplates` は削除しました。クラシックテーマでは `templates/*.html` を持たないため機能せず、ページテンプレートは PHP の `Template Name:` ヘッダーで定義します。

> **注意**: `add_theme_support( 'align-wide' )` は**追加していません**。`theme.json` の `settings.layout` が上位互換で、両方を宣言すると幅の解決が競合します。

`baizy-color-palette` プラグインは `wp_theme_json_data_theme` フィルターでパレットを**マージ**する実装なので、theme.json 側のパレットと共存します。

### PHP 名前空間構成

`BaizyBlock\` 名前空間配下のクラスを Composer の classmap オートロードで管理します（ファイル名は WordPress 規約の snake_case のため PSR-4 は使用しません）。クラスを追加したら `composer dump-autoload` を実行してください。

| 名前空間 | 役割 |
|---------|------|
| `BaizyBlock\Setup\ThemeSetup` | テーマサポート追加・wp_head クリーンアップ・著者アーカイブ無効化 |
| `BaizyBlock\Setup\Scripts` | CSS / JS のエンキュー管理 |
| `BaizyBlock\Setup\Customizer` | カスタマイザーセクション（head / body タグ追加）登録 |
| `BaizyBlock\Helpers\ImageHelper` | 画像 URL 生成・width/height 属性出力・SVG サイズ取得 |
| `BaizyBlock\Helpers\TemplateHelper` | テンプレートパーツ読み込み |
| `BaizyBlock\Models\PostModel` | 投稿データ取得 |
| `BaizyBlock\Models\TaxonomyModel` | タクソノミー・ターム取得（背景色メタ含む） |
| `BaizyBlock\Services\ExternalLinksManager` | JSON ファイルベースの外部リンク管理 |

### グローバル関数ラッパー

`app/functions/my_functions.php` にヘルパークラスへの薄いラッパーを定義しています。

```php
// 画像
baizy_block_img( 'path/to/image.png' )        // URL を返す
baizy_block_img_wh( 'path/to/image.png' )     // width / height 属性を出力
baizy_block_get_svg_dimensions( $svg_path )   // SVG の幅・高さを配列で返す

// テンプレートパーツ
baizy_block_template_part( 'slug' )
```

エスケープ出力は WordPress 標準の `esc_html()` / `esc_attr()` / `esc_url()` / `esc_js()` をそのまま使用します。

### 画像表示ヘルパー

```html
<picture>
  <source srcset="<?php echo baizy_block_img('xx/xx.png'); ?> 1x, <?php echo baizy_block_img('xx/xx@2x.png'); ?> 2x" media="(max-width: 750px)">
  <img src="<?php echo baizy_block_img('xx/xx.png'); ?>" srcset="<?php echo baizy_block_img('xx/xx.png'); ?> 1x, <?php echo baizy_block_img('xx/xx@2x.png'); ?> 2x" <?php baizy_block_img_wh('xx/xx.png'); ?> alt="">
</picture>

<!-- loading="lazy" あり（デフォルト） -->
<img src="<?php echo baizy_block_img('sample.jpg'); ?>" <?php baizy_block_img_wh('sample.jpg'); ?> alt="">

<!-- loading="lazy" なし -->
<img src="<?php echo baizy_block_img('hero.jpg'); ?>" <?php baizy_block_img_wh('hero.jpg', false); ?> alt="">
```

### カスタマイザー（head / body タグ追加）

外観 > カスタマイズ > タグ追加 にて、`<head>` 直後・`<body>` 直後に任意のコードを追加できます。

- Google Analytics / Google Tag Manager などのトラッキングコードの挿入を想定
- 管理者専用。入力値は `wp_kses` で `<script>` / `<meta>` / `<link>` などの許可タグのみ保持

実装: [app/setup/customizer.php](app/setup/customizer.php)

### 管理画面カスタマイズ

#### カラーパレット管理

**baizy-color-palette プラグイン**として分離しました。
`wp-content/plugins/baizy-color-palette/` に配置して有効化すると、外観 > カラーパレット からブロックエディタで使用するカラーパレットを追加・編集できます。詳細はプラグイン側の README を参照してください。

#### タームの背景色設定

**baizy-term-color プラグイン**として分離しました。
`wp-content/plugins/baizy-term-color/` に配置して有効化してください。対象タクソノミーの設定・テンプレートでの利用方法はプラグイン側の README を参照してください。

```php
// テンプレート内での利用（プラグイン未有効時も動くよう function_exists でガード）
$color = function_exists( 'get_term_background_color' ) ? get_term_background_color( $term_id ) : '';
```

#### 管理画面タクソノミーフィルター

カスタム投稿タイプの一覧画面にタクソノミー絞り込みセレクトボックスを追加します。`register_taxonomy` 済みの情報から自動で導出されるため、投稿タイプを追加しても設定は不要です（標準の category / post_tag は WP 標準 UI があるため除外）。

実装: [app/functions/admin.php](app/functions/admin.php)

### 外部リンク管理

`resources/settings/links.json` に URL を一元管理し、PHP またはショートコードから呼び出します。

```json
{
  "instagram": { "url": "https://www.instagram.com/example/" },
  "twitter":   { "url": "https://twitter.com/example" }
}
```

```php
// PHP から取得
$url = \BaizyBlock\Services\ExternalLinksManager::get_url( 'instagram' );
```

```
<!-- ショートコードで取得 -->
[external_url key="instagram"]
```

実装: [app/services/external_links_manager.php](app/services/external_links_manager.php), [app/functions/global_links.php](app/functions/global_links.php)

### SEO 機能

実装: [app/functions/seo.php](app/functions/seo.php)

- **パンくずリスト**: `create_breadcrumb()` を呼び出すと Schema.org 対応のパンくずを出力します
- **noindex**: 404 / 指定カスタム投稿 / カテゴリー / タグページに `noindex, nofollow` を自動付与します
- **カスタム投稿メタディスクリプション**: アーカイブページにメタディスクリプションを出力します
- **HTML ミニファイ**: `start_html_minify()` を `get_header` アクションに追加することで有効化できます（デフォルト無効）

### ブロックテンプレートパーツ（ヘッダー / フッター）

`add_theme_support( 'block-template-parts' )` により、`parts/*.html` が**サイトエディターで編集可能**になります。

- 管理画面: **外観 → エディター → テンプレートパーツ**（WP バージョンによっては 外観 → テンプレートパーツ）
- PHP からの呼び出し: `block_template_part( 'header' )`（[resources/layouts/index.php](resources/layouts/index.php)）
- パーツ名・表示名・エリアは [theme.json](theme.json) の `templateParts` で宣言

パーツを追加する場合は `parts/{name}.html` を作り、`theme.json` の `templateParts` に追記して、テンプレートから `block_template_part( '{name}' )` で呼び出します。

> **既知の制約**: クラシックテーマのブロックテンプレートパーツ内では **`core/shortcode` ブロックが処理されません**。ヘッダー / フッターでショートコードが必要な場合は、パーツではなく PHP 側で出力してください。

PHP 版のヘッダー / フッターに戻したい案件では [sample/include/](sample/include/) を `resources/include/` へ戻し、ルーターの `block_template_part()` を `baizy_block_template_part()` に差し替えます。

### レイアウト幅と alignwide / alignfull

ブロックテーマではコアが自動生成するレイアウト用ラッパーを、クラシックテーマでは**手動で出力する必要があります**。これが無いと `theme.json` の `settings.layout` と `useRootPaddingAwareAlignments` がフロントで一切効きません。

| クラス | 出力箇所 | 役割 |
|---|---|---|
| `.wp-site-blocks` | [resources/layouts/index.php](resources/layouts/index.php) の `#container` | `alignfull` の基準 |
| `.is-layout-constrained` | `baizy_block_content_class()` | `contentSize` / `wideSize` の max-width |
| `.has-global-padding` | `baizy_block_content_class()` | ルートパディングと `alignfull` のネガティブマージン |

本文ラッパーを書くときは必ずこのヘルパーを使ってください。

```php
<main class="site-main">
	<div class="<?php baizy_block_content_class(); ?>">
		<?php the_content(); ?>
	</div>
</main>
```

実装: [app/helpers/template_helper.php](app/helpers/template_helper.php) の `content_wrapper_class()`

### ブロックパターン

`patterns/*.php` は WordPress 6.0+ の組み込み機能で自動登録されます（手動登録は不要）。

| パターン | 用途 |
|---|---|
| `page-lp.php` | LP 型ページの雛形。**新規固定ページ作成時のスターターパターン**に出る |
| `page-standard.php` | 下層ページの雛形。同上 |
| `section-hero.php` | ヒーロー |
| `section-cards.php` | 3 カラムカード |
| `section-cta.php` | CTA |
| `section-faq.php` | `core/details` を使った FAQ（JS 不要） |

**新規ページ作成時の選択肢に出す**には、ファイルヘッダーに次の 2 行を入れます。

```php
 * Block Types: core/post-content
 * Post Types: page
```

パターンカテゴリーのラベル登録は [app/functions/block_editor.php](app/functions/block_editor.php) で行います。

> WordPress デフォルトのパターンは `remove_theme_support( 'core-block-patterns' )` で無効化しています。インサーターをテーマ提供パターンだけに絞る方針のためです。コアパターンも使いたい場合はこの 1 行を削除してください。

### ブロックエディター関連（テーマ側）

[app/functions/block_editor.php](app/functions/block_editor.php) がテーマ側のブロックエディター調整をまとめています。

- パターンカテゴリーの登録
- **ブロックスタイルの登録**（`register_block_style()` の `style_data` を使うので CSS ファイル不要）
  - ボタン「アウトライン（アクセント）」/ 見出し「下線付き」/ グループ「カード」
- 投稿タイプごとの使用可能ブロック制限（`allowed_block_types_all`）
- エディター専用 JS（[resources/common/js/editor.js](resources/common/js/editor.js)）の読み込み
  - ビルド工程を持たないため素の JavaScript で書きます
  - ブロックバリエーション「セクション（全幅）」「リード文」を登録

TypeScript 製カスタムブロック本体は baizy-custom-blocks プラグインへ移行済みです。

### エディターとフロントのスタイル整合

| ファイル | 適用先 | 読み込み |
|---|---|---|
| `resources/common/css/blocks.css` | **フロント + エディター** | `enqueue_block_assets` |
| `resources/common/css/common.css` | フロントのみ | `wp_enqueue_scripts` |
| `resources/common/css/editor-style.css` | エディターのみ | `add_editor_style`（`after_setup_theme`） |

ブロックの見た目を調整するときは `resources/common/scss/blocks/` に書けば、フロントとエディターの両方に同じ CSS が当たります。

> 色・フォントサイズ・余白は `theme.json` 側で持ってください。`blocks/` に書くとエディターの UI から設定を変えても CSS が勝ってしまい、「変えたのに反映されない」の原因になります。

実装: [app/setup/scripts.php](app/setup/scripts.php)

### カスタムフィールドの JSON 同期

ACF（Advanced Custom Fields）と SCF（Smart Custom Fields）のフィールドグループ設定を `/data/field-groups/` ディレクトリで JSON 形式で管理します。

#### ACF JSON 同期

- ACF の標準 JSON 同期機能を使用
- フィールドグループの保存・読み込み先を `/data/field-groups/` に設定
- 管理画面での設定変更が自動的に JSON ファイルとして保存されます

#### SCF JSON エクスポート

- SCF のカスタムフィールド設定を `scf-{設定ID}.json` 形式でエクスポート
- `smart-cf` 投稿タイプの保存時に自動的にエクスポートされます

実装: [app/functions/acf_json_export.php](app/functions/acf_json_export.php)

## Chrome DevTools MCP

このリポジトリには、Chrome DevTools MCP（Model Context Protocol）を使うための設定ファイルが含まれています。

- パッケージ: `chrome-devtools-mcp`（バージョンは `package.json` を参照）
- 設定ファイル:
  - `mcp/servers.json`
  - `mcp/config.json`

### 利用可能なスクリプト

```bash
pnpm mcp:start
pnpm mcp:headless
pnpm mcp:dev
```

### 注意事項

- ブラウザ内容が MCP クライアントに公開されるため、機密情報や個人情報を含むページでは使用しないでください
- サンドボックス環境等で制限が出る場合は `--isolated=true` などのオプションを使用してください

## 本番デプロイ（FTP アップ用の dist 生成）

アップするファイルを目視で選別する必要はありません。以下のコマンドで、本番に必要なファイルだけを含む `dist/baizy_block/` が生成されます。

```bash
pnpm dist
```

FTP では **`dist/baizy_block/` の中身をそのまま本番の `wp-content/themes/baizy_block/` にアップ**してください。

### `pnpm dist` がやること

1. `pnpm sass:build` — SCSS のコンパイル（`resources/common/css/`）
2. [`scripts/create_dist.sh`](scripts/create_dist.sh) — 開発用ファイルを除外して `dist/baizy_block/` へコピーし、`composer install --no-dev --optimize-autoloader` で本番用の最小 `vendor/`（オートローダーのみ）を生成

### 除外されるもの（抜粋）

`node_modules/`・`.git/`・`.claude/`・`mcp/`・`sample/`・`scripts/`・`baizy-custom-blocks/`（プラグインは別途デプロイ）・各種設定ファイル（`phpcs.xml`, `package.json`, `pnpm-lock.yaml` など）・ドキュメント / レポート類・`.DS_Store` / `*.log` / `*.map`

> **SCSS ソース（`resources/common/scss/`）は除外していません**。本番側でもスタイルの元ファイルを追える状態にしておくためです。

除外リストの正式な定義は [`scripts/create_dist.sh`](scripts/create_dist.sh) を参照してください。除外を変更したい場合もこのスクリプトを編集します。

> **注意**: `vendor/` は開発ツールだけでなく、`app/` 以下のクラス・関数を読み込む Composer オートローダーを含むため**本番でも必須**です。`pnpm dist` は開発用パッケージ（phpcs 等）を除いた本番用 `vendor/` を自動生成するので、`dist/baizy_block/` に含まれる `vendor/` をそのままアップすれば問題ありません。

`dist/` は Git 管理外（`.gitignore` 済み）です。

---

## ライセンス

GPL-2.0-or-later

詳細は `LICENSE` を参照してください。
