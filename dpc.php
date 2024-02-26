<?php
/**
 * This plugin ordered by a client and done by Remal Mahmud (fiverr.com/mahmud_remal). Authority dedicated to that cient.
 *
 * @wordpress-plugin
 * Plugin Name:       Dynamic Pricing Calculation
 * Plugin URI:        https://github.com/mahmudremal/dynamic-pricing-calculation/
 * Description:       Customizable Pricing table with simple conditional calculation.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Remal Mahmud
 * Author URI:        https://github.com/mahmudremal/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       teddybearsprompts
 * Domain Path:       /languages
 * 
 * @package QuizAndFilterSearch
 * @author  Remal Mahmud (https://github.com/mahmudremal)
 * @version 1.0.2
 * @link https://github.com/mahmudremal/dynamic-pricing-calculation/
 * @category	WordPress Plugin
 * @copyright	Copyright (c) 2024-26
 * 
 */

/**
 * Bootstrap the plugin.
 */



defined('DPC__FILE__') || define('DPC__FILE__', untrailingslashit(__FILE__));
defined('DPC_DIR_PATH') || define('DPC_DIR_PATH', untrailingslashit(plugin_dir_path(DPC__FILE__)));
defined('DPC_DIR_URI') || define('DPC_DIR_URI', untrailingslashit(plugin_dir_url(DPC__FILE__)));
defined('DPC_BUILD_URI') || define('DPC_BUILD_URI', untrailingslashit(DPC_DIR_URI ) . '/assets/build');
defined('DPC_BUILD_PATH') || define('DPC_BUILD_PATH', untrailingslashit(DPC_DIR_PATH ) . '/assets/build');
defined('DPC_BUILD_JS_URI') || define('DPC_BUILD_JS_URI', untrailingslashit(DPC_DIR_URI ) . '/assets/build/js');
defined('DPC_BUILD_JS_DIR_PATH') || define('DPC_BUILD_JS_DIR_PATH', untrailingslashit(DPC_DIR_PATH ) . '/assets/build/js');
defined('DPC_BUILD_IMG_URI') || define('DPC_BUILD_IMG_URI', untrailingslashit(DPC_DIR_URI ) . '/assets/build/src/img');
defined('DPC_BUILD_CSS_URI') || define('DPC_BUILD_CSS_URI', untrailingslashit(DPC_DIR_URI ) . '/assets/build/css');
defined('DPC_BUILD_CSS_DIR_PATH') || define('DPC_BUILD_CSS_DIR_PATH', untrailingslashit(DPC_DIR_PATH ) . '/assets/build/css');
defined('DPC_BUILD_LIB_URI') || define('DPC_BUILD_LIB_URI', untrailingslashit(DPC_DIR_URI ) . '/assets/build/library');
defined('DPC_ARCHIVE_POST_PER_PAGE') || define('DPC_ARCHIVE_POST_PER_PAGE', 9);
defined('DPC_SEARCH_RESULTS_POST_PER_PAGE') || define('DPC_SEARCH_RESULTS_POST_PER_PAGE', 9);
defined('DPC_OPTIONS') || define('DPC_OPTIONS', get_option('dpc'));
defined('DPC_UPLOAD_DIR') || define('DPC_UPLOAD_DIR', wp_upload_dir()['basedir'].'/custom_popup/');
defined('DPC_AUDIO_DURATION') || define('DPC_AUDIO_DURATION', 20);

require_once DPC_DIR_PATH . '/inc/helpers/autoloader.php';
// require_once DPC_DIR_PATH . '/inc/helpers/template-tags.php';

if(!function_exists('dpc_get_instance')) {
	function dpc_get_instance() {\DPC\inc\Project::get_instance();}
	dpc_get_instance();
}




