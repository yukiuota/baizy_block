<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/** アーカイブ / 検索結果の基本テンプレート（投稿タイプ別は resources/archives/{post_type}.php を作れば優先される） */

// custom_search_form() の定義を読み込む
baizy_block_template_part( 'resources/include/search/search' );
?>

<main class="site-main">
	<div class="<?php baizy_block_content_class( array( 'archive' ) ); ?>">
		<h1 class="archive-title wp-block-heading">
			<?php
			if ( is_search() ) {
				printf( '「%s」の検索結果', esc_html( get_search_query() ) );
			} else {
				the_archive_title();
			}
			?>
		</h1>

		<?php if ( have_posts() ) : ?>
		<ul class="archive-list">
			<?php while ( have_posts() ) : the_post(); ?>
			<li class="archive-list__item">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</li>
			<?php endwhile; ?>
		</ul>
		<?php else : ?>
		<p>記事がありません。</p>
		<?php endif; ?>

		<?php
		custom_search_form(
			array(
				'placeholder' => 'サイト内検索',
				'button_text' => '検索する',
				'form_class'  => 'archive-search-form',
			)
		);
		?>

		<?php custom_pagination(); ?>
	</div>
</main>
