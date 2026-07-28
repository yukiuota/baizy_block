/**
 * ブロックエディター専用スクリプト（ビルド不要）
 *
 * app/functions/block_editor.php の baizy_block_enqueue_editor_assets() が
 * enqueue_block_editor_assets フックで読み込む。
 *
 * ビルド工程を持たないため、JSX やモジュール構文は使わず素の JavaScript で書く。
 * wp.blocks / wp.domReady はグローバルから参照する（依存は PHP 側で宣言済み）。
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.blocks ) {
		return;
	}

	/**
	 * ブロックバリエーション
	 *
	 * 「設定済みのブロック」をインサーターに並べる仕組み。
	 * パターンより粒度が細かく、1 ブロック単位で初期値を決めたいときに使う。
	 */
	wp.blocks.registerBlockVariation( 'core/group', {
		name: 'baizy-block-section',
		title: 'セクション（全幅）',
		description: '全幅・上下余白付きの Group。ページのセクション区切りに使う。',
		attributes: {
			align: 'full',
			layout: { type: 'constrained' },
			style: {
				spacing: {
					padding: {
						top: 'var:preset|spacing|70',
						bottom: 'var:preset|spacing|70',
						right: 'var:preset|spacing|40',
						left: 'var:preset|spacing|40',
					},
				},
			},
		},
		scope: [ 'inserter' ],
	} );

	wp.blocks.registerBlockVariation( 'core/paragraph', {
		name: 'baizy-block-lead',
		title: 'リード文',
		description: 'セクション冒頭の導入文。本文より一回り大きい。',
		attributes: {
			fontSize: 'large',
			placeholder: 'リード文を入力…',
		},
		scope: [ 'inserter' ],
	} );
} )( window.wp );
