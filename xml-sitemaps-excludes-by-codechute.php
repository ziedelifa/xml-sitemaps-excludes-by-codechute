<?php
/**
 * Plugin Name: XML Sitemaps Excludes for Yoast SEO 
 * Version:     1.0
 * Plugin URI:  https://codechute.com/
 * Description: XML Sitemaps Excludes for Yoast SEO.
 * Author:      CodeChute
 * Author URI:  https://codechute.com/
 * Text Domain: yoast-sitemaps-exclude
 * Domain Path: /languages/
 * License:     GPL v3
 */
 
// If this file is called directly, abort.
if(!defined('WPINC')){ die; }
 
define('YSE_REVISION',			'1.0');
define('YSE_PLUGIN_PATH',		plugin_dir_path(__FILE__));
define('YSE_PLUGIN_SLUG_PATH',	plugin_basename(__FILE__));
define('YSE_PLUGIN_URL',	plugin_dir_url(__FILE__));

if(!class_exists('XmlSitemapsExcludesByCodeChute')){
	class XmlSitemapsExcludesByCodeChute{
		/**
		 * Core singleton class
		 * @var self - pattern realization
		 */
		 private static $instance;
		 
		/**
		 * WmlSitemapsExcludesByCodeChute Constructor
		 */
		public function __construct(){
			add_action( 'admin_init', array($this, 'check_requirements') );
			$this->setup();
		}
		public function setup(){
			require_once YSE_PLUGIN_PATH . 'inc/admin/settings.php';
			require_once YSE_PLUGIN_PATH . 'inc/xml-sitemaps-excludes-frontend.php';
			
		}
		
		/**
		 * Get the instane of VC_Manager
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		
		/**
		 * Checks if Yoast SEO activated.
		 *
		 * @return bool
		 */
		public function check_requirements() {
			if(!is_plugin_active('wordpress-seo/wp-seo.php')){
				add_action( 'admin_notices', array($this, 'admin_notice__error') );
				return;
			}
		}
		
		public function admin_notice__error(){
			$class = 'notice notice-error';
			$message = __( 'The following required plugin is currently inactive: Yoast SEO.', 'yoast-sitemaps-exclude' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
		}
	}
}

global $xml_sitemaps_excludes_by_codechute;
if ( ! $xml_sitemaps_excludes_by_codechute ) {
	$xml_sitemaps_excludes_by_codechute = XmlSitemapsExcludesByCodeChute::getInstance();
}
