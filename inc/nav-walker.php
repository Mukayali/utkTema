<?php
/*
 * =============================================================================
 * DOSYA: inc/nav-walker.php
 * =============================================================================
 * Bu dosya WordPress'in menü çizim sistemini özelleştirir.
 *
 * WordPress normalde menüleri çizerken kendi HTML yapısını kullanır.
 * Bu tema ise BEM metodolojisine uygun özel CSS sınıfları kullanır
 * (örn. .main-nav__item, .main-nav__link, .main-nav__dropdown).
 *
 * "Walker" kelimesi İngilizce'de "yürüyen" demektir. Bu sınıf menü
 * ağacını "yürüyerek" (her öğeyi sırayla gezerek) HTML üretir.
 *
 * Tıpkı bir matbaacının her sayfayı tek tek baskıya hazırlaması gibi,
 * bu Walker sınıfı her menü öğesini tek tek HTML'e çevirir.
 *
 * CSS sınıf yapısı:
 *   <li class="main-nav__item">          → menü öğesi (li)
 *     <a class="main-nav__link">         → üst seviye link
 *       <ul class="main-nav__dropdown">  → açılır alt menü
 *         <a class="main-nav__dropdown-link"> → alt menü linki
 * =============================================================================
 */

// WordPress'in dışından doğrudan bu dosyaya girilmesini engeller.
defined('ABSPATH') || exit;


// =============================================================================
// UTK_Nav_Walker SINIFI
// =============================================================================
// WordPress'in Walker_Nav_Menu sınıfından miras alır ve 4 metodu geçersiz kılar.
class UTK_Nav_Walker extends Walker_Nav_Menu {

    // =========================================================================
    // Alt menü AÇILIŞI → <ul class="main-nav__dropdown"> yazar
    // =========================================================================
    // Bir menü öğesinin alt menüsü başladığında çağrılır.
    // $depth = kaçıncı derinlik seviyesinde olduğumuz (0 = üst, 1 = alt vb.)
    public function start_lvl( &$output, $depth = 0, $args = null ) {

        // Açılır menü listesini başlat; role="menu" erişilebilirlik için
        $output .= "\n<ul class=\"main-nav__dropdown\" role=\"menu\">\n";
    }

    // =========================================================================
    // Alt menü KAPANIŞI → </ul> yazar
    // =========================================================================
    // Alt menünün tüm öğeleri bittiğinde çağrılır.
    public function end_lvl( &$output, $depth = 0, $args = null ) {

        $output .= "</ul>\n";
    }

    // =========================================================================
    // Menü ÖĞESİ AÇILIŞI → <li> ve <a> etiketlerini yazar
    // =========================================================================
    // Her menü öğesi için çağrılır. Hem üst hem alt seviye öğeleri işler.
    // $depth = 0 ise üst menü, $depth > 0 ise alt menü öğesidir.
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {

        $item = $data_object; // Menü öğesi verisi (başlık, URL, sınıflar vb.)

        // -------------------------------------------------------------------
        // <li> ETİKETİ VE CSS SINIFLARI
        // -------------------------------------------------------------------
        // WordPress'in atadığı sınıfları al (current-menu-item, has-children vb.)
        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;

        // Temanın BEM sınıfını ekle: her <li> 'main-nav__item' olsun
        $classes[] = 'main-nav__item';

        // Sınıfları birleştir (tekrarları kaldır, boşları filtrele)
        $class_names = implode( ' ', array_filter( array_unique( $classes ) ) );

        // <li> etiketini yaz
        $output .= '<li class="' . esc_attr( $class_names ) . '">';

        // -------------------------------------------------------------------
        // <a> ETİKETİ VE NİTELİKLERİ (ATTRIBUTES)
        // -------------------------------------------------------------------
        $atts          = [];
        $atts['href']  = ! empty( $item->url ) ? $item->url : '#'; // Bağlantı adresi

        // Derinliğe göre farklı CSS sınıfı:
        //   depth=0 → üst menü linki:  main-nav__link
        //   depth>0 → alt menü linki:  main-nav__dropdown-link
        $atts['class'] = $depth === 0 ? 'main-nav__link' : 'main-nav__dropdown-link';

        // İsteğe bağlı nitelikler (varsa ekle)
        if ( $item->attr_title ) $atts['title']        = $item->attr_title;   // Fare üzerine gelince çıkan metin
        if ( $item->target )     $atts['target']       = $item->target;       // _blank = yeni sekmede aç
        if ( $item->xfn )        $atts['rel']          = $item->xfn;          // noopener gibi güvenlik
        if ( $item->current )    $atts['aria-current'] = 'page';              // Şu an açık sayfayı işaretle (erişilebilirlik)

        // Eklentilerin nitelikleri değiştirmesine izin ver
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        // Nitelikleri string'e dönüştür: href="..." class="..." gibi
        $attr_str = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $attr_str .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
            }
        }

        // -------------------------------------------------------------------
        // BAŞLIK METNİ
        // -------------------------------------------------------------------
        // Menü öğesinin görünen adını al (filtrelenmiş hali)
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        // <a> etiketini yaz: <a href="..." class="...">Menü Adı</a>
        $output .= '<a' . $attr_str . '>' . $title . '</a>';
    }

    // =========================================================================
    // Menü ÖĞESİ KAPANIŞI → </li> yazar
    // =========================================================================
    // Her menü öğesinin HTML'i bittikten sonra çağrılır.
    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {

        $output .= "</li>\n";
    }
}
