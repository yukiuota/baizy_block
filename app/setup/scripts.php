<?php
namespace BaizyBlock\Setup;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Scripts {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'register_editor_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_styles' ) );
	}

	private function get_file_version( string $file_path ): ?int {
		return file_exists( $file_path ) ? filemtime( $file_path ) : null;
	}

	/** エディター内のスタイルを登録する（add_editor_style() は after_setup_theme で呼ぶ必要がある） */
	public function register_editor_styles(): void {
		add_editor_style( 'resources/common/css/editor-style.css' );
	}

	/** フロント専用のスタイルを読み込む（common.css は reset・サイトシェルなどエディターに出さないものだけ） */
	public function enqueue_styles(): void {
		$path    = get_template_directory() . '/resources/common/css/common.css';
		$version = $this->get_file_version( $path );
		if ( $version ) {
			wp_enqueue_style( 'baizy-block-main', BAIZY_BLOCK_THEME_URI . '/resources/common/css/common.css', array(), $version );
		}
	}

	/** ブロック・パターンの見た目 CSS をフロントとエディターの両方へ流す（肥大したらページ単位ではなく wp_enqueue_block_style() でブロック単位に分ける） */
	public function enqueue_block_styles(): void {
		$path    = get_template_directory() . '/resources/common/css/blocks.css';
		$version = $this->get_file_version( $path );
		if ( ! $version ) {
			return;
		}
		wp_enqueue_style( 'baizy-block-blocks', BAIZY_BLOCK_THEME_URI . '/resources/common/css/blocks.css', array(), $version );
	}

	public function enqueue_scripts(): void {
		$path    = get_template_directory() . '/resources/common/js/script.js';
		$version = $this->get_file_version( $path );
		if ( $version ) {
			// script.js は jQuery 非依存。defer は WP 6.3+ の strategy 引数で付与
			wp_enqueue_script(
				'baizy-block-main-script',
				BAIZY_BLOCK_THEME_URI . '/resources/common/js/script.js',
				array(),
				$version,
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}
	}
}
