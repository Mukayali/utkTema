<?php
/*
 * =============================================================================
 * DOSYA: header.php
 * =============================================================================
 * Bu dosya sitenin HER sayfasında görünen üst kısmının HTML şablonudur.
 *
 * Tıpkı bir gazetenin her sayısında aynı kalan başlık bandı gibi:
 * logo, navigasyon menüsü ve arama butonu her sayfada buradan gelir.
 *
 * WordPress, bir sayfa yüklendiğinde önce bu dosyayı çalıştırır.
 * Sayfanın geri kalanı (<main> etiketi içindedir) başka dosyalarda.
 * Dosyanın sonunda <main> etiketi AÇILIR ama KAPANMAZ;
 * footer.php'de kapatılır.
 *
 * Bu dosyanın içerdiği HTML bölümleri:
 *   1. <!DOCTYPE html> → Tarayıcıya "bu bir HTML5 belgesidir" der
 *   2. <head>          → Meta bilgiler, CSS/JS yükleme (görünmez)
 *   3. Erişilebilirlik → "İçeriğe geç" linki (ekran okuyucular için)
 *   4. Topbar          → Üstteki kırmızı duyuru çubuğu (opsiyonel)
 *   5. <header>        → Logo + navigasyon + butonlar
 *   6. Arama overlay   → Büyüteç tıklandığında açılan tam ekran arama
 *   7. <main>          → İçerik alanının başlangıcı
 *
 * Kullanıldığı yer: get_header() çağrısıyla tüm sayfa şablonlarından.
 * =============================================================================
 */
?>
<!DOCTYPE html>
<?php /* language_attributes() → <html lang="tr"> gibi dil etiketi ekler */ ?>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <?php /* Karakter seti: UTF-8 → Türkçe karakterler (ş, ğ, ü, ö, ç, ı) doğru görünsün */ ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php /* Viewport: mobil cihazlarda sayfanın doğru boyutta gösterilmesi için */ ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php /* XFN profili: WordPress'in standart bağlantı biçimi */ ?>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php
    /*
     * wp_head() → WordPress'in büyülü kancası.
     * Bu tek satır şunları otomatik ekler:
     *   - Tüm CSS dosyaları (wp_enqueue_style ile eklenenler)
     *   - Tüm JS dosyaları (wp_enqueue_script ile eklenenler)
     *   - Favicon, RSS bağlantıları
     *   - SEO eklentilerinin meta etiketleri (title, description, og:image...)
     *   - ve daha fazlası...
     */
    wp_head();
    ?>
</head>
<?php
/*
 * body_class() → <body> etiketine otomatik CSS sınıfları ekler.
 * Örnek: "home logged-out single-post category-egitim"
 * Bu sayede CSS ile "sadece ana sayfada" ya da "sadece tekil yazılarda"
 * geçerli stiller yazabilirsiniz.
 */
?>
<body <?php body_class(); ?>>
<?php
/*
 * wp_body_open() → <body> etiketinin hemen arkasına eklentilerin
 * kendi kodlarını enjekte edebileceği bir kanca.
 * Örnek: Google Tag Manager, canlı destek chat widget'ları buraya girer.
 */
wp_body_open();
?>

<?php
/*
 * ERİŞİLEBİLİRLİK: "İçeriğe Geç" Linki
 * ======================================
 * Bu link sayfada görünmez (CSS ile ekran dışında gizlidir).
 * Ancak klavyeyle gezinen kullanıcılar TAB tuşuna bastığında
 * bu link görünür hale gelir ve tıklayarak menüyü atlayıp
 * doğrudan içeriğe geçebilirler.
 * WCAG 2.1 erişilebilirlik standardı bunu zorunlu kılar.
 */
?>
<a class="skip-nav" href="#main-content"><?php esc_html_e('İçeriğe geç', 'utkvakfi'); ?></a>

<?php
/*
 * TOPBAR – ÜST DUYURU ÇUBUĞU
 * ============================
 * WordPress yönetici panelinden "topbar_text" ayarı doldurulmuşsa
 * sayfanın en üstünde kırmızı bir duyuru şeridi gösterilir.
 * Örnek: "Yeni raporumuz yayınlandı → Okumak için tıklayın"
 * Ayar boşsa bu alan hiç oluşturulmaz.
 */
?>
<?php if (get_theme_mod('topbar_text')) : ?>
<div class="topbar" role="banner">
    <div class="container">
        <?php
        /*
         * wp_kses_post() → HTML çıktısında sadece güvenli etiketlere izin verir.
         * Yönetici panelinde yazılan metin burada güvenle gösterilir.
         * <a href="..."> linki içerebilir ama <script> içeremez.
         */
        ?>
        <p class="topbar__text"><?php echo wp_kses_post(get_theme_mod('topbar_text')); ?></p>
    </div>
</div>
<?php endif; ?>

<?php /* Ana header: logo + menü + butonlar */ ?>
<header class="site-header" role="banner">
    <div class="container">
        <div class="header-inner">

            <?php
            /*
             * LOGO
             * ====
             * Yönetici paneli → Görünüm → Özelleştir → Site Kimliği'nden
             * logo yüklendiyse görsel logo gösterilir.
             * Yüklenmemişse site adı ve sloganı metin olarak gösterilir.
             *
             * has_custom_logo() → Logo yüklü mü? (true/false döner)
             * the_custom_logo() → Logo <img> etiketini basar
             */
            ?>
            <?php if (has_custom_logo()) : ?>
                <?php /* Görsel logo: tıklanınca ana sayfaya götürür */ ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home" aria-label="<?php bloginfo('name'); ?> – Ana Sayfa">
                    <?php the_custom_logo(); ?>
                </a>
            <?php else : ?>
                <?php /* Metin logo: site adı + slogan */ ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                    <div class="site-logo__text">
                        <span class="site-logo__name"><?php bloginfo('name'); ?></span>
                        <span class="site-logo__tagline"><?php bloginfo('description'); ?></span>
                    </div>
                </a>
            <?php endif; ?>

            <?php
            /*
             * ANA NAVİGASYON MENÜSÜ
             * ======================
             * WordPress yönetici panelinden atanan "primary" konumundaki menü.
             * wp_nav_menu() → menü HTML'ini otomatik üretir.
             *
             * Parametre açıklamaları:
             *   theme_location  → functions.php'de register_nav_menus() ile tanımlanan ad
             *   menu_class      → <ul> etiketine eklenecek CSS sınıfı
             *   container       → false → <div> veya <nav> sarmalayıcı oluşturma
             *   items_wrap      → <ul> etrafındaki HTML şablonu; role="menubar" erişilebilirlik için
             *   fallback_cb     → false → Menü atanmamışsa hiçbir şey gösterme
             *   walker          → BEM sınıflarını eklemek için özel menü oluşturucu (inc/nav-walker.php)
             */
            ?>
            <nav class="main-nav" id="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Ana navigasyon', 'utkvakfi'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location'  => 'primary',
                    'menu_class'      => 'main-nav__list',
                    'container'       => false,
                    'items_wrap'      => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                    'fallback_cb'     => false,
                    'walker'          => class_exists('UTK_Nav_Walker') ? new UTK_Nav_Walker() : null,
                ]);
                ?>
            </nav>

            <?php /* Sağdaki butonlar: arama + hamburger */ ?>
            <div class="header-actions">
                <?php
                /*
                 * ARAMA BUTONU
                 * =============
                 * Büyüteç ikonuna tıklanınca JavaScript arama overlay'ini açar.
                 * aria-controls="search-overlay" → bu buton hangi alanı kontrol ettiğini
                 *   ekran okuyuculara söyler.
                 * aria-expanded="false" → JavaScript, açılınca bunu "true" yapar.
                 */
                ?>
                <button class="header-search-btn" aria-label="<?php esc_attr_e('Arama', 'utkvakfi'); ?>" aria-controls="search-overlay" aria-expanded="false">
                    <?php echo utkvakfi_get_svg('search'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </button>

                <?php
                /*
                 * HAMBURGEr BUTONU (MOBİL MENÜ)
                 * ================================
                 * Mobil cihazlarda görünen üç çizgili menü butonu.
                 * Tıklanınca JavaScript, ana navigasyona "is-open" sınıfı ekler
                 * → menü tam ekranı kaplar.
                 * Üç <span> = üç çizgi; CSS ile X animasyonu yapılır.
                 * aria-hidden="true" → çizgiler dekoratif, ekran okuyucu duymasın.
                 */
                ?>
                <button class="hamburger" aria-label="<?php esc_attr_e('Menüyü aç', 'utkvakfi'); ?>" aria-controls="main-navigation" aria-expanded="false">
                    <span class="hamburger__line" aria-hidden="true"></span>
                    <span class="hamburger__line" aria-hidden="true"></span>
                    <span class="hamburger__line" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>
</header>

<?php
/*
 * ARAMA OVERLAY
 * ==============
 * Büyüteç butonuna tıklanınca tüm ekranı kaplayan arama kutusu.
 * Varsayılan olarak görünmez (CSS: opacity:0, visibility:hidden).
 * JavaScript "is-open" sınıfını ekleyince görünür hale gelir.
 *
 * role="dialog" + aria-modal="true" → Ekran okuyuculara "bu bir diyalog
 *   kutusu ve sayfa arkaplanını engeller" bilgisini verir.
 * Arama sonuçları için kullanıcı ENTER'a basar ve WordPress'in
 * standart arama sayfasına (/?s=aranan+kelime) yönlendirilir.
 */
?>
<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Arama', 'utkvakfi'); ?>">
    <div class="search-overlay__inner">
        <?php /* Üst küçük etiket: "ARAMA" */ ?>
        <span class="search-overlay__label"><?php esc_html_e('Arama', 'utkvakfi'); ?></span>
        <?php
        /*
         * Arama formu: method="get" → arama terimi URL'e eklenir (?s=...)
         * WordPress bu URL'i otomatik yakalar ve arama sonuçlarını gösterir.
         * action → ana sayfa URL'i (WordPress arama için buraya gönderir)
         * name="s" → WordPress'in beklediği standart arama parametre adı
         */
        ?>
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-overlay__form">
            <?php
            /*
             * <label> ekran okuyucular için zorunlu; görsel olarak gizli
             * (sr-only sınıfı: görünmez ama DOM'da mevcut).
             * for="search-input" → aşağıdaki input'un id'siyle eşleşir.
             */
            ?>
            <label for="search-input" class="sr-only"><?php esc_html_e('Ara', 'utkvakfi'); ?></label>
            <input
                type="search"
                id="search-input"
                class="search-overlay__input"
                name="s"
                placeholder="<?php esc_attr_e('Ne aramak istiyorsunuz?', 'utkvakfi'); ?>"
                autocomplete="off"
            >
            <?php /* Kapatma butonu: JavaScript overlay'i gizler */ ?>
            <button type="button" class="search-overlay__close" aria-label="<?php esc_attr_e('Aramayı kapat', 'utkvakfi'); ?>">
                <?php echo utkvakfi_get_svg('close'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </button>
        </form>
    </div>
</div>

<?php
/*
 * MAIN – İÇERİK ALANI BAŞLANGICI
 * ================================
 * id="main-content" → üstteki "İçeriğe geç" linkinin hedefi.
 * tabindex="-1" → JavaScript ile bu elemana odaklanılabilmesi için
 *   (erişilebilirlik: "İçeriğe geç" tıklandığında klavye odağı buraya taşınır).
 * Bu etiket footer.php'de kapatılır: </main>
 */
?>
<main id="main-content" tabindex="-1">
