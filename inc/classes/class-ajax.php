<?php
/**
 * Add ajax requests
 *
 * @package DynamicPricingCalculation
 */

namespace DPC\inc;
use DPC\inc\Traits\Singleton;

class Ajax {
	use Singleton;
	protected function __construct() {
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		// add_action('wp_ajax_dpc/action/get_autocomplete', [$this, 'get_autocomplete'], 10, 0);
		// add_action('wp_ajax_nopriv_dpc/action/get_autocomplete', [$this, 'get_autocomplete'], 10, 0);
	}
}
