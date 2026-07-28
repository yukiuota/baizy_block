<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * ウィジェットエリア / ナビゲーションメニューについて
 *
 * baizy_block ではヘッダー・フッターを parts/*.html（ブロックテンプレートパーツ）で
 * 構成するため、以下は登録していない。
 *
 * - register_sidebar()  … Group / Columns ブロックを直接パーツに置くため不要。
 *                          登録すると管理画面に空のウィジェットエリアが残るだけになる。
 * - register_nav_menus() … core/navigation ブロックはメニューを wp_navigation
 *                          投稿タイプで管理するため、クラシックメニューの登録は不要。
 *
 * サイドバー付きのレイアウトなど、どうしてもウィジェットエリアが必要な案件では
 * ここに register_sidebar() を追加し、テンプレート側で dynamic_sidebar() を呼ぶ。
 */
