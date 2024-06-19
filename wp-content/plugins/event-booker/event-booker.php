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
        // AJAX request from front-end (Event):
        add_action("wp_ajax_get_event_data_action" , array($this, "get_event_data_action") );
        add_action("wp_ajax_nopriv_get_event_data_action" , array($this, "get_event_data_action") );

        // AJAX request from front-end (Lead):
        add_action("wp_ajax_submit_lead_data_action" , array($this, "submit_lead_data_action") );
        add_action("wp_ajax_nopriv_submit_lead_data_action" , array($this, "submit_lead_data_action") );

		// add admin panel Event CPT
        require_once('includes/event-custom-post-type.php');

        // add admin panel Lead CPT
        require_once('includes/lead-custom-post-type.php');
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_event_booker_scripts'));
	}

    function get_event_data_action(){
        if($_POST['title']){
            $title = sanitize_text_field( wp_unslash( $_POST['title'] ) );
            if($title){
                $this->get_event_by_title($title);
            }
        }
    }

    function submit_lead_data_action(){
        if($_POST['name'] && $_POST['phone'] && $_POST['email']){
            $name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
            $phone = sanitize_text_field( wp_unslash( $_POST['phone'] ) );
            $email = sanitize_text_field( wp_unslash( $_POST['email'] ) );
            if($name && $phone &&  $email){
                $this->create_a_lead($name, $phone, $email);
            }
        }
    }

	function enqueue_event_booker_scripts() {
		$plugin_version = EVENT_BOOKER_VERSION;
		$plugin_dir_url = plugin_dir_url( __FILE__ );

        // full-calendar js lib
		wp_enqueue_script('full-calendar',  plugin_dir_url( __FILE__ ) . 'js/fullcalendar-6.1.14/dist/index.global.min.js', false, $plugin_version, true );
        
        // app.js
        wp_enqueue_script('app-js',  plugin_dir_url( __FILE__ ) . 'js/app.js', array('jquery'), $plugin_version, true );
        
        $all_events_data = $this->get_all_events_data();

        $variables = array(
            'allevents' => $all_events_data,
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'plugindir' => $plugin_dir_url
        );
        wp_localize_script('app-js', "variables", $variables);
    }

    function get_all_events_data() {
        $args = array(
            'post_type' => 'yvg_event',
            'posts_per_page' => -1, // get all posts
        );

        $result = array();

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                // get Event CPT data
                $id_start_date =  'yvg_event_start';
                $id_end_date =  'yvg_event_end';

                $event_title = get_the_title();
                $start_date = get_post_meta(get_the_ID(), 'event-booker_'. $id_start_date, true);
                $end_date = get_post_meta(get_the_ID(), 'event-booker_'. $id_end_date, true);
                
                $event = array('title' => $event_title, 'start_date' => $start_date, 'end_date' => $end_date);
                array_push($result, $event);
            }
            
            // reset WP_Query
            wp_reset_postdata();
        } else {
            // if no posts
            echo 'No events found.';
        }

        return $result;
    }

    function get_event_by_title($title){
        $args = array(
            'post_type' => 'yvg_event', 
            'posts_per_page' => 1, // only one post
            'post_status' => 'publish', // only published
            'title' => $title, // post title
        );
    

        $query = new WP_Query($args);
        
        if ($query->have_posts()) {

            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('template-parts/content/content-modal-window');
            }

            // reset WP_Query
            wp_reset_postdata();
        }
    }

    function create_a_lead($name, $phone, $email){
        $post_data = array(
            'post_title'    => $name,
            'post_type'     => 'yvg_lead', // type of CPT
            'post_status'   => 'publish',
        );

        // create post
        $post_id = wp_insert_post($post_data);

        $id_phone =  'yvg_lead_phone';
        $id_email =  'yvg_lead_email';

        $response = 'Submitted. Thank you!';
        if ($post_id) {
            update_post_meta($post_id, 'event-booker_'.$id_phone, $phone);
            update_post_meta($post_id, 'event-booker_'.$id_email, $email);           
        } else {
            $response = 'Error. Sorry:(';
        }

        wp_send_json($response);
    }
}



new Event_Booker();