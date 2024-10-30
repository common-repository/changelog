<?php
/**
 * Plugin Name: Changelog
 * Plugin URI: http://en.kilo-moana.com/
 * Description: Simple Plugin to display a Changelog on an App, Plugin or whatever else page.
 * Version: 0.1
 * Author: Alexander Siemer-Schmetzke
 * Author URI: http://en.kilo-moana.com/
 * Text Domain: changelog-plugin
 * License: GPLv2
 */

function create_changelog_postype() {

    $labels = array(
        'name' => _x('Changelog', 'Changelog', 'changelog-plugin'),
        'singular_name' => _x('Changelog', 'Changelog', 'changelog-plugin'),
        'add_new' => _x('New Version', 'New Version', 'changelog-plugin'),
        'add_new_item' => __('Add new Version', 'changelog-plugin'),
        'edit_item' => __('Edit Version', 'changelog-plugin'),
        'new_item' => __('New Version', 'changelog-plugin'),
        'view_item' => __('View Version', 'changelog-plugin'),
        'search_items' => __('Search Version', 'changelog-plugin'),
        'not_found' =>  __('No Version found', 'changelog-plugin'),
        'not_found_in_trash' => __('No Version found in Trash', 'changelog-plugin'),
        'parent_item_colon' => '',
    );

    $args = array(
        'label' => __('Changelog', 'changelog-plugin'),
        'labels' => $labels,
        'public' => false,
        'exclude_from_search' => true,
        'can_export' => true,
        'show_ui' => true,
        'menu_position'     => 38,
        '_builtin' => false,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array( "slug" => "version" ),
        'supports'=> array('title', 'editor'),
        'show_in_nav_menus' => false
    );

    register_post_type( 'changelog', $args);
}

add_action( 'init', 'create_changelog_postype' );

function create_changelog_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Projects', 'Projects', 'changelog-plugin' ),
        'singular_name'              => _x( 'Project', 'Projects', 'changelog-plugin' ),
        'search_items'               => __( 'Search Projects', 'changelog-plugin' ),
        'popular_items'              => __( 'Popular Projects', 'changelog-plugin' ),
        'all_items'                  => __( 'All Projects', 'changelog-plugin' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Project', 'changelog-plugin' ),
        'update_item'                => __( 'Update Project', 'changelog-plugin' ),
        'add_new_item'               => __( 'Add New Project', 'changelog-plugin' ),
        'new_item_name'              => __( 'New Project', 'changelog-plugin' ),
        'separate_items_with_commas' => __( 'Separate Projects with commas', 'changelog-plugin' ),
        'add_or_remove_items'        => __( 'Add or remove Project', 'changelog-plugin' ),
        'choose_from_most_used'      => __( 'Choose from the most used Projects', 'changelog-plugin' ),
        'not_found'                  => __( 'No Projects found.', 'changelog-plugin' ),
        'menu_name'                  => __( 'Projects', 'changelog-plugin' ),
    );

    $args = array(
        'hierarchical'          => true,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'project' ),
    );

    register_taxonomy( 'project', 'changelog', $args );
}

add_action( 'init', 'create_changelog_taxonomy', 0 );

function show_changelog( $args = '' ) {
    ob_start();
    $args = array(
        'post_type'	 =>	array('changelog'),
        'post_status' =>	'publish',
        'project'	=> $args['project'],
        'order' => 'DESC'
    );

    $wp_query = new WP_Query($args);
    echo '<h3>'.__('Changelog', 'changelog-plugin').'</h3>';
    if ( $wp_query->have_posts() ) {
        while ( $wp_query->have_posts() ) {

            $wp_query->the_post();

            echo '<strong>'.get_the_title().'</strong>';
            echo '<p>'.the_content().'</p>';

            echo '<hr />';
        }
        echo '</ul>';
    } else
    {
        _e("No Changes Yet");
    }
    wp_reset_postdata();
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('changelog', 'show_changelog');

?>