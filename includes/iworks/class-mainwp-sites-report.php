<?php
/*

Copyright 2024-PLUGIN_TILL_YEAR Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'iworks_mainwp_sites_report' ) ) {
	return;
}

require_once( dirname( __FILE__ ) . '/class-mainwp-sites-report-base.php' );

class iworks_mainwp_sites_report extends iworks_mainwp_sites_report_base {

	private $capability;

	public function __construct() {
		parent::__construct();
		$this->version = 'PLUGIN_VERSION';
		/**
		 * init
		 */
		add_action( 'init', array( $this, 'action_init_load_plugin_textdomain' ), 0 );
		/**
		 * admin menu
		 */
		add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
		/**
		 * is active?
		 */
		add_filter( 'mainwp-sites-report/is_active', '__return_true' );
	}

	public function action_admin_menu() {
		add_management_page(
			__( 'Sites Report', 'mainwp-sites-report' ),
			__( 'Sites Report', 'mainwp-sites-report' ),
			'activate_plugins',
			'mainwp-sites-report',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Display callback for the submenu page.
	 */
	public function render_page() {
		global $wpdb;
		echo '<div class="wrap">';
		echo '<h1>';
		esc_html_e( 'Sites Report', 'mainwp-sites-report' );
		echo '</h1>';
		/**
		 * tables
		 */
		$mainwp_group    = $wpdb->prefix . 'mainwp_group';
		$mainwp_wp       = $wpdb->prefix . 'mainwp_wp';
		$mainwp_wp_group = $wpdb->prefix . 'mainwp_wp_group';
		/**
		 * query
		 */
		$query  = "select g.name, count(b.groupid) as count from $mainwp_wp_group b left join $mainwp_group g on b.groupid = g.id group by b.groupid order by 1";
		$result = $wpdb->get_results( $query );
		if ( $result ) {
			echo '<table class="wp-list-table widefat fixed striped table-view-list">';
			echo '<tr>';
			printf(
				'<td>%s</td>',
				esc_html__( 'All', 'mainwp-sites-report' )
			);
			printf(
				'<td>%d</td>',
				$wpdb->get_var( "select count(*) from $mainwp_wp" )
			);
			foreach ( $result as $one ) {
				printf(
					'<td>%s</td>',
					$one->name
				);
			}
			echo '</tr>';
			echo '<tr>';
			foreach ( $result as $one ) {
				printf(
					'<td>%d</td>',
					$one->count
				);
			}
			echo '</tr>';
			echo '</table>';
		} else {
			echo wpautop( esc_html_e( 'no groups', 'mainwp-sites-report' ) );
		}
		echo '</div>';
	}

	/**
	 * i18n
	 *
	 * @since 1.0.0
	 */
	public function action_init_load_plugin_textdomain() {
		load_plugin_textdomain(
			'mainwp-sites-report',
			false,
			plugin_basename( $this->dir ) . '/languages'
		);
	}

}
