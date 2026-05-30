<?php get_header(); ?>

<!-- 1. HERO – Smart Slider 3 -->
<section class="hero-slider" aria-label="<?php esc_attr_e('Ana Slider', 'utkvakfi'); ?>">
    <?php echo do_shortcode('[smartslider3 slider="2"]'); ?>
</section>

<!-- 2. ÖNE ÇIKAN İÇERİK -->
<section class="section featured-section" aria-labelledby="featured-title">
    <div class="container">
        <div class="section-header">
            <span class="section-header__label"><?php esc_html_e('Öne Çıkan', 'utkvakfi'); ?></span>
            <h2 class="section-header__title" id="featured-title"><?php esc_html_e('Son Önemli Yayınlar', 'utkvakfi'); ?></h2>
        </div>
        <?php
        $featured = new WP_Query([
            'post_type'      => ['yayin', 'post'],
            'posts_per_page' => 3,
            'meta_key'       => '_featured',
            'meta_value'     => '1',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
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
            $count = 0;
            while ($featured->have_posts()) : $featured->the_post();
                if ($count === 0) : ?>
                    <article class="featured-main" aria-labelledby="fm-title-<?php the_ID(); ?>">
                        <div class="featured-main__image" aria-hidden="true">
                            <?php if (has_post_thumbnail()) : the_post_thumbnail('utkvakfi-hero', ['alt' => '']); endif; ?>
                        </div>
                        <div class="featured-main__body">
                            <span class="featured-main__type">
                                <?php
                                $terms = get_the_terms(get_the_ID(), 'tur');
                                echo $terms ? esc_html($terms[0]->name) : esc_html(get_post_type_object(get_post_type())->labels->singular_name);
                                ?>
                            </span>
                            <h3 class="featured-main__title" id="fm-title-<?php the_ID(); ?>">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="featured-main__excerpt"><?php echo esc_html(utkvakfi_excerpt(30)); ?></p>
                            <div class="featured-main__meta">
                                <span><?php echo esc_html(get_the_date()); ?></span>
                                <span><?php echo esc_html(utkvakfi_reading_time()); ?></span>
                            </div>
                        </div>
                    </article>
                <?php else : ?>
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
                        <p class="featured-card-sm__meta"><?php echo esc_html(get_the_date()); ?> &middot; <?php echo esc_html(utkvakfi_reading_time()); ?></p>
                    </article>
                <?php endif;
                $count++;
            endwhile;
            wp_reset_postdata();
            ?>
            <?php if ($count < 3) : ?>
                <div class="featured-card-sm" style="display:flex;align-items:center;justify-content:center;">
                    <a href="<?php echo esc_url(get_post_type_archive_link('yayin')); ?>" class="btn btn--primary">
                        <?php esc_html_e('Tüm Yayınlar', 'utkvakfi'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- 3. KONU NAVİGASYONU -->
<section class="section topics-section" aria-labelledby="topics-title">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <span class="section-header__label"><?php esc_html_e('Araştırma Alanları', 'utkvakfi'); ?></span>
            <h2 class="section-header__title" id="topics-title"><?php esc_html_e('Konulara Göre İçerikler', 'utkvakfi'); ?></h2>
        </div>
        <?php
        $konu_terms = get_terms(['taxonomy' => 'konu', 'number' => 8, 'hide_empty' => false]);
        $topic_icons = [
            'eğitim'   => 'M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 12.27L4.28 11.5 12 7.73l7.72 3.77L12 15.27z',
            'kültür'   => 'M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z',
            'demokrasi'=> 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z',
        ];
        if (!empty($konu_terms) && !is_wp_error($konu_terms)) : ?>
            <div class="topics-grid">
                <?php foreach ($konu_terms as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="topic-card">
                        <div class="topic-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1 1-.26 2.28-1.7 1.8l-1.29-.43"/>
                            </svg>
                        </div>
                        <span class="topic-card__name"><?php echo esc_html($term->name); ?></span>
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

<!-- 4. SON HABERLER -->
<section class="section news-section" aria-labelledby="news-title">
    <div class="container">
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
                    <?php get_template_part('template-parts/content/card'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 5. HAKKIMIZDA ÖZETİ -->
<section class="section about-section" aria-labelledby="about-title">
    <div class="container">
        <div class="about-inner">
            <div>
                <div class="section-header">
                    <span class="section-header__label"><?php esc_html_e('Kurumsal', 'utkvakfi'); ?></span>
                    <h2 class="section-header__title" id="about-title"><?php esc_html_e('Biz Kimiz?', 'utkvakfi'); ?></h2>
                    <hr class="divider">
                    <p class="section-header__desc">
                        <?php echo esc_html(get_theme_mod('about_desc', __('UTK Vakfı, 2014 yılından bu yana Türkiye\'de demokratik uzlaşı kültürünü güçlendirmek, toplumsal diyalogu desteklemek ve politika önerileri üretmek amacıyla çalışmaktadır.', 'utkvakfi'))); ?>
                    </p>
                </div>
                <div class="about-stats">
                    <?php
                    $about_stats = [
                        [get_theme_mod('stat_years',  '10+'), __('Yıl', 'utkvakfi')],
                        [get_theme_mod('stat_pubs',  '200+'), __('Yayın', 'utkvakfi')],
                        [get_theme_mod('stat_events', '50+'), __('Etkinlik', 'utkvakfi')],
                        [get_theme_mod('stat_experts', '30+'), __('Uzman', 'utkvakfi')],
                    ];
                    foreach ($about_stats as $stat) : ?>
                        <div class="about-stat">
                            <span class="about-stat__num"><?php echo esc_html($stat[0]); ?></span>
                            <span class="about-stat__label"><?php echo esc_html($stat[1]); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="margin-top: var(--space-8);">
                    <a href="<?php echo esc_url(home_url('/hakkimizda/')); ?>" class="btn btn--accent">
                        <?php esc_html_e('Daha Fazla Bilgi', 'utkvakfi'); ?>
                    </a>
                </div>
            </div>
            <div class="about-visual">
                <?php
                $about_img = get_theme_mod('about_image');
                if ($about_img) :
                    echo wp_get_attachment_image($about_img, 'utkvakfi-card', false, ['alt' => '']);
                else : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/about-placeholder.jpg" alt="" loading="lazy">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- 6. YAKLAŞAN ETKİNLİKLER -->
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
            'orderby'        => 'meta_value',
            'meta_key'       => 'etkinlik_tarih',
            'order'          => 'ASC',
        ]);
        if ($events->have_posts()) : ?>
            <div class="events-list" style="margin-top: var(--space-8);">
                <?php while ($events->have_posts()) : $events->the_post();
                    $tarih = get_post_meta(get_the_ID(), 'etkinlik_tarih', true);
                    $yer   = get_post_meta(get_the_ID(), 'etkinlik_yer', true);
                    $saat  = get_post_meta(get_the_ID(), 'etkinlik_saat', true);
                    $tur_terms = get_the_terms(get_the_ID(), 'tur');
                ?>
                    <a href="<?php the_permalink(); ?>" class="event-item">
                        <div class="event-date" aria-label="<?php echo esc_attr($tarih ? date_i18n('j F Y', strtotime($tarih)) : get_the_date()); ?>">
                            <span class="event-date__day"><?php echo esc_html($tarih ? date_i18n('j', strtotime($tarih)) : get_the_date('j')); ?></span>
                            <span class="event-date__month"><?php echo esc_html($tarih ? date_i18n('M', strtotime($tarih)) : get_the_date('M')); ?></span>
                        </div>
                        <div class="event-info">
                            <?php if ($tur_terms && !is_wp_error($tur_terms)) : ?>
                                <div class="event-type"><?php echo esc_html($tur_terms[0]->name); ?></div>
                            <?php endif; ?>
                            <div class="event-title"><?php the_title(); ?></div>
                            <div class="event-meta">
                                <?php if ($saat) : ?>
                                    <span><?php echo utkvakfi_get_svg('clock'); echo esc_html($saat); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
                                <?php endif; ?>
                                <?php if ($yer) : ?>
                                    <span><?php echo utkvakfi_get_svg('location'); echo esc_html($yer); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p style="margin-top:var(--space-8); color:#777;"><?php esc_html_e('Yaklaşan etkinlik bulunmuyor.', 'utkvakfi'); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- 7. BÜLTEN ABONELİĞİ -->
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
            $form_id = get_theme_mod('newsletter_form_id');
            if (function_exists('wpforms_display') && $form_id) :
                wpforms_display($form_id, false, true);
            else : ?>
                <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                    <?php wp_nonce_field('utkvakfi_newsletter', 'newsletter_nonce'); ?>
                    <input type="hidden" name="action" value="utkvakfi_newsletter">
                    <label for="newsletter-email" class="sr-only"><?php esc_html_e('E-posta adresiniz', 'utkvakfi'); ?></label>
                    <input
                        type="email"
                        id="newsletter-email"
                        name="email"
                        class="newsletter-form__input"
                        placeholder="<?php esc_attr_e('E-posta adresiniz', 'utkvakfi'); ?>"
                        required
                        autocomplete="email"
                    >
                    <button type="submit" class="btn btn--accent">
                        <?php esc_html_e('Abone Ol', 'utkvakfi'); ?>
                    </button>
                </form>
                <p class="newsletter-note"><?php esc_html_e('Verileriniz KVKK kapsamında korunmaktadır. İstediğiniz zaman aboneliğinizi iptal edebilirsiniz.', 'utkvakfi'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
