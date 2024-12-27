<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function iworks_mainwp_sites_report_options() {
	$options = array();
	//$parent = SET SOME PAGE;
	/**
	 * main settings
	 */
	$options['index'] = array(
		'version'    => '0.0',
		'page_title' => __( 'Configuration', 'mainwp-sites-report' ),
		'menu'       => 'options',
		// 'parent' => $parent,
		'options'    => array(),
		'metaboxes'  => array(),
		'pages'      => array(),
	);
	return $options;
}

