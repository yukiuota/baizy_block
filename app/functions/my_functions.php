<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// グローバル関数ラッパー（実装は app/helpers/ の各クラスに委譲）

// TemplateHelper
if ( ! function_exists( 'baizy_block_template_part' ) ) {
	/** テンプレートパーツを読み込む（$slug は拡張子なしのパス、$args はテンプレート側で $args として参照） */
	function baizy_block_template_part( $slug, $args = array() ) {
		\BaizyBlock\Helpers\TemplateHelper::part( $slug, (array) $args );
	}
}

if ( ! function_exists( 'baizy_block_content_class' ) ) {
	/** 本文ラッパー用のクラス名を出力する（$extra で追加クラスを指定） */
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
