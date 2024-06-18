<?php 
/**
 * Plugin Name: Event Booker
 * Plugin URI: https://yvg.com.ua/
 * Description: Event Booker.
 * Version: 1.0.0
 * Author: Yaroslav Grushko
 * Author URI: https://yvg.com.ua/
 * Text Domain: eventbookeryvg
 * Domain Path: /languages/
 */


 if( !defined('EVENT_BOOKER_VERSION') ){
	define('EVENT_BOOKER_VERSION', '1.0.0');
}

if( !defined('EVENT_BOOKER_DIR') ){
	define('EVENT_BOOKER_DIR', plugin_dir_path( __FILE__ ));
}
class Event_Booker {
    function __construct(){
		
		if(is_admin()){
		}
		add_action('wp_enqueue_scripts', array($this, 'enqueue_event_booker_scripts'));
	}



	function enqueue_event_booker_scripts() {
		$plugin_version = EVENT_BOOKER_VERSION;
		$plugin_dir_url = plugin_dir_url( __FILE__ );

        // full-calendar js lib
		wp_enqueue_script('full-calendar',  plugin_dir_url( __FILE__ ) . 'js/fullcalendar-6.1.14/index.global.min.js', false, $plugin_version, true );
	}

}



new Event_Booker();