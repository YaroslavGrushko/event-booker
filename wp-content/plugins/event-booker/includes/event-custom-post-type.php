<?php 
if( !defined('ABSPATH')) {
	exit;
}

/* YVG_Event (in admin) */
if( !class_exists('YVG_Event') ){
	class YVG_Event{
		public $post_type;
		
		function __construct(){
			$this->post_type = 'yvg_event';
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'exclude_yvg_event_from_post_search'));
            
            if( is_admin() ){
                add_action('add_meta_boxes',  array($this, 'add_yvg_event_meta'));
                add_action('save_post',  array($this, 'save_yvg_event_meta'));
            }
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Events', 'general name', 'eventbookeryvg' ),
				'singular_name' 	=> esc_html_x( 'Event', 'singular name', 'eventbookeryvg' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'eventbookeryvg' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'eventbookeryvg' ),
				'edit_item' 		=> esc_html__( 'Edit Event', 'eventbookeryvg' ),
				'new_item' 			=> esc_html__( 'New Event', 'eventbookeryvg' ),
				'all_items' 		=> esc_html__( 'All Events', 'eventbookeryvg' ),
				'view_item' 		=> esc_html__( 'View Event', 'eventbookeryvg' ),
				'search_items' 		=> esc_html__( 'Search Event', 'eventbookeryvg' ),
				'not_found' 		=> esc_html__( 'No Events', 'eventbookeryvg' ),
				'not_found_in_trash'=> esc_html__( 'No Events In Trash', 'eventbookeryvg' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Event', 'eventbookeryvg' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor', 'thumbnail' ),
				'menu_position' 	=> 5,
			);
			register_post_type( $this->post_type, $args );
		}
		
		// exclude yvg_Event from post search results 
		function exclude_yvg_event_from_post_search() {
			global $wp_post_types;
			if (post_type_exists('yvg_event')) {
				// exclude from search results
				$wp_post_types['yvg_event']->exclude_from_search = true;
			}
		}

        // add additional meta for every Event CPT
        function add_yvg_event_meta(){
            $data = array(
                'id' 			=> 'yvg_event_meta',
                'label' 		=> esc_html__('Event Settings', 'eventbookeryvg'),
                'post_type'		=> 'yvg_event'
            );
            add_meta_box($data['id'], $data['label'], array($this, 'render_yvg_event_meta' ), $data['post_type'], 'normal', 'high');
        }
        // let’s render Event meta
        function render_yvg_event_meta (){
            global $post;

            $id_start_date =  'yvg_event_start';
            $label_start_date =  'Event Start Date';

            $id_end_date =  'yvg_event_end';
            $label_end_date =  'Event End Date';
            
                
            $post_meta_start = get_post_meta($post->ID, 'event-booker_'.$id_start_date, true);
            $post_meta_end = get_post_meta($post->ID, 'event-booker_'.$id_end_date, true);
                ?>
                <div class="yvg-event-meta"> 
                    <label for="event-booker_<?php echo esc_attr($id_start_date); ?>"><?php echo esc_html($label_start_date); ?></label>
                    <input name="event-booker_<?php echo esc_attr($id_start_date); ?>" id="event-booker_<?php echo esc_attr($id_start_date); ?>" value="<?php echo esc_attr($post_meta_start); ?>">
                    
                    <label for="event-booker_<?php echo esc_attr($id_end_date); ?>"><?php echo esc_html($label_end_date); ?></label>
                    <input name="event-booker_<?php echo esc_attr($id_end_date); ?>" id="event-booker_<?php echo esc_attr($id_end_date); ?>" value="<?php echo esc_attr($post_meta_end); ?>"> 
                </div>
        <?php 
        }

        // when admin updates a Event CPT our setting saves
        function save_yvg_event_meta( $post_id ){
            if( wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ){
                return;
            }
            
            if( isset($_POST['post_type']) && $_POST['post_type'] == 'yvg_event'){
                if ( !current_user_can('edit_page', $post_id) ) {
                    return $post_id;
                }
            }


            foreach( $_POST as $key => $value ){
                if( strpos($key, 'event-booker_') !== false  ){
                    update_post_meta($post_id, $key, $value);
                }
            }
        }


	}
}

new YVG_Event();