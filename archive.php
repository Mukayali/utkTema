<?php get_header(); ?>

<section class="archive-header">
    <div class="container">
        <?php if (is_post_type_archive()) : ?>
            <span class="archive-header__label"><?php esc_html_e('Arşiv', 'utkvakfi'); ?></span>
            <h1 class="archive-header__title"><?php post_type_archive_title(); ?></h1>
        <?php elseif (is_tax()) : ?>
            <span class="archive-header__label"><?php echo esc_html(get_queried_object()->taxonomy === 'konu' ? __('Konu', 'utkvakfi') : get_taxonomy_labels(get_taxonomy(get_queried_object()->taxonomy))->singular_name); ?></span>
            <h1 class="archive-header__title"><?php single_term_title(); ?></h1>
            <?php if (term_description()) : ?>
                <p class="archive-header__desc"><?php echo wp_kses_post(term_description()); ?></p>
            <?php endif; ?>
        <?php elseif (is_author()) : ?>
            <span class="archive-header__label"><?php esc_html_e('Yazar', 'utkvakfi'); ?></span>
            <h1 class="archive-header__title"><?php the_author(); ?></h1>
        <?php elseif (is_date()) : ?>
            <span class="archive-header__label"><?php esc_html_e('Tarih Arşivi', 'utkvakfi'); ?></span>
            <h1 class="archive-header__title"><?php the_archive_title(); ?></h1>
        <?php endif; ?>
    </div>
</section>

<?php
$tur_terms = get_terms(['taxonomy' => 'tur', 'hide_empty' => true, 'number' => 10]);
if (!empty($tur_terms) && !is_wp_error($tur_terms)) : ?>
    <div class="archive-filters">
        <div class="container">
            <div class="archive-filters__inner">
                <span class="archive-filters__label"><?php esc_html_e('Tür:', 'utkvakfi'); ?></span>
                <a href="<?php echo esc_url(get_post_type_archive_link(get_post_type() ?: 'yayin')); ?>"
                   class="filter-btn<?php echo !is_tax('tur') ? ' active' : ''; ?>">
                    <?php esc_html_e('Tümü', 'utkvakfi'); ?>
                </a>
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
                <p><?php esc_html_e('Bu kategoride içerik bulunamadı.', 'utkvakfi'); ?></p>
            <?php endif; ?>
        </div>
        <aside aria-label="<?php esc_attr_e('Arşiv yan paneli', 'utkvakfi'); ?>">
            <?php dynamic_sidebar('sidebar-archive'); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
