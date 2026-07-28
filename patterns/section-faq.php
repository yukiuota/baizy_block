<?php
/**
 * Title: セクション: よくある質問
 * Slug: baizy-block/section-faq
 * Categories: baizy-block-content
 * Keywords: faq, よくある質問, アコーディオン, details
 * Description: core/details ブロックを使ったアコーディオン型の FAQ。JS 不要。
 *
 * @package baizy_block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","right":"var:preset|spacing|40","left":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:heading {"textAlign":"center"} -->
	<h2 class="wp-block-heading has-text-align-center">よくある質問</h2>
	<!-- /wp:heading -->

	<!-- wp:details {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}},"border":{"width":"1px"}},"borderColor":"contrast-2"} -->
	<details class="wp-block-details has-border-color has-contrast-2-border-color" style="border-width:1px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><summary>質問の 1 つ目がここに入ります</summary>
		<!-- wp:paragraph -->
		<p>回答がここに入ります。</p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->

	<!-- wp:details {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}},"border":{"width":"1px"}},"borderColor":"contrast-2"} -->
	<details class="wp-block-details has-border-color has-contrast-2-border-color" style="border-width:1px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><summary>質問の 2 つ目がここに入ります</summary>
		<!-- wp:paragraph -->
		<p>回答がここに入ります。</p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->

	<!-- wp:details {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}},"border":{"width":"1px"}},"borderColor":"contrast-2"} -->
	<details class="wp-block-details has-border-color has-contrast-2-border-color" style="border-width:1px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><summary>質問の 3 つ目がここに入ります</summary>
		<!-- wp:paragraph -->
		<p>回答がここに入ります。</p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->
</div>
<!-- /wp:group -->
