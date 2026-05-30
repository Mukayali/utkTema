<?php
defined('ABSPATH') || exit;
$post_type = get_post_type();
?>
<article class="card" aria-labelledby="card-title-<?php the_ID(); ?>">
    <?php if (has_post_thumbnail()) : ?>
        <div class="card__image">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail('utkvakfi-card', ['alt' => '']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="card__body">
        <div class="card__meta">
            <?php
            $tur_terms = get_the_terms(get_the_ID(), 'tur');
            if ($tur_terms && !is_wp_error($tur_terms)) :
                foreach ($tur_terms as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="tag"><?php echo esc_html($term->name); ?></a>
                <?php endforeach;
            endif;
            ?>
            <time class="card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
        </div>

        <h3 class="card__title" id="card-title-<?php the_ID(); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <p class="card__excerpt"><?php echo esc_html(utkvakfi_excerpt(20)); ?></p>

        <div class="card__footer">
            <?php
            $author_id = get_post_field('post_author');
            $author_avatar = get_avatar_url($author_id, ['size' => 56]);
            ?>
            <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="card__author">
                <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr(get_the_author_meta('display_name', $author_id)); ?>" width="28" height="28" loading="lazy">
                <span><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></span>
            </a>
            <a href="<?php the_permalink(); ?>" class="card__read-more" aria-label="<?php printf(esc_attr__('%s – Devamını oku', 'utkvakfi'), get_the_title()); ?>">
                <?php esc_html_e('Oku', 'utkvakfi'); ?>
                <?php echo utkvakfi_get_svg('arrow'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </a>
        </div>
    </div>
</article>
