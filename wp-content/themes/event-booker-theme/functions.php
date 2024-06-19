<?php
function event_booker_get_theme_version(){
	$theme = wp_get_theme();
	$theme_version = $theme->get('Version');
	return $theme_version;
}

 function event_booker_scripts(){
    $theme_version = event_booker_get_theme_version();
	wp_enqueue_style( 'event_booker-style', get_template_directory_uri() . '/style.css', array(), $theme_version, 'all' );	
 }

 add_action( 'wp_enqueue_scripts', 'event_booker_scripts' );

 add_theme_support( 'post-thumbnails' );
?>