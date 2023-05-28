<?php
if(!class_exists('YoastSitemapsExcludeOptions')){
	class YoastSitemapsExcludeOptions{
		/**
		 * List of options.
		 *
		 * @var array
		 */
		private $options = array();
		
		public function __construct(){
			add_action( 'admin_enqueue_scripts', array( $this, 'settings_assets' ) );
			add_action( 'admin_menu', array($this, 'options_page_menu') );
			add_action( 'admin_init', array($this, 'init') );
		}
		/**
		 * Add the top level menu page.
		 */
		public function options_page_menu() {
			add_menu_page(
				'XML Sitemaps Excludes for Yoast SEO By CodeChute',
				'XML Sitemaps Excludes for Yoast SEO By CodeChute',
				'manage_options',
				'yoast_sitemaps_exclude',
				array($this, 'options_page_html')
			);
		} 
		
		/**
		 * Load settings JS & CSS
		 * @return void
		 */
		public function settings_assets() {
			
    
			//Add the Select2 CSS file
			wp_enqueue_style( 'yoast_sitemaps_exclude-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', false, '1.0', 'all' );
			
			//Add the Select2 JavaScript file
			wp_enqueue_script( 'yoast_sitemaps_exclude-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '1.0', true );
			
		}

		/**
		 * custom option and settings
		 */
		 public function init() {
			 
			 // Register a new setting for "yoast_sitemaps_exclude" page.
			 register_setting( 'yoast_sitemaps_exclude_specific_posts', 'yoast_sitemaps_exclude_specific_posts_options' );
			 
			 // Register a new section in the "yoast_sitemaps_exclude" page.
			 add_settings_section(
				'yoast_sitemaps_exclude_section_specific_posts',
				__( 'Exclude specific posts', 'yoast_sitemaps_exclude' ), 
				null,
				'yoast_sitemaps_exclude_specific_posts'
			);
			
			$options_posts = array();
			
			$posts = get_posts([
				'post_type' => get_post_types(),
				'post_status' => 'publish',
				'numberposts' => -1,
			]);
			foreach($posts as $post){
				$options_posts[$post->ID] = $post->post_title;
			}
			
			// Register a new field in the "yoast_sitemaps_exclude_section_specific_posts" section, inside the "yoast_sitemaps_exclude_specific_posts" page
			add_settings_field(
				'yoast_sitemaps_exclude_specific_posts_field',
				__( 'Posts', 'yoast_sitemaps_exclude' ),
				array($this, 'fields_callback'),
				'yoast_sitemaps_exclude_specific_posts',
				'yoast_sitemaps_exclude_section_specific_posts',
				array(
					'options_name'         => 'yoast_sitemaps_exclude_specific_posts_options',
					'field_name'           => 'yoast_sitemaps_exclude_specific_posts_field',
					'options'			   =>  $options_posts,
					'class'				   => 'js-yoast-sitemaps-exclude'
				)
			);
			
			// Register a new setting for "yoast_sitemaps_exclude_post_type" page.
			 register_setting( 'yoast_sitemaps_exclude_post_type', 'yoast_sitemaps_exclude_post_type_options' );
			 
			 
			 $options_post_types = array();
			 
			 $post_types = get_post_types();
			 foreach($post_types as $key => $post_type){
				$options_post_types[$key] = $key;
			}
			
			// Register a new section in the "yoast_sitemaps_exclude_post_type" page.
			 add_settings_section(
				'yoast_sitemaps_exclude_section_post_type',
				__( 'Exclude a post type.', 'yoast_sitemaps_exclude' ), 
				null,
				'yoast_sitemaps_exclude_post_type'
			);
			
			// Register a new field in the "yoast_sitemaps_exclude_section_specific_posts" section, inside the "yoast_sitemaps_exclude_specific_posts" page
			add_settings_field(
				'yoast_sitemaps_exclude_post_type_field',
				__( 'Post type', 'yoast_sitemaps_exclude' ),
				array($this, 'fields_callback'),
				'yoast_sitemaps_exclude_post_type',
				'yoast_sitemaps_exclude_section_post_type',
				array(
					'options_name'         => 'yoast_sitemaps_exclude_post_type_options',
					'field_name'           => 'yoast_sitemaps_exclude_post_type_field',
					'options'			   =>  $options_post_types,
					'class'				   => 'js-yoast-sitemaps-exclude'
				)
			);
			
			// Register a new setting for "yoast_sitemaps_exclude_taxonomy" page.
			register_setting( 'yoast_sitemaps_exclude_taxonomy', 'yoast_sitemaps_exclude_taxonomy_options' );
			
			$options_taxonomies = array();
			$taxonomies = get_taxonomies();
			foreach($taxonomies as $taxonomy){
				$options_taxonomies[$taxonomy] = $taxonomy;
			}
			
			// Register a new section in the "yoast_sitemaps_exclude_taxonomy" page.
			 add_settings_section(
				'yoast_sitemaps_exclude_section_taxonomy',
				__( 'Exclude a taxonomy.', 'yoast_sitemaps_exclude' ), 
				null,
				'yoast_sitemaps_exclude_taxonomy'
			);
			
			// Register a new field in the "yoast_sitemaps_exclude_section_specific_posts" section, inside the "yoast_sitemaps_exclude_specific_posts" page
			add_settings_field(
				'yoast_sitemaps_exclude_taxonomy_field',
				__( 'Taxonomy', 'yoast_sitemaps_exclude' ),
				array($this, 'fields_callback'),
				'yoast_sitemaps_exclude_taxonomy',
				'yoast_sitemaps_exclude_section_taxonomy',
				array(
					'options_name'         => 'yoast_sitemaps_exclude_taxonomy_options',
					'field_name'           => 'yoast_sitemaps_exclude_taxonomy_field',
					'options'			   =>  $options_taxonomies,
					'class'				   => 'js-yoast-sitemaps-exclude'
				)
			);
			
			// Register a new setting for "yoast_sitemaps_exclude_author" page.
			register_setting( 'yoast_sitemaps_exclude_author', 'yoast_sitemaps_exclude_author_options' );
			
			$options_authors = array();
			
			$authors = get_users();
			foreach($authors as $author){
				$options_authors[$author->ID] = $author->display_name . '(' . $author->ID . ')';
			}
			
			// Register a new section in the "yoast_sitemaps_exclude" page.
			 add_settings_section(
				'yoast_sitemaps_exclude_section_author',
				__( 'Exclude an author.', 'yoast_sitemaps_exclude' ), 
				null,
				'yoast_sitemaps_exclude_author'
			);
			
			// Register a new field in the "yoast_sitemaps_exclude_section_specific_posts" section, inside the "yoast_sitemaps_exclude_specific_posts" page
			add_settings_field(
				'yoast_sitemaps_exclude_author_field',
				__( 'Author', 'yoast_sitemaps_exclude' ),
				array($this, 'fields_callback'),
				'yoast_sitemaps_exclude_author',
				'yoast_sitemaps_exclude_section_author',
				array(
					'options_name'         => 'yoast_sitemaps_exclude_author_options',
					'field_name'           => 'yoast_sitemaps_exclude_author_field',
					'options'			   =>  $options_authors,
					'class'				   => 'js-yoast-sitemaps-exclude'
				)
			);
			
			// Register a new setting for "yoast_sitemaps_exclude_taxonomy_term" page.
			register_setting( 'yoast_sitemaps_exclude_taxonomy_term', 'yoast_sitemaps_exclude_taxonomy_term_options' );
			
			$options_terms = array();
			
			$terms = get_terms();
			foreach($terms as $term){
				$options_terms[$term->term_id] = $term->name . '(' . $term->term_id . ')';
			}
			
			// Register a new section in the "yoast_sitemaps_exclude_taxonomy_term" page.
			 add_settings_section(
				'yoast_sitemaps_exclude_section_taxonomy_term',
				__( 'Exclude a taxonomy term.', 'yoast_sitemaps_exclude' ), 
				null,
				'yoast_sitemaps_exclude_taxonomy_term'
			);
			
			// Register a new field in the "yoast_sitemaps_exclude_section_specific_posts" section, inside the "yoast_sitemaps_exclude_specific_posts" page
			add_settings_field(
				'yoast_sitemaps_exclude_taxonomy_term_field',
				__( 'Taxonomy term', 'yoast_sitemaps_exclude' ),
				array($this, 'fields_callback'),
				'yoast_sitemaps_exclude_taxonomy_term',
				'yoast_sitemaps_exclude_section_taxonomy_term',
				array(
					'options_name'         => 'yoast_sitemaps_exclude_taxonomy_term_options',
					'field_name'           => 'yoast_sitemaps_exclude_taxonomy_term_field',
					'options'			   =>  $options_terms,
					'class'				   => 'js-yoast-sitemaps-exclude'
				)
			);
			
			
		}
		
		/**
		 * Pill field callbakc function.
		 *
		 * @param array $args
		 */
		 public function fields_callback( $args ) {
			 // Get the value of the setting we've registered with register_setting()
			 $options_name = $args['options_name'];
			 $options = get_option( $options_name );
			 $field_name = $args['field_name'];
			 $field_value = !empty($options[$field_name]) ? $options[$field_name] : array();
			 ?>
			<select 
				id="<?php echo esc_attr( $field_name ); ?>"
				class="<?php echo esc_attr( $args['class'] ); ?>" 
				name="<?php echo $options_name;?>[<?php echo esc_attr( $field_name ); ?>][]" 
				multiple>
					<?php foreach($args['options'] as $key => $option):?>
						<option value="<?php echo esc_attr( $key );?>" <?php if(in_array($key, $field_value)):?>selected<?php endif;?>>
							<?php echo $option; ?>
						</option>
					<?php endforeach;?>
			</select>
			<script>
			(function($){
				$(document).ready(function () {
					$("#<?php echo esc_attr( $field_name ); ?>").select2({
						tags: true
					});
				});
			})(jQuery);
			</script>
			<p class="description">
				<?php esc_html_e( 'select one or more options.', 'yoast_sitemaps_exclude' ); ?>
			</p>
		<?php
		}
		
		public function getTabContent($tab){
			switch($tab) :
				case 'post_type':
					// output security fields for the registered setting "yoast_sitemaps_exclude_post_type"
					settings_fields( 'yoast_sitemaps_exclude_post_type' );
					do_settings_sections( 'yoast_sitemaps_exclude_post_type' );
					break;
				case 'taxonomy':
					// output security fields for the registered setting "yoast_sitemaps_exclude_taxonomy"
					settings_fields( 'yoast_sitemaps_exclude_taxonomy' );
					do_settings_sections( 'yoast_sitemaps_exclude_taxonomy' );
					break;
				case 'author':
					// output security fields for the registered setting "yoast_sitemaps_exclude_author"
					settings_fields( 'yoast_sitemaps_exclude_author' );
					do_settings_sections( 'yoast_sitemaps_exclude_author' );
					break;
				case 'taxonomy_term':
					// output security fields for the registered setting "yoast_sitemaps_exclude_taxonomy_term"
					settings_fields( 'yoast_sitemaps_exclude_taxonomy_term' );
					do_settings_sections( 'yoast_sitemaps_exclude_taxonomy_term' );
					break;
				default:
					// output security fields for the registered setting "yoast_sitemaps_exclude_specific_posts"
					settings_fields( 'yoast_sitemaps_exclude_specific_posts' );
					do_settings_sections( 'yoast_sitemaps_exclude_specific_posts' );
					break;
			endswitch;
		}
		
		/**
		 * Top level menu callback function
		 */
		 public function options_page_html() {
			 // check user capabilities
			 if ( ! current_user_can( 'manage_options' ) ) {
				 return;
			}
			
			//Get the active tab from the $_GET param
			$default_tab = null;
			$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
			
			// add error/update messages
			
			// check if the user have submitted the settings
			// WordPress will add the "settings-updated" $_GET parameter to the url
			if ( isset( $_GET['settings-updated'] ) ) {
				// add settings saved message with the class of "updated"
				
				switch($tab) :
					case 'post_type':
						add_settings_error( 'yoast_sitemaps_exclude_post_type_messages', 'yoast_sitemaps_exclude_post_type_message', __( 'Settings Saved', 'yoast_sitemaps_exclude' ), 'updated' );
						break;
					case 'taxonomy':
						add_settings_error( 'yoast_sitemaps_exclude_taxonomy_messages', 'yoast_sitemaps_exclude_taxonomy_message', __( 'Settings Saved', 'yoast_sitemaps_exclude' ), 'updated' );
						break;
					case 'author':
						add_settings_error( 'yoast_sitemaps_exclude_author_messages', 'yoast_sitemaps_exclude_author_message', __( 'Settings Saved', 'yoast_sitemaps_exclude' ), 'updated' );
						break;
					case 'taxonomy_term':
						add_settings_error( 'yoast_sitemaps_exclude_taxonomy_term_messages', 'yoast_sitemaps_exclude_taxonomy_term_message', __( 'Settings Saved', 'yoast_sitemaps_exclude' ), 'updated' );
						break;
					default:
						add_settings_error( 'yoast_sitemaps_exclude_specific_posts_messages', 'yoast_sitemaps_exclude_specific_posts_message', __( 'Settings Saved', 'yoast_sitemaps_exclude' ), 'updated' );
						break;
				endswitch;
			}
			
			// show error/update messages
			settings_errors( 'yoast_sitemaps_exclude_post_type_messages' );
			settings_errors( 'yoast_sitemaps_exclude_taxonomy_messages' );
			settings_errors( 'yoast_sitemaps_exclude_author_messages' );
			settings_errors( 'yoast_sitemaps_exclude_taxonomy_term_messages' );
			settings_errors( 'yoast_sitemaps_exclude_specific_posts_messages' );
			
			?>
			<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<!-- Here are our tabs -->
				<nav class="nav-tab-wrapper">
					<a href="?page=yoast_sitemaps_exclude" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Exclude specific posts', 'yoast_sitemaps_exclude' ); ?></a>
					<a href="?page=yoast_sitemaps_exclude&tab=post_type" class="nav-tab <?php if($tab==='post_type'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Exclude a post type', 'yoast_sitemaps_exclude' ); ?></a>
					<a href="?page=yoast_sitemaps_exclude&tab=taxonomy" class="nav-tab <?php if($tab==='taxonomy'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Exclude a taxonomy', 'yoast_sitemaps_exclude' ); ?></a>
					<a href="?page=yoast_sitemaps_exclude&tab=author" class="nav-tab <?php if($tab==='author'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Exclude an author', 'yoast_sitemaps_exclude' ); ?></a>
					<a href="?page=yoast_sitemaps_exclude&tab=taxonomy_term" class="nav-tab <?php if($tab==='taxonomy_term'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Exclude a taxonomy term', 'yoast_sitemaps_exclude' ); ?></a>
				</nav>
				<div class="tab-content">
					<form action="options.php" method="post">
						<?php $this->getTabContent($tab);?>
						<?php
						// output save settings button
						submit_button( 'Save Settings' );
						?>
					</form>
				</div>
			</div>
			<?php
		}
	}
	new YoastSitemapsExcludeOptions();
}