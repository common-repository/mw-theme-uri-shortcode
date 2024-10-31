<?php
/**
 * Plugin Name: MW Theme URI Shortcode
 * Plugin URI: http://2inc.org
 * Description: MW Theme URI Shortcode to make a shortcord outputting theme directory uri.
 * Version: 0.1
 * Author: Takashi Kitajima
 * Author URI: http://2inc.org
 * Text Domain: mw-theme-uri-shortcode
 * Domain Path: /languages/
 * Created: July 30, 2013
 * License: GPL2
 *
 * Copyright 2012 Takashi Kitajima (email : inc@2inc.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class mw_theme_uri_shortcode {

	const NAME = 'mw-theme-uri-shortcode';
	const DOMAIN = 'mw-theme-uri-shortcode';
	protected $option = array();

	/**
	 * __construct
	 */
	public function __construct() {
		add_shortcode( 'theme_directory', array( $this, 'get_stylesheet_directory_uri' ) );
		add_shortcode( 'template_directory_uri', array( $this, 'get_template_directory_uri' ) );
		add_shortcode( 'stylesheet_directory_uri', array( $this, 'get_stylesheet_directory_uri' ) );
		add_action( 'init', array( $this, 'load_text_domain' ) );
		add_action( 'admin_init', array( $this, 'add_TinyMCE_plugin' ) );
	}

	/**
	 * 言語ファイルをロード
	 */
	public function load_text_domain() {
		load_plugin_textdomain( self::DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * get_template_directory_uri
	 * @return  String  親テーマディレクトリURL
	 */
	public function get_template_directory_uri() {
		return get_template_directory_uri();
	}

	/**
	 * get_stylesheet_directory_uri
	 * @return  String  子テーマディレクトリURL
	 */
	public function get_stylesheet_directory_uri() {
		return get_stylesheet_directory_uri();
	}

	/**
	 * add_TinyMCE_plugin
	 * TinyMCE に プラグイン を追加
	 */
	public function add_TinyMCE_plugin() {
		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) )
			return;
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_action( 'admin_footer', array( $this, 'define_theme_name_in_js' ) );
			add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
		}
	}
	public function define_theme_name_in_js() {
		?>
		<script type="text/javascript">
		var template_directory = '<?php echo get_template(); ?>';
		var stylesheet_directory = '<?php echo get_stylesheet(); ?>';
		</script>
		<?php
	}
	public function mce_external_plugins( $plugin_array ) {
		$plugin_array['mwThemeUriShortcode'] = plugin_dir_url( __FILE__ ) . 'js/editor_plugin.js';
		return $plugin_array;
	}
}
$mw_theme_uri_shortcode = new mw_theme_uri_shortcode();
