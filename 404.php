<?php get_header(); ?>

<div class="container">
    <div class="error-page">
        <div>
            <div class="error-page__code" aria-hidden="true">404</div>
            <h1 class="error-page__title"><?php esc_html_e('Sayfa Bulunamadı', 'utkvakfi'); ?></h1>
            <p class="error-page__desc">
                <?php esc_html_e('Aradığınız sayfa taşınmış, silinmiş ya da hiç var olmamış olabilir. Ana sayfaya dönüp tekrar deneyebilirsiniz.', 'utkvakfi'); ?>
            </p>
            <div style="display:flex;gap:var(--space-4);justify-content:center;flex-wrap:wrap;">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                    <?php esc_html_e('Ana Sayfaya Dön', 'utkvakfi'); ?>
                </a>
                <a href="<?php echo esc_url(get_post_type_archive_link('yayin')); ?>" class="btn btn--secondary">
                    <?php esc_html_e('Yayınlara Göz At', 'utkvakfi'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
