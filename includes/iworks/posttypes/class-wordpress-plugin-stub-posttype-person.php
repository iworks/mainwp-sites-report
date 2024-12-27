<?php
/**
 * Class for custom Post Type: PERSON
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

require_once 'class-mainwp-sites-report-posttype.php';

class iworks_mainwp_sites_report_posttype_person extends iworks_mainwp_sites_report_posttype_base {

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
		 * Taxonomy name
		 */
		$this->taxonomy_name = preg_replace( '/^iworks_mainwp_sites_report_posttype_/', '', __CLASS__ );
		$this->register_class_custom_taxonomy_name( $this->taxonomy_name, 'iw', 'role' );
		/**
		 * WordPress Hooks
		 */
		add_action( 'add_meta_boxes_' . $this->posttypes_names[ $this->posttype_name ], array( $this, 'add_meta_boxes' ) );
		add_shortcode( 'iworks_persons_list', array( $this, 'get_list' ) );
		add_filter( 'og_og_type_value', array( $this, 'filter_og_og_type_value' ) );
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
		$args                = wp_parse_args(
			$atts,
			array(
				'orderby'        => 'rand',
				'posts_per_page' => -1,
			)
		);
		$args['post_type']   = $this->posttype_name;
		$args['post_status'] = 'publish';
		$the_query           = new WP_Query( $args );
		/**
		 * No data!
		 */
		if ( ! $the_query->have_posts() ) {
			return $content;
		}
		/**
		 * Content
		 */
		ob_start();
		get_template_part( 'template-parts/heroes/header' );
		$join = rand( 0, 2 );
		$i    = 0;
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$args = array(
				'join' => $join,
				'i'    => $i++,
			);
			get_template_part( 'template-parts/heroes/one', get_post_type(), $args );
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		get_template_part( 'template-parts/heroes/footer' );
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 */
	public function action_init_register_post_type() {
		$labels = array(
			'name'                  => _x( 'Persons', 'Post Type General Name', 'mainwp-sites-report' ),
			'singular_name'         => _x( 'Person', 'Post Type Singular Name', 'mainwp-sites-report' ),
			'menu_name'             => __( 'Persons', 'mainwp-sites-report' ),
			'name_admin_bar'        => __( 'Persons', 'mainwp-sites-report' ),
			'archives'              => __( 'Persons', 'mainwp-sites-report' ),
			'all_items'             => __( 'Persons', 'mainwp-sites-report' ),
			'add_new_item'          => __( 'Add New Person', 'mainwp-sites-report' ),
			'add_new'               => __( 'Add New', 'mainwp-sites-report' ),
			'new_item'              => __( 'New Person', 'mainwp-sites-report' ),
			'edit_item'             => __( 'Edit Person', 'mainwp-sites-report' ),
			'update_item'           => __( 'Update Person', 'mainwp-sites-report' ),
			'view_item'             => __( 'View Person', 'mainwp-sites-report' ),
			'view_items'            => __( 'View Person', 'mainwp-sites-report' ),
			'search_items'          => __( 'Search Person', 'mainwp-sites-report' ),
			'not_found'             => __( 'Not found', 'mainwp-sites-report' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'mainwp-sites-report' ),
			'items_list'            => __( 'Person list', 'mainwp-sites-report' ),
			'items_list_navigation' => __( 'Person list navigation', 'mainwp-sites-report' ),
			'filter_items_list'     => __( 'Filter items list', 'mainwp-sites-report' ),
		);
		$args   = array(
			'can_export'          => true,
			'capability_type'     => 'page',
			'description'         => __( 'Person', 'mainwp-sites-report' ),
			'exclude_from_search' => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'label'               => __( 'Persons', 'mainwp-sites-report' ),
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-businessperson',
			'public'              => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 20,
			'show_in_nav_menus'   => false,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'supports'            => array( 'title', 'thumbnail', 'editor', 'revisions' ),
			'rewrite'             => array(
				'slug' => defined( 'ICL_SITEPRESS_VERSION' ) ? 'person' : _x( 'person', 'iWorks Post Type Person SLUG', 'mainwp-sites-report' ),
			),
		);
		register_post_type(
			$this->posttype_name,
			apply_filters( 'iworks_post_type_person_args', $args )
		);
	}

	/**
	 * Register Custom Taxonomy
	 *
	 * @since 1.0.0
	 */
	public function action_init_register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Roles', 'Role General Name', 'mainwp-sites-report' ),
			'singular_name'              => _x( 'Role', 'Role Singular Name', 'mainwp-sites-report' ),
			'menu_name'                  => __( 'Roles', 'mainwp-sites-report' ),
			'all_items'                  => __( 'All Roles', 'mainwp-sites-report' ),
			'parent_item'                => __( 'Parent Role', 'mainwp-sites-report' ),
			'parent_item_colon'          => __( 'Parent Role:', 'mainwp-sites-report' ),
			'new_item_name'              => __( 'New Role Name', 'mainwp-sites-report' ),
			'add_new_item'               => __( 'Add New Role', 'mainwp-sites-report' ),
			'edit_item'                  => __( 'Edit Role', 'mainwp-sites-report' ),
			'update_item'                => __( 'Update Role', 'mainwp-sites-report' ),
			'view_item'                  => __( 'View Role', 'mainwp-sites-report' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'mainwp-sites-report' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'mainwp-sites-report' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'mainwp-sites-report' ),
			'popular_items'              => __( 'Popular Roles', 'mainwp-sites-report' ),
			'search_items'               => __( 'Search Roles', 'mainwp-sites-report' ),
			'not_found'                  => __( 'Not Found', 'mainwp-sites-report' ),
			'no_terms'                   => __( 'No items', 'mainwp-sites-report' ),
			'items_list'                 => __( 'Roles list', 'mainwp-sites-report' ),
			'items_list_navigation'      => __( 'Roles list navigation', 'mainwp-sites-report' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_tagcloud'     => false,
			'rewrite'           => array(
				'slug' => defined( 'ICL_SITEPRESS_VERSION' ) ? 'role' : _x( 'role', 'iWorks Post Type Person SLUG', 'mainwp-sites-report' ),
			),
		);
		register_taxonomy( $this->taxonomy_name, array( $this->posttype_name ), $args );
	}

	public function filter_og_og_type_value( $value ) {
		if ( is_singular( $this->posttype_name ) ) {
			return 'profile';
		}
		return $value;
	}

}

