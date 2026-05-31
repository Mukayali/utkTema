<?php
/*
 * =============================================================================
 * DOSYA: template-parts/content/card.php
 * =============================================================================
 * Bu dosya tek bir "haber/yayın kartı"nın HTML şablonudur.
 * Tıpkı gazete kütüphanelerindeki hazır küpür kutusu gibi: nereye
 * yerleştirilirse orada aynı yapıyla görünür.
 *
 * Kart yapısı:
 *   ┌─────────────────────────┐
 *   │  [Görsel - 16:9]        │
 *   ├─────────────────────────┤
 *   │  [Tür Etiketi]  [Tarih] │
 *   │  Başlık                 │
 *   │  Kısa özet metni...     │
 *   ├─────────────────────────┤
 *   │  [Yazar] [Oku →]        │
 *   └─────────────────────────┘
 *
 * Kullanıldığı yerler:
 *   - archive.php (yayın listesi sayfaları)
 *   - front-page.php (son haberler bölümü)
 *   - single.php (ilgili yayınlar)
 * =============================================================================
 */

// WordPress'in dışından doğrudan bu dosyaya girilmesini engeller.
defined('ABSPATH') || exit;

// Şu anki yazının türünü al ('post', 'yayin' vb.)
$post_type = get_post_type();
?>

<?php /* Kart kapsayıcısı: aria-labelledby erişilebilirlik için başlıkla ilişkilendirir */ ?>
<article class="card" aria-labelledby="card-title-<?php the_ID(); ?>">

    <?php /* Öne çıkan görsel varsa göster (yoksa görsel alanı hiç oluşmaz) */ ?>
    <?php if (has_post_thumbnail()) : ?>
        <div class="card__image">
            <?php /* Görselin linki: tabindex/aria-hidden → ekran okuyucular çift link duymasın */ ?>
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php
                // 'utkvakfi-card' → 800x450px otomatik kırpılmış boyut (functions.php'de tanımlı)
                // alt="" → görsel dekoratif, başlık linki zaten açıklıyor
                the_post_thumbnail('utkvakfi-card', ['alt' => '']);
                ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="card__body">

        <?php /* Meta bilgiler: tür etiketi + tarih */ ?>
        <div class="card__meta">
            <?php
            // Yazıya atanmış "tür" etiketleri (Makale, Rapor, Analiz vb.)
            $tur_terms = get_the_terms(get_the_ID(), 'tur');
            if ($tur_terms && !is_wp_error($tur_terms)) :
                foreach ($tur_terms as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="tag">
                        <?php echo esc_html($term->name); ?>
                    </a>
                <?php endforeach;
            endif;
            ?>
            <?php /* Yayın tarihi: datetime özelliği makine tarafından okunabilir format */ ?>
            <time class="card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
        </div>

        <?php /* Yazı başlığı: id'si aria-labelledby ile kart ile eşleştirildi */ ?>
        <h3 class="card__title" id="card-title-<?php the_ID(); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php /* Kısa özet metni: 20 kelimeyle kesilir, sonuna "…" gelir */ ?>
        <p class="card__excerpt"><?php echo esc_html(utkvakfi_excerpt(20)); ?></p>

        <?php /* Kart alt kısmı: yazar + "Oku →" linki */ ?>
        <div class="card__footer">
            <?php
            // Yazarın ID'sini al
            $author_id     = get_post_field('post_author');
            // 56px avatar URL'si (gerçekte 28px gösterilir, @2x için 2 katı)
            $author_avatar = get_avatar_url($author_id, ['size' => 56]);
            ?>
            <?php /* Yazar bilgisi: küçük yuvarlak fotoğraf + isim */ ?>
            <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="card__author">
                <img
                    src="<?php echo esc_url($author_avatar); ?>"
                    alt="<?php echo esc_attr(get_the_author_meta('display_name', $author_id)); ?>"
                    width="28" height="28" loading="lazy"
                >
                <span><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></span>
            </a>

            <?php /* "Oku →" linki: aria-label tam başlığı söyler (erişilebilirlik için) */ ?>
            <a href="<?php the_permalink(); ?>" class="card__read-more"
               aria-label="<?php printf(esc_attr__('%s – Devamını oku', 'utkvakfi'), get_the_title()); ?>">
                <?php esc_html_e('Oku', 'utkvakfi'); ?>
                <?php echo utkvakfi_get_svg('arrow'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </a>
        </div>

    </div>
</article>
