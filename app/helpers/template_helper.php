<?php
namespace BaizyBlock\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class TemplateHelper {

	/** get_template_part をフック付き（baizy_block_part_before__ / part__ / after__{slug}）で読み込む */
	public static function part( string $slug, array $args = array() ): void {
		ob_start();
		get_template_part( $slug, null, $args );
		$content = ob_get_clean();

		if ( has_filter( "baizy_block_part_before__{$slug}" ) ) {
			do_action( "baizy_block_part_before__{$slug}" );
		}

		if ( has_filter( "baizy_block_part__{$slug}" ) ) {
			$content = apply_filters( "baizy_block_part__{$slug}", $content );
		}

		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( has_filter( "baizy_block_part_after__{$slug}" ) ) {
			do_action( "baizy_block_part_after__{$slug}" );
		}
	}

	/** 候補テンプレートのうち最初に存在するものを読み込む（どれも無ければ $fallback） */
	public static function first_part( array $candidates, string $fallback, array $args = array() ): void {
		foreach ( $candidates as $candidate ) {
			if ( locate_template( $candidate . '.php' ) ) {
				self::part( $candidate, $args );
				return;
			}
		}
		self::part( $fallback, $args );
	}

	/** 本文ラッパーのクラス名を返す。これが無いと theme.json の contentSize / wideSize と alignwide / alignfull が front で効かない */
	public static function content_wrapper_class( array $extra = array() ): string {
		$classes = array_merge(
			array( 'entry-content', 'is-layout-constrained', 'has-global-padding' ),
			$extra
		);

		return implode( ' ', array_map( 'sanitize_html_class', array_unique( $classes ) ) );
	}
}
