<?php
namespace BaizyBlock\Controllers;

use BaizyBlock\Models\PageMetaModel;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/** 【サンプル】コントローラーの書き方見本。Model を呼びビューへ渡す配列を組み立てる（ビュー: resources/pages/sample_mvc.php） */
class SampleController {

	/** ビューへ渡すデータを組み立てる（$post_id 省略時は現在の投稿） */
	public static function data( int $post_id = 0 ): array {
		$post_id = $post_id ? $post_id : get_the_ID();

		return array(
			// パターン1: 単純な値は PageMetaModel::field() で直接取得
			'catch_copy' => PageMetaModel::field( 'catch_copy', $post_id, '' ),

			// パターン2: 整形が必要なフィールド群は Model のメソッドへ委譲（変更箇所が1つで済む）
			'hero'       => PageMetaModel::get_hero( $post_id ),

			// パターン3: Repeater は生配列のまま渡さず、ビュー用に整形してから渡す
			'faq_items'  => self::shape_faq( PageMetaModel::field( 'faq', $post_id, array() ) ),
		);
	}

	/** ACF Repeater「faq」の生データをビュー用に整形する */
	private static function shape_faq( array $rows ): array {
		$items = array();
		foreach ( $rows as $row ) {
			// 必須項目が空の行はビューへ渡す前に除外しておく
			if ( empty( $row['question'] ) ) {
				continue;
			}
			$items[] = array(
				'question' => (string) $row['question'],
				'answer'   => (string) ( $row['answer'] ?? '' ),
			);
		}
		return $items;
	}
}
