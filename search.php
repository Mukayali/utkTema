<?php
/*
 * =============================================================================
 * DOSYA: search.php
 * =============================================================================
 * Arama sonuçları sayfası. /?s=arama+terimi adresinde çalışır.
 * Tüm kayıtlı içerik tiplerini (yayin, etkinlik, medya, proje vb.) kapsar.
 * =============================================================================
 */
defined('ABSPATH') || exit;

get_header();

$search_query = get_search_query();
$result_count = $wp_query->found_posts;
?>

<section class="archive-header">
    <div class="container">
        <span class="archive-header__label"><?php esc_html_e('Arama Sonuçları', 'utkvakfi'); ?></span>
        <h1 class="archive-header__title">
            <?php
            if ($search_query) {
                printf(
                    /* translators: %s: search term */
                    esc_html__('"%s" için sonuçlar', 'utkvakfi'),
                    esc_html($search_query)
                );
            } else {
                esc_html_e('Arama', 'utkvakfi');
            }
            ?>
        </h1>
        <?php if ($search_query) : ?>
            <p class="archive-header__desc">
                <?php
                printf(
                    /* translators: %d: number of results */
                    esc_html(_n('%d sonuç bulundu', '%d sonuç bulundu', $result_count, 'utkvakfi')),
                    (int) $result_count
                );
                ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<div class="container">

    <?php /* Arama formu – yeni arama yapılabilsin */ ?>
    <div class="search-page__form">
        <?php get_search_form(); ?>
    </div>

    <div class="archive-content">
        <div>
            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content/card'); ?>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination(['mid_size' => 2, 'class' => 'pagination']); ?>
            <?php else : ?>
                <div class="search-page__no-results">
                    <p>
                        <?php
                        printf(
                            /* translators: %s: search term */
                            esc_html__('"%s" için herhangi bir sonuç bulunamadı.', 'utkvakfi'),
                            esc_html($search_query)
                        );
                        ?>
                    </p>
                    <p><?php esc_html_e('Farklı anahtar kelimeler deneyebilir veya aşağıdaki konulara göz atabilirsiniz.', 'utkvakfi'); ?></p>
                    <?php
                    $konu_terms = get_terms(['taxonomy' => 'konu', 'hide_empty' => true, 'number' => 8]);
                    if (!empty($konu_terms) && !is_wp_error($konu_terms)) : ?>
                        <div class="search-page__topics">
                            <?php foreach ($konu_terms as $term) : ?>
                                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="tag">
                                    <?php echo esc_html($term->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
