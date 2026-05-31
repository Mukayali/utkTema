<?php
/*
 * =============================================================================
 * DOSYA: front-page.php
 * =============================================================================
 * Bu dosya sitenin ANA SAYFASININ şablonudur.
 * Ziyaretçi "utkvakfi.org" adresine girdiğinde bu dosya çalışır.
 *
 * WordPress, yönetici panelinde "Ön Sayfa Görünümü" olarak bu sayfa
 * seçildiğinde front-page.php'yi otomatik bulur ve kullanır.
 *
 * Ana sayfa bölümleri (yukarıdan aşağıya):
 *   1. Hero          → Smart Slider 3 eklentisinin kaydırmalı görseli
 *   2. Öne Çıkan     → En önemli 3 yayın büyük kart formatında
 *   3. Konular       → Araştırma alanlarına ikon kartları
 *   4. Son Haberler  → Son 6 yayın kart ızgarasında
 *   5. Etkinlikler   → Yaklaşan 4 etkinlik liste görünümünde
 *   6. Bülten        → E-posta abonelik formu
 *
 * NOT: "Biz Kimiz?" bölümü kullanıcı isteğiyle kaldırıldı (numaralamada 5 atlandı).
 * Stilleri homepage.css'de korunmaktadır.
 * =============================================================================
 */
?>
<?php get_header(); /* header.php'yi dahil et → DOCTYPE, <head>, <header> */ ?>

<?php /* ============================================================
 * BÖLÜM 1 – HERO (ANA KAYDIRICI)
 * ============================================================
 * Smart Slider 3 eklentisinin slider'ını buraya yerleştiriyoruz.
 * do_shortcode('[smartslider3 slider="2"]') → eklentinin kısa kodunu
 * çalıştırır ve slider HTML'ini üretir.
 *
 * Slider ID'si "2" → yönetici paneli → Smart Slider 3'ten değiştirilebilir.
 * section etiketi: aria-label ile erişilebilirlik sağlanır.
 * ============================================================ */ ?>
<section class="hero-slider" aria-label="<?php esc_attr_e('Ana Slider', 'utkvakfi'); ?>">
    <?php echo do_shortcode('[smartslider3 slider="2"]'); ?>
</section>

<?php /* ============================================================
 * BÖLÜM 2 – ÖNE ÇIKAN İÇERİK
 * ============================================================
 * En önemli 3 yayın büyük-küçük kart düzeniyle gösterilir.
 *
 * Yayın seçimi önceliği:
 *   1. Önce: "_featured" meta değeri "1" olan yayınlar (manuel seçim)
 *   2. Yoksa: en son 3 yayın (tarih sırasına göre)
 *
 * WP_Query ile iki ayrı sorgu yapılır:
 *   - İlk sorgu öne çıkan içerikleri arar
 *   - Sonuç yoksa ikinci sorgu son yayınları getirir
 *
 * Düzen:
 *   İlk yazı (count=0) → büyük kart (.featured-main)
 *   2. ve 3. yazı      → küçük kart (.featured-card-sm)
 *
 * wp_reset_postdata() → özel sorgu bittikten sonra ana döngüyü geri yükle.
 * ============================================================ */ ?>
<section class="section featured-section" aria-labelledby="featured-title">
    <div class="container">
        <div class="section-header">
            <span class="section-header__label"><?php esc_html_e('Öne Çıkan', 'utkvakfi'); ?></span>
            <h2 class="section-header__title" id="featured-title"><?php esc_html_e('Son Önemli Yayınlar', 'utkvakfi'); ?></h2>
        </div>
        <?php
        /* Önce öne çıkan (featured) yayınları dene */
        $featured = new WP_Query([
            'post_type'      => ['yayin', 'post'],
            'posts_per_page' => 3,
            'meta_key'       => '_featured',   /* Bu meta alanı 1 olan yazılar */
            'meta_value'     => '1',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        /* Öne çıkan yoksa son 3 yazıya dön */
        if (!$featured->have_posts()) {
            $featured = new WP_Query([
                'post_type'      => ['yayin', 'post'],
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
        }
        ?>
        <?php if ($featured->have_posts()) : ?>
        <div class="featured-grid">
            <?php
            $count = 0; /* Kaçıncı kart olduğunu takip et */
            while ($featured->have_posts()) : $featured->the_post();
                if ($count === 0) : /* İlk yazı → büyük kart */ ?>
                    <article class="featured-main" aria-labelledby="fm-title-<?php the_ID(); ?>">
                        <?php /* Arka plan görseli: dekoratif, düşük opaklık (aria-hidden) */ ?>
                        <div class="featured-main__image" aria-hidden="true">
                            <?php if (has_post_thumbnail()) : the_post_thumbnail('utkvakfi-hero', ['alt' => '']); endif; ?>
                        </div>
                        <div class="featured-main__body">
                            <span class="featured-main__type">
                                <?php
                                /*
                                 * Tür etiketi: önce "tur" taksonomisine bak.
                                 * Yoksa post type'ın tekil adını kullan (örn. "Yayın").
                                 */
                                $terms = get_the_terms(get_the_ID(), 'tur');
                                echo $terms ? esc_html($terms[0]->name) : esc_html(get_post_type_object(get_post_type())->labels->singular_name);
                                ?>
                            </span>
                            <h3 class="featured-main__title" id="fm-title-<?php the_ID(); ?>">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php /* utkvakfi_excerpt(30) → 30 kelimelik özet (helpers.php) */ ?>
                            <p class="featured-main__excerpt"><?php echo esc_html(utkvakfi_excerpt(30)); ?></p>
                            <div class="featured-main__meta">
                                <span><?php echo esc_html(get_the_date()); ?></span>
                                <span><?php echo esc_html(utkvakfi_reading_time()); ?></span>
                            </div>
                        </div>
                    </article>
                    <?php /* Sağ sütunu başlat – küçük kartlar buraya girecek */ ?>
                    <div class="featured-side">
                <?php else : /* 2. ve 3. yazı → küçük kart */ ?>
                    <article class="featured-card-sm" aria-labelledby="fsm-title-<?php the_ID(); ?>">
                        <span class="featured-card-sm__type">
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'tur');
                            echo $terms ? esc_html($terms[0]->name) : esc_html(get_post_type_object(get_post_type())->labels->singular_name);
                            ?>
                        </span>
                        <h3 class="featured-card-sm__title" id="fsm-title-<?php the_ID(); ?>">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <?php /* &middot; → orta nokta ayracı (·) */ ?>
                        <p class="featured-card-sm__meta"><?php echo esc_html(get_the_date()); ?> &middot; <?php echo esc_html(utkvakfi_reading_time()); ?></p>
                    </article>
                <?php endif;
                $count++;
            endwhile;
            wp_reset_postdata(); /* Özel WP_Query bitti, ana döngüyü geri yükle */
            ?>
            <?php /* 3'ten az öne çıkan varsa "Tüm Yayınlar" butonu göster */ ?>
            <?php if ($count < 3) : ?>
                <div class="featured-card-sm" style="display:flex;align-items:center;justify-content:center;">
                    <a href="<?php echo esc_url(get_post_type_archive_link('yayin')); ?>" class="btn btn--primary">
                        <?php esc_html_e('Tüm Yayınlar', 'utkvakfi'); ?>
                    </a>
                </div>
            <?php endif; ?>
            </div><!-- .featured-side -->
        </div><!-- .featured-grid -->
        <?php endif; ?>
    </div>
</section>

<?php /* ============================================================
 * BÖLÜM 3 – KONU NAVİGASYONU (ARAŞTIRMA ALANLARI)
 * ============================================================
 * "konu" taksonomisindeki terimleri ikon kartı olarak listeler.
 * Her kart o konunun arşiv sayfasına link verir.
 *
 * İkon sistemi:
 *   $topic_icons dizisi → slug → SVG path eşlemesi
 *   Slug eşleşirse: o konuya özel ikon gösterilir
 *   Eşleşmezse: $icon_fallback (belge ikonu) gösterilir
 *
 * Yeni konu eklendiğinde:
 *   1. WordPress'te konu taksonomisine ekle
 *   2. $topic_icons dizisine slug → SVG path çifti ekle
 *
 * sanitize_title() → slug'ı normalize eder
 *   (büyük harf, özel karakter vb. sorun yaratmasın)
 *
 * SVG ikonlar: viewBox="0 0 24 24" (Material Design ikonları boyutu)
 * fill="currentColor" → CSS renk değişkeniyle kontrol edilir
 * ============================================================ */ ?>
<section class="section topics-section" aria-labelledby="topics-title">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <span class="section-header__label"><?php esc_html_e('Araştırma Alanları', 'utkvakfi'); ?></span>
            <h2 class="section-header__title" id="topics-title"><?php esc_html_e('Konulara Göre İçerikler', 'utkvakfi'); ?></h2>
        </div>
        <?php
        /* Veritabanından konu listesi: en fazla 8, boş konular dahil */
        $konu_terms = get_terms(['taxonomy' => 'konu', 'number' => 8, 'hide_empty' => false]);

        /*
         * Konu slug'ına göre SVG ikon eşlemesi.
         * Her değer bir SVG <path d="..."> içeriğidir.
         * İkonlar Material Design ikonları seti kaynaklıdır.
         * Yeni konu eklenirse buraya slug => 'SVG_path_verisi' ekle.
         */
        $topic_icons = [
            /* Eğitim – kitap ve mezuniyet sembolü */
            'egitim'    => 'M12 3 1 9l11 6 9-4.91V17h2V9L12 3zm0 12.27L4.28 11.5 12 7.73l7.72 3.77L12 15.27zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z',
            /* Kültür – yıldız sembolü */
            'kultur'    => 'M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm4.24 16L12 15.45 7.77 18l1.12-4.81-3.73-3.23 4.92-.42L12 5l1.92 4.53 4.92.42-3.73 3.23L16.23 18z',
            /* Demokrasi – küre/dünya sembolü */
            'demokrasi' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z',
            /* Çevre – yaprak/çevre sembolü */
            'cevre'     => 'M17 8C8 10 5.9 16.17 3.82 21c-.19.45.39.86.74.5C6.05 19.96 8.5 19 12 19c5 0 9-4 9-9 0-.84-.09-1.66-.25-2.44A5.98 5.98 0 0017 8z',
            /* Ekonomi – para/finans sembolü */
            'ekonomi'   => 'M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z',
            /* Dijital – bilgisayar/ekran sembolü */
            'dijital'   => 'M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zm-8-4c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z',
            /* Toplumsal Uzlaşı – grup/insanlar sembolü */
            'toplumsal-uzlasi' => 'M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z',
            /* İnanç – kalkan/güven sembolü */
            'inanc'     => 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z',
            /* Haberler – gazete sembolü */
            'haberler'  => 'M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z',
            /* Etkinlikler – takvim sembolü */
            'etkinlikler' => 'M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z',
            /* Ziyaretler – uçak/konum sembolü */
            'ziyaretlerimiz' => 'M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z',
            /* Hukuk – kalkan sembolü */
            'hukuk'     => 'M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z',
            /* Sağlık – artı/sağlık sembolü */
            'saglik'    => 'M10.5 13H8v-3h2.5V7.5h3V10H16v3h-2.5v2.5h-3V13zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z',
            /* Tarih – saat sembolü */
            'tarih'     => 'M13 2.05v2.02c3.95.49 7 3.85 7 7.93s-3.05 7.44-7 7.93v2.02c5.05-.5 9-4.76 9-9.95S18.05 2.55 13 2.05zM11 2.05C5.95 2.55 2 6.81 2 12s3.95 9.45 9 9.95v-2.02C7.05 19.44 4 16.08 4 12s3.05-7.44 7-7.93V2.05zM12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm.5 9H11v-5.5l4.5 2.7-.75 1.23L12.5 12V15z',
        ];

        /* Eşleşen ikon bulunamazsa gösterilecek varsayılan belge ikonu */
        $icon_fallback = 'M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z';

        if (!empty($konu_terms) && !is_wp_error($konu_terms)) : ?>
            <div class="topics-grid">
                <?php foreach ($konu_terms as $term) :
                    /*
                     * sanitize_title() → slug'ı küçük harfe çevirir, özel karakterleri temizler.
                     * Bu sayede $topic_icons dizisinde güvenle arama yapabiliriz.
                     */
                    $slug      = sanitize_title($term->slug);
                    $icon_path = isset($topic_icons[$slug]) ? $topic_icons[$slug] : $icon_fallback;
                ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="topic-card">
                        <?php /* İkon dairesi: SVG path dinamik olarak ekleniyor */ ?>
                        <div class="topic-card__icon">
                            <?php /* aria-hidden: dekoratif SVG, ekran okuyucu duymasın */ ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="<?php echo esc_attr($icon_path); ?>"/>
                            </svg>
                        </div>
                        <span class="topic-card__name"><?php echo esc_html($term->name); ?></span>
                        <?php
                        /*
                         * Yayın sayısı: $term->count → bu konuda kaç içerik var?
                         * _n() → tekil/çoğul çeviri desteği
                         * "%d Yayın" → tek; "%d Yayın" → çok (Türkçede aynı)
                         */
                        ?>
                        <span class="topic-card__count">
                            <?php printf(
                                /* translators: %d: number of publications */
                                esc_html(_n('%d Yayın', '%d Yayın', $term->count, 'utkvakfi')),
                                esc_html($term->count)
                            ); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php /* ============================================================
 * BÖLÜM 4 – SON HABERLER VE ANALİZLER
 * ============================================================
 * En son 6 yayın kart ızgarasında gösterilir.
 * Kartların HTML'i template-parts/content/card.php'de tanımlanmıştır.
 *
 * WP_Query parametreleri:
 *   post_type: ['yayin', 'post'] → hem özel yayınlar hem standart blog yazıları
 *   posts_per_page: 6 → en fazla 6 yazı
 *   orderby: 'date' + order: 'DESC' → en yeni önce
 *
 * wp_reset_postdata() → özel sorgu döngüsü bittikten sonra ana döngüyü geri yükle.
 * ============================================================ */ ?>
<section class="section news-section" aria-labelledby="news-title">
    <div class="container">
        <?php /* Başlık satırı: sol başlık, sağ "Tümünü Gör" butonu */ ?>
        <div class="news-header">
            <div class="section-header" style="margin-bottom:0;">
                <span class="section-header__label"><?php esc_html_e('Güncel', 'utkvakfi'); ?></span>
                <h2 class="section-header__title" id="news-title"><?php esc_html_e('Son Haberler ve Analizler', 'utkvakfi'); ?></h2>
            </div>
            <a href="<?php echo esc_url(get_post_type_archive_link('yayin')); ?>" class="btn btn--secondary">
                <?php esc_html_e('Tümünü Gör', 'utkvakfi'); ?>
                <?php echo utkvakfi_get_svg('arrow'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </a>
        </div>
        <?php
        $recent = new WP_Query([
            'post_type'      => ['yayin', 'post'],
            'posts_per_page' => 6,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        if ($recent->have_posts()) : ?>
            <div class="card-grid">
                <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                    <?php get_template_part('template-parts/content/card'); /* card.php şablonu */ ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php /* ============================================================
 * BÖLÜM 6 – YAKLAŞAN ETKİNLİKLER
 * ============================================================
 * (Numara 5 atlandı çünkü "Biz Kimiz?" bölümü kaldırıldı)
 *
 * "etkinlik" post type'ından gelecek 4 etkinlik gösterilir.
 * Sıralama "etkinlik_tarih" meta değerine göre ASC (eskiden yeniye)
 * çünkü en yakın tarihteki etkinlik önce görünmeli.
 *
 * Her etkinlik için kullanılan özel meta alanları (ACF ile eklenir):
 *   etkinlik_tarih → "2025-06-15" formatında tarih
 *   etkinlik_yer   → "Ankara" veya "Online" gibi yer bilgisi
 *   etkinlik_saat  → "14:00" formatında saat
 *
 * date_i18n() → tarihi yerelleştirilmiş formatta basar.
 *   'j'  → gün numarası (örn. "15")
 *   'M'  → ay kısaltması (örn. "Haz")
 *   'j F Y' → tam tarih (örn. "15 Haziran 2025")
 *
 * strtotime() → "2025-06-15" gibi metni PHP zaman damgasına çevirir.
 * ============================================================ */ ?>
<section class="section events-section" aria-labelledby="events-title">
    <div class="container">
        <div class="news-header">
            <div class="section-header" style="margin-bottom:0;">
                <span class="section-header__label"><?php esc_html_e('Takvim', 'utkvakfi'); ?></span>
                <h2 class="section-header__title" id="events-title"><?php esc_html_e('Yaklaşan Etkinlikler', 'utkvakfi'); ?></h2>
            </div>
            <a href="<?php echo esc_url(get_post_type_archive_link('etkinlik')); ?>" class="btn btn--secondary">
                <?php esc_html_e('Tüm Etkinlikler', 'utkvakfi'); ?>
            </a>
        </div>
        <?php
        $events = new WP_Query([
            'post_type'      => 'etkinlik',
            'posts_per_page' => 4,
            'orderby'        => 'meta_value',        /* Meta değerine göre sırala */
            'meta_key'       => 'etkinlik_tarih',    /* Sıralama yapılacak meta alanı */
            'order'          => 'ASC',               /* En yakın tarih önce */
        ]);
        if ($events->have_posts()) : ?>
            <div class="events-list" style="margin-top: var(--space-8);">
                <?php while ($events->have_posts()) : $events->the_post();
                    /* Her etkinliğe ait özel meta alanları */
                    $tarih = get_post_meta(get_the_ID(), 'etkinlik_tarih', true);
                    $yer   = get_post_meta(get_the_ID(), 'etkinlik_yer', true);
                    $saat  = get_post_meta(get_the_ID(), 'etkinlik_saat', true);
                    $tur_terms = get_the_terms(get_the_ID(), 'tur'); /* Etkinlik türü */
                ?>
                    <a href="<?php the_permalink(); ?>" class="event-item">
                        <?php /* Takvim kutusu: gün + ay */ ?>
                        <div class="event-date" aria-label="<?php echo esc_attr($tarih ? date_i18n('j F Y', strtotime($tarih)) : get_the_date()); ?>">
                            <span class="event-date__day"><?php echo esc_html($tarih ? date_i18n('j', strtotime($tarih)) : get_the_date('j')); ?></span>
                            <span class="event-date__month"><?php echo esc_html($tarih ? date_i18n('M', strtotime($tarih)) : get_the_date('M')); ?></span>
                        </div>
                        <div class="event-info">
                            <?php /* Etkinlik türü: "Panel", "Konferans" vb. */ ?>
                            <?php if ($tur_terms && !is_wp_error($tur_terms)) : ?>
                                <div class="event-type"><?php echo esc_html($tur_terms[0]->name); ?></div>
                            <?php endif; ?>
                            <div class="event-title"><?php the_title(); ?></div>
                            <div class="event-meta">
                                <?php /* Saat: varsa ikon + saat göster */ ?>
                                <?php if ($saat) : ?>
                                    <span><?php echo utkvakfi_get_svg('clock'); echo esc_html($saat); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
                                <?php endif; ?>
                                <?php /* Yer: varsa ikon + yer göster */ ?>
                                <?php if ($yer) : ?>
                                    <span><?php echo utkvakfi_get_svg('location'); echo esc_html($yer); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <?php /* Etkinlik yoksa bilgi mesajı */ ?>
            <p style="margin-top:var(--space-8); color:#777;"><?php esc_html_e('Yaklaşan etkinlik bulunmuyor.', 'utkvakfi'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php /* ============================================================
 * BÖLÜM 7 – BÜLTEN ABONELİĞİ
 * ============================================================
 * Ziyaretçilerin e-posta adresiyle bülten abone olabileceği alan.
 *
 * İki senaryo:
 *   A. WPForms eklentisi kuruluysa ve form ID'si girilmişse:
 *      wpforms_display() ile eklentinin formu gösterilir.
 *   B. Kurulu değilse: özel basit HTML formu gösterilir.
 *
 * Özel formun çalışması:
 *   1. action → admin-post.php (WordPress'in form işleyici URL'si)
 *   2. wp_nonce_field() → güvenlik token'ı (CSRF koruması)
 *   3. action="utkvakfi_newsletter" → functions.php'deki handler'ı tetikler
 *   4. Handler e-postayı doğrular, kaydeder ve bu sayfaya geri yönlendirir
 *   5. URL'deki ?subscribed=... parametresine göre mesaj gösterilir:
 *      ?subscribed=1       → başarı mesajı (yeşil kutu)
 *      ?subscribed=invalid → hatalı e-posta (kırmızı kutu)
 *      ?subscribed=error   → sunucu hatası (kırmızı kutu)
 *
 * sanitize_text_field() → URL parametresini güvenle oku.
 * wp_unslash() → WordPress'in "magic quotes" özelliğini temizle.
 * ============================================================ */ ?>
<section class="section newsletter-section" aria-labelledby="newsletter-title">
    <div class="container">
        <div class="newsletter-inner">
            <div class="section-header">
                <span class="section-header__label"><?php esc_html_e('Güncel Kalın', 'utkvakfi'); ?></span>
                <h2 class="section-header__title" id="newsletter-title"><?php esc_html_e('Bültenimize Abone Olun', 'utkvakfi'); ?></h2>
                <p class="section-header__desc">
                    <?php esc_html_e('Yeni yayın, etkinlik ve analizlerden ilk siz haberdar olun.', 'utkvakfi'); ?>
                </p>
            </div>
            <?php
            /* WPForms form ID'si yönetici panelinden alınır */
            $form_id        = get_theme_mod('newsletter_form_id');
            /* URL'deki durum parametresi: 1=başarı, invalid=hatalı email, error=sunucu hatası */
            $subscribed_val = isset($_GET['subscribed']) ? sanitize_text_field(wp_unslash($_GET['subscribed'])) : '';

            if (function_exists('wpforms_display') && $form_id) :
                /* A. WPForms eklentisi kuruluysa eklentinin formunu göster */
                wpforms_display($form_id, false, true);
            elseif ($subscribed_val === '1') : /* Başarıyla abone olundu */ ?>
                <p class="newsletter-success">
                    <?php esc_html_e('Teşekkürler! Bültenimize başarıyla abone oldunuz.', 'utkvakfi'); ?>
                </p>
            <?php else : /* B. Kendi basit formumuzu göster */ ?>
                <?php /* Hata mesajları: form gönderiminden önce gösterilir */ ?>
                <?php if ($subscribed_val === 'invalid') : ?>
                    <p class="newsletter-error"><?php esc_html_e('Lütfen geçerli bir e-posta adresi girin.', 'utkvakfi'); ?></p>
                <?php elseif ($subscribed_val === 'error') : ?>
                    <p class="newsletter-error"><?php esc_html_e('Bir hata oluştu, lütfen tekrar deneyin.', 'utkvakfi'); ?></p>
                <?php endif; ?>
                <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                    <?php
                    /*
                     * Güvenlik token'ı (nonce):
                     * Form gönderilince functions.php'deki handler bu token'ı doğrular.
                     * Token eşleşmezse işlem reddedilir → CSRF saldırısı önlenir.
                     */
                    wp_nonce_field('utkvakfi_newsletter', 'newsletter_nonce');
                    ?>
                    <?php /* Hangi handler'ın çalışacağını belirtir */ ?>
                    <input type="hidden" name="action" value="utkvakfi_newsletter">
                    <?php /* sr-only: görünmez ama ekran okuyucular için etiket */ ?>
                    <label for="newsletter-email" class="sr-only"><?php esc_html_e('E-posta adresiniz', 'utkvakfi'); ?></label>
                    <input
                        type="email"
                        id="newsletter-email"
                        name="email"
                        class="newsletter-form__input"
                        placeholder="<?php esc_attr_e('E-posta adresiniz', 'utkvakfi'); ?>"
                        required                        <?php /* Boş bırakılamaz – tarayıcı doğrulaması */ ?>
                        autocomplete="email"            <?php /* Tarayıcı e-posta otomatik doldursun */ ?>
                    >
                    <button type="submit" class="btn btn--accent">
                        <?php esc_html_e('Abone Ol', 'utkvakfi'); ?>
                    </button>
                </form>
                <?php /* KVKK uyarısı – zorunlu bilgilendirme */ ?>
                <p class="newsletter-note"><?php esc_html_e('Verileriniz KVKK kapsamında korunmaktadır. İstediğiniz zaman aboneliğinizi iptal edebilirsiniz.', 'utkvakfi'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); /* footer.php'yi dahil et → </main>, <footer>, </body> */ ?>
