<?php
/**
 * Plugin Name:       Woo Hidden Product
 * Description:       A handy little plugin to contain your theme customisation snippets.
 * Plugin URI:        http://github.com/woothemes/theme-customisations
 * Version:           1.0.0
 * Author:            NicolaErario
 * Author URI:        https://www.woocommerce.com/
 * Requires at least: 3.0.0
 * Tested up to:      4.4.2
 *
 * @package Woo_hiddenproduct
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Woo_hiddenproduct Class
 *
 * @class Woo_hiddenproduct
 * @version	1.0.0
 * @since 1.0.0
 * @package	Woo_hiddenproduct
 */
final class Woo_hiddenproduct {

	/**
	 * Set up the plugin
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'Woo_hiddenproduct_setup' ), -1 );
		require_once( 'custom/functions.php' );
	}

	/**
	 * Setup all the things
	 */
	public function Woo_hiddenproduct_setup() {
		add_action( 'wp_enqueue_scripts', array( $this, 'Woo_hiddenproduct_css' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'Woo_hiddenproduct_js' ) );
		add_filter( 'template_include',   array( $this, 'Woo_hiddenproduct_template' ), 11 );
		add_filter( 'wc_get_template',    array( $this, 'Woo_hiddenproduct_wc_get_template' ), 11, 5 );
	}

	/**
	 * Enqueue the CSS
	 *
	 * @return void
	 */
	public function Woo_hiddenproduct_css() {
		wp_enqueue_style( 'custom-css', plugins_url( '/custom/style.css', __FILE__ ) );
	}

	/**
	 * Enqueue the Javascript
	 *
	 * @return void
	 */
	public function Woo_hiddenproduct_js() {
		wp_enqueue_script( 'custom-js', plugins_url( '/custom/custom.js', __FILE__ ), array( 'jquery' ) );
	}

	/**
	 * Look in this plugin for template files first.
	 * This works for the top level templates (IE single.php, page.php etc). However, it doesn't work for
	 * template parts yet (content.php, header.php etc).
	 *
	 * Relevant trac ticket; https://core.trac.wordpress.org/ticket/13239
	 *
	 * @param  string $template template string.
	 * @return string $template new template string.
	 */
	public function Woo_hiddenproduct_template( $template ) {
		if ( file_exists( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/custom/templates/' . basename( $template ) ) ) {
			$template = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/custom/templates/' . basename( $template );
		}

		return $template;
	}

	/**
	 * Look in this plugin for WooCommerce template overrides.
	 *
	 * For example, if you want to override woocommerce/templates/cart/cart.php, you
	 * can place the modified template in <plugindir>/custom/templates/woocommerce/cart/cart.php
	 *
	 * @param string $located is the currently located template, if any was found so far.
	 * @param string $template_name is the name of the template (ex: cart/cart.php).
	 * @return string $located is the newly located template if one was found, otherwise
	 *                         it is the previously found template.
	 */
	public function Woo_hiddenproduct_wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
		$plugin_template_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/custom/templates/woocommerce/' . $template_name;

		if ( file_exists( $plugin_template_path ) ) {
			$located = $plugin_template_path;
		}

		return $located;
	}
} // End Class

/**
 * The 'main' function
 *
 * @return void
 */
function Woo_hiddenproduct_main() {
	new Woo_hiddenproduct();
}

/**
 * Initialise the plugin
 */
add_action( 'plugins_loaded', 'Woo_hiddenproduct_main' );
