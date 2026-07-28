<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/**
 * 固定ページの基本テンプレート
 *
 * baizy_block ではページの見た目はブロックパターンで組み立てる前提のため、
 * ここでは見出しを自動出力せず the_content() を出すだけにしている。
 * 「ページタイトルの h1」もパターン側に含める（LP でタイトルが不要な場合に消せる）。
 *
 * ラッパーのクラスは baizy_block_content_class() が返す。これが無いと
 * theme.json の contentSize / wideSize と alignwide / alignfull が効かない。
 */
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
