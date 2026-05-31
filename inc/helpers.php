<?php
/*
 * =============================================================================
 * DOSYA: inc/helpers.php
 * =============================================================================
 * Bu dosya temanın "çakı" kutusu gibidir: her yerde kullanılan küçük ama
 * çok işe yarayan araçları barındırır.
 *
 * İçindekiler:
 *   1. utkvakfi_reading_time() → Yazıyı okumak kaç dakika sürer?
 *   2. utkvakfi_excerpt()      → Yazının kısaltılmış özeti
 *   3. utkvakfi_get_konu_terms()→ Yazının konu etiketlerini getir
 *   4. utkvakfi_konu_tags()    → Konu etiketlerini HTML olarak çiz
 *   5. utkvakfi_get_svg()      → SVG ikon kütüphanesi (takvim, saat, konum vb.)
 * =============================================================================
 */

// WordPress'in dışından doğrudan bu dosyaya girilmesini engeller.
defined('ABSPATH') || exit;


// =============================================================================
// 1. OKUMA SÜRESİ HESAPLAMA
// =============================================================================
// Bir yazının kaç dakikada okunacağını hesaplar.
// Yetişkin bir okuyucu dakikada ortalama 200 kelime okur.
// Bu fonksiyon kelime sayısını 200'e bölerek süreyi bulur.
//
// Kullanım örneği: echo utkvakfi_reading_time();
// Çıktı örneği:    "5 dakika okuma"
function utkvakfi_reading_time(int $post_id = 0): string {

    // Yazının ham içeriğini al (HTML etiketleri temizlenmiş)
    $content = get_post_field('post_content', $post_id ?: get_the_ID());

    // İçerikteki kelime sayısını say (HTML ve boşluklar dahil değil)
    $words = str_word_count(wp_strip_all_tags($content));

    // Kelime sayısını 200'e böl ve yukarı yuvarla (en az 1 dakika)
    $minutes = max(1, (int) ceil($words / 200));

    /* translators: %d: number of minutes */
    return sprintf(_n('%d dakika okuma', '%d dakika okuma', $minutes, 'utkvakfi'), $minutes);
}


// =============================================================================
// 2. YAZININ KISA ÖZETİNİ GETIR
// =============================================================================
// Yazının kısa özetini belirli kelime sayısında keser.
// Haber kartlarında ve listelerde yazının ilk birkaç cümlesini göstermek için kullanılır.
//
// $length parametresi: kaç kelime gösterilsin? (varsayılan: 20)
//
// Kullanım örneği: echo utkvakfi_excerpt(25);
// Çıktı örneği:    "UTK Vakfı, demokrasi ve uzlaşı alanında önemli bir..."
function utkvakfi_excerpt(int $length = 20): string {

    // Önce yazının hazır özetini dene, yoksa içerikten kes
    return wp_trim_words(get_the_excerpt() ?: get_the_content(), $length, '…');
}


// =============================================================================
// 3. YAZININ KONU ETİKETLERİNİ DİZİ OLARAK GETIR
// =============================================================================
// Bir yazıya atanmış "konu" etiketlerini dizi olarak döndürür.
// Sonuç boşsa boş dizi döner (hata değil).
//
// Kullanım örneği: $konular = utkvakfi_get_konu_terms();
// Çıktı: [ {id:5, name:'Eğitim', slug:'egitim'}, ... ]
function utkvakfi_get_konu_terms(int $post_id = 0): array {

    $terms = get_the_terms($post_id ?: get_the_ID(), 'konu');

    // Hata veya boş sonuç durumunda güvenli şekilde boş dizi döndür
    return is_array($terms) ? $terms : [];
}


// =============================================================================
// 4. KONU ETİKETLERİNİ HTML OLARAK EKRANA YAZ
// =============================================================================
// Yazının konu etiketlerini tıklanabilir köprüler olarak sayfaya basar.
// Her etiket, o konunun arşiv sayfasına link verir.
//
// Kullanım örneği (PHP şablonunda): utkvakfi_konu_tags();
// Çıktı HTML: <a href="/konu/egitim/" class="tag">Eğitim</a>
//             <a href="/konu/cevre/"  class="tag">Çevre</a>
function utkvakfi_konu_tags(int $post_id = 0): void {

    foreach (utkvakfi_get_konu_terms($post_id) as $term) {
        printf(
            '<a href="%s" class="tag">%s</a>',
            esc_url(get_term_link($term)),   // Güvenli URL
            esc_html($term->name)            // Güvenli metin
        );
    }
}


// =============================================================================
// 5. SVG İKON KÜTÜPHANESİ
// =============================================================================
// Temada kullanılan tüm küçük simgeler (ikonlar) burada saklanır.
// Harici ikon kütüphanesi yerine dahili SVG kullanmak sayfayı daha hızlı yapar.
//
// Her SVG "inline" (satır içi) olduğu için CSS ile renklendirilebilir.
//
// Kullanım örneği: echo utkvakfi_get_svg('calendar');
// Mevcut ikonlar:
//   'calendar' → Takvim simgesi (etkinlik tarihleri için)
//   'clock'    → Saat simgesi (okuma süresi için)
//   'location' → Konum iğnesi (etkinlik yeri için)
//   'arrow'    → Sağ ok (butonlar ve breadcrumb için)
//   'search'   → Büyüteç (arama butonu için)
//   'close'    → Çarpı (modalları kapatmak için)
//   'download' → İndirme oku (PDF indirme için)
//   'mail'     → Zarf (e-posta için)
//   'phone'    → Telefon (iletişim için)
//   'pin'      → Konum iğnesi (adres için)
function utkvakfi_get_svg(string $icon): string {

    $icons = [
        // Takvim → etkinlik tarihleri, yayın tarihleri
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',

        // Saat → okuma süresi
        'clock'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',

        // Konum iğnesi → etkinlik yeri
        'location' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',

        // Sağ ok → "Devamını oku", breadcrumb ayırıcı
        'arrow'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',

        // Büyüteç → arama butonu (header'da)
        'search'   => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',

        // Çarpı → arama overlay'ini ve modalları kapat
        'close'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',

        // İndirme oku → PDF indirme butonu
        'download' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',

        // Zarf → e-posta iletişim bilgisi
        'mail'     => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',

        // Telefon → iletişim telefon numarası
        'phone'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',

        // Konum iğnesi → footer adres bilgisi (location ile aynı, farklı bağlam)
        'pin'      => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
    ];

    // İstenilen ikon varsa döndür, yoksa boş string döndür (hata verme)
    return $icons[$icon] ?? '';
}
