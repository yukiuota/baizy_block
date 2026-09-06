<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// セキュリティ設定

/**
 * REST API のユーザー一覧・ユーザー詳細を未権限ユーザーから隠す
 *
 * /wp-json/wp/v2/users と /wp-json/wp/v2/users/<id> は未ログインでも
 * ユーザー名・スラッグを返すため、ユーザー列挙の入口になる。
 * list_users 権限（管理者・編集者）を持つ場合だけ従来どおり通し、
 * それ以外にはルート自体を存在しない扱い（404）にする。
 *
 * /wp/v2/users/me は残す。ログイン中のユーザー自身の情報を返すだけで
 * 列挙には使えず、ブロックエディターが参照するため。
 *
 * @param array $endpoints REST ルート定義。
 * @return array
 */
function baizy_block_hide_rest_user_endpoints( array $endpoints ): array {
	if ( current_user_can( 'list_users' ) ) {
		return $endpoints;
	}

	unset( $endpoints['/wp/v2/users'] );
	unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );

	return $endpoints;
}
add_filter( 'rest_endpoints', 'baizy_block_hide_rest_user_endpoints' );


/**
 * コアサイトマップから著者一覧（/wp-sitemap-users-1.xml）を除外する
 *
 * WP 5.5 以降のコアサイトマップは、投稿を持つ著者の一覧を
 * 表示名・著者アーカイブ URL 付きで出力する。
 * プロバイダー登録時に false を返すと users 系サイトマップごと消え、
 * インデックス（/wp-sitemap.xml）からも参照されなくなる。
 *
 * @param WP_Sitemaps_Provider|false $provider プロバイダー実装。
 * @param string                     $provider_name プロバイダー名。
 * @return WP_Sitemaps_Provider|false
 */
function baizy_block_remove_users_sitemap( $provider, string $provider_name ) {
	if ( 'users' === $provider_name ) {
		return false;
	}
	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'baizy_block_remove_users_sitemap', 10, 2 );


/**
 * 著者ページの正規化リダイレクトを止める
 *
 * 著者アーカイブは ThemeSetup::disable_author_archive() で 404 にしているが、
 * redirect_canonical() が template_redirect の同じ優先度で先に走るため、
 * /?author=1 が 404 になる前に /author/<slug>/ へリダイレクトされ、
 * URL にログインスラッグが露出してしまう。
 * ここで false を返してリダイレクト自体を打ち消し、404 に倒す。
 *
 * @param string|false $redirect_url リダイレクト先 URL。
 * @return string|false
 */
function baizy_block_disable_author_canonical_redirect( $redirect_url ) {
	if ( is_author() ) {
		return false;
	}
	return $redirect_url;
}
add_filter( 'redirect_canonical', 'baizy_block_disable_author_canonical_redirect' );


/**
 * oEmbed レスポンスから著者情報を除く
 *
 * /wp-json/oembed/1.0/embed?url=... は投稿ごとに author_name（表示名）と
 * author_url（著者アーカイブ URL＝ログインスラッグ）を返すため、
 * 投稿 URL を総当たりすれば著者を列挙できる。
 * 埋め込み表示自体には必須の項目ではないので取り除く。
 *
 * @param array $data oEmbed レスポンス。
 * @return array
 */
function baizy_block_remove_oembed_author( array $data ): array {
	unset( $data['author_name'], $data['author_url'] );
	return $data;
}
add_filter( 'oembed_response_data', 'baizy_block_remove_oembed_author' );
