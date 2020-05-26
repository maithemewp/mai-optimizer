<?php

namespace MaiOptimizer\Modules;

use MaiOptimizer\Helpers;

/**
 * Class NavWalker
 *
 * @package OptimizeWP\Modules
 */
class NavMenuWalker extends \Walker_Nav_Menu {
	private $cpt;
	private $archive;

	public function __construct() {
		$cpt           = get_post_type();
		$this->cpt     = in_array( $cpt, get_post_types( [ '_builtin' => false ] ) );
		$this->archive = get_post_type_archive_link( $cpt );
	}

	public function hooks() {
		add_filter( 'nav_menu_css_class', [ $this, 'cssClasses' ], 10, 2 );
		add_filter( 'nav_menu_item_id', '__return_null' );
	}

	public function check_current( $classes ) {
		return preg_match( '/(current[-_])|active/', $classes );
	}

	public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		$element->is_subitem = ( ( ! empty( $children_elements[ $element->ID ] ) && ( ( $depth + 1 ) < $max_depth || ( $max_depth === 0 ) ) ) );
		if ( $element->is_subitem ) {
			foreach ( $children_elements[ $element->ID ] as $child ) {
				if ( $child->current_item_parent || Helpers::url_compare( $this->archive, $child->url ) ) {
					$element->classes[] = 'active';
				}
			}
		}
		$element->is_active = ( ! empty( $element->url ) && strpos( $this->archive, $element->url ) );
		if ( $element->is_active ) {
			$element->classes[] = 'active';
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	public function cssClasses( $classes, $item ) {
		$slug = sanitize_title( $item->title );

		if ( $this->cpt ) {
			$classes = str_replace( 'current_page_parent', '', $classes );
			if ( $this->archive ) {
				if ( Helpers::url_compare( $this->archive, $item->url ) ) {
					$classes[] = 'active';
				}
			}
		}

		$classes = preg_replace( '/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes );
		$classes = preg_replace( '/^((menu|page)[-_\w+]+)+/', '', $classes );

		$classes[] = 'menu-item';

		if ( $item->is_subitem ) {
			$classes[] = 'menu-item-has-children';
		}

		$classes[] = 'menu-' . $slug;
		$classes   = array_unique( $classes );
		$classes   = array_map( 'trim', $classes );

		return array_filter( $classes );
	}
}
