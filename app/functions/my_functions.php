<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// ----------------------------------------------------- //
// グローバル関数ラッパー
// 実装は app/Helpers/ の各クラスに委譲しています
// ----------------------------------------------------- //

// TemplateHelper
if ( ! function_exists( 'baizy_block_template_part' ) ) {
	/**
	 * @param string $slug テンプレートパス（拡張子なし）
	 * @param array  $args テンプレートへ渡すデータ（テンプレート側では $args で参照）
	 */
	function baizy_block_template_part( $slug, $args = array() ) {
		\BaizyBlock\Helpers\TemplateHelper::part( $slug, (array) $args );
	}
}

if ( ! function_exists( 'baizy_block_content_class' ) ) {
	/**
	 * 本文ラッパー用のクラス名を出力する
	 *
	 * 例: <main class="site-main <?php baizy_block_content_class(); ?>">
	 *
	 * @param array $extra 追加クラス名。
	 */
	function baizy_block_content_class( $extra = array() ) {
		echo esc_attr( \BaizyBlock\Helpers\TemplateHelper::content_wrapper_class( (array) $extra ) );
	}
}

// ImageHelper
if ( ! function_exists( 'baizy_block_img' ) ) {
	function baizy_block_img( $path ) {
		return \BaizyBlock\Helpers\ImageHelper::url( $path );
	}
}

if ( ! function_exists( 'baizy_block_img_wh' ) ) {
	function baizy_block_img_wh( $path, $lazy = true ) {
		\BaizyBlock\Helpers\ImageHelper::attributes( $path, $lazy );
	}
}

if ( ! function_exists( 'baizy_block_get_svg_dimensions' ) ) {
	function baizy_block_get_svg_dimensions( $svg_file_path ) {
		return \BaizyBlock\Helpers\ImageHelper::svg_dimensions( $svg_file_path );
	}
}
