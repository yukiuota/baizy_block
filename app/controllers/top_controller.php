<?php
namespace BaizyBlock\Controllers;

use BaizyBlock\Models\PageMetaModel;
use BaizyBlock\Models\PostModel;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/** トップページ用コントローラー。Model からデータを集めビュー（resources/pages/top.php）へ渡す */
class TopController {

	/** ビューへ渡すデータを組み立てる */
	public static function data(): array {
		$front_id = (int) get_option( 'page_on_front' );

		return array(
			'news' => PostModel::get_latest_news(),
			'hero' => PageMetaModel::get_hero( $front_id ),
		);
	}
}
