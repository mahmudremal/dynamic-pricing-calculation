<?php
/**
 * Register Menus
 *
 * @package ESignBindingAddons
 */
namespace DPC\inc;
use DPC\inc\Traits\Singleton;
class Menus {
	use Singleton;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		// add_action('init', [$this, 'register_menus']);
		add_filter('dpc/project/settings/general', [$this, 'general'], 10, 1);
		add_filter('dpc/project/settings/fields', [$this, 'menus'], 10, 1);
		add_action('in_admin_header', [$this, 'in_admin_header'], 100, 0);
	}
	public function register_menus() {
		register_nav_menus([
			'aquila-header-menu' => esc_html__('Header Menu', 'dpc'),
			'aquila-footer-menu' => esc_html__('Footer Menu', 'dpc'),
		]);
	}
	/**
	 * Get the menu id by menu location.
	 *
	 * @param string $location
	 *
	 * @return integer
	 */
	public function get_menu_id($location) {
		// Get all locations
		$locations = get_nav_menu_locations();
		// Get object id by location.
		$menu_id = ! empty($locations[$location]) ? $locations[$location] : '';
		return ! empty($menu_id) ? $menu_id : '';
	}
	/**
	 * Get all child menus that has given parent menu id.
	 *
	 * @param array   $menu_array Menu array.
	 * @param integer $parent_id Parent menu id.
	 *
	 * @return array Child menu array.
	 */
	public function get_child_menu_items($menu_array, $parent_id) {
		$child_menus = [];
		if(! empty($menu_array) && is_array($menu_array)) {
			foreach ($menu_array as $menu) {
				if(intval($menu->menu_item_parent) === $parent_id) {
					array_push($child_menus, $menu);
				}
			}
		}
		return $child_menus;
	}
	public function in_admin_header() {
		if(! isset($_GET['page']) || $_GET['page'] != 'crm_dashboard') {return;}
		
		remove_all_actions('admin_notices');
		remove_all_actions('all_admin_notices');
		// add_action('admin_notices', function () {echo 'My notice';});
	}
	/**
	 * Supply necessry tags that could be replace on frontend.
	 * 
	 * @return string
	 * @return array
	 */
	public function commontags($html = false) {
		$arg = [];$tags = [
			'username', 'sitename', 
		];
		if($html === false) {return $tags;}
		foreach($tags as $tag) {
			$arg[] = sprintf("%s{$tag}%s", '<code>{', '}</code>');
		}
		return implode(', ', $arg);
	}
	public function contractTags($tags) {
		$arg = [];
		foreach($tags as $tag) {
			$arg[] = sprintf("%s{$tag}%s", '<code>{', '}</code>');
		}
		return implode(', ', $arg);
	}
	/**
	 * WordPress Option page.
	 * 
	 * @return array
	 */
	public function general($args) {
		return $args;
	}
	public function menus($args) {
		// apply_filters('dpc/project/system/isactive', 'standard-enable')
		$args['standard']	= [
			'title'							=> __('General', 'dpc'),
			'description'					=> __('General settings for teddy-bear customization popup.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'standard-enable',
					'label'					=> __('Enable', 'dpc'),
					'description'			=> __('Mark to enable teddy-bear customization popup.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				// [
				// 	'id' 					=> 'standard-global',
				// 	'label'					=> __('Global product', 'dpc'),
				// 	'description'			=> __('Select a global product that will be replaced if requsted product doesn\'t have any customization popup set.', 'dpc'),
				// 	'type'					=> 'select',
				// 	'default'				=> '',
				// 	'options'				=> $this->get_query(['post_type' => 'product', 'type' => 'option', 'limit' => 500])
				// ],
				[
					'id' 					=> 'standing-global',
					'label'					=> __('Global standing product', 'dpc'),
					'description'			=> __('Select a global standing product that will be replaced if requsted product doesn\'t have any customization popup set.', 'dpc'),
					'type'					=> 'select',
					'default'				=> '',
					'options'				=> $this->get_query(['post_type' => 'product', 'type' => 'option', 'limit' => 500, 'noaccessory' => true])
				],
				[
					'id' 					=> 'sitting-global',
					'label'					=> __('Global sitting product', 'dpc'),
					'description'			=> __('Select a global sitting product that will be replaced if requsted product doesn\'t have any customization popup set.', 'dpc'),
					'type'					=> 'select',
					'default'				=> '',
					'options'				=> $this->get_query(['post_type' => 'product', 'type' => 'option', 'limit' => 500, 'noaccessory' => true])
				],
				[
					'id' 					=> 'standard-forceglobal',
					'label'					=> __('Force global', 'dpc'),
					'description'			=> __('Forcefully globalize this product for all products whether there are customization exists or not.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 					=> 'standard-category',
					'label'					=> __('Cross-sale category', 'dpc'),
					'description'			=> __('Select a cross sale category to suggest on added to cart confirmation popup. Each product under your selected category will be displayed to confirmation popup.', 'dpc'),
					'type'					=> 'select',
					'options'				=> $this->get_query(['post_type' => 'product', 'type' => 'option', 'limit' => 500, 'queryType' => 'term']),
					'default'				=> false
				],
				[
					'id' 					=> 'standard-sitelogo',
					'label'					=> __('Header logo', 'dpc'),
					'description'			=> __('Full url of your site popup logo for popup header. This could be any kind of image formate and optimized resulation.', 'dpc'),
					'type'					=> 'url',
					'default'				=> false
				],
				[
					'id' 					=> 'standard-standingdoll',
					'label'					=> __('Standing teddy image', 'dpc'),
					'description'			=> __('Full url of a standing teddy bear image for first step selecting. This could be any kind of image formate and optimized resulation.', 'dpc'),
					'type'					=> 'url',
					'default'				=> false
				],
				[
					'id' 					=> 'standard-sittingdoll',
					'label'					=> __('Sitting teddy image', 'dpc'),
					'description'			=> __('Full url of a sitting teddy bear image for first step selecting. This could be any kind of image formate and optimized resulation.', 'dpc'),
					'type'					=> 'url',
					'default'				=> false
				],
				[
					'id' 					=> 'standard-accessory',
					'label'					=> __('Default accessory', 'dpc'),
					'description'			=> __('Select a default accessoty that will be effective on customization confirmation.', 'dpc'),
					'type'					=> 'select',
					'options'				=> $this->get_query(['post_type' => 'page', 'type' => 'option', 'limit' => 500]),
					'default'				=> false
				],
			]
		];
		$args['default']		= [
			'title'							=> __('Teddy Meta', 'dpc'),
			'description'					=> __('Teddy bear\'s default data that will be replaced if meta on specific product not exists or empty exists. Existing data won\'t be replaced.', 'dpc'),
			'fields'						=> [
				[
					'id' 						=> 'default-eye',
					'label'					=> __('Eye color', 'dpc'),
					'description'			=> __('Teddy\'s default eye color that will be replaced if meta not exists on birth certificates.', 'dpc'),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'default-brow',
					'label'					=> __('Fur color', 'dpc'),
					'description'			=> __('Teddy\'s default brow color that will be replaced if meta not exists on birth certificates.', 'dpc'),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'default-weight',
					'label'					=> __('Teddy\'s weight', 'dpc'),
					'description'			=> __('Teddy\'s default weight that will be replaced if meta not exists on birth certificates.', 'dpc'),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'default-height',
					'label'					=> __('Teddy\'s height', 'dpc'),
					'description'			=> __('Teddy\'s default height that will be replaced if meta not exists on birth certificates.', 'dpc'),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 						=> 'default-accessoriesUrl',
					'label'					=> __('Accessories url', 'dpc'),
					'description'			=> __('Accessories url that will be applied after user added an item on cart through customization process. It will redirect user to this url when user choose to purches accessories.', 'dpc'),
					'type'					=> 'text',
					'default'				=> ''
				],
			]
		];
		$args['names']			= [
			'title'							=> __('Teddy name', 'dpc'),
			'description'					=> __('List of teddy names that will include in a lottery when user choose to suggest a teddy name.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'names-randomize',
					'label'					=> __('Randomize names', 'dpc'),
					'description'			=> __('Mark to randomize these names before sending to client.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				...$this->optionaize_teddy_names(),
				[
					'id' 					=> 'do_repeater_name',
					'label'					=> '',
					'description'			=> false,
					'type'					=> 'button',
					'default'				=> __('Add another', 'dpc')
				],
			]
		];
		$args['addons']			= [
			'title'							=> __('Addons', 'dpc'),
			'description'					=> __('Necessary addons for after customization process. Including packaging wrappings.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'addons-enable',
					'label'					=> __('Enable', 'dpc'),
					'description'			=> __('Mark to enable wrapping addons.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 					=> 'addons-thumbnail',
					'label'					=> __('Thumbnail URL', 'dpc'),
					'description'			=> __('Full thumbnail URL that will be shown on checkout screen. By default or if you leave blank, it\'ll replace with site icon.', 'dpc'),
					'type'					=> 'url',
					'default'				=> ''
				],
				[
					'id' 					=> 'addons-title',
					'label'					=> __('Title', 'dpc'),
					'description'			=> __('Give here a title not more then 50 chars. Will incude on H3 tag.', 'dpc'),
					'type'					=> 'text',
					'default'				=> false
				],
				[
					'id' 					=> 'addons-subtitle',
					'label'					=> __('Sub-title', 'dpc'),
					'description'			=> __('Give here a short subtitle not more then 30 chars. Will include in H4 tag.', 'dpc'),
					'type'					=> 'text',
					'default'				=> false
				],
				[
					'id' 					=> 'addons-subtitle',
					'label'					=> __('Sub-title', 'dpc'),
					'description'			=> __('Give here a short subtitle not more then 30 chars. Will include in H4 tag.', 'dpc'),
					'type'					=> 'text',
					'default'				=> false
				],
				[
					'id' 					=> 'addons-text',
					'label'					=> __('Description', 'dpc'),
					'description'			=> __('Give here a full descrition that suppose to be short and could be able to give a clear idea about what it is.', 'dpc'),
					'type'					=> 'text',
					'default'				=> false
				],
				[
					'id' 					=> 'addons-feetitle',
					'label'					=> __('Fee label', 'dpc'),
					'description'			=> __('wrapping package feee label for invice, checkout etc.', 'dpc'),
					'type'					=> 'text',
					'default'				=> false
				],
				[
					'id' 					=> 'addons-feeamount',
					'label'					=> __('Fee Amount', 'dpc'),
					'description'			=> __('The amount for the label or wrapping box.', 'dpc'),
					'type'					=> 'text',
					'default'				=> false
				],
			]
		];
		$args['badges']			= [
			'title'							=> __('Badges', 'dpc'),
			'description'					=> __('Products shop grid featured & Best Seller badges', 'dpc'),
			'fields__'						=> [
				[
					'id' 						=> 'badges-enable',
					'label'					=> __('Enable', 'dpc'),
					'description'			=> __('Mark to enable badges on products grid.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'badges-featured',
					'label'					=> __('Enable Featured', 'dpc'),
					'description'			=> __('Mark to enable individual featured badge.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'badges-bestseller',
					'label'					=> __('Enable Best Seller', 'dpc'),
					'description'			=> __('Mark to enable individual best seller badge.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'badges-featured-bgcolor',
					'label'					=> __('Featured BG color', 'dpc'),
					'description'			=> __('Define a color as background color for featured image badge.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#e63f51'
				],
				[
					'id' 						=> 'badges-featured-color',
					'label'					=> __('Featured Text color', 'dpc'),
					'description'			=> __('Define a color as text color for featured image badge.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#ffffff'
				],
				[
					'id' 						=> 'badges-bestseller-bgcolor',
					'label'					=> __('Bestseller BG color', 'dpc'),
					'description'			=> __('Define a color as background color for bestseller image badge.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#FFCF02'
				],
				[
					'id' 						=> 'badges-bestseller-color',
					'label'					=> __('Bestseller Text color', 'dpc'),
					'description'			=> __('Define a color as text color for bestseller image badge.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#333'
				],
				[
					'id' 						=> 'badges-bestseller',
					'label'					=> __('Enable Best Seller', 'dpc'),
					'description'			=> __('Mark to enable individual best seller badge.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'badges-onsale',
					'label'					=> __('Enable On-Sale', 'dpc'),
					'description'			=> __('Mark to enable offer/On Sale badge.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 						=> 'badges-onsale-bgcolor',
					'label'					=> __('On-sale BG color', 'dpc'),
					'description'			=> __('Define a color as background color for onsale badge.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#FFCF02'
				],
				[
					'id' 						=> 'badges-onsale-color',
					'label'					=> __('On-sale Text color', 'dpc'),
					'description'			=> __('Define a color as text color for onsale badge.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#333'
				],
			],
			'fields'						=> [
				...$this->optionaize_teddy_badges(),
				[
					'id' 					=> 'do_repeater_badge',
					'label'					=> '',
					'description'			=> false,
					'type'					=> 'button',
					'default'				=> __('Add another', 'dpc')
				],
			]
		];
		$args['email']			= [
			'title'							=> __('Email', 'dpc'),
			'description'					=> __('Email template & necessey informations.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'email-enable_shipped',
					'label'					=> __('Enable shipped email', 'dpc'),
					'description'			=> __('Mark to enable shipped event email confirmation.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> false
				],
				[
					'id' 					=> 'email-shipped_cc',
					'label'					=> __('Shipped email CC', 'dpc'),
					'description'			=> __('Give here an email address if you wish to send a carbon copy.', 'dpc'),
					'type'					=> 'text',
					'default'				=> ''
				],
				[
					'id' 					=> 'email-shipped_subject',
					'label'					=> __('Enable shipped email', 'dpc'),
					'description'			=> __('Mark to enable shipped event email confirmation.', 'dpc'),
					'type'					=> 'text',
					'default'				=> __('Your Order shipped successfully', 'dpc')
				],
				[
					'id' 					=> 'email-shipped_template',
					'label'					=> __('Shipped template', 'dpc'),
					'description'			=> __('Give here shipping text or html email template with inlined css & no js.', 'dpc'),
					'type'					=> 'textarea',
					'default'				=> sprintf(
						__("Hey {{customer}},\nWe're glad to say that your order has been shipped successfully.\nBest Wishes", 'dpc'),
						// 
					)
				],
				[
					'id' 					=> 'email-shipped_htmlmode',
					'label'					=> __('Shipped email formate', 'dpc'),
					'description'			=> __('Select html if you give html contents on the above field.', 'dpc'),
					'type'					=> 'radio',
					'default'				=> 'text',
					'options'				=> [
						'text'				=> 'Text mode',
						'html'				=> 'HTML mode'
					]
				],
			]
		];
		$args['order']			= [
			'title'							=> __('Order', 'dpc'),
			'description'					=> __('Order information an necessery data.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'order-attach_status',
					'label'					=> __('Attach certificates', 'dpc'),
					'description'			=> __('Give here all WC Order status slug, on that status changed event, certificates will be attached with confirmation.', 'dpc'),
					'type'					=> 'text',
					'default'				=> 'shipped, completed'
				],
				[
					'id' 					=> 'order-certificate_email',
					'label'					=> __('Certificates email', 'dpc'),
					'description'			=> __('Give here order status slug so that when order thatus changed to this slug, it will send a seperate certificate email template with certificates attached.', 'dpc'),
					'type'					=> 'text',
					'default'				=> 'completed'
				],
				[
					'id' 					=> 'order-avoid_askvoice',
					'label'					=> __('Avoid voice button', 'dpc'),
					'description'			=> __('Give here those order status slug that will act like when order status changed to that slug and send a confirmation email, it won\'t put an Send voice file button.', 'dpc'),
					'type'					=> 'text',
					'default'				=> 'shipped, completed'
				],
			]
		];
		$args['voice']			= [
			'title'							=> __('Voice', 'dpc'),
			'description'					=> __('Voice template & necessey informations.', 'dpc'),
			'fields'						=> [

				[
					'id' 					=> 'voice-reminder_enable',
					'label'					=> __('Enable Voice Reminding', 'dpc'),
					'description'			=> __('Marking this checkbox will apear a link button after single order item.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 					=> 'voice-reminder_label',
					'label'					=> __('Button text', 'dpc'),
					'description'			=> __('Give here a button text for the "Send Recorded file" button.', 'dpc'),
					'type'					=> 'text',
					'default'				=> __('Send Recorded voice', 'dpc')
				],
				[
					'id' 					=> 'voice-reminder_bg',
					'label'					=> __('Button Background', 'dpc'),
					'description'			=> __('Pick a color for the button.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#e63f51'
				],
				[
					'id' 					=> 'voice-reminder_color',
					'label'					=> __('Text Color', 'dpc'),
					'description'			=> __('Pick a color for the button text.', 'dpc'),
					'type'					=> 'color',
					'default'				=> '#ffffff'
				],
				[
					'id' 					=> 'voice-reminder_reciever',
					'label'					=> __('Voice Reciever', 'dpc'),
					'description'			=> sprintf(
						__('Give here an Email address where clients would be replied with. Site admin address is %s', 'dpc'),
						get_option('admin_email')
					),
					'type'					=> 'email',
					'default'				=> get_option('admin_email')
				],
				[
					'id' 					=> 'voice-reminder_subject',
					'label'					=> __('Email Subject', 'dpc'),
					'description'			=> sprintf(
						__('Give here an Email subject format that would be replaced with these magic word below. For Order ID: %s, for Item ID: %s.', 'dpc'),
						'<strong>{{order_id}}</strong>', '<strong>{{item_id}}</strong>'
					),
					'type'					=> 'text',
					'default'				=> 'Order #{{order_id}}'
				],
				// [
				// 	'id' 					=> 'voice-reminder_orderstatuses',
				// 	'label'					=> __('Visible on Statuses', 'dpc'),
				// 	'description'			=> __('Give here all of the order statuses those are allowed to show voice reminder button visibility.', 'dpc'),
				// 	'type'					=> 'text',
				// 	'default'				=> str_replace('wc-', '', implode(', ', function_exists('wc_get_order_statuses')?array_keys((array) wc_get_order_statuses()):['processing']))
				// ],
				
			]
		];
		$args['certificate']	= [
			'title'							=> __('Certificate', 'dpc'),
			'description'					=> __('Certificate template & necessey informations.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'certificate-enable',
					'label'					=> __('Enable Certification', 'dpc'),
					'description'			=> __('Mark this option to enable or disable certification on order line.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 					=> 'certificate-onstatuses',
					'label'					=> __('Order Statuses', 'dpc'),
					'description'			=> __('Give here those order statuses where you want to allow certificates.', 'dpc'),
					'type'					=> 'text',
					'default'				=> 'completed, shipped'
				],
				[
					'id' 					=> 'certificate-myacc-enable',
					'label'					=> __('Abailable on My-Account', 'dpc'),
					'description'			=> __('Mark this option to show available certificates on user bashboard called my-account order details screen.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 					=> 'certificate-404template',
					'label'					=> __('404 Template', 'dpc'),
					'description'			=> __('Select a template for certificate not found issue.', 'dpc'),
					'type'					=> 'select',
					'default'				=> false,
					'options'				=> $this->get_query(['post_type' => 'elementor_library', 'type' => 'option', 'limit' => 500])
				],
				
			]
		];
		$args['translate']		= [
			'title'							=> __('Translate', 'dpc'),
			'description'					=> __('Setup your translations related informations here.', 'dpc'),
			'fields'						=> [
				[
					'id' 					=> 'translate-enable',
					'label'					=> __('Enable translation', 'dpc'),
					'description'			=> __('Enable live translations those are setting from here Required API key.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 					=> 'translate-toonly',
					'label'					=> __('Translate to', 'dpc'),
					'description'			=> false, // __('', 'dpc'),
					'type'					=> 'radio',
					'options'				=> [
						'user'				=> __('User Profile', 'dpc'),
						'site'				=> __('Site Language', 'dpc'),
					],
					// 'default'				=> 'site'
				],
				// [
				// 	'id' 					=> 'translate-api',
				// 	'label'					=> __('API key', 'dpc'),
				// 	'description'			=> __('Provice lecto.ai api key to translate text. Text will store after translation to avoid api limit exceed.', 'dpc'),
				// 	'type'					=> 'text',
				// 	'default'				=> ''
				// ],
				/**
				 * Repeater
				 */
				[
					'id' 					=> 'repeater_translate',
					'label'					=> '',
					'description'			=> false,
					'type'					=> 'button',
					'default'				=> __('Translation list', 'dpc')
				],
			]
		];
		$args['cusrev']			= [
			'title'							=> __('Review', 'dpc'),
			'description'					=> __('Setup your custom settings for woocommerce customer review plugin.', 'dpc'),
			'fields'						=> [

				[
					'id' 					=> 'cusrev-completedorder-link',
					'label'					=> __('Completed order Link', 'dpc'),
					'description'			=> __('Mark to enable link pushing on completed order notification.', 'dpc'),
					'type'					=> 'checkbox',
					'default'				=> true
				],
				[
					'id' 					=> 'cusrev-completedorder-css',
					'label'					=> __('Completed order CSS', 'dpc'),
					'description'			=> __('"Write a Review" button inline CSS. Button is and HTML <a> element.', 'dpc'),
					'type'					=> 'text',
					'default'				=> 'font-weight:normal;background:#0085ba;border-color:#0073aa;color:#fff;text-decoration:none;padding:10px;border-radius:10px;'
				],
				[
					'id' 					=> 'cusrev-completedorder-text',
					'label'					=> __('Completed order Text', 'dpc'),
					'description'			=> __('Setup custom button text here for completed order review link.', 'dpc'),
					'type'					=> 'text',
					'default'				=> 'Write a Review'
				],
				[
					'id' 					=> 'cusrev-completedorder-template',
					'label'					=> __('Completed order HTML template', 'dpc'),
					'description'			=> sprintf(__('Give here HTML template for the section of Review Link. Use (%s) for the place of the Button. Use (%s) for the link only.', 'dpc'), '{{button}}', '{{link}}'),
					'type'					=> 'text',
					'default'				=> '{{button}}'
				],
				
			]
		];

		unset($args['email']);
		return $args;
	}
	public function get_query($args) {
		global $teddy_Plushies;
		$args = (object) wp_parse_args($args, [
			'post_type'		=> 'product',
			'type'			=> 'option',
			'limit'			=> 500,
			'queryType'		=> 'post',
			'noaccessory'	=> false
		]);
		$options = [];
		if($args->queryType == 'post') {
			$query = get_posts([
				'numberposts'		=> $args->limit,
				'post_type'			=> $args->post_type,
				'order'				=> 'DESC',
				'orderby'			=> 'date',
				'post_status'		=> 'publish',
				
			]);
			foreach($query as $_post) {
				if($args->noaccessory && $teddy_Plushies->is_accessory($_post->ID)) {continue;}
				$options[$_post->ID] = get_the_title($_post->ID);

				// Function to remove popup customization meta.
				// _product_custom_popup || _teddy_custom_data
				// $meta = get_post_meta($_post->ID, '_product_custom_popup', true);
				// $exists = get_post_meta($_post->ID, '_product_custom_popup_stagged', true);
				// if(! $meta && $exists) {
				// 	update_post_meta($_post->ID, '_product_custom_popup', $exists);
				// 	$updated = delete_post_meta($_post->ID, '_product_custom_popup_stagged');
				// 	if(!$updated) {echo 'post meta failed to removed';}
				// }
				
			}
		} else if($args->queryType == 'term') {
			$query = get_categories('taxonomy=product_cat&post_type=product');
			foreach($query as $_post) {
				$options[$_post->cat_ID] = $_post->cat_name;
			}
		} else {}
		return $options;
	}
	public function optionaize_teddy_names() {
		$args = [];$filteredData = [];
		foreach((array) DPC_OPTIONS as $key => $value) {
			if(strpos($key, 'teddy-name-') !== false) {
				$filteredData[] = $value;
			}
		}
		foreach($filteredData as $i => $name) {
			$args[] = [
				'id' 					=> 'teddy-name-' . $i,
				'label'					=> sprintf('%s%s', __('#', 'dpc'), number_format_i18n($i, 0)),
				'description'			=> false,
				'type'					=> 'text',
				'default'				=> $name
			];
		}
		return $args;
	}
	public function optionaize_teddy_badges() {
		$args = [];$filteredData = [];$filteredRow = [];
		foreach((array) DPC_OPTIONS as $key => $value) {
			if(strpos($key, 'teddy-badge-') !== false) {
				$filteredData[$key] = $value;
			}
		}
		// try {
			foreach($filteredData as $key => $value) {
				$key = substr($key, 12);$split = explode('-', $key);
				$filteredRow[$split[1]] = isset($filteredRow[$split[1]])?$filteredRow[$split[1]]:[];
				$filteredRow[$split[1]][$split[0]] = $value;
			}
		// } catch (\Exception $th) {
		// 	echo 'Message: ' . $e->getMessage();
		// }
		foreach($filteredRow as $i => $badge) {
			$args[] = [
				'id' 					=> 'teddy-badge-enable-' . $i,
				'label'					=> sprintf('#%s %s', number_format_i18n($i, 0), __('Enable', 'dpc')),
				'description'			=> __('Mark to enable this badge everyehere', 'dpc'),
				'type'					=> 'checkbox',
				'default'				=> isset($badge['enable'])?$badge['enable']:false
			];
			$args[] = [
				'id' 					=> 'teddy-badge-label-' . $i,
				'label'					=> sprintf('#%s %s', number_format_i18n($i, 0), __('Label text', 'dpc')),
				'description'			=> __('Label name will be displayed on the badge on product card.', 'dpc'),
				'type'					=> 'text',
				'default'				=> isset($badge['label'])?$badge['label']:false
			];
			$args[] = [
				'id' 					=> 'teddy-badge-backgound-' . $i,
				'label'					=> sprintf('#%s %s', number_format_i18n($i, 0), __('Backgound Color', 'dpc')),
				'description'			=> __('Backgound color of this badge.', 'dpc'),
				'type'					=> 'color',
				'default'				=> isset($badge['backgound'])?$badge['backgound']:false
			];
			$args[] = [
				'id' 					=> 'teddy-badge-textcolor-' . $i,
				'label'					=> sprintf('#%s %s', number_format_i18n($i, 0), __('Text color', 'dpc')),
				'description'			=> __('Text color of this badge.', 'dpc'),
				'type'					=> 'color',
				'default'				=> isset($badge['textcolor'])?$badge['textcolor']:false
			];
		}
		return $args;
	}
}

/**
 * {{client_name}}, {{client_address}}, {{todays_date}}, {{retainer_amount}}
 */
