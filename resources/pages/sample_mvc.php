<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** 【サンプル】ビューの書き方見本。データは $args で受け取り必ずエスケープして出力する（コントローラー: app/controllers/sample_controller.php） */

$catch_copy = $args['catch_copy'] ?? '';
$hero       = $args['hero'] ?? array();
$faq_items  = $args['faq_items'] ?? array();
?>

<main class="sample-mvc">

	<?php // パターン1: シンプルな値の出力 ?>
	<?php if ( $catch_copy ) : ?>
	<p class="sample-mvc__catch"><?php echo esc_html( $catch_copy ); ?></p>
	<?php endif; ?>

	<?php // パターン2: 整形済みフィールド群（ヒーロー）の出力 ?>
	<section class="hero">
		<h1 class="hero__title"><?php echo esc_html( $hero['title'] ?? '' ); ?></h1>
		<?php if ( ! empty( $hero['image']['url'] ) ) : ?>
		<img
			src="<?php echo esc_url( $hero['image']['url'] ); ?>"
			alt="<?php echo esc_attr( $hero['image']['alt'] ?? '' ); ?>"
			width="<?php echo esc_attr( $hero['image']['width'] ?? '' ); ?>"
			height="<?php echo esc_attr( $hero['image']['height'] ?? '' ); ?>"
		>
		<?php endif; ?>
	</section>

	<?php // パターン3: Repeater（整形済み配列）のループ出力 ?>
	<?php if ( $faq_items ) : ?>
	<section class="faq">
		<h2>よくある質問</h2>
		<dl class="faq__list">
			<?php foreach ( $faq_items as $item ) : ?>
			<dt class="faq__question"><?php echo esc_html( $item['question'] ); ?></dt>
			<dd class="faq__answer"><?php echo wp_kses_post( $item['answer'] ); ?></dd>
			<?php endforeach; ?>
		</dl>
	</section>
	<?php endif; ?>

</main>
