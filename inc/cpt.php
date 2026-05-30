<?php
defined('ABSPATH') || exit;

function utkvakfi_register_post_types(): void {
    $post_types = [
        'yayin' => [
            'singular' => 'Yayın',
            'plural'   => 'Yayınlar',
            'icon'     => 'dashicons-book-alt',
            'supports' => ['title','editor','thumbnail','excerpt','author','revisions'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'yayinlar'],
        ],
        'etkinlik' => [
            'singular' => 'Etkinlik',
            'plural'   => 'Etkinlikler',
            'icon'     => 'dashicons-calendar-alt',
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'etkinlikler'],
        ],
        'uzman' => [
            'singular' => 'Uzman',
            'plural'   => 'Uzmanlar',
            'icon'     => 'dashicons-groups',
            'supports' => ['title','editor','thumbnail'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'uzmanlar'],
        ],
        'medya' => [
            'singular' => 'Medya',
            'plural'   => 'Medya',
            'icon'     => 'dashicons-megaphone',
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'medya'],
        ],
        'proje' => [
            'singular' => 'Proje',
            'plural'   => 'Projeler',
            'icon'     => 'dashicons-portfolio',
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'projeler'],
        ],
    ];

    foreach ($post_types as $slug => $args) {
        register_post_type($slug, [
            'labels' => [
                'name'          => $args['plural'],
                'singular_name' => $args['singular'],
                'add_new_item'  => 'Yeni ' . $args['singular'] . ' Ekle',
                'edit_item'     => $args['singular'] . ' Düzenle',
                'view_item'     => $args['singular'] . ' Görüntüle',
                'search_items'  => $args['singular'] . ' Ara',
                'not_found'     => $args['plural'] . ' bulunamadı',
            ],
            'public'       => true,
            'show_in_rest' => true,
            'menu_icon'    => $args['icon'],
            'supports'     => $args['supports'],
            'has_archive'  => $args['has_archive'],
            'rewrite'      => $args['rewrite'],
        ]);
    }
}
add_action('init', 'utkvakfi_register_post_types');
