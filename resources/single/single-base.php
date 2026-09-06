<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/** 投稿詳細の基本テンプレート。一覧からの流入があるためタイトルを自動出力する（投稿タイプ別は resources/single/{post_type}.php が優先） */
?>

<main class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="<?php baizy_block_content_class( array( 'entry-header' ) ); ?>">
			<h1 class="entry-title wp-block-heading"><?php the_title(); ?></h1>
		</header>

		<div class="<?php baizy_block_content_class(); ?>">
			<?php the_content(); ?>
		</div>

		<footer class="<?php baizy_block_content_class( array( 'entry-footer' ) ); ?>">
			<?php display_prev_next_post_links(); ?>
		</footer>
	</article>
	<?php endwhile; ?>
</main>
