<?php
/**
 * Title: ページ: LP（ランディングページ）
 * Slug: baizy-block/page-lp
 * Categories: baizy-block-page
 * Block Types: core/post-content
 * Post Types: page
 * Keywords: lp, ランディングページ, 縦積み
 * Description: ヒーロー → サービス → FAQ → CTA の縦積み構成。新規固定ページ作成時の雛形。
 *
 * 各セクションは core/pattern ブロックで section-* パターンを参照している。
 * 挿入時に実体のブロックへ展開されるため、クライアントは通常どおり編集できる。
 * セクションを増やしたいときはこのファイルに wp:pattern を 1 行足すだけでよい。
 *
 * @package baizy_block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:pattern {"slug":"baizy-block/section-hero"} /-->

<!-- wp:pattern {"slug":"baizy-block/section-cards"} /-->

<!-- wp:pattern {"slug":"baizy-block/section-faq"} /-->

<!-- wp:pattern {"slug":"baizy-block/section-cta"} /-->
