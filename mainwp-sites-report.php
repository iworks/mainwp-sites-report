<?php
/*
Plugin Name: MainWP — Sites Report
Text Domain: mainwp-sites-report
Plugin URI: http://iworks.pl/mainwp-sites-report/
Description:
Version: PLUGIN_VERSION
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Copyright 2023-PLUGIN_TILL_YEAR Marcin Pietrzak (marcin@iworks.pl)

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * static options
 */
define( 'IWORKS_IMWPSR__VERSION', 'PLUGIN_VERSION' );
define( 'IWORKS_IMWPSR__PREFIX', 'iworks_mainwp-sites-report_' );
$base   = dirname( __FILE__ );
$vendor = $base . '/includes';

/**
 * require: Iworksmainwp-sites-report Class
 */
if ( ! class_exists( 'iworks_mainwp_sites_report' ) ) {
	require_once $vendor . '/iworks/class-mainwp-sites-report.php';
}
/**
 * configuration
 */
require_once $base . '/etc/options.php';
/**
 * require: IworksOptions Class
 */
if ( ! class_exists( 'iworks_options' ) ) {
	require_once $vendor . '/iworks/options/options.php';
}
/**
 * load posttypes - change to `__return_true' to load
 */
add_filter( 'mainwp-sites-report/load/posttype/faq', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/hero', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/opinion', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/page', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/person', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/post', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/project', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/promo', '__return_false' );
add_filter( 'mainwp-sites-report/load/posttype/publication', '__return_false' );

/**
 * load options
 */
global $iworks_mainwp_sites_report_options;
$iworks_mainwp_sites_report_options = new iworks_options();
$iworks_mainwp_sites_report_options->set_option_function_name( 'iworks_mainwp_sites_report_options' );
$iworks_mainwp_sites_report_options->set_option_prefix( IWORKS_IMWPSR__PREFIX );

function iworks_mainwp_sites_report_get_options() {
	global $iworks_mainwp_sites_report_options;
	return $iworks_mainwp_sites_report_options;
}

function iworks_mainwp_sites_report_options_init() {
	global $iworks_mainwp_sites_report_options;
	$iworks_mainwp_sites_report_options->options_init();
}

function iworks_mainwp_sites_report_activate() {
	$iworks_mainwp_sites_report_options = new iworks_options();
	$iworks_mainwp_sites_report_options->set_option_function_name( 'iworks_mainwp_sites_report_options' );
	$iworks_mainwp_sites_report_options->set_option_prefix( IWORKS_IMWPSR__PREFIX );
	$iworks_mainwp_sites_report_options->activate();
}

function iworks_mainwp_sites_report_deactivate() {
	global $iworks_mainwp_sites_report_options;
	$iworks_mainwp_sites_report_options->deactivate();
}

$iworks_mainwp_sites_report = new iworks_mainwp_sites_report();

/**
 * install & uninstall
 */
register_activation_hook( __FILE__, 'iworks_mainwp_sites_report_activate' );
register_deactivation_hook( __FILE__, 'iworks_mainwp_sites_report_deactivate' );
