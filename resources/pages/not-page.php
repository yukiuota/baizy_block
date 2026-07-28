<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<main class="site-main">
	<section class="<?php baizy_block_content_class( array( 'not-page' ) ); ?>">
		<h1 class="not-page__ttl wp-block-heading">404 Not Found</h1>
		<p class="not-page__text">お探しのページは見つかりませんでした。</p>
		<div class="not-page__btn wp-block-buttons">
			<div class="wp-block-button">
				<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">TOPへ戻る</a>
			</div>
		</div>
	</section>
</main>
