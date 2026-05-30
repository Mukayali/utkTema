<?php
defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/nav-walker.php';

function utkvakfi_setup(): void {
    load_theme_textdomain('utkvakfi', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');

    add_image_size('utkvakfi-hero',     1440, 720, true);
    add_image_size('utkvakfi-card',     800,  450, true);
    add_image_size('utkvakfi-card-sm',  400,  225, true);
    add_image_size('utkvakfi-portrait', 400,  400, true);
    add_image_size('utkvakfi-thumb',    120,  120, true);

    register_nav_menus([
        'primary'  => __('Ana Menü', 'utkvakfi'),
        'footer-1' => __('Footer Hızlı Linkler', 'utkvakfi'),
        'footer-2' => __('Footer Konular', 'utkvakfi'),
    ]);
}
add_action('after_setup_theme', 'utkvakfi_setup');

function utkvakfi_enqueue_scripts(): void {
    $ver = wp_get_theme()->get('Version');

    wp_enqueue_style('utkvakfi-main', get_template_directory_uri() . '/assets/css/main.css', [], $ver);

    wp_enqueue_script(
        'utkvakfi-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        $ver,
        true
    );
    wp_localize_script('utkvakfi-main', 'utkData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('utkvakfi_nonce'),
        'i18n'    => [
            'searchPlaceholder' => __('Ne aramak istiyorsunuz?', 'utkvakfi'),
            'menuClose'         => __('Menüyü kapat', 'utkvakfi'),
        ],
    ]);
}
add_action('wp_enqueue_scripts', 'utkvakfi_enqueue_scripts');

function utkvakfi_register_sidebars(): void {
    $sidebars = [
        ['id' => 'sidebar-main',      'name' => 'Ana Sidebar'],
        ['id' => 'sidebar-archive',   'name' => 'Arşiv Sidebar'],
        ['id' => 'footer-col-1',      'name' => 'Footer – 1. Kolon'],
        ['id' => 'newsletter-widget', 'name' => 'Bülten Widget'],
    ];

    foreach ($sidebars as $sidebar) {
        register_sidebar([
            'id'            => $sidebar['id'],
            'name'          => $sidebar['name'],
            'before_widget' => '<div class="sidebar-widget" id="%1$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="sidebar-widget__title">',
            'after_title'   => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'utkvakfi_register_sidebars');

function utkvakfi_body_classes(array $classes): array {
    if (is_singular()) {
        $classes[] = 'is-singular';
    }
    if (is_home() || is_front_page()) {
        $classes[] = 'is-homepage';
    }
    return $classes;
}
add_filter('body_class', 'utkvakfi_body_classes');

/* Excerpt uzunluğu */
add_filter('excerpt_length', fn() => 25);
add_filter('excerpt_more',   fn() => '…');

/* Yazar profil alanları */
function utkvakfi_user_contactmethods(array $methods): array {
    $methods['uzmanlik'] = __('Uzmanlık Alanları', 'utkvakfi');
    $methods['kurum']    = __('Kurum / Üniversite', 'utkvakfi');
    $methods['linkedin'] = 'LinkedIn URL';
    return $methods;
}
add_filter('user_contactmethods', 'utkvakfi_user_contactmethods');

/* Admin kolon – Yayın türü */
function utkvakfi_yayin_columns(array $columns): array {
    $columns['tur']  = __('Tür', 'utkvakfi');
    $columns['konu'] = __('Konu', 'utkvakfi');
    return $columns;
}
add_filter('manage_yayin_posts_columns', 'utkvakfi_yayin_columns');

function utkvakfi_yayin_column_content(string $column, int $post_id): void {
    if ($column === 'tur') {
        $terms = get_the_terms($post_id, 'tur');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
        }
    }
    if ($column === 'konu') {
        $terms = get_the_terms($post_id, 'konu');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
        }
    }
}
add_action('manage_yayin_posts_custom_column', 'utkvakfi_yayin_column_content', 10, 2);

/* Yayınlar arşivinde standart 'post' tipini de dahil et */
function utkvakfi_archive_query( WP_Query $query ): void {
    if ( is_admin() || ! $query->is_main_query() ) return;
    if ( $query->is_post_type_archive( 'yayin' ) ) {
        $query->set( 'post_type', [ 'yayin', 'post' ] );
    }
}
add_action( 'pre_get_posts', 'utkvakfi_archive_query' );
