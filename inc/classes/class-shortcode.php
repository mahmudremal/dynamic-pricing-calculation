<?php
/**
 * Shortcode template functions
 *
 * @package DynamicPricingCalculation
 */

namespace DPC\inc;
use DPC\inc\Traits\Singleton;

class Shortcode {
	use Singleton;
	protected function __construct() {
		$this->setup_hooks();
	}
	public function setup_hooks() {
		// add_filter('manage_edit-product_columns', [$this, 'manage_edit_product_columns'], 10, 0);
		// add_action('manage_posts_custom_column', [$this, 'manage_posts_custom_column'], 10, 0);
		add_shortcode('dpc_table', [$this, 'dpc_table']);
	}
	/**
	 * Shortcode [dpc_table] comes with price calculation table where user could input quantity and se the calculated prices
	 * @param tableid is to include the table ID which have to show here.
	 * @param trace meant whether it will show the prices increation or decreation.
	 * @param summary meant whether it will show sub total, tax and total overview below the table
	 * 
	 * @return string
	 */
	public function dpc_table($args = []) {
		global $post;global $dpc_Calc;
		$args = (object) wp_parse_args($args, [
			'tableid'		=> false,
			'trace'			=> true,
			'summary'		=> true,
			'tax'			=> false,
		]);

		if (!$args->tableid) {return '';}
		$prices		= get_post_meta($args->tableid, 'prices', true);
		$updated_on	= get_post_meta($args->tableid, 'updated_on', true);
		$units = $dpc_Calc->get_units();
		$columns = [
            'title'     => __('Metal type', 'dpc'),
            'price'      => __('Unit Price', 'dpc'),
            'unit'     => __('Unit Qty', 'dpc'),
            // 'tax'	=> __('Tax', 'dpc'),
            'cost'      => __('Cost', 'dpc'),
        ];
		if ($args->tax) {
			$columns['tax'] = __('Tax', 'dpc');
		}
		ob_start();
		?>
		<div class="dpc">
			<div class="dpc__container">
				<div class="dpc__wrapper">
					<div class="dpc__responsive">
						<div class="dpc__caption">
							<span class="dpc__caption__text"><?php esc_html_e('Last Update:', 'dpc'); ?></span>
							<span class="dpc__caption__date"><?php echo esc_html(wp_date('M d, Y', strtotime($updated_on))); ?></span>
						</div>
						<table class="dpc__table" data-table-id="<?php echo esc_attr($args->tableid); ?>">
							<thead class="dpc__thead">
								<tr class="dpc__tr">
									<?php foreach ($columns as $key => $text) : ?>
									<th class="dpc__th" data-column="<?php echo esc_attr($key); ?>"><?php echo esc_html($text); ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody class="dpc__tbody">
								<?php foreach ($prices as $index => $row) : ?>
									<?php if (!is_array($row)) {continue;} ?>
									<tr class="dpc__tr">
										<?php foreach ($columns as $key => $text) : ?>
										<td class="dpc__td" data-column="<?php echo esc_attr($key); ?>">
											<?php
											switch ($key) {
												case 'unit':
													// step="0.01" min="0.01"
													?>
													<input type="number" name="stored[<?php echo esc_attr($index); ?>]" class="dpc__input" value="<?php echo esc_attr(isset($row[$key])?$row[$key]:0.00); ?>" data-index="<?php echo esc_attr($index); ?>" data-price="<?php echo esc_attr((float) $row['price']); ?>">
													<?php
													break;
												default:
													?>
													<?php
													if ($key == 'price') :
														$is_reducing = (isset($row['prev']) && $row['prev'] > $row['price']);
														?>
														<span class="dpc__trace" data-index="<?php echo esc_attr($index); ?>">
															<span class="dashicons dashicons-arrow-<?php echo esc_attr($is_reducing?'down':'up'); ?>"></span>
														<?php
													endif;
													?>
													<span class="dpc__<?php echo esc_attr($key); ?>" data-index="<?php echo esc_attr($index); ?>" data-currency="<?php echo esc_attr($dpc_Calc->get_currency_sign($row['currency'])); ?>">
														<?php echo esc_html(
															isset($row[$key])?(
																($key == 'price')?sprintf(
																	isset($row['per_unit'])?$row['per_unit']:'%s per %s', $row[$key], $row['unit']):$row[$key]
															):0.00
														); ?>
													</span>
													<?php
													if ($key == 'price') :
														?>
														</span>
													<?php
													endif;
													break;
											}
											?>
										</td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

}
