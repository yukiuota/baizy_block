<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** トップページ ビュー。データは TopController::data() から $args で受け取り、取得処理はここに書かない */

$news = $args['news'] ?? array();
$hero = $args['hero'] ?? array();
?>

<main class="site-main">
	<div class="<?php baizy_block_content_class( array( 'home' ) ); ?>">

		<?php if ( ! empty( $hero['title'] ) ) : ?>
		<h1 class="hero__title wp-block-heading"><?php echo esc_html( $hero['title'] ); ?></h1>
		<?php endif; ?>

		<?php if ( $news ) : ?>
		<ul class="news-list">
			<?php
			foreach ( $news as $post ) :
				setup_postdata( $post );
				baizy_block_template_part( 'resources/include/components/news/item' );
			endforeach;
			wp_reset_postdata();
			?>
		</ul>
		<?php else : ?>
		<p>ニュースはありません。</p>
		<?php endif; ?>

	</div>
</main>
