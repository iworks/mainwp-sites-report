<?php
/**
 * Class for custom Post Type: HERO
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

require_once 'class-mainwp-sites-report-posttype.php';

class iworks_mainwp_sites_report_posttype_hero extends iworks_mainwp_sites_report_posttype_base {

	public function __construct() {
		parent::__construct();
		/**
		 * Post Type Name
		 *
		 * @since 1.0.0
		 */
		$this->posttype_name = preg_replace( '/^iworks_mainwp_sites_report_posttype_/', '', __CLASS__ );
		$this->register_class_custom_posttype_name( $this->posttype_name, 'iw' );
		/**
		 * WordPress Hooks
		 */
		add_action( 'add_meta_boxes_' . $this->posttypes_names[ $this->posttype_name ], array( $this, 'add_meta_boxes' ) );
		add_shortcode( 'opi_heroes', array( $this, 'get_list' ) );
	}

	/**
	 * class settings
	 *
	 * @since 1.0.0
	 */
	public function action_init_settings() {
	}

	/**
	 * Get post list
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes
	 * @param string $content current content
	 *
	 * @return string $content
	 */
	public function get_list( $atts, $content = '' ) {
		$args      = array(
			'post_type'      => $this->posttype_name,
			'orderby'        => 'rand',
			'posts_per_page' => 2,
			'post_status'    => 'publish',
		);
		$the_query = new WP_Query( $args );
		/**
		 * No data!
		 */
		if ( ! $the_query->have_posts() ) {
			return $content;
		}
		/**
		 * Content
		 */
		$content .= '<div class="wp-block-group alignfull work-with-us work-with-us-heroes">';
		$content .= '<div class="wp-block-group__inner-container">';
		$content .= sprintf(
			'<h2>%s</h2>',
			esc_html__( 'Learn what our employees are saying.', 'mainwp-sites-report' )
		);
		$content .= sprintf(
			'<p class="become-one-of-them">%s</p>',
			esc_html__( 'Become one of them!', 'mainwp-sites-report' )
		);
		$content .= '<ul>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$content .= sprintf( '<li class="%s">', implode( ' ', get_post_class() ) );
			$content .= sprintf( '<h3>%s</h3>', get_the_title() );
			$content .= '<div class="post-inner">';
			$content .= '<blockquote class="post-content">';
			$content .= get_the_content();
			$content .= '</blockquote>';
			$content .= '</div>';
			$content .= get_the_post_thumbnail( get_the_ID(), 'full' );
			$content .= '<div class="post-excerpt">';
			$content .= get_the_excerpt();
			$content .= '</div>';
			$content .= '</li>';
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		$content .= '</ul>';
		$content .= '</div>';
		$content .= '</div>';
		return $content;
	}

	public function action_init_register_taxonomy() {}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 */
	public function action_init_register_post_type() {
		$labels = array(
			'name'                  => _x( 'Heroes', 'Post Type General Name', 'mainwp-sites-report' ),
			'singular_name'         => _x( 'Hero', 'Post Type Singular Name', 'mainwp-sites-report' ),
			'menu_name'             => __( 'Heroes', 'mainwp-sites-report' ),
			'name_admin_bar'        => __( 'Heroes', 'mainwp-sites-report' ),
			'archives'              => __( 'Heroes', 'mainwp-sites-report' ),
			'all_items'             => __( 'Heroes', 'mainwp-sites-report' ),
			'add_new_item'          => __( 'Add New Hero', 'mainwp-sites-report' ),
			'add_new'               => __( 'Add New', 'mainwp-sites-report' ),
			'new_item'              => __( 'New Hero', 'mainwp-sites-report' ),
			'edit_item'             => __( 'Edit Hero', 'mainwp-sites-report' ),
			'update_item'           => __( 'Update Hero', 'mainwp-sites-report' ),
			'view_item'             => __( 'View Hero', 'mainwp-sites-report' ),
			'view_items'            => __( 'View Hero', 'mainwp-sites-report' ),
			'search_items'          => __( 'Search Hero', 'mainwp-sites-report' ),
			'not_found'             => __( 'Not found', 'mainwp-sites-report' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'mainwp-sites-report' ),
			'items_list'            => __( 'Hero list', 'mainwp-sites-report' ),
			'items_list_navigation' => __( 'Hero list navigation', 'mainwp-sites-report' ),
			'filter_items_list'     => __( 'Filter items list', 'mainwp-sites-report' ),
		);
		$args   = array(
			'can_export'          => true,
			'capability_type'     => 'page',
			'description'         => __( 'Hero', 'mainwp-sites-report' ),
			'exclude_from_search' => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'label'               => __( 'Heroes', 'mainwp-sites-report' ),
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-businessperson',
			'public'              => false,
			'show_in_admin_bar'   => false,
			'show_in_menu'        => apply_filters( 'opi_post_type_show_in_menu' . $this->posttype_name, 'edit.php' ),
			'show_in_nav_menus'   => false,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'thumbnail', 'editor', 'excerpt', 'page-attributes' ),
		);
		register_post_type( $this->posttype_name, $args );
	}

}

