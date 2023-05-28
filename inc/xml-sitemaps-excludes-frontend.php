<?php
if(!class_exists('XmlSitemapsExcludesFrontend')){
	class XmlSitemapsExcludesFrontend{
		
		/**
		 * Author option.
		 *
		 * @var array
		 */
		private $option_author = array(
			'option_name'	=>	'yoast_sitemaps_exclude_author_options',
			'field_name'	=>	'yoast_sitemaps_exclude_author_field'
		);
		
		/**
		 * Post type option.
		 *
		 * @var array
		 */
		private $option_post_type = array(
			'option_name'	=>	'yoast_sitemaps_exclude_post_type_options',
			'field_name'	=>	'yoast_sitemaps_exclude_post_type_field'
		);
		
		/**
		 * Specific posts option.
		 *
		 * @var array
		 */
		private $option_specific_posts = array(
			'option_name'	=>	'yoast_sitemaps_exclude_specific_posts_options',
			'field_name'	=>	'yoast_sitemaps_exclude_specific_posts_field'
		);
		
		/**
		 * Taxonomy option.
		 *
		 * @var array
		 */
		private $option_taxonomy = array(
			'option_name'	=>	'yoast_sitemaps_exclude_taxonomy_options',
			'field_name'	=>	'yoast_sitemaps_exclude_taxonomy_field'
		);
		/**
		 * List of options.
		 *
		 * @var array
		 */
		private $option_taxonomy_term = array(
			'option_name'	=>	'yoast_sitemaps_exclude_taxonomy_term_options',
			'field_name'	=>	'yoast_sitemaps_exclude_taxonomy_term_field'
		);
		
		public function __construct(){
			if(!empty($this->get_options($this->option_author))){
				add_filter( 'wpseo_sitemap_exclude_author', array( $this, 'sitemap_exclude_authors' ));
			}
			if(!empty($this->get_options($this->option_post_type))){
				add_filter( 'wpseo_sitemap_exclude_post_type', array( $this, 'sitemap_exclude_post_type'), 10, 2 );
			}
			if(!empty($this->get_options($this->option_specific_posts))){
				add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', array( $this, 'exclude_posts_from_xml_sitemaps') );
			}
			if(!empty($this->get_options($this->option_taxonomy))){
				add_filter( 'wpseo_sitemap_exclude_taxonomy', array( $this, 'sitemap_exclude_taxonomy'), 10, 2 );
			}
			if(!empty($this->get_options($this->option_taxonomy_term))){
				add_filter( 'wpseo_exclude_from_sitemap_by_term_ids', array( $this, 'sitemap_exclude_terms') );
			}	
		}
		
		/**
		 * Retrieves an option value based on an option name.
		 *
		 * @param array $option Array.
		 *
		 * @return array.
		 */
		public function get_options($option){
			$value = get_option( $option['option_name'] );
			if(!empty($value) && !empty($value[$option['field_name']])){
				return $value[$option['field_name']];
			}
			return false;
		}
		
		/**
		 * Excludes author with ID from author sitemaps.
		 *
		 * @param array $users Array of User objects to filter through.
		 *
		 * @return array The remaining authors.
		 */
		public function sitemap_exclude_authors( $users ) {
			$users_values = $this->get_options($this->option_author);
			return array_filter( $users, function( $user ) use ($users_values) {
				if ( in_array($user->ID, $users_values) ) {
					return false;
				}
				return true;
			} );
		}
		
		/**
		 * Exclude a post type from XML sitemaps.
		 *
		 * @param boolean $excluded  Whether the post type is excluded by default.
		 * @param string  $post_type The post type to exclude.
		 *
		 * @return bool Whether or not a given post type should be excluded.
		 */
		public function sitemap_exclude_post_type( $excluded, $post_type ) {
			$post_type_values = $this->get_options($this->option_post_type);
			return in_array($post_type, $post_type_values);
		}
		
		/**
		 * Excludes posts from XML sitemaps.
		 *
		 * @return array The IDs of posts to exclude.
		 */
		public function exclude_posts_from_xml_sitemaps() {
			return $this->get_options($this->option_specific_posts);
		}
		
		/**
		 * Exclude a taxonomy from XML sitemaps.
		 *
		 * @param boolean $excluded Whether the taxonomy is excluded by default.
		 * @param string  $taxonomy The taxonomy to exclude.
		 *
		 * @return bool Whether or not a given taxonomy should be excluded.
		 */
		public function sitemap_exclude_taxonomy( $excluded, $taxonomy ) {
			return $taxonomy === 'ingredients';
		}
		
		/**
		 * Excludes terms with ID  from terms sitemaps.
		 *
		 * @param array $terms Array of term IDs already excluded.
		 *
		 * @return array The terms to exclude.
		 */
		function sitemap_exclude_terms( $terms ) {
			return $this->get_options($this->option_taxonomy_term);
		}
	}
	new XmlSitemapsExcludesFrontend();
}

