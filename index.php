<?php
/**
 * Plugin Name: Slides Post Type
 * Description: Adds a slide custom post type and custom taxonomy. 
 * Version: 0.1
 * Author: thesquatchman
 * Licence: GPL3
 */

add_action( 'init', 'codex_slide_init' );
/**
 * Register a slide post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
	// Menu Position
    // 5 - below Posts
    // 10 - below Media
    // 15 - below Links
    // 20 - below Pages
    // 25 - below comments
    // 60 - below first separator
    // 65 - below Plugins
    // 70 - below Users
    // 75 - below Tools
    // 80 - below Settings
    // 100 - below second separator

function codex_slide_init() {
	$labels = array(
		'name'               => _x( 'Slides', 'post type general name', 'wp-squatch-slider' ),
		'singular_name'      => _x( 'Slide', 'post type singular name', 'wp-squatch-slider' ),
		'menu_name'          => _x( 'Slides', 'admin menu', 'wp-squatch-slider' ),
		'name_admin_bar'     => _x( 'Slide', 'add new on admin bar', 'wp-squatch-slider' ),
		'add_new'            => _x( 'Add New', 'Slide', 'wp-squatch-slider' ),
		'add_new_item'       => __( 'Add New Slide', 'wp-squatch-slider' ),
		'new_item'           => __( 'New Slide', 'wp-squatch-slider' ),
		'edit_item'          => __( 'Edit Slide', 'wp-squatch-slider' ),
		'view_item'          => __( 'View Slide', 'wp-squatch-slider' ),
		'all_items'          => __( 'All Slides', 'wp-squatch-slider' ),
		'search_items'       => __( 'Search slides', 'wp-squatch-slider' ),
		'parent_item_colon'  => __( 'Parent slides:', 'wp-squatch-slider' ),
		'not_found'          => __( 'No slides found.', 'wp-squatch-slider' ),
		'not_found_in_trash' => __( 'No slides found in Trash.', 'wp-squatch-slider' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => false,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'slide' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 12,
		'menu_icon'			 => 'dashicons-images-alt2',
		'supports'           => array( 'title', 'thumbnail','page-attributes', 'editor' )
	);

	register_post_type( 'slide', $args );
}

add_action( 'init', 'create_slide_taxonomies', 0 );

// create two taxonomies, product-categorys and writers for the post type "product"
function create_slide_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Slide Gallery', 'taxonomy general name' ),
		'singular_name'     => _x( 'Slide Gallery', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Slide Gallery' ),
		'all_items'         => __( 'All Slide Gallery' ),
		'parent_item'       => __( 'Parent Slide Gallery' ),
		'parent_item_colon' => __( 'Parent Slide Gallery:' ),
		'edit_item'         => __( 'Edit Slide Gallery' ),
		'update_item'       => __( 'Update Slide Gallery' ),
		'add_new_item'      => __( 'Add New Slide Gallery' ),
		'new_item_name'     => __( 'New Slide Gallery Name' ),
		'menu_name'         => __( 'Slide Galleries' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => false,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'slide-gallery' ),
	);

	register_taxonomy( 'slide-gallery', array( 'slide' ), $args );
}




add_filter('manage_slide_posts_columns' , 'add_slide_columns');
 
function add_slide_columns($columns) {
    unset($columns['author']);
    unset($columns['date']);
    return array_merge($columns,
          array( 'slide_order' => 'Order', 'slide_thumbnail' => 'Image'));
}
 
add_action('manage_posts_custom_column' , 'slide_custom_columns', 10, 2 );
 
function slide_custom_columns( $column_name, $post_id ) {
  if ( $column_name == 'slide_thumbnail' ) {
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		if ( $post_thumbnail_id ) {
			$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
			echo '<img style="width: 100px;" src="' . $post_thumbnail_img[0] . '" />';
		}
	}
	if ( $column_name == 'slide_order' ) {
		$order = get_post_field( 'menu_order', $post_id);
		echo '<a class="editinline">'.$order.' <i>(edit)</i></a>';
	}
}