<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// クラス実装は app/Setup/ 以下を参照
new BaizyBlock\Setup\ThemeSetup();
new BaizyBlock\Setup\Scripts();
new BaizyBlock\Setup\Customizer();
