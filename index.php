<?php get_header(); ?>

<section class="archive-header">
    <div class="container">
        <span class="archive-header__label"><?php esc_html_e('Tüm İçerikler', 'utkvakfi'); ?></span>
        <h1 class="archive-header__title"><?php esc_html_e('Son Yayınlar', 'utkvakfi'); ?></h1>
    </div>
</section>

<div class="container">
    <div class="archive-content">
        <div>
            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content/card', get_post_type()); ?>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination(['mid_size' => 2, 'class' => 'pagination']); ?>
            <?php else : ?>
                <p><?php esc_html_e('İçerik bulunamadı.', 'utkvakfi'); ?></p>
            <?php endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
