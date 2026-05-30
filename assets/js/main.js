(function () {
    'use strict';

    // Hamburger Menü
    const hamburger  = document.querySelector('.hamburger');
    const mainNav    = document.querySelector('.main-nav');

    if (hamburger && mainNav) {
        hamburger.addEventListener('click', () => {
            const isOpen = hamburger.getAttribute('aria-expanded') === 'true';
            hamburger.setAttribute('aria-expanded', String(!isOpen));
            mainNav.classList.toggle('is-open', !isOpen);
            document.body.style.overflow = isOpen ? '' : 'hidden';
        });

        // ESC ile kapat
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mainNav.classList.contains('is-open')) {
                hamburger.setAttribute('aria-expanded', 'false');
                mainNav.classList.remove('is-open');
                document.body.style.overflow = '';
                hamburger.focus();
            }
        });
    }

    // Arama Overlay
    const searchBtn     = document.querySelector('.header-search-btn');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose   = document.querySelector('.search-overlay__close');
    const searchInput   = document.querySelector('.search-overlay__input');

    function openSearch() {
        searchOverlay.classList.add('is-open');
        searchBtn && searchBtn.setAttribute('aria-expanded', 'true');
        setTimeout(() => searchInput && searchInput.focus(), 100);
    }

    function closeSearch() {
        searchOverlay.classList.remove('is-open');
        searchBtn && searchBtn.setAttribute('aria-expanded', 'false');
        searchBtn && searchBtn.focus();
    }

    if (searchBtn && searchOverlay) {
        searchBtn.addEventListener('click', openSearch);
        searchClose && searchClose.addEventListener('click', closeSearch);
        searchOverlay.addEventListener('click', (e) => { if (e.target === searchOverlay) closeSearch(); });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('is-open')) closeSearch();
        });
    }

    // Sticky Header – scroll ile gölge
    const header = document.querySelector('.site-header');
    if (header) {
        const observer = new IntersectionObserver(
            ([entry]) => header.classList.toggle('is-scrolled', !entry.isIntersecting),
            { rootMargin: '-1px 0px 0px 0px', threshold: [1] }
        );
        const sentinel = document.createElement('div');
        sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none';
        document.body.prepend(sentinel);
        observer.observe(sentinel);
    }

    // Dropdown klavye navigasyonu
    document.querySelectorAll('.main-nav__item').forEach((item) => {
        const link     = item.querySelector('.main-nav__link');
        const dropdown = item.querySelector('.main-nav__dropdown');
        if (!dropdown) return;

        link.setAttribute('aria-haspopup', 'true');
        link.setAttribute('aria-expanded', 'false');

        item.addEventListener('mouseenter', () => link.setAttribute('aria-expanded', 'true'));
        item.addEventListener('mouseleave', () => link.setAttribute('aria-expanded', 'false'));

        link.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const expanded = link.getAttribute('aria-expanded') === 'true';
                link.setAttribute('aria-expanded', String(!expanded));
            }
        });
    });

    // Lazy Image Observer
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        const imgObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-loaded');
                    obs.unobserve(entry.target);
                }
            });
        }, { rootMargin: '50px' });
        lazyImages.forEach((img) => imgObserver.observe(img));
    }

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                target.focus({ preventScroll: true });
            }
        });
    });

    // İçindekiler Aktif Bölüm
    const tocLinks   = document.querySelectorAll('.toc-list a');
    const headings   = document.querySelectorAll('.article-content h2, .article-content h3');

    if (tocLinks.length && headings.length) {
        const headingObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        tocLinks.forEach((link) => link.classList.remove('active'));
                        const active = document.querySelector(`.toc-list a[href="#${entry.target.id}"]`);
                        active && active.classList.add('active');
                    }
                });
            },
            { rootMargin: '-20% 0px -70% 0px' }
        );
        headings.forEach((h) => { if (h.id) headingObserver.observe(h); });
    }

}());
