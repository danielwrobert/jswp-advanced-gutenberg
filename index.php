<?php

/**
 * Plugin Name: Gutenberg Blocks Demo
 * Plugin URI: https://github.com/danielwrobert/jswp-advanced-gutenberg
 * Description: This is a demo plugin that can be used as a starting point for a single plugin that contains multiple blocks.
 * Version: 1.1.0
 * Author: Dan Robert
 *
 * @package jswp-advanced-gutenberg
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load all translations for our plugin from the MO file.
*/
function jswp_advanced_gutenberg_load_textdomain() {
	load_plugin_textdomain( 'jswp-advanced-gutenberg', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'init', 'jswp_advanced_gutenberg_load_textdomain' );

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function jswp_advanced_gutenberg_register_block() {

	// Fail if block editor is not supported
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// automatically load dependencies and version
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php' );

	wp_register_script(
		'jswp-advanced-gutenberg',
		plugins_url( 'build/index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version']
	);

	// List all of the blocks for your plugin
	$blocks = [
		'demo',
	];

	// Register each block with same CSS and JS
	foreach ( $blocks as $block ) {
		register_block_type(
			'jswp-advanced-gutenberg/' . $block,
			[
				'editor_script' => 'jswp-advanced-gutenberg',
			]
		);
	}

	if ( function_exists( 'wp_set_script_translations' ) ) {
		/**
		 * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
		 * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
		 * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
		 */
		wp_set_script_translations( $block, 'jswp-advanced-gutenberg' );
	}
}
add_action( 'init', 'jswp_advanced_gutenberg_register_block' );

/**
 * Sets containing category for blocks in admin editor
 */
function jswp_advanced_gutenberg_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'jswp-advanced-blocks',
				'title' => esc_html__( 'JSforWP Advanced Blocks', 'jswp-advanced-gutenberg' ),
			),
		)
	);
}
add_filter( 'block_categories', 'jswp_advanced_gutenberg_category', 10, 2 );

