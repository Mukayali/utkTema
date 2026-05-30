<?php
defined('ABSPATH') || exit;

function utkvakfi_register_taxonomies(): void {
    register_taxonomy('konu', ['post', 'yayin', 'etkinlik', 'proje'], [
        'labels' => [
            'name'          => 'Konular',
            'singular_name' => 'Konu',
            'search_items'  => 'Konu Ara',
            'all_items'     => 'Tüm Konular',
            'edit_item'     => 'Konuyu Düzenle',
            'add_new_item'  => 'Yeni Konu Ekle',
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'konu'],
    ]);

    register_taxonomy('tur', ['post', 'yayin'], [
        'labels' => [
            'name'          => 'Türler',
            'singular_name' => 'Tür',
            'all_items'     => 'Tüm Türler',
            'add_new_item'  => 'Yeni Tür Ekle',
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'tur'],
    ]);

    register_taxonomy('dil', ['post', 'yayin', 'etkinlik'], [
        'labels' => [
            'name'          => 'Diller',
            'singular_name' => 'Dil',
            'all_items'     => 'Tüm Diller',
            'add_new_item'  => 'Yeni Dil Ekle',
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'dil'],
    ]);
}
add_action('init', 'utkvakfi_register_taxonomies');
