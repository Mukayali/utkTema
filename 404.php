<?php
/*
 * =============================================================================
 * DOSYA: 404.php
 * =============================================================================
 * Bu dosya, ziyaretçi var olmayan bir sayfaya girdiğinde gösterilen
 * "Sayfa Bulunamadı" hata sayfasının şablonudur.
 *
 * Tıpkı bir postanenin "Böyle bir adres yok, dönün" mektubu gibi:
 * ziyaretçiye nazikçe yanlış adreste olduğunu söyler ve yönlendirir.
 *
 * 404 kodu ne demek?
 * HTTP 404 = sunucu isteği aldı ama sayfa bulunamadı.
 * WordPress bu dosyayı otomatik kullanır; bizim bir şey yapmamız gerekmez.
 *
 * Sayfada neler var?
 *   - Büyük dekoratif "404" rakamı (görsel vurgu için, ekran okuyucular duymasın)
 *   - "Sayfa Bulunamadı" başlığı
 *   - Açıklama metni (neden olmuş olabilir?)
 *   - İki yönlendirme butonu:
 *       1. "Ana Sayfaya Dön" → lacivert dolu buton
 *       2. "Yayınlara Göz At" → ikincil buton
 * =============================================================================
 */
?>
<?php get_header(); /* header.php'yi dahil et */ ?>

<div class="container">
    <div class="error-page">
        <div>
            <?php
            /*
             * Büyük "404" rakamı: sadece görsel amaçlı.
             * aria-hidden="true" → ekran okuyucular "dört yüz dört" demesin,
             * zaten başlıkta "Sayfa Bulunamadı" yazıyor.
             */
            ?>
            <div class="error-page__code" aria-hidden="true">404</div>

            <?php /* Ana mesaj başlığı */ ?>
            <h1 class="error-page__title"><?php esc_html_e('Sayfa Bulunamadı', 'utkvakfi'); ?></h1>

            <?php /* Açıklama: ne olmuş olabilir ve ne yapabilirler */ ?>
            <p class="error-page__desc">
                <?php esc_html_e('Aradığınız sayfa taşınmış, silinmiş ya da hiç var olmamış olabilir. Ana sayfaya dönüp tekrar deneyebilirsiniz.', 'utkvakfi'); ?>
            </p>

            <?php /* İki buton yan yana (flexbox ile, mobilde alt alta) */ ?>
            <div style="display:flex;gap:var(--space-4);justify-content:center;flex-wrap:wrap;">
                <?php /* Birincil buton: ana sayfaya dön */ ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                    <?php esc_html_e('Ana Sayfaya Dön', 'utkvakfi'); ?>
                </a>
                <?php
                /*
                 * İkincil buton: yayın arşivine git.
                 * get_post_type_archive_link('yayin') → /yayinlar/ URL'ini döner.
                 * 'yayin' custom post type'ı cpt.php'de tanımlanmıştır.
                 */
                ?>
                <a href="<?php echo esc_url(get_post_type_archive_link('yayin')); ?>" class="btn btn--secondary">
                    <?php esc_html_e('Yayınlara Göz At', 'utkvakfi'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); /* footer.php'yi dahil et */ ?>
