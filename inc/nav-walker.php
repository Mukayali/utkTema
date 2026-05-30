<?php
defined('ABSPATH') || exit;

class UTK_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= "\n<ul class=\"main-nav__dropdown\" role=\"menu\">\n";
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= "</ul>\n";
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item = $data_object;

        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'main-nav__item';

        $class_names = implode( ' ', array_filter( array_unique( $classes ) ) );

        $output .= '<li class="' . esc_attr( $class_names ) . '">';

        $atts          = [];
        $atts['href']  = ! empty( $item->url ) ? $item->url : '#';
        $atts['class'] = 'main-nav__link';

        if ( $item->attr_title ) $atts['title']        = $item->attr_title;
        if ( $item->target )     $atts['target']       = $item->target;
        if ( $item->xfn )        $atts['rel']          = $item->xfn;
        if ( $item->current )    $atts['aria-current'] = 'page';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attr_str = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $attr_str .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $output .= '<a' . $attr_str . '>' . $title . '</a>';
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}
