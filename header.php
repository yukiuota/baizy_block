<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php $head_tag = (is_home() || is_front_page()) ? 'website' : 'article'; ?>

<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# <?php echo esc_attr( $head_tag ); ?>: https://ogp.me/ns/<?php echo esc_attr( $head_tag ); ?>#">
    <?php baizy_block_template_part( 'resources/include/tags/head_top' ); ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>