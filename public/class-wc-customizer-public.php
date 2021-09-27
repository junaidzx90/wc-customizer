<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wc_Customizer
 * @subpackage Wc_Customizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Customizer
 * @subpackage Wc_Customizer/public
 * @author     Md Junayed <admin@easeare.com>
 */
class Wc_Customizer_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-customizer-public.css', array(), '0.1', 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-customizer-public.js', array( 'jquery' ), $this->version, false );

	}

	function customizer_editinfo_link( $menu_links ){

		$menu_links = array_slice( $menu_links, 0, 5, true ) 
		+ array( 'editinfo' => 'Edit info' )
		+ array_slice( $menu_links, 5, NULL, true );

		return $menu_links;
	}

	function customizer_editinfo_endpoints() {
		add_rewrite_endpoint( 'editinfo', EP_PAGES );
	}

	function customizer_my_account_endpoint_content() {
		require_once plugin_dir_path( __FILE__ ).'partials/wc-customizer-edit-info.php';
	}

	//attachment helper function   
	function insert_attachment( $file_handler, $post_id ) {
		$filename = $file_handler["name"];

		$upload_file = wp_upload_bits($filename, null, file_get_contents($file_handler["tmp_name"]));

		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
		
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}
	}
}
