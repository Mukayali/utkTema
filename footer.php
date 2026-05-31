<?php
/*
 * =============================================================================
 * DOSYA: footer.php
 * =============================================================================
 * Bu dosya sitenin HER sayfasında görünen alt bölümünün (footer) şablonudur.
 *
 * Tıpkı bir gazetenin son sayfasındaki künyesi gibi:
 * logo, linkler, iletişim bilgileri ve telif hakkı notu burada yer alır.
 *
 * WordPress, sayfanın asıl içeriğini bitirdikten sonra bu dosyayı çalıştırır.
 * get_footer() çağrısıyla her sayfa şablonundan dahil edilir.
 *
 * Bu dosyada bulunanlar:
 *   1. </main>     → header.php'de açılan ana içerik alanı burada kapanır
 *   2. <footer>    → Alt bilgi şeridi (4 kolon + telif çubuğu)
 *      Kolon 1     → Logo + misyon cümlesi + sosyal medya
 *      Kolon 2     → Hızlı linkler menüsü
 *      Kolon 3     → Konu taksonomisi listesi
 *      Kolon 4     → Adres / e-posta / telefon
 *      Alt çubuk   → Telif hakkı + yasal linkler
 *   3. wp_footer() → WordPress eklentilerinin JS kodlarını eklemesi için
 *   4. </body>     → HTML belgesi kapanışı
 * =============================================================================
 */
?>
</main><!-- #main-content – header.php'de açılan <main> etiketi burada kapanır -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-main">

            <?php /* ============================================================
             * KOLON 1 – MARKA
             * ============================================================
             * Sol köşede logo, vakfın kısa misyon cümlesi ve
             * Twitter/X, LinkedIn, YouTube ikonları yer alır.
             * Sosyal medya URL'leri yönetici panelinden ayarlanır:
             *   Görünüm → Özelleştir → Footer ayarları → Sosyal medya
             * ============================================================ */ ?>
            <div class="footer-brand">
                <?php /* Logo: görsel varsa görsel, yoksa site adı */ ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo" rel="home" aria-label="<?php bloginfo('name'); ?> – Ana Sayfa">
                    <?php if (has_custom_logo()) :
                        the_custom_logo();
                    else : ?>
                        <span class="footer-logo__name"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>

                <?php
                /*
                 * Misyon cümlesi: Yönetici → Özelleştir → footer_mission ayarı.
                 * Ayar yoksa varsayılan Türkçe metin gösterilir.
                 * esc_html() → XSS koruması için çıktıyı güvenle bas.
                 */
                ?>
                <p class="footer-mission">
                    <?php echo esc_html(get_theme_mod('footer_mission', __('Türkiye\'de uzlaşı kültürünü ve toplumsal kalkınmayı destekleyen bağımsız bir vakıf.', 'utkvakfi'))); ?>
                </p>

                <?php
                /*
                 * Sosyal medya ikonları
                 * ----------------------
                 * Her platform için URL, etiket ve SVG ikon tanımlanmıştır.
                 * get_theme_mod("social_{$key}", '#') → yönetici panelindeki URL
                 * URL boşsa '#' (sayfa yenilemez) gösterilir.
                 * SVG'ler fill="currentColor" ile CSS color özelliğinden renk alır.
                 */
                ?>
                <ul class="footer-social" aria-label="<?php esc_attr_e('Sosyal medya linkleri', 'utkvakfi'); ?>">
                    <?php
                    $socials = [
                        'twitter'  => ['label' => 'Twitter/X',  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.257 5.629 5.907-5.629zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
                        'linkedin' => ['label' => 'LinkedIn',   'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>'],
                        'youtube'  => ['label' => 'YouTube',    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'],
                    ];
                    foreach ($socials as $key => $data) {
                        $url = get_theme_mod("social_{$key}", '#');
                        printf(
                            '<li><a href="%s" rel="noopener noreferrer" target="_blank" aria-label="%s">%s</a></li>',
                            esc_url($url),
                            esc_attr($data['label']),
                            $data['icon'] // phpcs:ignore WordPress.Security.EscapeOutput
                        );
                    }
                    ?>
                </ul>
            </div>

            <?php /* ============================================================
             * KOLON 2 – HIZLI LİNKLER
             * ============================================================
             * Yönetici panelinden atanan "footer-1" menüsü buraya gelir.
             * wp_nav_menu() ile WordPress menü sistemini kullanıyoruz.
             * depth:1 → sadece üst seviye linkler (alt menü olmadan).
             * fallback_cb:false → menü atanmamışsa hiçbir şey gösterme.
             * ============================================================ */ ?>
            <div>
                <h3 class="footer-col__title"><?php esc_html_e('Hızlı Linkler', 'utkvakfi'); ?></h3>
                <?php wp_nav_menu([
                    'theme_location' => 'footer-1',
                    'menu_class'     => 'footer-col__list',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]); ?>
            </div>

            <?php /* ============================================================
             * KOLON 3 – KONULAR
             * ============================================================
             * "konu" taksonomisindeki ilk 6 terimi listeler.
             * Konular WordPress veritabanına kayıtlıysa oradan gelir;
             * yoksa "footer-2" konumundaki menü fallback olarak gösterilir.
             *
             * get_terms() → veritabanından konu listesi çeker.
             * hide_empty:false → içerik olmasa bile konuları göster.
             * is_wp_error() → veritabanı hatası varsa listeyi gösterme.
             * ============================================================ */ ?>
            <div>
                <h3 class="footer-col__title"><?php esc_html_e('Konular', 'utkvakfi'); ?></h3>
                <?php
                $konu_terms = get_terms(['taxonomy' => 'konu', 'number' => 6, 'hide_empty' => false]);
                if (!empty($konu_terms) && !is_wp_error($konu_terms)) : ?>
                    <ul class="footer-col__list">
                        <?php foreach ($konu_terms as $term) : ?>
                            <li>
                                <?php /* get_term_link() → o konunun arşiv sayfasına URL */ ?>
                                <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <?php /* Konular veritabanında yoksa yönetici paneli menüsüne dön */ ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer-2',
                        'menu_class'     => 'footer-col__list',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]); ?>
                <?php endif; ?>
            </div>

            <?php /* ============================================================
             * KOLON 4 – İLETİŞİM
             * ============================================================
             * Adres, e-posta ve telefon bilgileri.
             * Her biri yönetici panelinden ayarlanır:
             *   Görünüm → Özelleştir → İletişim bilgileri
             *
             * get_theme_mod() → temaya özel yönetici paneli ayarını getirir.
             * Ayar boşsa bu <li> hiç oluşturulmaz (koşullu gösterim).
             * ============================================================ */ ?>
            <div>
                <h3 class="footer-col__title"><?php esc_html_e('İletişim', 'utkvakfi'); ?></h3>
                <ul class="footer-contact__list">
                    <?php if ($address = get_theme_mod('contact_address')) : ?>
                    <li class="footer-contact__item">
                        <?php echo utkvakfi_get_svg('pin'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                        <span><?php echo esc_html($address); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if ($email = get_theme_mod('contact_email')) : ?>
                    <li class="footer-contact__item">
                        <?php echo utkvakfi_get_svg('mail'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                        <?php /* mailto: linki → tıklanınca e-posta uygulaması açılır */ ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if ($phone = get_theme_mod('contact_phone')) : ?>
                    <li class="footer-contact__item">
                        <?php echo utkvakfi_get_svg('phone'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                        <?php
                        /*
                         * tel: linki → mobil cihazlarda tıklanınca arama başlatır.
                         * preg_replace('/[^+\d]/', '', $phone) → boşluk ve tire gibi
                         * karakterleri kaldırır, sadece rakam ve + kalır.
                         * Görünen metin orijinal formatta kalır (örn. "+90 212 123 45 67")
                         */
                        ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div><!-- .footer-main -->

        <?php /* ============================================================
         * ALT ÇUBUK – TELİF HAKKI + YASAL LİNKLER
         * ============================================================
         * En alttaki ince şerit:
         * Sol: © 2025 UTK Vakfı. Tüm hakları saklıdır.
         * Sağ: Gizlilik Politikası | KVKK | Site Haritası
         *
         * date_i18n('Y') → sistemin yerelleştirme ayarına göre yılı basar.
         * get_bloginfo('name') → WordPress ayarlarındaki site adı.
         * ============================================================ */ ?>
        <div class="footer-bottom">
            <p class="footer-bottom__copy">
                <?php
                /*
                 * printf ile formatlanmış çıktı: "%1$s" yıl, "%2$s" site adı.
                 * esc_html__() → çeviri desteği sağlar ve HTML karakterlerini kaçırır.
                 */
                printf(
                    esc_html__('© %1$s %2$s. Tüm hakları saklıdır.', 'utkvakfi'),
                    esc_html(date_i18n('Y')),
                    esc_html(get_bloginfo('name'))
                );
                ?>
            </p>
            <ul class="footer-bottom__links">
                <?php /* get_privacy_policy_url() → WordPress gizlilik sayfasının URL'i */ ?>
                <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Gizlilik Politikası', 'utkvakfi'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/kvkk/')); ?>">KVKK</a></li>
                <li><a href="<?php echo esc_url(home_url('/site-haritasi/')); ?>"><?php esc_html_e('Site Haritası', 'utkvakfi'); ?></a></li>
            </ul>
        </div>

    </div>
</footer>

<?php
/*
 * wp_footer() → WordPress'in büyülü kancası (footer versiyonu).
 * Bu tek satır şunları otomatik ekler:
 *   - Footer'da yüklenmesi gereken JS dosyaları
 *   - Google Analytics, Facebook Pixel gibi izleme kodları
 *   - Eklentilerin kendi JS/HTML çıktıları
 * wp_enqueue_script() çağrılarında 'in_footer: true' ayarı varsa
 * script buraya eklenir (sayfanın altı → daha hızlı yükleme).
 */
wp_footer();
?>
</body>
</html>
