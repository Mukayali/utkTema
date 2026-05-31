<?php
/*
 * =============================================================================
 * DOSYA: inc/cpt.php  (CPT = Custom Post Types = Özel İçerik Tipleri)
 * =============================================================================
 * WordPress normalde sadece "Yazı" ve "Sayfa" türlerini bilir.
 * Bu dosya WordPress'e yeni içerik türleri öğretir:
 *
 *   📄 yayin     → Makaleler, raporlar, analizler
 *   📅 etkinlik  → Paneller, konferanslar, ziyaretler
 *   👤 uzman     → Uzman/yazar profil sayfaları
 *   📢 medya     → Basın açıklamaları ve bültenler
 *   📁 proje     → Vakfın yürüttüğü projeler
 *
 * Tıpkı bir okulda "öğrenci", "öğretmen", "ders" gibi farklı
 * kategoriler oluşturmak gibi.
 * =============================================================================
 */

// WordPress'in dışından doğrudan bu dosyaya girilmesini engeller.
defined('ABSPATH') || exit;


// =============================================================================
// ÖZEL İÇERİK TİPLERİNİ KAYDET
// =============================================================================
// Bu fonksiyon WordPress'e "bu yeni içerik tipini tanı, yönetici panelinde
// göster ve URL'lerde bu şekilde adresle" der.
function utkvakfi_register_post_types(): void {

    // -----------------------------------------------------------
    // TÜM ÖZEL İÇERİK TİPLERİNİN TANIMI
    // -----------------------------------------------------------
    // Her içerik tipinin:
    //   'singular' → tekil adı  (örn. "1 Yayın")
    //   'plural'   → çoğul adı  (örn. "Tüm Yayınlar")
    //   'icon'     → yönetici panelindeki simge (dashicons kütüphanesi)
    //   'supports' → hangi alanların aktif olacağı (başlık, editör, görsel vb.)
    //   'has_archive' → /yayinlar/ gibi liste sayfası oluşturulsun mu?
    //   'rewrite'  → URL'de hangi kelimeyi kullan (/yayinlar/, /etkinlikler/ vb.)
    $post_types = [

        // --- YAYINLAR (makaleler, raporlar, analizler) ---
        'yayin' => [
            'singular' => 'Yayın',
            'plural'   => 'Yayınlar',
            'icon'     => 'dashicons-book-alt',      // Kitap simgesi
            'supports' => ['title','editor','thumbnail','excerpt','author','revisions'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'yayinlar'],    // URL: site.com/yayinlar/
        ],

        // --- ETKİNLİKLER (paneller, konferanslar, ziyaretler) ---
        'etkinlik' => [
            'singular' => 'Etkinlik',
            'plural'   => 'Etkinlikler',
            'icon'     => 'dashicons-calendar-alt',  // Takvim simgesi
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'etkinlikler'], // URL: site.com/etkinlikler/
        ],

        // --- UZMANLAR (yazar/araştırmacı profilleri) ---
        'uzman' => [
            'singular' => 'Uzman',
            'plural'   => 'Uzmanlar',
            'icon'     => 'dashicons-groups',        // Kişi grubu simgesi
            'supports' => ['title','editor','thumbnail'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'uzmanlar'],    // URL: site.com/uzmanlar/
        ],

        // --- MEDYA (basın açıklamaları, bültenler) ---
        'medya' => [
            'singular' => 'Medya',
            'plural'   => 'Medya',
            'icon'     => 'dashicons-megaphone',     // Megafon simgesi
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'medya'],       // URL: site.com/medya/
        ],

        // --- PROJELER (vakfın yürüttüğü projeler) ---
        'proje' => [
            'singular' => 'Proje',
            'plural'   => 'Projeler',
            'icon'     => 'dashicons-portfolio',     // Dosya/portföy simgesi
            'supports' => ['title','editor','thumbnail','excerpt'],
            'has_archive' => true,
            'rewrite'  => ['slug' => 'projeler'],    // URL: site.com/projeler/
        ],
    ];

    // -----------------------------------------------------------
    // HER İÇERİK TİPİNİ WORDPRESS'E KAYDET
    // -----------------------------------------------------------
    // Yukarıdaki listeyi tek tek dolaşarak her biri için
    // WordPress'in resmi register_post_type() fonksiyonunu çağırır.
    foreach ($post_types as $slug => $args) {
        register_post_type($slug, [

            // Yönetici panelindeki buton ve başlık metinleri
            'labels' => [
                'name'          => $args['plural'],
                'singular_name' => $args['singular'],
                'add_new_item'  => 'Yeni ' . $args['singular'] . ' Ekle',
                'edit_item'     => $args['singular'] . ' Düzenle',
                'view_item'     => $args['singular'] . ' Görüntüle',
                'search_items'  => $args['singular'] . ' Ara',
                'not_found'     => $args['plural'] . ' bulunamadı',
            ],

            'public'       => true,           // Sitede görünür olsun
            'show_in_rest' => true,           // Gutenberg editörüyle uyumlu olsun
            'menu_icon'    => $args['icon'],  // Sol menüdeki simge
            'supports'     => $args['supports'],
            'has_archive'  => $args['has_archive'],
            'rewrite'      => $args['rewrite'],
        ]);
    }
}
// 'init' → WordPress başladığında içerik tiplerini kaydet.
add_action('init', 'utkvakfi_register_post_types');
