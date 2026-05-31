<?php
/*
 * =============================================================================
 * DOSYA: single.php
 * =============================================================================
 * Bu dosya tek bir yazı/yayın sayfasının HTML şablonudur.
 * Yani birine "https://utkvakfi.org/bir-makale/" adresini gönderdiğinizde
 * açılan sayfa bu şablonla oluşturulur.
 *
 * Tıpkı bir gazetedeki tam sayfa makale gibi:
 * yazar fotoğrafı, başlık, içerik, paylaşım butonları ve
 * ilgili yayınlar hepsi burada bir araya gelir.
 *
 * Sayfa düzeni (2 sütunlu):
 *   ┌──────────────────────────────┬──────────────┐
 *   │  Breadcrumb (sayfa yolu)     │              │
 *   │  Etiketler + Başlık          │   Yazar      │
 *   │  Yazar + Tarih + Okuma süresi│   Widget     │
 *   │  Öne çıkan görsel            │              │
 *   │  PDF indirme (varsa)         │   Konular    │
 *   │  Makale içeriği              │   Widget     │
 *   │  Paylaşım butonları          │              │
 *   │  İlgili yayınlar             │   Widget     │
 *   └──────────────────────────────┴──────────────┘
 *
 * WordPress döngüsü (The Loop):
 *   have_posts() → yazı var mı?
 *   the_post()   → o yazıyı aktif yap
 * Bu şablonda sadece 1 yazı olduğu için döngü tek kez döner.
 * =============================================================================
 */
?>
<?php get_header(); /* header.php'yi dahil et → DOCTYPE, <head>, <header> */ ?>

<?php while (have_posts()) : the_post(); /* Aktif yazıyı yükle */ ?>

<div class="container">
    <div class="single-layout"> <?php /* İki sütunlu düzen: makale sol, sidebar sağ */ ?>
        <article class="article" aria-labelledby="article-title"> <?php /* aria-labelledby → başlıkla ilişkilendirir */ ?>

            <?php /* ============================================================
             * BREADCRUMB – SAYFA YOLU
             * ============================================================
             * "Ana Sayfa → Yayınlar → Makale Başlığı" formatında
             * kullanıcının sitenin neresinde olduğunu gösterir.
             * Hem navigasyon kolaylığı hem SEO için değerlidir.
             *
             * get_post_type_archive_link() → "Yayınlar" listesine URL
             * get_post_type_object()->labels->name → "Yayınlar" gibi çoğul ad
             * aria-label="Sayfa yolu" → ekran okuyuculara bu nav'ın amacını söyler
             * aria-current="page" → son öğe mevcut sayfa olduğunu belirtir
             * ============================================================ */ ?>
            <nav class="article-breadcrumb" aria-label="<?php esc_attr_e('Sayfa yolu', 'utkvakfi'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Ana Sayfa', 'utkvakfi'); ?></a>
                <?php echo utkvakfi_get_svg('arrow'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                <a href="<?php echo esc_url(get_post_type_archive_link(get_post_type())); ?>">
                    <?php echo esc_html(get_post_type_object(get_post_type())->labels->name); ?>
                </a>
                <?php echo utkvakfi_get_svg('arrow'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                <span aria-current="page"><?php the_title(); ?></span>
            </nav>

            <?php /* ============================================================
             * MAKALE BAŞLIK ALANI
             * ============================================================
             * Etiketler + büyük başlık + yazar bilgisi + tarih + okuma süresi
             * ============================================================ */ ?>
            <header class="article-header">

                <?php /* Etiketler satırı: konu etiketleri + tür etiketi */ ?>
                <div class="article-tags">
                    <?php
                    /*
                     * utkvakfi_konu_tags() → helpers.php'deki yardımcı fonksiyon.
                     * Bu yazıya atanmış "konu" taksonomisi terimlerini
                     * tıklanabilir etiket olarak basar.
                     */
                    utkvakfi_konu_tags();
                    ?>
                    <?php
                    /*
                     * "tur" taksonomisi etiketleri: "Makale", "Rapor" gibi.
                     * Bunlar lacivert arka planlı beyaz metin olarak gösterilir
                     * (konu etiketlerinden görsel olarak ayırt edilsin diye).
                     */
                    $tur_terms = get_the_terms(get_the_ID(), 'tur');
                    if ($tur_terms && !is_wp_error($tur_terms)) :
                        foreach ($tur_terms as $term) :
                            printf('<a href="%s" class="tag" style="background:var(--color-primary);color:white;">%s</a>',
                                esc_url(get_term_link($term)),
                                esc_html($term->name)
                            );
                        endforeach;
                    endif;
                    ?>
                </div>

                <?php /* Ana başlık: id="article-title" → article'ın aria-labelledby ile eşleşir */ ?>
                <h1 class="article-title" id="article-title"><?php the_title(); ?></h1>

                <?php /* Yazar + Tarih + Okuma süresi satırı */ ?>
                <div class="article-meta">
                    <?php
                    /*
                     * Yazar bilgileri:
                     *   get_post_field('post_author') → yazıya bağlı kullanıcı ID'si
                     *   get_avatar_url() → Gravatar profil fotoğrafı URL'si
                     *   get_the_author_meta('kurum') → ACF/custom user meta'dan kurum adı
                     */
                    $author_id = get_post_field('post_author');
                    $author_avatar = get_avatar_url($author_id, ['size' => 88]); /* 88px → 2x için 44px gösterilir */
                    ?>
                    <?php /* Yazar fotoğrafı + isim + kurum/unvan → tıklanınca yazarın tüm yazılarına gider */ ?>
                    <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="article-meta__author">
                        <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr(get_the_author_meta('display_name', $author_id)); ?>" width="44" height="44" loading="lazy">
                        <div class="article-meta__author-info">
                            <span class="article-meta__author-name"><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></span>
                            <span class="article-meta__author-title"><?php echo esc_html(get_the_author_meta('kurum', $author_id)); ?></span>
                        </div>
                    </a>
                    <div class="article-meta__info">
                        <?php /* Yayın tarihi: datetime özelliği arama motorları için ISO 8601 formatı */ ?>
                        <span>
                            <?php echo utkvakfi_get_svg('calendar'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                        </span>
                        <?php
                        /*
                         * utkvakfi_reading_time() → helpers.php'deki fonksiyon.
                         * Yazının kelime sayısına bakarak "5 dakika okuma" gibi süre hesaplar.
                         */
                        ?>
                        <span>
                            <?php echo utkvakfi_get_svg('clock'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                            <?php echo esc_html(utkvakfi_reading_time()); ?>
                        </span>
                    </div>
                </div>
            </header>

            <?php /* ============================================================
             * ÖNE ÇIKAN GÖRSEL
             * ============================================================
             * Yazıya eklenen kapak görseli tam genişlikte gösterilir.
             * 'utkvakfi-hero' → functions.php'de tanımlanan 1200x630px boyutu.
             * Görsel varsa <figure> oluşturulur; yoksa bu blok hiç çıkmaz.
             * Görsel altyazısı (caption) varsa <figcaption> da eklenir.
             * ============================================================ */ ?>
            <?php if (has_post_thumbnail()) : ?>
                <figure class="article-featured-image">
                    <?php the_post_thumbnail('utkvakfi-hero'); ?>
                    <?php $caption = get_the_post_thumbnail_caption(); if ($caption) : ?>
                        <figcaption><?php echo esc_html($caption); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <?php /* ============================================================
             * PDF İNDİRME KUTUSU
             * ============================================================
             * Yazıya ACF/özel meta ile bir PDF dosyası eklenirse
             * içerik üstünde indirme butonu gösterilir.
             * 'pdf_url' → post meta anahtarı (ACF ile yönetici panelinden eklenir).
             * Değer yoksa bu blok hiç çıkmaz.
             * ============================================================ */ ?>
            <?php $pdf_url = get_post_meta(get_the_ID(), 'pdf_url', true); if ($pdf_url) : ?>
                <div class="article-download">
                    <div class="article-download__icon">
                        <?php echo utkvakfi_get_svg('download'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </div>
                    <div class="article-download__info">
                        <div class="article-download__title"><?php esc_html_e('Bu yayını PDF olarak indirin', 'utkvakfi'); ?></div>
                        <div class="article-download__size"><?php esc_html_e('Ücretsiz erişim', 'utkvakfi'); ?></div>
                    </div>
                    <?php /* download → tarayıcı sayfada açmak yerine indir; rel="noopener" → güvenlik */ ?>
                    <a href="<?php echo esc_url($pdf_url); ?>" class="btn btn--primary" download rel="noopener">
                        <?php esc_html_e('PDF İndir', 'utkvakfi'); ?>
                        <?php echo utkvakfi_get_svg('download'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php /* ============================================================
             * MAKALE İÇERİĞİ
             * ============================================================
             * the_content() → WordPress editöründe yazılan tüm içeriği basar.
             * Gutenberg blokları, görseller, başlıklar, listeler hepsi burada.
             * single.css'teki .article-content kuralları bu içeriği stilize eder.
             * ============================================================ */ ?>
            <div class="article-content">
                <?php the_content(); ?>
            </div>

            <?php /* ============================================================
             * PAYLAŞIM BUTONLARI
             * ============================================================
             * Twitter/X, LinkedIn ve WhatsApp'a paylaşım linkleri.
             *
             * rawurlencode() → URL'i encode eder (boşluk → %20 gibi).
             * Her platform kendi paylaşım URL'i formatını ister:
             *   Twitter  → intent/tweet?url=...&text=...
             *   LinkedIn → share-offsite/?url=...
             *   WhatsApp → send?text=...
             *
             * target="_blank" → yeni sekmede açılır.
             * rel="noopener noreferrer" → güvenlik (açılan sayfa ana pencereye erişemesin).
             * ============================================================ */ ?>
            <div class="article-share" aria-label="<?php esc_attr_e('Paylaşım butonları', 'utkvakfi'); ?>">
                <span class="article-share__label"><?php esc_html_e('Paylaş:', 'utkvakfi'); ?></span>
                <div class="article-share__links">
                    <?php
                    $url   = rawurlencode(get_permalink());
                    $title = rawurlencode(get_the_title());
                    $shares = [
                        ['Twitter/X', "https://twitter.com/intent/tweet?url={$url}&text={$title}", '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.257 5.629 5.907-5.629zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
                        ['LinkedIn', "https://www.linkedin.com/sharing/share-offsite/?url={$url}", '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>'],
                        ['WhatsApp', "https://api.whatsapp.com/send?text={$title}%20{$url}", '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>'],
                    ];
                    foreach ($shares as [$label, $href, $icon]) :
                        printf(
                            '<a href="%s" class="article-share__link" target="_blank" rel="noopener noreferrer" aria-label="%s">%s %s</a>',
                            esc_url($href),
                            esc_attr($label . ' ile paylaş'),
                            $icon, // phpcs:ignore WordPress.Security.EscapeOutput
                            esc_html($label)
                        );
                    endforeach;
                    ?>
                </div>
            </div>

            <?php /* ============================================================
             * İLGİLİ YAYINLAR
             * ============================================================
             * Bu yazıyla aynı "konu" etiketini paylaşan diğer yayınlar.
             * Ziyaretçiyi sitede tutmak ve içerikler arası bağlantı kurmak için.
             *
             * Adımlar:
             * 1. Bu yazıya atanmış konu terimlerinin ID'lerini al
             * 2. Aynı konulardaki diğer yazıları sorgula (bu yazı hariç)
             * 3. Varsa card şablonuyla göster
             *
             * wp_reset_postdata() → ilgili yayınlar sorgusunu bitirdikten sonra
             * ana WordPress döngüsünü geri yükler (önemli!).
             * ============================================================ */ ?>
            <?php
            $konu_ids = wp_get_post_terms(get_the_ID(), 'konu', ['fields' => 'ids']);
            if (!empty($konu_ids)) :
                $related = new WP_Query([
                    'post_type'      => get_post_type(),
                    'posts_per_page' => 3,
                    'post__not_in'   => [get_the_ID()], /* Mevcut yazıyı dışla */
                    'tax_query'      => [['taxonomy' => 'konu', 'field' => 'term_id', 'terms' => $konu_ids]],
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ]);
                if ($related->have_posts()) : ?>
                    <div class="related-posts">
                        <h2 class="related-posts__title"><?php esc_html_e('İlgili Yayınlar', 'utkvakfi'); ?></h2>
                        <div class="card-grid">
                            <?php while ($related->have_posts()) : $related->the_post(); ?>
                                <?php get_template_part('template-parts/content/card'); /* card.php şablonu */ ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endif;
            endif; ?>

        </article>

        <?php /* ============================================================
         * SIDEBAR – YAN PANEL
         * ============================================================
         * Makalenin sağ tarafındaki dar sütun.
         * İçerir:
         *   1. Yazar Widget   → Yazar biyografisi ve "Tüm Yazılar" butonu
         *   2. Konular Widget → Etiket bulutu formatında konu linkleri
         *   3. Dinamik Widget → Yönetici panelinden eklenebilir widget'lar
         * ============================================================ */ ?>
        <aside class="article-sidebar" aria-label="<?php esc_attr_e('Yan panel', 'utkvakfi'); ?>">

            <?php /* ============================================================
             * YAZAR WİDGET'I
             * ============================================================
             * Yazarın biyografisi ("Hakkımda" alanı) doluysa gösterilir.
             * Boşsa widget hiç oluşturulmaz (sessiz).
             *
             * get_the_author_meta('description') → WordPress profil sayfasındaki
             *   "Hakkımda" alanının içeriği.
             * ============================================================ */ ?>
            <?php
            $author_id = get_post_field('post_author');
            $bio = get_the_author_meta('description', $author_id);
            if ($bio) : ?>
                <div class="sidebar-widget author-widget">
                    <h2 class="sidebar-widget__title"><?php esc_html_e('Yazar', 'utkvakfi'); ?></h2>
                    <?php /* 80x80 yazar fotoğrafı (160px URL, @2x ekranlar için) */ ?>
                    <img src="<?php echo esc_url(get_avatar_url($author_id, ['size' => 160])); ?>"
                         alt="<?php echo esc_attr(get_the_author_meta('display_name', $author_id)); ?>"
                         class="author-widget__img" width="80" height="80" loading="lazy">
                    <div class="author-widget__name"><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></div>
                    <div class="author-widget__role"><?php echo esc_html(get_the_author_meta('kurum', $author_id)); ?></div>
                    <p class="author-widget__bio"><?php echo esc_html($bio); ?></p>
                    <?php /* "Tüm Yazılar" butonu yazarın arşiv sayfasına götürür */ ?>
                    <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="btn btn--secondary" style="width:100%;justify-content:center;">
                        <?php esc_html_e('Tüm Yazılar', 'utkvakfi'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php /* ============================================================
             * KONULAR WİDGET'I
             * ============================================================
             * En fazla 8 konu etiket formatında listelenir.
             * hide_empty:true → içerikleri olmayan konular gösterilmez.
             * ============================================================ */ ?>
            <?php
            $sidebar_konular = get_terms(['taxonomy' => 'konu', 'number' => 8, 'hide_empty' => true]);
            if (!empty($sidebar_konular) && !is_wp_error($sidebar_konular)) : ?>
                <div class="sidebar-widget">
                    <h2 class="sidebar-widget__title"><?php esc_html_e('Konular', 'utkvakfi'); ?></h2>
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);">
                        <?php foreach ($sidebar_konular as $term) : ?>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="tag"><?php echo esc_html($term->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            /*
             * dynamic_sidebar('sidebar-main') → WordPress widget sistemi.
             * Yönetici paneli → Görünüm → Widget'lar bölümünden
             * "Ana Sidebar"a eklenen tüm widget'ları buraya basar.
             * Örnek: son yazılar, arama kutusu, özel HTML...
             */
            dynamic_sidebar('sidebar-main');
            ?>

        </aside>
    </div>
</div>

<?php endwhile; /* WordPress döngüsü sonu */ ?>

<?php get_footer(); /* footer.php'yi dahil et → </main>, <footer>, </body> */ ?>
