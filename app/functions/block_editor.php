<?php
/**
 * ブロックエディター設定のうちテーマ担当分（パターン・ブロックスタイル・利用制限）。ブロック本体は baizy-custom-blocks プラグイン
 *
 * @package baizy_block
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// ブロックパターン

add_action(
	'after_setup_theme',
	function () {
		// コアパターンを無効化し patterns/ のテーマ提供パターンだけをインサーターに出す（コアも使う案件ではこの 1 行を削除）
		remove_theme_support( 'core-block-patterns' );
	}
);

/** パターンカテゴリーのラベルを登録する（パターン本体は patterns/ から WP が自動登録） */
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

// ブロックスタイル（ビルド不要 / PHP のみ）

/** 見た目はページ単位の CSS ではなくブロック単位のバリエーションで出し分ける（色・余白・枠線は style_data、擬似要素等は _styles.scss） */
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

	// ここから下は style_data で表現できないもの（擬似要素が必要）。CSS は _styles.scss に置く
	register_block_style(
		'core/list',
		array(
			'name'  => 'check',
			'label' => 'チェックリスト',
		)
	);

	register_block_style(
		'core/heading',
		array(
			'name'  => 'centered-rule',
			'label' => '中央下線',
		)
	);
}
add_action( 'init', 'baizy_block_register_block_styles' );

// 投稿タイプごとの利用可能ブロック制限

/** 投稿タイプごとに使えるブロックを絞る（崩しやすいレイアウト系を外し記事本文用だけ残す） */
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

// エディター専用スクリプト

/** エディター内でのみ動く JS を読み込む（ビルド工程が無いため editor.js は素の JavaScript で書く） */
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
