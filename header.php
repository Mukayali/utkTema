<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-nav" href="#main-content"><?php esc_html_e('İçeriğe geç', 'utkvakfi'); ?></a>

<?php if (get_theme_mod('topbar_text')) : ?>
<div class="topbar" role="banner">
    <div class="container">
        <p class="topbar__text"><?php echo wp_kses_post(get_theme_mod('topbar_text')); ?></p>
    </div>
</div>
<?php endif; ?>

<header class="site-header" role="banner">
    <div class="container">
        <div class="header-inner">

            <?php if (has_custom_logo()) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home" aria-label="<?php bloginfo('name'); ?> – Ana Sayfa">
                    <?php the_custom_logo(); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                    <div class="site-logo__text">
                        <span class="site-logo__name"><?php bloginfo('name'); ?></span>
                        <span class="site-logo__tagline"><?php bloginfo('description'); ?></span>
                    </div>
                </a>
            <?php endif; ?>

            <nav class="main-nav" id="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Ana navigasyon', 'utkvakfi'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location'  => 'primary',
                    'menu_class'      => 'main-nav__list',
                    'container'       => false,
                    'items_wrap'      => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                    'fallback_cb'     => false,
                    'walker'          => class_exists('UTK_Nav_Walker') ? new UTK_Nav_Walker() : null,
                ]);
                ?>
            </nav>

            <div class="header-actions">
                <button class="header-search-btn" aria-label="<?php esc_attr_e('Arama', 'utkvakfi'); ?>" aria-controls="search-overlay" aria-expanded="false">
                    <?php echo utkvakfi_get_svg('search'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </button>

                <button class="hamburger" aria-label="<?php esc_attr_e('Menüyü aç', 'utkvakfi'); ?>" aria-controls="main-navigation" aria-expanded="false">
                    <span class="hamburger__line" aria-hidden="true"></span>
                    <span class="hamburger__line" aria-hidden="true"></span>
                    <span class="hamburger__line" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>
</header>

<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Arama', 'utkvakfi'); ?>">
    <div class="search-overlay__inner">
        <span class="search-overlay__label"><?php esc_html_e('Arama', 'utkvakfi'); ?></span>
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-overlay__form">
            <label for="search-input" class="sr-only"><?php esc_html_e('Ara', 'utkvakfi'); ?></label>
            <input
                type="search"
                id="search-input"
                class="search-overlay__input"
                name="s"
                placeholder="<?php esc_attr_e('Ne aramak istiyorsunuz?', 'utkvakfi'); ?>"
                autocomplete="off"
            >
            <button type="button" class="search-overlay__close" aria-label="<?php esc_attr_e('Aramayı kapat', 'utkvakfi'); ?>">
                <?php echo utkvakfi_get_svg('close'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </button>
        </form>
    </div>
</div>

<main id="main-content" tabindex="-1">
