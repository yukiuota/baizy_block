<?php
/**
 * WP Template Theme Functions
 *
 * @package baizy_block
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// パス・URIの定数化
define( 'BAIZY_BLOCK_THEME_PATH', get_template_directory() );
define( 'BAIZY_BLOCK_THEME_URI', get_template_directory_uri() );

// Composerオートローダーを読み込み（テーマの全機能がこれ経由のため、無い場合は原因を示して停止する）
if ( ! file_exists( BAIZY_BLOCK_THEME_PATH . '/vendor/autoload.php' ) ) {
	wp_die( 'baizy_block テーマ: vendor/autoload.php が見つかりません。テーマディレクトリで <code>composer install</code> を実行してください。' );
}
require_once BAIZY_BLOCK_THEME_PATH . '/vendor/autoload.php';
