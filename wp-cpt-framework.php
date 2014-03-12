<?php
/**
 * @package WP-CPT-Framework
 */
/*
Plugin Name: WP CPT Framework
Plugin URI: https://github.com/holisticnetworking/wp-cpt-framework
Description: Provides a quick-start means of creating and manipulating custom post types.
Version: 1.0
Author: Thomas J Belknap
Author URI: http://holisticnetworking.net
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class WPCustomPostType {
	
	public static function admin_pages() {
		$types	= get_option('wpcptf_post_types');
		add_submenu_page('index.php', 'Allowed Content Types', 'Content Types', 'activate_plugins', 'content_types', 'WPCustomPostType::content_types');
		if( is_array( $types ) ) :
			if(!in_array('post', $types)) :
				remove_menu_page('edit.php');
			endif;
			if(!in_array('page', $types)) :
				remove_menu_page('edit.php?post_type=page');
			endif;
		endif;
	}
	
	public static function content_types() {
		// Handle submissions:
		if(!empty($_POST['submit']) && wp_verify_nonce($_POST['content-types-nonce'],'content_types')) :
			$save	= array();
			foreach($_POST['types'] as $type) :
				$save[]	= sanitize_text_field($type);
			endforeach;
			update_option('wpcptf_post_types', $save);
		endif;
		$types	= get_option('wpcptf_post_types', array('post', 'page'));
		?>
		<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div><h2>Administer Allowed Content Types</h2>
			<p>Users of this website are allowed to use the following content types:</p>
			<form id="content-types" method="post" action="">
				<?php wp_nonce_field( 'content_types', 'content-types-nonce' ) ?>
				<ul>
					<li><input type="checkbox" name="types[]" value="post" id="post" <?php if(in_array('post', $types)) : echo 'checked="checked"'; endif; ?>><label for="post">Blog Posts</label></li>
					<li><input type="checkbox" name="types[]" value="page" id="page" <?php if(in_array('page', $types)) : echo 'checked="checked"'; endif; ?>><label for="page">Pages</label></li>
					<li><input type="checkbox" name="types[]" value="headline" id="headline" <?php if(in_array('headline', $types)) : echo 'checked="checked"'; endif; ?>><label for="headline">Headlines</label></li>
				</ul>
				<input type="submit" value="Update Allowed Content Types" class="button button-primary" name="submit" id="submit" />
			</form>
		</div>
		<?php
	}
	
	
	/*
	// For each allowed content type, open the corresponding class file and register the new type:
	*/
	public static function register_content_types() {
		$types	= get_option('wpcptf_post_types');
		if( is_array( $types ) ) : 
			foreach($types as $type) :
				if($type != 'post' && $type != 'page') :
					include( plugin_dir_path(__FILE__) . 'cpt/' . $type . '.class.php' );
					$call	= 'WPCustomPostType' . ucwords($type);
					if(class_exists($call)) :
						$$type	= new $call;
						$$type->register_type();
					endif;
				endif;
			endforeach; 
			// This seems to be causing notices, so I'm taking it out. Fine for production servers, if you want to still use it. /wp-admin/menu.php on line 52
			/* if(!in_array('post', $types)) :
				WPCustomPostType::unregister_type('post');
			endif;
			if(!in_array('page', $types)) :
				WPCustomPostType::unregister_type('page');
			endif; */
		endif;
	}
	
	private static function unregister_type($type) {
		global $wp_post_types;
		// die(print_r($wp_post_types));
		if ( isset( $wp_post_types[ $type ] ) ) {
			unset( $wp_post_types[ $type ] );
			return true;
		}
		return false;
	}
	
	/*
	// Providing default templates where templates do not include them:
	*/
	public static function template( $template ) {
		$type		= get_post_type();
		$registered	= WPCustomPostType::get_post_types();
		$regex		= WPCustomPostType::get_post_types('regex');
		$views		= array('single', 'archive');
		// We are currently viewing a DFE custom post type:
		if( in_array($type, $registered) ) :
			$found	= array_search($type, $registered);
			// die('dude. post type, bro.');
			foreach($views as $view) :
				$theview	= 'is_' . $view;
				// The current template does _not_ have an overriding template file, serve the default:
				if($theview() && !preg_match($regex, $template)) :
					$template	= dirname(__FILE__) . '/tpl/' . $view . '-' . $registered[$found] . '.php';
				endif;
			endforeach;
		endif;
		return $template;
	}
	public static function get_post_types( $format=null ) {
		$result	= array();
		$types	= get_option('wpcptf_post_types');
		foreach($types as $key=>$value) :
			if(!in_array($value, array('post', 'page'))) :
				$result[]	= $value;
			endif;
		endforeach;
		if($format == 'regex') :
			$result	= '/' . implode('|', $result) . '/';
		endif;
		return $result;
	}
	
	public static function flush_rewrite() {
		flush_rewrite_rules();
	}
	
	/* Giddyup */
	public function WPCustomPostType() {
		add_action( 'init', 'WPCustomPostType::register_content_types' );
		add_action( 'admin_menu', 'WPCustomPostType::admin_pages' );
		add_filter( 'template_include', 'WPCustomPostType::template' );
		// Flush rewrite rules:
		register_activation_hook( __FILE__, 'WPCustomPostType::flush_rewrite' );
		register_deactivation_hook( __FILE__, 'WPCustomPostType::flush_rewrite' );
	}
}

$wpcptf	= new WPCustomPostType;
?>
