<?php
/*
 * =============================================================================
 * DOSYA: archive.php
 * =============================================================================
 * Bu dosya "arşiv" sayfalarının şablonudur.
 * Arşiv sayfası = belirli bir kategorinin, yazarın veya tarihin
 * tüm yazılarını listeleyen sayfa.
 *
 * Örneğin bu adreslerde bu şablon kullanılır:
 *   - /yayinlar/              → tüm yayınlar
 *   - /konu/egitim/           → eğitim konusundaki yayınlar
 *   - /tur/rapor/             → rapor türündeki yayınlar
 *   - /yazar/mehmet-yilmaz/   → bir yazarın tüm yazıları
 *   - /2024/                  → 2024 yılındaki yazılar
 *
 * Sayfa yapısı:
 *   ┌─────────────────────────────────────┐
 *   │  Lacivert başlık alanı (Arşiv adı) │
 *   ├─────────────────────────────────────┤
 *   │  Filtre çubuğu: [Tümü][Makale]...  │ ← sticky (sayfayla birlikte iner)
 *   ├──────────────────────────┬──────────┤
 *   │  Kart ızgarası           │ Sidebar  │
 *   │  (yazı kartları)         │ Widget'ı │
 *   ├──────────────────────────┤          │
 *   │  Sayfalama: [1][2][3]... │          │
 *   └──────────────────────────┴──────────┘
 * =============================================================================
 */
?>
<?php get_header(); /* header.php'yi dahil et */ ?>

<?php /* ============================================================
 * BAŞLIK ALANI
 * ============================================================
 * Sayfanın üstündeki lacivert banner.
 * WordPress hangi arşiv türündeyiz diye kontrol eder ve
 * uygun başlığı gösterir.
 *
 * is_post_type_archive() → "/yayinlar/" gibi post type arşivi mi?
 * is_tax()               → "/konu/egitim/" gibi taksonomi sayfası mı?
 * is_author()            → Yazar sayfası mı?
 * is_date()              → Tarih arşivi mi?
 *
 * get_queried_object() → URL'deki mevcut terim/yazar/tarih bilgisi
 * ============================================================ */ ?>
<section class="archive-header">
    <div class="container">
        <?php if (is_post_type_archive()) : ?>
            <?php /* Post type arşivi: "ARŞİV" etiketi + post type adı */ ?>
            <span class="archive-header__label"><?php esc_html_e('Arşiv', 'utkvakfi'); ?></span>
            <h1 class="archive-header__title"><?php post_type_archive_title(); ?></h1>
        <?php elseif (is_tax()) : ?>
            <?php
            /*
             * Taksonomi sayfası (konu veya tür).
             * "konu" taksonomisiyse "Konu" yazar, diğerleriyse terim tipi adı.
             * single_term_title() → mevcut terimin adını basar (örn. "Eğitim").
             * term_description() → yönetici panelinde girilen konu açıklaması.
             */
            ?>
            <span class="archive-header__label"><?php echo esc_html(get_queried_object()->taxonomy === 'konu' ? __('Konu', 'utkvakfi') : get_taxonomy_labels(get_taxonomy(get_queried_object()->taxonomy))->singular_name); ?></span>
            <h1 class="archive-header__title"><?php single_term_title(); ?></h1>
            <?php if (term_description()) : ?>
                <p class="archive-header__desc"><?php echo wp_kses_post(term_description()); ?></p>
            <?php endif; ?>
        <?php elseif (is_author()) : ?>
            <?php /* Yazar sayfası: "YAZAR" etiketi + yazar adı */ ?>
            <span class="archive-header__label"><?php esc_html_e('Yazar', 'utkvakfi'); ?></span>
            <h1 class="archive-header__title"><?php the_author(); ?></h1>
        <?php elseif (is_date()) : ?>
            <?php /* Tarih arşivi: "TARİH ARŞİVİ" + the_archive_title() otomatik başlık */ ?>
            <span class="archive-header__label"><?php esc_html_e('Tarih Arşivi', 'utkvakfi'); ?></span>
            <h1 class="archive-header__title"><?php the_archive_title(); ?></h1>
        <?php endif; ?>
    </div>
</section>

<?php
/*
 * FİLTRE ÇUBUĞU
 * ==============
 * "Tür" taksonomisindeki terimler (Makale, Rapor, Analiz vb.) buton olarak gösterilir.
 * Ziyaretçi bir türe tıklayınca sadece o türdeki içerikler görünür.
 *
 * hide_empty:true → içerikleri olmayan türleri gösterme.
 * number:10       → en fazla 10 tür göster.
 *
 * is_tax('tur') → şu an bir tür filtresi aktif mi?
 * is_tax('tur', $term->term_id) → bu buton aktif mi? (aktifse 'active' sınıfı eklenir)
 * aria-current="page" → erişilebilirlik: aktif filtreyi ekran okuyuculara belirtir.
 *
 * Filtre çubuğu yoksa (türler tanımlı değilse) hiç gösterilmez.
 */
$tur_terms = get_terms(['taxonomy' => 'tur', 'hide_empty' => true, 'number' => 10]);
if (!empty($tur_terms) && !is_wp_error($tur_terms)) : ?>
    <div class="archive-filters">
        <div class="container">
            <div class="archive-filters__inner">
                <span class="archive-filters__label"><?php esc_html_e('Tür:', 'utkvakfi'); ?></span>
                <?php /* "Tümü" butonu: herhangi bir filtre aktif değilken aktif */ ?>
                <a href="<?php echo esc_url(get_post_type_archive_link(get_post_type() ?: 'yayin')); ?>"
                   class="filter-btn<?php echo !is_tax('tur') ? ' active' : ''; ?>">
                    <?php esc_html_e('Tümü', 'utkvakfi'); ?>
                </a>
                <?php /* Her tür için bir filtre butonu */ ?>
                <?php foreach ($tur_terms as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>"
                       class="filter-btn<?php echo is_tax('tur', $term->term_id) ? ' active' : ''; ?>"
                       aria-current="<?php echo is_tax('tur', $term->term_id) ? 'page' : 'false'; ?>">
                        <?php echo esc_html($term->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container">
    <div class="archive-content"> <?php /* İki sütunlu düzen: kartlar sol, sidebar sağ */ ?>

        <?php /* Sol sütun: kart ızgarası + sayfalama */ ?>
        <div>
            <?php if (have_posts()) : ?>
                <?php /* Kart ızgarası: her yazı için card.php şablonu */ ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content/card'); /* card.php */ ?>
                    <?php endwhile; ?>
                </div>
                <?php
                /*
                 * Sayfalama
                 * ----------
                 * the_posts_pagination() → [1][2][3]...[Son] sayfa numaraları.
                 * mid_size:2 → aktif sayfanın etrafında kaç sayfa numarası göster.
                 * class:'pagination' → özel CSS sınıfı (main.css'de stilize edildi).
                 */
                the_posts_pagination(['mid_size' => 2, 'class' => 'pagination']);
            ?>
            <?php else : ?>
                <?php /* İçerik bulunamadıysa kullanıcıya bilgi ver */ ?>
                <p><?php esc_html_e('Bu kategoride içerik bulunamadı.', 'utkvakfi'); ?></p>
            <?php endif; ?>
        </div>

        <?php /* Sağ sütun: yönetici panelinden yönetilebilir widget'lar */ ?>
        <aside aria-label="<?php esc_attr_e('Arşiv yan paneli', 'utkvakfi'); ?>">
            <?php
            /*
             * dynamic_sidebar('sidebar-archive') → arşiv sidebar'ı.
             * Yönetici paneli → Görünüm → Widget'lar → "Arşiv Sidebar"
             * Widget alanına eklenenleri buraya basar.
             */
            dynamic_sidebar('sidebar-archive');
            ?>
        </aside>

    </div>
</div>

<?php get_footer(); /* footer.php'yi dahil et */ ?>
