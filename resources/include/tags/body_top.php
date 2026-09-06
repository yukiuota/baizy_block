<?php 
if ( !defined( 'ABSPATH' ) ) exit; 

// body上部にカスタマイザー設定のコードを出力（GTM等の script を含むため生出力・管理者専用設定）
$body_top_code = get_theme_mod( 'baizy_block_body_top_code', '' );
if ( !empty( $body_top_code ) ) {
    echo wp_unslash( $body_top_code ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
?>