<?php
/**
 * Enqueue Plugin assets
 *
 * @package DynamicPricingCalculation
 */

 namespace DPC\inc;
 use DPC\inc\Traits\Singleton;

class Assets {
	use Singleton;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action('wp_enqueue_scripts', [$this, 'register_styles']);
		add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
		
		add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'], 10, 1);
		add_filter('futurewordpress/project/ctto/javascript/siteconfig', [$this, 'siteConfig'], 1, 2);
	}
	public function register_styles() {
		// Register styles.
		$version = $this->filemtime(DPC_BUILD_CSS_DIR_PATH . '/public.css');
		wp_register_style('ctto-public', DPC_BUILD_CSS_URI . '/public.css', [], $version, 'all');
		// Enqueue Styles.
		wp_enqueue_style('ctto-public');
		// if($this->allow_enqueue()) {}
	}
	public function register_scripts() {
		// Register scripts.
		$version = $this->filemtime(DPC_BUILD_JS_DIR_PATH.'/public.js');
		wp_register_script('ctto-public', DPC_BUILD_JS_URI . '/public.js', ['jquery'], $version.'.'.rand(0, 999), true);
		wp_enqueue_script('ctto-public');
		wp_localize_script('ctto-public', 'fwpSiteConfig', apply_filters('futurewordpress/project/ctto/javascript/siteconfig', []));
	}
	private function allow_enqueue() {
		return (function_exists('is_checkout') && (is_checkout() || is_order_received_page() || is_wc_endpoint_url('order-received')));
	}
	public function admin_enqueue_scripts($curr_page) {
		global $post;
		// if(!in_array($curr_page, ['post-new.php', 'post.php', 'edit.php', 'order-terms'])) {return;}
		wp_register_style('ctto-admin', DPC_BUILD_CSS_URI . '/admin.css', [], $this->filemtime(DPC_BUILD_CSS_DIR_PATH . '/admin.css'), 'all');
		wp_register_script('ctto-admin', DPC_BUILD_JS_URI . '/admin.js', ['jquery'], $this->filemtime(DPC_BUILD_JS_DIR_PATH . '/admin.js'), true);
		
		// if(!in_array($curr_page, ['settings_page_ctto'])) {}
		wp_enqueue_style('ctto-admin');
		wp_enqueue_script('ctto-admin');
		wp_enqueue_style('ctto-public');wp_enqueue_script('ctto-admin');
		wp_localize_script('ctto-admin', 'fwpSiteConfig', apply_filters('futurewordpress/project/ctto/javascript/siteconfig', [
			// 
		], true));
	}
	private function filemtime($path) {
		return (file_exists($path)&&!is_dir($path))?filemtime($path):false;
	}
	public function siteConfig($args, $is_admin = false) {
		$args = wp_parse_args([
			'ajaxUrl'    		=> admin_url('admin-ajax.php'),
			'ajax_nonce' 		=> wp_create_nonce('futurewordpress/project/ctto/verify/nonce'),
			'is_admin' 			=> is_admin(),
			'buildPath'  		=> DPC_BUILD_URI,
			'audioDuration'  	=> DPC_AUDIO_DURATION,
			'siteLogo'			=> apply_filters('ctto/project/system/getoption', 'standard-sitelogo', false),
			'i18n'					=> [
				'pls_wait'			=> __('Please wait...', 'ctto'),
			],
			'local'				=> apply_filters('ctto/project/system/get_locale', get_user_locale())
			
		], (array) $args);
		
		if ($is_admin) {
			// admin scripts here
		} else {
			// public scripts here.
			$args['notifications'] = apply_filters('ctto/project/assets/notifications', false, []);
		}
		
		return $args;
	}
}
