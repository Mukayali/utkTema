<?php
/*
 * =============================================================================
 * DOSYA: inc/taxonomies.php
 * =============================================================================
 * Taksonomi = içerikleri gruplamak için kullanılan etiket/kategori sistemi.
 *
 * Tıpkı bir kütüphanede kitapların "konu", "dil", "tür" gibi bölümlere
 * ayrılması gibi, bu dosya da site içeriklerini gruplar:
 *
 *   🏷️  konu  → Tematik gruplar: Eğitim, Kültür, Demokrasi, Çevre...
 *   📂  tur   → İçerik türü: Makale, Rapor, Analiz, Röportaj...
 *   🌍  dil   → Türkçe, İngilizce, Diğer
 *
 * Her taksonomi birden fazla içerik tipine bağlanabilir.
 * Örneğin "Eğitim" konusu hem yazılara hem yayınlara hem etkinliklere
 * atanabilir.
 * =============================================================================
 */

// WordPress'in dışından doğrudan bu dosyaya girilmesini engeller.
defined('ABSPATH') || exit;


// =============================================================================
// TAKSONOMİLERİ KAYDET
// =============================================================================
function utkvakfi_register_taxonomies(): void {

    // ==========================================================================
    // KONU TAKSONOMİSİ
    // ==========================================================================
    // Tematik araştırma alanları: Eğitim, Kültür, Demokrasi, Çevre...
    //
    // 'hierarchical' => true  → Kategoriler gibi iç içe olabilir.
    //                           Örneğin: Eğitim > Yükseköğretim > Lisans
    //
    // Bağlı içerik tipleri: yazılar, yayınlar, etkinlikler, projeler
    register_taxonomy('konu', ['post', 'yayin', 'etkinlik', 'proje'], [
        'labels' => [
            'name'              => 'Konular',
            'singular_name'     => 'Konu',
            'search_items'      => 'Konu Ara',
            'all_items'         => 'Tüm Konular',
            'edit_item'         => 'Konuyu Düzenle',
            'add_new_item'      => 'Yeni Konu Ekle',
            'menu_name'         => 'Konular',          // Sol menüde görünecek ad
        ],
        'public'            => true,    // Sitede erişilebilir URL'ler oluştur
        'show_ui'           => true,    // Yönetici panelinde göster
        'show_in_menu'      => true,    // Sol menüde görünsün
        'show_in_rest'      => true,    // Gutenberg editöründe kullanılabilsin
        'hierarchical'      => true,    // Alt-üst kategori yapısına izin ver (kategori gibi)
        'show_admin_column' => true,    // İçerik listelerinde konu kolonu göster
        'rewrite'           => ['slug' => 'konu'], // URL: site.com/konu/egitim/
    ]);


    // ==========================================================================
    // TÜR TAKSONOMİSİ
    // ==========================================================================
    // İçeriğin ne tür bir metin olduğunu belirtir: Makale mi? Rapor mu?
    //
    // 'hierarchical' => false → Etiketler gibi düz listedir, alt-üst yok.
    //
    // Bağlı içerik tipleri: yazılar, yayınlar
    register_taxonomy('tur', ['post', 'yayin'], [
        'labels' => [
            'name'          => 'Türler',
            'singular_name' => 'Tür',
            'all_items'     => 'Tüm Türler',
            'add_new_item'  => 'Yeni Tür Ekle',
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,   // Düz liste (etiket gibi), iç içe yok
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'tur'], // URL: site.com/tur/makale/
    ]);


    // ==========================================================================
    // DİL TAKSONOMİSİ
    // ==========================================================================
    // İçeriğin hangi dilde yazıldığını belirtir.
    // Örneğin: Türkçe, İngilizce
    //
    // Bağlı içerik tipleri: yazılar, yayınlar, etkinlikler
    register_taxonomy('dil', ['post', 'yayin', 'etkinlik'], [
        'labels' => [
            'name'          => 'Diller',
            'singular_name' => 'Dil',
            'all_items'     => 'Tüm Diller',
            'add_new_item'  => 'Yeni Dil Ekle',
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,   // Düz liste
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'dil'], // URL: site.com/dil/turkce/
    ]);
}
// 'init' → WordPress başladığında taksonomileri kaydet.
add_action('init', 'utkvakfi_register_taxonomies');
