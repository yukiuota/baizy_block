<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ページ種別ごとに resources/ 以下のテンプレートへ振り分けるルーター
 *
 * 候補テンプレートの解決は TemplateHelper::first_part()
 * （存在する最初の候補を読み込み、なければフォールバック）に統一している。
 *
 * ヘッダー / フッターはブロックテンプレートパーツ（parts/*.html）。
 * 外観 > エディター > テンプレートパーツ から編集できる。
 * PHP 版に戻したい場合は sample/include/ 以下を参照。
 *
 * 最外周の .wp-site-blocks は、ブロックテーマでコアが自動生成しているラッパーを
 * クラシックテーマ側で再現するためのもの。alignfull / alignwide の基準になる。
 */

use BaizyBlock\Helpers\TemplateHelper;

echo '<div id="container" class="wp-site-blocks">';
block_template_part( 'header' );

if ( is_front_page() && is_page() ) :
    // 固定ページがトップページに設定されている場合
    baizy_block_template_part( 'resources/pages/page-base' );
elseif ( is_home() || is_front_page() ) :
    // ホームページ・フロントページ（データは TopController が組み立てる）
    baizy_block_template_part( 'resources/pages/top', \BaizyBlock\Controllers\TopController::data() );
elseif ( is_single() ) :
    // 単一投稿ページ（resources/single/{post_type}.php → single-base.php）
    TemplateHelper::first_part(
        array( 'resources/single/' . get_post_type() ),
        'resources/single/single-base'
    );
elseif ( is_page() ) :
    // 固定ページ
    // 1. 階層付きパス（parent/child → resources/pages/parent-child.php）
    // 2. スラッグ（resources/pages/{slug}.php）
    // 3. page-base.php
    global $wp;
    $candidates = array();
    if ( ! empty( $wp->request ) ) {
        $candidates[] = 'resources/pages/' . str_replace( '/', '-', $wp->request );
    }
    $candidates[] = 'resources/pages/' . get_post_field( 'post_name' );
    TemplateHelper::first_part( $candidates, 'resources/pages/page-base' );
elseif ( is_search() ) :
    // 検索結果ページ
    TemplateHelper::first_part(
        array( 'resources/pages/search' ),
        'resources/archives/archive-base'
    );
elseif ( is_archive() ) :
    // アーカイブページ（カテゴリ、タグ、カスタムタクソノミー、日付アーカイブを含む）
    $post_type = get_post_type();

    // 投稿が0件の場合など、投稿タイプが取得できない場合はクエリ対象から導出する
    if ( ! $post_type ) {
        $queried_object = get_queried_object();

        if ( $queried_object instanceof WP_Post_Type ) {
            $post_type = $queried_object->name;
        } elseif ( $queried_object instanceof WP_Term ) {
            // タクソノミーアーカイブの場合、関連する投稿タイプを取得（最初のものを使用）
            $taxonomy  = get_taxonomy( $queried_object->taxonomy );
            $post_type = ( $taxonomy && ! empty( $taxonomy->object_type ) ) ? $taxonomy->object_type[0] : 'post';
        } else {
            $post_type = 'post';
        }
    }

    TemplateHelper::first_part(
        array( 'resources/archives/' . $post_type ),
        'resources/archives/archive-base'
    );
elseif ( is_404() ) :
    // 404ページ
    baizy_block_template_part( 'resources/pages/not-page' );
else :
    // その他の場合のフォールバック
    baizy_block_template_part( 'resources/archives/archive-base' );
endif;

block_template_part( 'footer' );
echo '</div>'; // /#container
