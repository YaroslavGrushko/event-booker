<?php 
if( !defined('ABSPATH')) {
	exit;
}

/* yvg_lead (in admin) */
if( !class_exists('Yvg_Lead') ){
	class Yvg_Lead{
		public $post_type;
		
		function __construct(){
			$this->post_type = 'yvg_lead';
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'exclude_yvg_lead_from_post_search'));
            
            if( is_admin() ){
                add_action('add_meta_boxes',  array($this, 'add_yvg_lead_meta'));
                add_action('save_post',  array($this, 'save_yvg_lead_meta'));
            }
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Leads', 'general name', 'eventbookeryvg' ),
				'singular_name' 	=> esc_html_x( 'Lead', 'singular name', 'eventbookeryvg' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'eventbookeryvg' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'eventbookeryvg' ),
				'edit_item' 		=> esc_html__( 'Edit Lead', 'eventbookeryvg' ),
				'new_item' 			=> esc_html__( 'New Lead', 'eventbookeryvg' ),
				'all_items' 		=> esc_html__( 'All Leads', 'eventbookeryvg' ),
				'view_item' 		=> esc_html__( 'View Lead', 'eventbookeryvg' ),
				'search_items' 		=> esc_html__( 'Search Lead', 'eventbookeryvg' ),
				'not_found' 		=> esc_html__( 'No Leads', 'eventbookeryvg' ),
				'not_found_in_trash'=> esc_html__( 'No Leads In Trash', 'eventbookeryvg' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Lead', 'eventbookeryvg' )
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
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
			);
			register_post_type( $this->post_type, $args );
		}
		
		// exclude yvg_lead from post search results 
		function exclude_yvg_lead_from_post_search() {
			global $wp_post_types;
			if (post_type_exists('yvg_lead')) {
				// exclude from search results
				$wp_post_types['yvg_lead']->exclude_from_search = true;
			}
		}

        // add additional meta for every Lead CPT
        function add_yvg_lead_meta(){
            $data = array(
                'id' 			=> 'yvg_lead_meta',
                'label' 		=> esc_html__('Lead Settings', 'eventbookeryvg'),
                'post_type'		=> 'yvg_lead'
            );
            add_meta_box($data['id'], $data['label'], array($this, 'render_yvg_lead_meta' ), $data['post_type'], 'normal', 'high');
        }
        // let’s render Lead meta
        function render_yvg_lead_meta (){
            global $post;

            $id_phone =  'yvg_lead_phone';
            $label_phone =  'Lead Phone';

			$id_email =  'yvg_lead_email';
            $label_email =  'Lead Email';
            
            $post_meta_phone = get_post_meta($post->ID, 'event-booker_'.$id_phone, true);
			$post_meta_email = get_post_meta($post->ID, 'event-booker_'.$id_email, true);

                ?>
                <div class="yvg-Lead-meta"> 
                    <label for="event-booker_<?php echo esc_attr($id_phone); ?>"><?php echo esc_html($label_phone); ?></label>
                    <input name="event-booker_<?php echo esc_attr($id_phone); ?>" id="event-booker_<?php echo esc_attr($id_phone); ?>" value="<?php echo esc_attr($post_meta_phone); ?>"> 
                
					<label for="event-booker_<?php echo esc_attr($id_email); ?>"><?php echo esc_html($label_email); ?></label>
                    <input name="event-booker_<?php echo esc_attr($id_email); ?>" id="event-booker_<?php echo esc_attr($id_email); ?>" value="<?php echo esc_attr($post_meta_email); ?>"> 
				</div>
        <?php 
        }

        // when admin updates a Lead CPT our setting saves
        function save_yvg_lead_meta( $post_id ){
            if( wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ){
                return;
            }
            
            if( isset($_POST['post_type']) && $_POST['post_type'] == 'yvg_lead'){
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

new Yvg_Lead();