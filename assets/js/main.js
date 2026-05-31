/*
 * =============================================================================
 * DOSYA: assets/js/main.js
 * =============================================================================
 * Bu dosya sitenin tüm JavaScript davranışlarını içerir.
 * JavaScript = sayfayı "canlı" yapan kod. HTML/CSS yalnızca görünüm sağlar,
 * JS ise kullanıcının tıklamalarına, kaydırmalarına tepki verir.
 *
 * İçindekiler:
 *   1. Hamburger Menü       → Mobilde menüyü aç/kapat
 *   2. Arama Overlay         → Arama kutusunu göster/gizle
 *   3. Sticky Header         → Kaydırınca header'a gölge ekle
 *   4. Dropdown Klavye       → Klavyeyle açılır menü navigasyonu
 *   5. Lazy Image Observer   → Görselleri sadece görününce yükle
 *   6. Smooth Scroll         → Sayfa içi linklerde yumuşak kaydırma
 *   7. İçindekiler Takip     → Okurken aktif başlığı işaretle
 * =============================================================================
 */

// Tüm kod bir IIFE (hemen çalışan fonksiyon) içine sarılır.
// Bu, dışarıdan değişkenlerimize ulaşılmasını engeller.
// Tıpkı özel bir odada çalışmak gibi: içerde her şey serbest,
// dışarıdan kimse karışamaz.
(function () {
    'use strict'; // Hatalı kod yazımını daha sıkı denetlemesini söyler


    // =========================================================================
    // 1. HAMBURGEr MENÜ
    // =========================================================================
    // Mobil cihazlarda (ekran dar olduğunda) menü gizlenir ve üç çizgili
    // "hamburger" ikonu çıkar. Bu ikona tıklanınca menü açılır/kapanır.
    // =========================================================================

    const hamburger = document.querySelector('.hamburger');  // Üç çizgili buton
    const mainNav   = document.querySelector('.main-nav');   // Navigasyon menüsü

    if (hamburger && mainNav) {

        // Hamburger'a tıklanınca menüyü aç ya da kapat
        hamburger.addEventListener('click', () => {
            const isOpen = hamburger.getAttribute('aria-expanded') === 'true';

            // aria-expanded → ekran okuyuculara menünün açık mı kapalı mı olduğunu söyler
            hamburger.setAttribute('aria-expanded', String(!isOpen));

            // Menüye 'is-open' sınıfı ekle/çıkar → CSS bu sınıfa göre gösterir/gizler
            mainNav.classList.toggle('is-open', !isOpen);

            // Menü açıkken sayfanın geri kalanı kaydırılamasın
            document.body.style.overflow = isOpen ? '' : 'hidden';
        });

        // ESC tuşuna basınca menüyü kapat
        // Klavye kullanıcıları ve ekran okuyucular için önemli
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mainNav.classList.contains('is-open')) {
                hamburger.setAttribute('aria-expanded', 'false');
                mainNav.classList.remove('is-open');
                document.body.style.overflow = '';
                hamburger.focus(); // Odağı hamburger butona geri ver
            }
        });
    }


    // =========================================================================
    // 2. ARAMA OVERLAY
    // =========================================================================
    // Header'daki büyüteç ikonu tıklandığında tüm ekranı kaplayan
    // bir arama kutusu açılır. X'e veya ESC'ye basınca kapanır.
    // =========================================================================

    const searchBtn     = document.querySelector('.header-search-btn');   // Büyüteç butonu
    const searchOverlay = document.getElementById('search-overlay');      // Tüm ekranı kaplayan arama alanı
    const searchClose   = document.querySelector('.search-overlay__close'); // Kapat (X) butonu
    const searchInput   = document.querySelector('.search-overlay__input'); // Arama yazı kutusu

    // Arama overlay'ini aç
    function openSearch() {
        searchOverlay.classList.add('is-open');
        searchBtn && searchBtn.setAttribute('aria-expanded', 'true');
        // 100ms gecikmeyle input'a odaklan → tarayıcı animasyonu tamamlayana kadar bekle
        setTimeout(() => searchInput && searchInput.focus(), 100);
    }

    // Arama overlay'ini kapat
    function closeSearch() {
        searchOverlay.classList.remove('is-open');
        searchBtn && searchBtn.setAttribute('aria-expanded', 'false');
        searchBtn && searchBtn.focus(); // Odağı arama butonuna geri ver
    }

    if (searchBtn && searchOverlay) {
        searchBtn.addEventListener('click', openSearch);             // Büyüteç → aç
        searchClose && searchClose.addEventListener('click', closeSearch); // X → kapat
        // Overlay'in dışına (arkaplan) tıklanınca kapat
        searchOverlay.addEventListener('click', (e) => { if (e.target === searchOverlay) closeSearch(); });
        // ESC tuşuyla kapat
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('is-open')) closeSearch();
        });
    }


    // =========================================================================
    // 3. STICKY HEADER – SCROLL İLE GÖLGE
    // =========================================================================
    // Sayfa aşağı kaydırıldığında header'a gölge eklenir.
    // Bu, header'ın içerik üzerinde "yüzdüğünü" görsel olarak vurgular.
    //
    // IntersectionObserver = tarayıcıya "şu element görünmez olunca beni haber ver" der.
    // Scroll event'inden çok daha performanslı çalışır (pil tasarrufu sağlar).
    // =========================================================================

    const header = document.querySelector('.site-header');

    if (header) {
        // Sayfanın en üstüne görünmez 1px bir "iz takip noktası" ekle
        const sentinel = document.createElement('div');
        sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none';
        document.body.prepend(sentinel);

        // Bu nokta ekrandan çıkınca header'a 'is-scrolled' sınıfı ekle
        const observer = new IntersectionObserver(
            ([entry]) => header.classList.toggle('is-scrolled', !entry.isIntersecting),
            { rootMargin: '-1px 0px 0px 0px', threshold: [1] }
        );
        observer.observe(sentinel);
    }


    // =========================================================================
    // 4. DROPDOWN MENÜ – KLAVYE NAVİGASYONU
    // =========================================================================
    // Alt menüsü olan menü öğelerinde:
    //   - Fare ile üzerine gelince → alt menü açılır (CSS ile)
    //   - Enter veya Space tuşuyla → alt menü açılır/kapanır (JS ile)
    //   - aria-expanded → ekran okuyuculara "açık mı?" bilgisini verir
    // =========================================================================

    document.querySelectorAll('.main-nav__item').forEach((item) => {
        const link     = item.querySelector('.main-nav__link');
        const dropdown = item.querySelector('.main-nav__dropdown');

        if (!dropdown) return; // Alt menüsü yoksa işlem yapma

        // Erişilebilirlik nitelikleri ekle
        link.setAttribute('aria-haspopup', 'true');   // "Alt menüm var" bilgisi
        link.setAttribute('aria-expanded', 'false');  // Başlangıçta kapalı

        // Fare ile üzerine gelince açık işaretle (CSS açıyor, biz sadece aria güncelliyoruz)
        item.addEventListener('mouseenter', () => link.setAttribute('aria-expanded', 'true'));
        item.addEventListener('mouseleave', () => link.setAttribute('aria-expanded', 'false'));

        // Enter veya Space tuşuyla açılır menüyü aç/kapat
        link.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const expanded = link.getAttribute('aria-expanded') === 'true';
                link.setAttribute('aria-expanded', String(!expanded));
            }
        });
    });


    // =========================================================================
    // 5. LAZY IMAGE OBSERVER (GECİKTİRİLMİŞ GÖRSEL YÜKLEME)
    // =========================================================================
    // Tüm görseller sayfa açıldığında değil, ekrana yaklaştığında yüklenir.
    // Bu sayfalarda çok görsel olduğunda internet ve pil tasarrufu sağlar.
    //
    // Yüklenince görsele 'is-loaded' sınıfı eklenir → CSS ile yumuşak geçiş yapılabilir.
    // =========================================================================

    if ('IntersectionObserver' in window) { // Eski tarayıcı desteği için kontrol

        const lazyImages = document.querySelectorAll('img[loading="lazy"]');

        const imgObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-loaded'); // Yüklendi sınıfı ekle
                    obs.unobserve(entry.target);             // Artık bu görseli takip etme
                }
            });
        }, { rootMargin: '50px' }); // 50px önceden haber ver (ekrana girmeden yüklemeye başla)

        lazyImages.forEach((img) => imgObserver.observe(img));
    }


    // =========================================================================
    // 6. SMOOTH SCROLL (YUMUŞAK KAYDIRMA)
    // =========================================================================
    // Sayfa içi linklere (#ile başlayan href) tıklanınca sayfa aniden
    // zıplamak yerine yumuşakça o bölüme kayar.
    //
    // Örnek: <a href="#makale-icerigi"> → sayfada o id'li elemana kaydır
    // =========================================================================

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault(); // Tarayıcının varsayılan anlık zıplamayı engelle
                target.scrollIntoView({ behavior: 'smooth', block: 'start' }); // Yumuşak kaydır
                target.focus({ preventScroll: true }); // Klavye odağını oraya taşı
            }
        });
    });


    // =========================================================================
    // 7. İÇİNDEKİLER – AKTİF BÖLÜM TAKİBİ
    // =========================================================================
    // Makale detay sayfasındaki "İçindekiler" widgetı için çalışır.
    // Okuyucu sayfayı aşağı kaydırdıkça, hangi başlık görünüyorsa
    // içindekiler listesinde o başlık vurgulanır (active sınıfı eklenir).
    //
    // Tıpkı bir kitapta "şu an X. bölümdesiniz" bildirimi gibi.
    // =========================================================================

    const tocLinks = document.querySelectorAll('.toc-list a');    // İçindekiler linkleri
    const headings = document.querySelectorAll('.article-content h2, .article-content h3'); // Makale başlıkları

    if (tocLinks.length && headings.length) {

        const headingObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        // Tüm aktif işaretleri kaldır
                        tocLinks.forEach((link) => link.classList.remove('active'));

                        // Görünen başlığa karşılık gelen içindekiler linkini bul ve vurgula
                        const active = document.querySelector(`.toc-list a[href="#${entry.target.id}"]`);
                        active && active.classList.add('active');
                    }
                });
            },
            // rootMargin: Ekranın %20 üstü ve %70 altını "görünür bölge" sayma
            // Bu sayede başlık ekrana girdikten biraz sonra aktif olarak işaretlenir
            { rootMargin: '-20% 0px -70% 0px' }
        );

        // Sadece id'si olan başlıkları gözlemle (id olmadan link veremeyiz)
        headings.forEach((h) => { if (h.id) headingObserver.observe(h); });
    }

}()); // IIFE bitti
