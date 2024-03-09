<?php
/**
 * Blocks
 *
 * @package DynamicPricingCalculation
 */
namespace DPC\inc;
use DPC\inc\Traits\Singleton;
class Calc {
	use Singleton;
	protected function __construct() {
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		// add_action( 'elementor/widget/render_content', [$this, 'elementor_widget_render_content'], 10, 2);
	}
    public function get_units($unit = false) {
        $units = [
            'grm'   => __('Gram', 'dpc'),
            'kg'    => __('Kilogram', 'dpc'),
            'ltr'   => __('Litre', 'dpc'),
            'ton'   => __('Ton', 'dpc'),
			// 
            'pcs'   => __('Piece', 'dpc'),
        ];
        if ($unit && !empty($unit)) {
            return isset($units[$unit])?$units[$unit]:false;
        }
        return $units;
    }
    public function get_currencies($unit = false) {
        $currencies = [
            'eur'   => __('Euro', 'dpc'),
            'usd'   => __('USD', 'dpc'),
            'gbp'   => __('GBP', 'dpc'),
        ];
        if ($unit && !empty($unit)) {
            return isset($currencies[$unit])?$currencies[$unit]:false;
        }
        return $currencies;
    }
    public function get_currency_sign($unit = false) {
        $currencies = [
            'eur'   => '€',
            'usd'   => '$',
            'gbp'   => '£',
        ];
        if ($unit && !empty($unit)) {
            return isset($currencies[$unit])?$currencies[$unit]:false;
        }
        return $currencies;
    }

}
