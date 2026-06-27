<?php
/**
 * Plugin Name: Fluid Glass Navigation
 * Description: A custom navbar widget for wordpress
 * Version: 2.0.0
 * Author: Tebogo L Selepe
 */

if (!defined('ABSPATH')) exit;

define('FGN_VERSION', '1.0.0');

function fgn_register_elementor_widgets($widgets_manager) {
    require_once __DIR__ . '/widgets/fluid-navbar-widget.php';
    $widgets_manager->register(new \Elementor\Fluid_Navbar_Widget());
}
add_action('elementor/widgets/register', 'fgn_register_elementor_widgets');

function fgn_enqueue_assets() {
    wp_enqueue_style('fgn-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=DM+Sans:wght@300;400&display=swap', array(), null);
    wp_enqueue_style('fgn-style', plugin_dir_url(__FILE__) . 'assets/css/fluid-navbar.css', array(), FGN_VERSION);
    wp_enqueue_script('fgn-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true);
    wp_enqueue_script('jquery');
    wp_enqueue_script('fgn-script', plugin_dir_url(__FILE__) . 'assets/js/fluid-navbar.js', array('jquery', 'fgn-gsap'), FGN_VERSION, true);
}
add_action('wp_enqueue_scripts', 'fgn_enqueue_assets');