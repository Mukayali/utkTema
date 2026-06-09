<?php
/*
 * =============================================================================
 * DOSYA: functions.php
 * =============================================================================
 * Bu dosya temanın "beyni" gibidir. Tıpkı bir evin elektrik panosunun tüm
 * kabloları bir araya getirmesi gibi, bu dosya da WordPress'e temanın nasıl
 * çalışacağını öğretir. Buraya yazılan her fonksiyon WordPress'e "şunu
 * yapabilirsin, şöyle davran" der.
 *
 * KONU: UTK Vakfı WordPress Teması
 * =============================================================================
 */

// WordPress'in dışından doğrudan bu dosyaya girilmesini engeller.
// Tıpkı evin kapısına "izinsiz girilmez" levhası asmak gibi.
defined('ABSPATH') || exit;

// -----------------------------------------------------------
// YARDIMCI DOSYALARI YÜKLE
// -----------------------------------------------------------
// Tema tek bir büyük dosya yerine küçük parçalara bölünmüştür.
// Bu satırlar o parçaları buraya taşır.
// "require_once" = "bir kez dahil et, tekrar etme" demek.

require_once get_template_directory() . '/inc/cpt.php';        // Özel içerik tipleri (Yayın, Etkinlik vb.)
require_once get_template_directory() . '/inc/taxonomies.php'; // Etiket/kategori sistemleri (Konu, Tür, Dil)
require_once get_template_directory() . '/inc/helpers.php';    // Her yerde kullanılan küçük yardımcı araçlar
require_once get_template_directory() . '/inc/nav-walker.php'; // Menüyü özel CSS sınıflarıyla çizen araç


// =============================================================================
// 1. TEMA KURULUM FONKSİYONU
// =============================================================================
// Bu fonksiyon tema aktif edildiğinde WordPress'e "bu tema şunları
// yapabilir" diye tanıtım yapar. Tıpkı yeni bir öğrencinin okulun
// birinci gününde kendini tanıtması gibi.
function utkvakfi_setup(): void {

    // Çeviri dosyalarını yükle → sitenin farklı dillerde çalışmasını sağlar.
    load_theme_textdomain('utkvakfi', get_template_directory() . '/languages');

    // Sayfanın <title> etiketini WordPress otomatik yönetsin.
    add_theme_support('title-tag');

    // Her yazıya/sayfaya öne çıkan görsel (kapak fotoğrafı) eklenebilsin.
    add_theme_support('post-thumbnails');

    // HTML5 standartlarında formlar, galeriler, resim açıklamaları çizilsin.
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);

    // Yönetici panelinden logo yüklenebilsin; max boyutlar ve esnek ölçek.
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // YouTube/Vimeo gibi gömülü içerikler mobilde de düzgün görünsün.
    add_theme_support('responsive-embeds');

    // Gutenberg editöründe "geniş hizalama" bloklarına izin ver.
    add_theme_support('align-wide');

    // Gutenberg'in kendi blok stillerini kullan.
    add_theme_support('wp-block-styles');

    // -----------------------------------------------------------
    // GÖRSEL BOYUTLARI TANIMLA
    // -----------------------------------------------------------
    // WordPress yüklenen her görseli farklı boyutlarda otomatik keser.
    // Tıpkı bir fotoğrafçının aynı fotoğrafı farklı çerçevelere koyması gibi.

    add_image_size('utkvakfi-hero',     1440, 720, true);  // Tam ekran hero görseli
    add_image_size('utkvakfi-card',     800,  450, true);  // Haber kartı görseli (16:9)
    add_image_size('utkvakfi-card-sm',  400,  225, true);  // Küçük kart görseli
    add_image_size('utkvakfi-portrait', 400,  400, true);  // Yazar profil fotoğrafı (kare)
    add_image_size('utkvakfi-thumb',    120,  120, true);  // Küçük önizleme görseli

    // -----------------------------------------------------------
    // MENÜ KONUMLARINI KAYDET
    // -----------------------------------------------------------
    // Yönetici panelinden "Görünüm > Menüler" kısmında bu menülere
    // link ataması yapılabilsin diye kayıt yapılır.
    register_nav_menus([
        'primary'  => __('Ana Menü', 'utkvakfi'),           // Sayfanın üst navigasyonu
        'footer-1' => __('Footer Hızlı Linkler', 'utkvakfi'), // Alt bölüm 2. kolonu
        'footer-2' => __('Footer Konular', 'utkvakfi'),     // Alt bölüm 3. kolonu
    ]);
}
// 'after_setup_theme' → WordPress tema yüklendikten hemen sonra bu fonksiyonu çalıştır.
add_action('after_setup_theme', 'utkvakfi_setup');


// =============================================================================
// 2. CSS VE JAVASCRIPT DOSYALARINI SAYFAYA EKLE
// =============================================================================
// Bu fonksiyon tıpkı bir öğretmenin sınıfa "bugün şu kitapları getirin"
// demesi gibi çalışır. WordPress'e hangi CSS ve JS dosyalarının
// sayfaya dahil edileceğini söyler.
function utkvakfi_enqueue_scripts(): void {

    // Tema sürüm numarasını al → tarayıcı eski dosyayı önbellekten kullanmasın.
    $ver = wp_get_theme()->get('Version');

    // Tek bir ana CSS dosyası yükle (main.css diğer tüm CSS'leri içe aktarır).
    wp_enqueue_style('utkvakfi-main', get_template_directory_uri() . '/assets/css/main.css', [], $ver);

    // Ana JavaScript dosyasını yükle.
    // Son parametre 'true' → dosyayı sayfanın SONUNDA yükle (performans için).
    wp_enqueue_script(
        'utkvakfi-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],    // Bağımlılık yok (jQuery vb. gerektirmez)
        $ver,
        true   // Footer'a koy
    );

    // JavaScript dosyasına PHP'den veri aktar.
    // Tıpkı bir mektuba not iliştirmek gibi: JS dosyası bu verileri okuyabilir.
    wp_localize_script('utkvakfi-main', 'utkData', [
        'ajaxUrl' => admin_url('admin-ajax.php'), // AJAX istekleri için URL
        'nonce'   => wp_create_nonce('utkvakfi_nonce'), // Güvenlik kodu (sahte istek engeli)
        'i18n'    => [
            'searchPlaceholder' => __('Ne aramak istiyorsunuz?', 'utkvakfi'),
            'menuClose'         => __('Menüyü kapat', 'utkvakfi'),
        ],
    ]);
}
// 'wp_enqueue_scripts' → Sayfa yüklenirken CSS/JS eklemek için doğru an.
add_action('wp_enqueue_scripts', 'utkvakfi_enqueue_scripts');


// =============================================================================
// 3. SIDEBAR (YAN PANEL) ALANLARINI KAYDET
// =============================================================================
// Sidebar = sayfanın sağ (ya da sol) kenarındaki widget alanı.
// Yönetici panelinden "Görünüm > Widget'lar" kısmında bu alanlara
// widget eklenebilsin diye kayıt yapılır.
function utkvakfi_register_sidebars(): void {

    // Kaydedilecek sidebar listesi
    $sidebars = [
        ['id' => 'sidebar-main',      'name' => 'Ana Sidebar'],        // Yazı detay sayfası yan panel
        ['id' => 'sidebar-archive',   'name' => 'Arşiv Sidebar'],      // Liste sayfaları yan panel
        ['id' => 'footer-col-1',      'name' => 'Footer – 1. Kolon'],  // Alt bölüm 1. kolonu
        ['id' => 'newsletter-widget', 'name' => 'Bülten Widget'],      // Bülten abonelik alanı
    ];

    // Her sidebar için WordPress'e kayıt yaptır
    foreach ($sidebars as $sidebar) {
        register_sidebar([
            'id'            => $sidebar['id'],
            'name'          => $sidebar['name'],
            // Widget'ın başında ve sonunda hangi HTML açılacak/kapanacak
            'before_widget' => '<div class="sidebar-widget" id="%1$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="sidebar-widget__title">',
            'after_title'   => '</h3>',
        ]);
    }
}
// 'widgets_init' → Widget sistemi başladığında sidebar'ları kaydet.
add_action('widgets_init', 'utkvakfi_register_sidebars');


// =============================================================================
// 4. BODY ETİKETİNE EK CSS SINIFLARI EKLE
// =============================================================================
// <body> etiketine hangi sayfada olduğumuzu belirten özel CSS sınıfları ekler.
// Böylece CSS ile sadece belirli sayfalara stil verebiliriz.
// Örnek çıktı: <body class="is-singular is-homepage ...">
function utkvakfi_body_classes(array $classes): array {

    // Tek yazı/sayfa görünümlerinde 'is-singular' sınıfı ekle
    if (is_singular()) {
        $classes[] = 'is-singular';
    }

    // Ana sayfa görünümlerinde 'is-homepage' sınıfı ekle
    if (is_home() || is_front_page()) {
        $classes[] = 'is-homepage';
    }

    return $classes;
}
// 'body_class' filtresine bağla → WordPress body sınıflarını oluştururken bu fonksiyonu çağır.
add_filter('body_class', 'utkvakfi_body_classes');


// =============================================================================
// 5. ÖZET (EXCERPT) AYARLARI
// =============================================================================
// Yazı özetleri (haber kartlarındaki kısa metin) için uzunluk ve üç nokta ayarı.

// Özet maksimum 25 kelime olsun (varsayılan 55'tir)
add_filter('excerpt_length', fn() => 25);

// Özetin sonuna "..." yerine "…" koy (tipografik üç nokta)
add_filter('excerpt_more',   fn() => '…');


// =============================================================================
// 6. YAZAR PROFİL ALANLARI
// =============================================================================
// WordPress'in standart yazar profiline ek alanlar ekler.
// Yönetici panelinde "Kullanıcılar > Profiliniz" sayfasında görünür.
function utkvakfi_user_contactmethods(array $methods): array {

    $methods['uzmanlik'] = __('Uzmanlık Alanları', 'utkvakfi'); // Akademik uzmanlık
    $methods['kurum']    = __('Kurum / Üniversite', 'utkvakfi'); // Çalışılan kurum
    $methods['linkedin'] = 'LinkedIn URL';                        // LinkedIn profil linki

    return $methods;
}
// 'user_contactmethods' → WordPress yazar iletişim alanlarını genişlet.
add_filter('user_contactmethods', 'utkvakfi_user_contactmethods');


// =============================================================================
// 7. YÖNETİCİ PANELİ – YAYIN LİSTESİNE EK KOLONLAR
// =============================================================================
// "Yayınlar" listesinde "Tür" ve "Konu" kolonlarını göster.
// Tıpkı bir tabloya yeni sütun eklemek gibi.

// Kolon başlıklarını tanımla
function utkvakfi_yayin_columns(array $columns): array {
    $columns['tur']  = __('Tür', 'utkvakfi');   // Makale mi? Rapor mu?
    $columns['konu'] = __('Konu', 'utkvakfi');   // Eğitim mi? Çevre mi?
    return $columns;
}
add_filter('manage_yayin_posts_columns', 'utkvakfi_yayin_columns');

// Her kolon için hangi verinin gösterileceğini belirle
function utkvakfi_yayin_column_content(string $column, int $post_id): void {

    // "Tür" kolonu → yayının türünü (Makale, Rapor vb.) listele
    if ($column === 'tur') {
        $terms = get_the_terms($post_id, 'tur');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
        }
    }

    // "Konu" kolonu → yayının konusunu (Eğitim, Çevre vb.) listele
    if ($column === 'konu') {
        $terms = get_the_terms($post_id, 'konu');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
        }
    }
}
add_action('manage_yayin_posts_custom_column', 'utkvakfi_yayin_column_content', 10, 2);


// =============================================================================
// 8. ARŞİV VE ARAMA SORGUSU AYARLARI
// =============================================================================
// WordPress'in listelemek için veritabanına attığı sorguyu özelleştirir.
// Tıpkı bir kütüphaneciye "bana sadece şu raftan kitap getir" demek gibi.
function utkvakfi_archive_query( WP_Query $query ): void {

    // Yönetici panelindeki sorguları veya ikincil sorguları etkileme
    if ( is_admin() || ! $query->is_main_query() ) return;

    // Arşiv, arama ve blog ana sayfasında sayfa başı 9 yazı göster
    if ( $query->is_archive() || $query->is_search() || $query->is_home() ) {
        $query->set( 'posts_per_page', 9 );
    }

    // Aramada tüm kamuya açık içerik tiplerini dahil et
    if ( $query->is_search() ) {
        $query->set( 'post_type', [ 'post', 'page', 'yayin', 'etkinlik', 'uzman', 'medya', 'proje' ] );
    }

    // Yayınlar arşivinde hem 'yayin' hem de standart 'post' tiplerini getir
    // (Böylece normal blog yazıları da yayın listesinde görünür)
    if ( $query->is_post_type_archive( 'yayin' ) ) {
        $query->set( 'post_type', [ 'yayin', 'post' ] );
    }
}
// 'pre_get_posts' → WordPress veritabanına sormadan hemen önce devreye gir.
add_action( 'pre_get_posts', 'utkvakfi_archive_query' );


// =============================================================================
// 9. BÜLTEN ABONELİK FORMU İŞLEYİCİSİ
// =============================================================================
// Kullanıcı bülten formunu doldurup "Abone Ol"a bastığında bu fonksiyon çalışır.
// Tıpkı bir postacının mektubu alıp doğru adrese götürmesi gibi.
function utkvakfi_handle_newsletter(): void {

    // -----------------------------------------------------------
    // GÜVENLİK KONTROLÜ: Nonce doğrulama
    // -----------------------------------------------------------
    // Form gönderimi gerçekten bizim sayfamızdan mı geldi?
    // Sahte (bot) istekleri bu kontrol engeller.
    if ( ! isset( $_POST['newsletter_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['newsletter_nonce'] ) ), 'utkvakfi_newsletter' ) ) {
        // Sahte istek → hata mesajıyla ana sayfaya geri gönder
        wp_safe_redirect( add_query_arg( 'subscribed', 'error', wp_get_referer() ) );
        exit;
    }

    // -----------------------------------------------------------
    // E-POSTA DOĞRULAMA
    // -----------------------------------------------------------
    // Gelen e-posta geçerli bir format mı? (örn. @, . işaretleri var mı?)
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

    if ( ! is_email( $email ) ) {
        // Geçersiz e-posta → uyarı mesajıyla geri gönder
        wp_safe_redirect( add_query_arg( 'subscribed', 'invalid', wp_get_referer() ) );
        exit;
    }

    // -----------------------------------------------------------
    // YÖNETİCİYE BİLDİRİM MAİLİ GÖNDER
    // -----------------------------------------------------------
    // Site yöneticisinin e-postasına "yeni biri abone oldu" bildirimi gider.
    $admin_email = get_option( 'admin_email' );
    $site_name   = get_bloginfo( 'name' );
    wp_mail(
        $admin_email,
        /* translators: %s: site name */
        sprintf( __( '[%s] Yeni Bülten Aboneliği', 'utkvakfi' ), $site_name ),
        sprintf( __( 'Yeni abone: %s', 'utkvakfi' ), $email )
    );

    // -----------------------------------------------------------
    // ABONE LİSTESİNE KAYDET
    // -----------------------------------------------------------
    // E-postayı ve kayıt tarihini WordPress veritabanına (wp_options tablosuna) yaz.
    // Tüm aboneler 'utkvakfi_newsletter_subscribers' anahtarı altında bir dizi olarak saklanır.
    $subscribers   = get_option( 'utkvakfi_newsletter_subscribers', [] );
    $subscribers[] = [ 'email' => $email, 'date' => current_time( 'mysql' ) ];
    update_option( 'utkvakfi_newsletter_subscribers', $subscribers );

    // Başarılı → "abone oldunuz" mesajıyla ana sayfaya yönlendir
    wp_safe_redirect( add_query_arg( 'subscribed', '1', wp_get_referer() ) );
    exit;
}

// Bu iki satır, formu hem giriş yapmış hem de misafir kullanıcılar için çalıştırır.
// 'admin_post_*' → admin-post.php dosyasına gelen POST isteklerini karşılar.
add_action( 'admin_post_utkvakfi_newsletter',        'utkvakfi_handle_newsletter' ); // Giriş yapmış kullanıcılar
add_action( 'admin_post_nopriv_utkvakfi_newsletter', 'utkvakfi_handle_newsletter' ); // Misafir kullanıcılar
