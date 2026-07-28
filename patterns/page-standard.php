<?php
/**
 * Title: ページ: 標準（下層ページ）
 * Slug: baizy-block/page-standard
 * Categories: baizy-block-page
 * Block Types: core/post-content
 * Post Types: page
 * Keywords: page, 下層ページ, 会社概要
 * Description: ページタイトル + 本文 + テーブルの、下層ページ向けの素直な構成。
 *
 * page-lp.php と違い、こちらはブロックを直接書いている。
 * パターンの書き方としてはどちらでもよいので、案件に合わせて使い分ける。
 *
 * @package baizy_block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70","right":"var:preset|spacing|40","left":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:heading {"level":1,"fontSize":"x-large"} -->
	<h1 class="wp-block-heading has-x-large-font-size">ページタイトル</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph -->
	<p>ページのリード文がここに入ります。ページの目的や概要を簡潔に説明します。</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading -->
	<h2 class="wp-block-heading">見出し</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph -->
	<p>本文がここに入ります。</p>
	<!-- /wp:paragraph -->

	<!-- wp:table {"className":"is-style-stripes"} -->
	<figure class="wp-block-table is-style-stripes">
		<table class="has-fixed-layout">
			<tbody>
				<tr><td>項目名</td><td>内容</td></tr>
				<tr><td>項目名</td><td>内容</td></tr>
				<tr><td>項目名</td><td>内容</td></tr>
			</tbody>
		</table>
	</figure>
	<!-- /wp:table -->
</div>
<!-- /wp:group -->
