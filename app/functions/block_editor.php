<?php
/**
 * ブロックエディター関連のテーマ側設定
 *
 * TypeScript 製のカスタムブロック本体は baizy-custom-blocks プラグインにある。
 * ここではテーマが受け持つ範囲（パターン・ブロックスタイル・利用制限）だけを扱う。
 *
 * @package baizy_block
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// =============================================================================
// ブロックパターン
// =============================================================================

add_action(
	'after_setup_theme',
	function () {
		/**
		 * WordPress デフォルトのブロックパターンを無効化する。
		 *
		 * baizy_block は patterns/ に置いたテーマ提供パターンだけを
		 * インサーターに並べる方針のため、コアパターンは出さない。
		 * コアパターンも使いたい案件ではこの 1 行を削除する。
		 */
		remove_theme_support( 'core-block-patterns' );
	}
);

/**
 * パターンカテゴリーを登録する
 *
 * パターンファイル自体は WordPress 6.0+ の組み込み機能により
 * patterns/ ディレクトリから自動登録されるため、手動登録は不要。
 * ここではカテゴリーのラベルのみ登録する。
 */
function baizy_block_register_block_pattern_categories() {
	$categories = array(
		'baizy-block-page'    => 'ページ雛形',
		'baizy-block-hero'    => 'ヒーローセクション',
		'baizy-block-content' => 'コンテンツセクション',
	);

	foreach ( $categories as $slug => $label ) {
		register_block_pattern_category( $slug, array( 'label' => $label ) );
	}
}
add_action( 'init', 'baizy_block_register_block_pattern_categories' );

// =============================================================================
// ブロックスタイル（ビルド不要 / PHP のみ）
// =============================================================================

/**
 * テーマ独自の見た目バリエーションを登録する
 *
 * WordPress 6.6+ の style_data を使うと CSS ファイルを別途用意せずに済む。
 * 値は theme.json のプリセット変数を参照しているため、パレットを差し替えれば
 * ここを触らなくても追従する。
 */
function baizy_block_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'       => 'outline-accent',
			'label'      => 'アウトライン（アクセント）',
			'style_data' => array(
				'color'  => array(
					'background' => 'transparent',
					'text'       => 'var(--wp--preset--color--accent)',
				),
				'border' => array(
					'color' => 'var(--wp--preset--color--accent)',
					'style' => 'solid',
					'width' => '1px',
				),
			),
		)
	);

	register_block_style(
		'core/heading',
		array(
			'name'       => 'underline',
			'label'      => '下線付き',
			'style_data' => array(
				'border'  => array(
					'bottom' => array(
						'color' => 'var(--wp--preset--color--accent)',
						'style' => 'solid',
						'width' => '2px',
					),
				),
				'spacing' => array(
					'padding' => array(
						'bottom' => 'var(--wp--preset--spacing--20)',
					),
				),
			),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'       => 'card',
			'label'      => 'カード',
			'style_data' => array(
				'color'   => array(
					'background' => 'var(--wp--preset--color--base-2)',
				),
				'border'  => array(
					'radius' => '4px',
				),
				'spacing' => array(
					'padding' => array(
						'top'    => 'var(--wp--preset--spacing--40)',
						'right'  => 'var(--wp--preset--spacing--40)',
						'bottom' => 'var(--wp--preset--spacing--40)',
						'left'   => 'var(--wp--preset--spacing--40)',
					),
				),
			),
		)
	);
}
add_action( 'init', 'baizy_block_register_block_styles' );

// =============================================================================
// 投稿タイプごとの利用可能ブロック制限
// =============================================================================

/**
 * 投稿タイプごとに使えるブロックを絞る
 *
 * クライアントが崩しやすいレイアウト系ブロックを外し、
 * 記事本文に必要なブロックだけを残す用途を想定している。
 *
 * @param bool|string[]           $allowed_blocks      許可するブロック名の配列、または true。
 * @param WP_Block_Editor_Context $block_editor_context エディターのコンテキスト。
 * @return bool|string[]
 */
function baizy_block_restrict_blocks_for_post_types( $allowed_blocks, $block_editor_context ) {
	if ( empty( $block_editor_context->post ) || 'news' !== $block_editor_context->post->post_type ) {
		// 他の投稿タイプではすべてのブロックを許可
		return $allowed_blocks;
	}

	return array(
		// カスタムブロック（baizy-custom-blocks プラグイン側で登録）
		// 'my-blocks/◯◯',

		// テキスト
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/code',
		'core/preformatted',
		'core/pullquote',
		'core/table',
		'core/verse',
		'core/details',

		// メディア
		'core/image',
		'core/gallery',
		'core/audio',
		'core/video',
		'core/file',
	);
}
add_filter( 'allowed_block_types_all', 'baizy_block_restrict_blocks_for_post_types', 10, 2 );

// =============================================================================
// エディター専用スクリプト
// =============================================================================

/**
 * エディター内でのみ動く JS を読み込む
 *
 * ビルド工程を持たないため、resources/common/js/editor.js は
 * 素の JavaScript で書く（wp.domReady / wp.blocks をグローバルから参照）。
 */
function baizy_block_enqueue_editor_assets() {
	$path = BAIZY_BLOCK_THEME_PATH . '/resources/common/js/editor.js';

	if ( ! file_exists( $path ) ) {
		return;
	}

	wp_enqueue_script(
		'baizy-block-editor',
		BAIZY_BLOCK_THEME_URI . '/resources/common/js/editor.js',
		array( 'wp-dom-ready', 'wp-blocks', 'wp-rich-text' ),
		filemtime( $path ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'baizy_block_enqueue_editor_assets' );
