<?php
namespace BaizyBlock\Models;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/** ACFフィールドの取得・整形を集約するモデル（ビュー内で get_field() を直接呼ばない） */
class PageMetaModel {

	/** ACFフィールドを安全に取得する（ACF無効・値が空なら $default を返す） */
	public static function field( string $name, int $post_id, $default = null ) {
		if ( ! function_exists( 'get_field' ) || ! $post_id ) {
			return $default;
		}
		$value = get_field( $name, $post_id );
		return ( null === $value || '' === $value || false === $value ) ? $default : $value;
	}

	/** ヒーローセクションのフィールド一式をビュー用に整形して返す */
	public static function get_hero( int $post_id ): array {
		return array(
			'title' => (string) self::field( 'hero_title', $post_id, get_the_title( $post_id ) ),
			'image' => self::field( 'hero_image', $post_id ), // ACF返り値形式「画像配列」を想定
		);
	}
}
