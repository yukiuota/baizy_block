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

	/**
	 * エディター内のスタイルを登録する
	 *
	 * add_editor_style() は after_setup_theme のタイミングで呼ぶ必要がある。
	 * admin_init など遅いフックだと反映されないことがある。
	 */
	public function register_editor_styles(): void {
		add_editor_style( 'resources/common/css/editor-style.css' );
	}

	public function enqueue_styles(): void {
		$path    = get_template_directory() . '/resources/common/css/common.css';
		$version = $this->get_file_version( $path );
		if ( $version ) {
			wp_enqueue_style( 'baizy-block-main', BAIZY_BLOCK_THEME_URI . '/resources/common/css/common.css', array(), $version );
		}
		$this->enqueue_body_class_styles();
	}

	/**
	 * ブロックの見た目調整 CSS をフロントとエディターの両方へ流す
	 *
	 * enqueue_block_assets は「フロント」と「エディター（iframe 内）」の
	 * 両方で発火するため、1 回の登録で両者の見た目を揃えられる。
	 * ソースは resources/common/scss/blocks/ に置き、
	 * common.scss / editor-style.scss の双方から @use している。
	 */
	public function enqueue_block_styles(): void {
		$path    = get_template_directory() . '/resources/common/css/blocks.css';
		$version = $this->get_file_version( $path );
		if ( ! $version ) {
			return;
		}
		wp_enqueue_style( 'baizy-block-blocks', BAIZY_BLOCK_THEME_URI . '/resources/common/css/blocks.css', array(), $version );
	}

	/**
	 * body class と同名の CSS ファイルを自動で読み込む
	 *
	 * 例: body class に「home」があれば resources/common/css/home.css を enqueue する。
	 * ThemeSetup::add_slug_to_body_class() が投稿スラッグを body class に追加するため、
	 * 「ページスラッグと同名の CSS を置くだけでそのページ専用 CSS になる」仕組み。
	 */
	private function enqueue_body_class_styles(): void {
		$body_classes = get_body_class();
		if ( empty( $body_classes ) ) {
			return;
		}
		$css_dir   = get_template_directory() . '/resources/common/css/';
		$css_files = glob( $css_dir . '*.css' );
		if ( empty( $css_files ) ) {
			return;
		}
		$css_map = array();
		foreach ( $css_files as $file ) {
			$css_map[ basename( $file, '.css' ) ] = $file;
		}
		foreach ( $body_classes as $class_name ) {
			$filename = sanitize_file_name( $class_name );
			if ( isset( $css_map[ $filename ] ) ) {
				wp_enqueue_style(
					'baizy-block-body-class-' . sanitize_html_class( $class_name ),
					BAIZY_BLOCK_THEME_URI . '/resources/common/css/' . $filename . '.css',
					array( 'baizy-block-main' ),
					filemtime( $css_map[ $filename ] )
				);
			}
		}
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
