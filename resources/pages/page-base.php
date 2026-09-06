<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/** 固定ページの基本テンプレート。見た目はパターン側で組む前提のため、h1 も出さず the_content() のみ出力する */
?>

<main class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<div class="<?php baizy_block_content_class(); ?>">
			<?php the_content(); ?>
		</div>
	</article>
	<?php endwhile; ?>
</main>
