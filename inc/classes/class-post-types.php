<?php
/**
 * Register Post Types
 *
 * @package ESignBindingAddons
 */
namespace DPC\inc;
use DPC\inc\Traits\Singleton;
class Post_Types {
	use Singleton;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		add_action('init', [$this, 'create_cpt'], 10, 0);
	}
	// Register Custom Post Type esignature
	public function create_cpt() {
		$icon = untrailingslashit(DPC_BUILD_PATH.'/icons/contract.svg');
		$icon = (file_exists($icon)&&!is_dir($icon))?esc_url(DPC_BUILD_URI.'/icons/contract.svg'):'dashicons-superhero';

		$labels = [
			'name'                  => _x('Pricing tables', 'Post Type General Name', 'dpc'),
			'singular_name'         => _x('Pricing table', 'Post Type Singular Name', 'dpc'),
			'menu_name'             => _x('Pricing tables', 'Admin Menu text', 'dpc'),
			'name_admin_bar'        => _x('Pricing table', 'Add New on Toolbar', 'dpc'),
			'archives'              => __('Pricing table Archives', 'dpc'),
			'attributes'            => __('Pricing table Attributes', 'dpc'),
			'parent_item_colon'     => __('Parent Pricing table:', 'dpc'),
			'all_items'             => __('All Pricing tables', 'dpc'),
			'add_new_item'          => __('Add New Pricing table', 'dpc'),
			'add_new'               => __('Add New', 'dpc'),
			'new_item'              => __('New Pricing table', 'dpc'),
			'edit_item'             => __('Edit Pricing table', 'dpc'),
			'update_item'           => __('Update Pricing table', 'dpc'),
			'view_item'             => __('View Pricing table', 'dpc'),
			'view_items'            => __('View Pricing tables', 'dpc'),
			'search_items'          => __('Search Pricing table', 'dpc'),
			'not_found'             => __('Not found', 'dpc'),
			'not_found_in_trash'    => __('Not found in Trash', 'dpc'),
			'featured_image'        => __('Featured Image', 'dpc'),
			'set_featured_image'    => __('Set featured image', 'dpc'),
			'remove_featured_image' => __('Remove featured image', 'dpc'),
			'use_featured_image'    => __('Use as featured image', 'dpc'),
			'insert_into_item'      => __('Insert into Pricing table', 'dpc'),
			'uploaded_to_this_item' => __('Uploaded to this Pricing table', 'dpc'),
			'items_list'            => __('Pricing tables list', 'dpc'),
			'items_list_navigation' => __('Pricing tables list navigation', 'dpc'),
			'filter_items_list'     => __('Filter Pricing tables list', 'dpc'),
		];
		$args   = [
			'label'               => __('Pricing table', 'dpc'),
			'description'         => __('The Pricing tables', 'dpc'),
			'labels'              => $labels,
			'menu_icon'           => $icon,
			'supports'            => [
				'title',
				// 'editor',
				// 'excerpt',
				// 'thumbnail',
				// 'revisions',
				'author',
				// 'comments',
				// 'trackbacks',
				// 'page-attributes',
				// 'custom-fields',
			],
			'taxonomies'          => [],
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		];
		register_post_type('pricestable', $args);
	}
}
