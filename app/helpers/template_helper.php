<?php
namespace BaizyBlock\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class TemplateHelper {

	/**
	 * get_template_part にフック機構を追加して読み込む
	 *
	 * フック例:
	 *   baizy_block_part_before__{slug}  読み込み前アクション
	 *   baizy_block_part__{slug}         コンテンツ書き換えフィルター
	 *   baizy_block_part_after__{slug}   読み込み後アクション
	 *
	 * @param string $slug テンプレートパス（拡張子なし）
	 * @param array  $args テンプレートへ渡すデータ（テンプレート側では $args で参照）
	 */
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

	/**
	 * 候補テンプレートのうち最初に存在するものを読み込む
	 *
	 * @param string[] $candidates 優先順のテンプレートパス（拡張子なし）
	 * @param string   $fallback   どの候補も存在しない場合に読み込むパス
	 * @param array    $args       テンプレートへ渡すデータ（テンプレート側では $args で参照）
	 */
	public static function first_part( array $candidates, string $fallback, array $args = array() ): void {
		foreach ( $candidates as $candidate ) {
			if ( locate_template( $candidate . '.php' ) ) {
				self::part( $candidate, $args );
				return;
			}
		}
		self::part( $fallback, $args );
	}

	/**
	 * 本文ラッパーのクラス名を返す
	 *
	 * ブロックテーマではコアが自動生成するレイアウト用クラスを、
	 * クラシックテーマでは手動で付与する必要がある。これが無いと
	 * theme.json の settings.layout（contentSize / wideSize）と
	 * useRootPaddingAwareAlignments が front 側で一切効かず、
	 * alignwide / alignfull も機能しない。
	 *
	 *   is-layout-constrained  … contentSize / wideSize の max-width
	 *   has-global-padding     … ルートパディングと alignfull のネガティブマージン
	 *
	 * @param string[] $extra 追加したいクラス名。
	 */
	public static function content_wrapper_class( array $extra = array() ): string {
		$classes = array_merge(
			array( 'entry-content', 'is-layout-constrained', 'has-global-padding' ),
			$extra
		);

		return implode( ' ', array_map( 'sanitize_html_class', array_unique( $classes ) ) );
	}
}
