<?php
/*
Plugin Name: vdSlider
Plugin URI: http://sogemaskineoptimering.com/blog/extensions
Description: This widget will display a slider based on the options available.
Version: 1.0
Author: Mark Pedersen
Author URI: http://www.nodesagency.com
License: GNU GPLv2
*/

function vds_init() {
    $args = array(
        'public' => true,
        'label' => 'vdSlider',
        'supports' => array(
            'title',
            'thumbnail'
        )
    );
    register_post_type('vds_images', $args);
}
add_action('init', 'vds_init');
//adding nivo js and css actions
add_action('wp_print_scripts', 'vds_register_scripts');
add_action('wp_print_styles', 'vds_register_styles');
//registering the nivo js scripts
function vds_register_scripts() {
    if (!is_admin()) {
        // register
        wp_register_script('vds_nivo-script', plugins_url('nivo-slider/jquery.nivo.slider.js', __FILE__), array( 'jquery' ));
        wp_register_script('vds_script', plugins_url('script.js', __FILE__));
 
        // enqueue
        wp_enqueue_script('vds_nivo-script');
        wp_enqueue_script('vds_script');
    }
}
//registering the nivo cs style
function vds_register_styles() {
    // register
    wp_register_style('vds_styles', plugins_url('nivo-slider/nivo-slider.css', __FILE__));
    wp_register_style('vds_styles_theme', plugins_url('nivo-slider/themes/default/default.css', __FILE__));
 
    // enqueue
    wp_enqueue_style('vds_styles');
    wp_enqueue_style('vds_styles_theme');
}

add_image_size('vds_function', 1060, 500, true);

add_theme_support( 'post-thumbnails' );

function vds_function($type='vds_function') {
    $args = array(
        'post_type' => 'vds_images',
        'posts_per_page' => 5
    );
    $result = '<div class="slider-wrapper theme-default">';
    $result .= '<div id="slider" class="nivoSlider">';
 
    //the loop
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();
 
        $the_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $type);
        $result .='<img title="'.get_the_title().'" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt=""/>';
    }
    $result .= '</div>';
    $result .='</div>';
    return $result;
}
add_shortcode('vds-shortcode', 'vds_function');

?>