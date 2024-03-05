<?php
/**
 * Metabox Functions
 *
 * @package DynamicPricingCalculation
 */

namespace DPC\inc;
use DPC\inc\Traits\Singleton;

class Metabox {
	use Singleton;
	protected function __construct() {
		$this->setup_hooks();
	}
	public function setup_hooks() {
		add_action('save_post', [$this, 'save_post'], 10, 1);
		add_action('add_meta_boxes', [$this, 'add_meta_boxes'], 10, 0);
	}
	public function save_post($post_id) {
		if (isset($_POST['prices']) && is_array($_POST['prices'])) {
			update_post_meta($post_id, 'prices', sanitize_text_field($_POST['prices']));
			update_post_meta($post_id, 'updated_on', date('M d, Y H:i:s'));
		}
	}
	public function add_meta_boxes() {
		$screens = ['post', 'page', 'book'];
		foreach ($screens as $screen) {
			add_meta_box('prices', __('Calculation Prices', 'dpc'), [$this, 'meta_box_content'], $screen, 'normal', 'high');
		}
	}
	public function meta_box_content() {
		global $post;global $dpc_Calc;
		$prices = get_post_meta($post->ID, 'prices', true);
        $units = $dpc_Calc->get_units();
        $columns = [
            'title'     => __('Product title', 'dpc'),
            'unit'      => __('Unit', 'dpc'),
            'price'     => __('Price', 'dpc'),
            'tax'       => __('Tax', 'dpc'),
        ];
		?>
        <table class="dpc__table">
            <thead class="dpc__thead">
                <tr class="dpc__thead__tr">
                    <?php foreach ($columns as $key => $text) : ?>
                    <th class="dpc__th" data-column="<?php echo esc_attr($key); ?>"><?php echo esc_html($text); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="dpc__tbody">
                <?php foreach ($prices as $index => $row) : ?>
                    <?php if (!is_array($row)) {continue;} ?>
                    <tr class="dpc__trow" data-index="<?php echo esc_attr($index); ?>">
                        <?php foreach ($columns as $key => $text) : ?>
                        <td class="dpc__td" data-column="<?php echo esc_attr($key); ?>">
                            <?php
                            switch ($key) {
                                case 'unit':
                                    ?>
                                    <select name="prices[<?php echo esc_attr($index); ?>][<?php echo esc_attr($key); ?>]" class="dpc__select">
                                        <?php foreach ($units as $value => $label) : ?>
                                        <option value="<?php echo esc_attr($value); ?>" <?php echo esc_attr((isset($row[$key]) && $value == $row[$key])?'selected':''); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php
                                    break;
                                default:
                                    ?>
                                    <input type="<?php echo esc_attr(in_array($key, ['title'])?'text':'number'); ?>" name="prices[<?php echo esc_attr($index); ?>][<?php echo esc_attr($key); ?>]" class="dpc__input" value="<?php echo esc_attr(isset($row[$key])?$row[$key]:0.00); ?>">
                                    <?php
                                    break;
                            }
                            ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="dpc__tfoot">
                <tr>
                    <td>
                        <button type="button" class="dpc__tfoot_repeater" data-columns="<?php echo esc_attr(json_encode(array_keys($columns))); ?>" data-units="<?php echo esc_attr(json_encode($units)); ?>"><?php esc_html_e('Add new Row', 'dpc'); ?></button>
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php echo esc_textarea($text); ?>
		<?php
	}
	
}