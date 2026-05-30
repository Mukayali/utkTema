</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-main">

            <!-- Kolon 1 – Marka -->
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo" rel="home" aria-label="<?php bloginfo('name'); ?> – Ana Sayfa">
                    <?php if (has_custom_logo()) :
                        the_custom_logo();
                    else : ?>
                        <span class="footer-logo__name"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>
                <p class="footer-mission">
                    <?php echo esc_html(get_theme_mod('footer_mission', __('Türkiye\'de uzlaşı kültürünü ve toplumsal kalkınmayı destekleyen bağımsız bir vakıf.', 'utkvakfi'))); ?>
                </p>
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

            <!-- Kolon 2 – Hızlı Linkler -->
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

            <!-- Kolon 3 – Konular -->
            <div>
                <h3 class="footer-col__title"><?php esc_html_e('Konular', 'utkvakfi'); ?></h3>
                <?php
                $konu_terms = get_terms(['taxonomy' => 'konu', 'number' => 6, 'hide_empty' => false]);
                if (!empty($konu_terms) && !is_wp_error($konu_terms)) : ?>
                    <ul class="footer-col__list">
                        <?php foreach ($konu_terms as $term) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer-2',
                        'menu_class'     => 'footer-col__list',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]); ?>
                <?php endif; ?>
            </div>

            <!-- Kolon 4 – İletişim -->
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
                        <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if ($phone = get_theme_mod('contact_phone')) : ?>
                    <li class="footer-contact__item">
                        <?php echo utkvakfi_get_svg('phone'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div><!-- .footer-main -->

        <div class="footer-bottom">
            <p class="footer-bottom__copy">
                <?php
                printf(
                    esc_html__('© %1$s %2$s. Tüm hakları saklıdır.', 'utkvakfi'),
                    esc_html(date_i18n('Y')),
                    esc_html(get_bloginfo('name'))
                );
                ?>
            </p>
            <ul class="footer-bottom__links">
                <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Gizlilik Politikası', 'utkvakfi'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/kvkk/')); ?>">KVKK</a></li>
                <li><a href="<?php echo esc_url(home_url('/site-haritasi/')); ?>"><?php esc_html_e('Site Haritası', 'utkvakfi'); ?></a></li>
            </ul>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
