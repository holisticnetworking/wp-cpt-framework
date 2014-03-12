<?php
/*
// News Posts:	For managing news items.
*/

class WPCustomPostTypeHeadline {
	
	public function register_type() {
		register_post_type('headline', array(
			'labels'				=> array(
				'name'					=> 'Headlines',
				'singular_name'			=> 'Headline',
				'add_new' 				=> 'Add Headline',
				'add_new_item' 			=> 'Add Headline',
				'edit_item' 			=> 'Edit Headline',
				'new_item' 				=> 'New Headline',
				'all_items' 			=> 'All Headlines',
				'view_item' 			=> 'View Headline',
				'search_items' 			=> 'Search Headlines',
				'not_found' 			=> 'No matching headlines found',
				'not_found_in_trash' 	=> 'No matching headlines found in Trash', 
				'parent_item_colon' 	=> '',
				'menu_name' 			=> 'Headlines'
				),
			'description'			=> 'Headlines from around the world.',
			'public'				=> true,
			'supports'				=> array('title', 'editor', 'author', 'excerpt', 'thumbnail', 'trackbacks', 'comments', 'revisions'),
			'taxonomies'			=> array('category', 'post_tag'),
			'register_meta_box_cb'	=> 'WPCustomPostTypeHeadline::add_meta_boxes',
			'has_archive'			=> 'headlines',
			'rewrite'				=> array(
				'with_front'	=> false
			),
			'can_export'			=> true
		));
	}
	
	
	public static function add_meta_boxes() {
		add_meta_box( 'headline-cite', 'Citation Info', 'WPCustomPostTypeHeadline::cite', 'headline', 'normal', 'high' );
		add_meta_box( 'headline-media', 'Attached Media', 'WPCustomPostTypeHeadline::media', 'headline', 'normal', 'high' );
	}
	
	
	public static function cite( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'wpcptf_headline_cite_nonce' );
		$cite			= get_post_meta( $post->ID, 'wpcptf_headline_cite', true );
		$url			= !empty( $cite['cite_url'] ) ? esc_attr( $cite['cite_url'] ) : '';
		$title			= !empty( $cite['cite_title'] ) ? esc_attr( $cite['cite_title'] ) : '';
		$description	= !empty( $cite['cite_description'] ) ? esc_attr( $cite['cite_description'] ) : '';
		
		echo '<p><label for="headline_cite_url">Original article link:</label></p>';
			echo '<p><input type="text" id="headline_cite_url" name="wpcptf_headline_cite[cite_url]" value="' . $url . '" size="100" maxlength="400" /></p>';
		echo '<p><label for="headline_cite_title">Original article title:</label></p>';
			echo '<p><input type="text" id="headline_cite_title" name="wpcptf_headline_cite[cite_title]" value="' . $title . '" size="100" maxlength="200" /></p>';
		echo '<p><label for="headline_cite_description">Article sample text:</label></p>';
			echo '<p><textarea id="headline_cite_description" name="wpcptf_headline_cite[cite_description]" cols="100">' . $description . '</textarea></p>';
	}
	public static function save_cite( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['wpcptf_headline_cite_nonce'] ) || ! wp_verify_nonce( $_POST['wpcptf_headline_cite_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		if( !empty( $_POST['wpcptf_headline_cite'] ) ) :
			$cite			= $_POST['wpcptf_headline_cite'];
			$url			= !empty( $cite['cite_url'] ) ? esc_attr( $cite['cite_url'] ) : '';
			$title			= !empty( $cite['cite_title'] ) ? esc_attr( $cite['cite_title'] ) : '';
			$description	= !empty( $cite['cite_description'] ) ? esc_attr( $cite['cite_description'] ) : '';
			$save			= array('cite_url' => $url, 'cite_title' => $title, 'cite_description' => $description);
			add_post_meta($post_id, 'wpcptf_headline_cite', $save, true) or update_post_meta( $post_id, 'wpcptf_headline_cite', $save);
		endif;
	}
	
	
	public static function media( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'wpcptf_headline_media_nonce' );
		$media			= get_post_meta( $post->ID, 'wpcptf_headline_media', true );
		$youtube		= !empty( $media['media_youtube'] ) ? esc_attr( $media['media_youtube'] ) : '';
		$image			= !empty( $media['media_image'] ) ? esc_attr( $media['media_image'] ) : '';
		
		echo '<p><label for="headline_media_youtube">YouTube video:</label></p>';
			echo '<p><input type="text" id="headline_media_youtube" name="wpcptf_headline_media[media_youtube]" value="' . $youtube . '" size="100" maxlength="400" /></p>';
		echo '<p><label for="headline_media_image">Image or Infographic:</label></p>';
			echo '<p><input type="text" id="headline_media_image" name="wpcptf_headline_media[media_image]" value="' . $image . '" size="100" maxlength="200" /></p>';
	}
	public static function save_media( $post_id ) {
		// Refuse without valid nonce:
		if ( ! isset( $_POST['wpcptf_headline_media_nonce'] ) || ! wp_verify_nonce( $_POST['wpcptf_headline_media_nonce'], plugin_basename( __FILE__ ) ) ) return;
		
		//sanitize user input
		if( !empty( $_POST['wpcptf_headline_media'] ) ) :
			$media			= $_POST['wpcptf_headline_media'];
			$youtube		= !empty( $media['media_youtube'] ) ? esc_attr( $media['media_youtube'] ) : '';
			$image			= !empty( $media['media_image'] ) ? esc_attr( $media['media_image'] ) : '';
			$save			= array('media_youtube' => $youtube, 'media_image' => $image);
			add_post_meta($post_id, 'wpcptf_headline_media', $save, true) or update_post_meta( $post_id, 'wpcptf_headline_media', $save);
		endif;
	}
	
	
	public function WPCustomPostTypeHeadline() {
		add_action( 'save_post', 'WPCustomPostTypeHeadline::save_cite' );
		add_action( 'save_post', 'WPCustomPostTypeHeadline::save_media' );
	}
}
?>
