<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wc_Customizer
 * @subpackage Wc_Customizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Customizer
 * @subpackage Wc_Customizer/admin
 * @author     Md Junayed <admin@easeare.com>
 */
class Wc_Customizer_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-customizer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-customizer-admin.js', array( 'jquery' ), $this->version, false );

	}

	function wp_dropdown_roles( $selected ) {
		$r = '';
		
		if(empty($selected)){
			$selected = [];
		}

		$editable_roles = array_reverse( get_editable_roles() );
	 
		foreach ( $editable_roles as $role => $details ) {
			$name = translate_user_role( $details['name'] );
			// Preselect specified role.
			if ( in_array($role, $selected) || in_array('all', $selected)) {
				$r .= "\n\t<option selected='selected' value='" . esc_attr( $role ) . "'>$name</option>";
			} else {
				$r .= "\n\t<option value='" . esc_attr( $role ) . "'>$name</option>";
			}
		}
		
		echo $r;
	}

	function customizer_opt_reg(){
		add_options_page( 'WC Customizer', 'WC Customizer', 'manage_options', 'wc-customizer', [$this,'opt_customizer_html'] );
		// options
		add_settings_section( 'wc_customizer_settings_section', '', '', 'wc_customizer_settings_page' );

		// User roles
		add_settings_field( 'role_for_unique_id', 'User Roles for uniqueID', [$this,'role_for_unique_id_cb'], 'wc_customizer_settings_page', 'wc_customizer_settings_section');
		register_setting( 'wc_customizer_settings_section', 'role_for_unique_id');
		// admin_panel access
		add_settings_field( 'role_for_admin_panel', 'Block admin access', [$this,'role_for_admin_panel_cb'], 'wc_customizer_settings_page', 'wc_customizer_settings_section');
		register_setting( 'wc_customizer_settings_section', 'role_for_admin_panel');
		// Team access
		add_settings_field( 'role_for_team_access', 'Team access', [$this,'role_for_team_access_cb'], 'wc_customizer_settings_page', 'wc_customizer_settings_section');
		register_setting( 'wc_customizer_settings_section', 'role_for_team_access');
		// Player access
		add_settings_field( 'role_for_player_access', 'Player access', [$this,'role_for_player_access_cb'], 'wc_customizer_settings_page', 'wc_customizer_settings_section');
		register_setting( 'wc_customizer_settings_section', 'role_for_player_access');
		// login_logo
		add_settings_field( 'login_logo', 'Login page logo', [$this,'login_logo_cb'], 'wc_customizer_settings_page', 'wc_customizer_settings_section');
		register_setting( 'wc_customizer_settings_section', 'login_logo');
	}

	function opt_customizer_html(){
		require_once plugin_dir_path( __FILE__ ).'partials/wc-customizer-admin-display.php';
	}

	function role_for_unique_id_cb(){
		echo '<select multiple name="role_for_unique_id[]">';
		echo '<option value="all">All</option>';
		echo $this->wp_dropdown_roles(get_option( 'role_for_unique_id' ));
		echo '</select>';
	}
	
	function role_for_admin_panel_cb(){
		echo '<select multiple name="role_for_admin_panel[]">';
		echo $this->wp_dropdown_roles(get_option( 'role_for_admin_panel' ));
		echo '</select>';
	}
	
	function role_for_team_access_cb(){
		echo '<select multiple name="role_for_team_access[]">';
		echo '<option value="all">All</option>';
		echo $this->wp_dropdown_roles(get_option( 'role_for_team_access' ));
		echo '</select>';
	}

	function role_for_player_access_cb(){
		echo '<select multiple name="role_for_player_access[]">';
		echo '<option value="all">All</option>';
		echo $this->wp_dropdown_roles(get_option( 'role_for_player_access' ));
		echo '</select>';
	}

	function login_logo_cb(){
		echo '<input class="widefat" type="url" value="'.get_option('login_logo').'" placeholder="Logo url" name="login_logo">';
	}

	function user_unique_id_generator( $total_rows, $myorder_obj ) {
		if(get_option('role_for_unique_id')){
			$roles = get_option('role_for_unique_id');

			$current_user_id = get_current_user_id(  );
			$user = get_user_by( 'ID', $current_user_id );
			$user_email = $user->user_email;
			$user_role = $user->roles;

			if(count(array_intersect($user_role, $roles)) > 0 || in_array('all', $roles)){
				$unique_id = md5($user_email.$myorder_obj->get_id());
				$unique_id = '#'.$unique_id;
	
				if(!get_user_meta( $current_user_id, 'order_unique_id', true ) || get_user_meta( $current_user_id, 'order_unique_id', true ) == '#'){
					update_user_meta( $current_user_id, 'order_unique_id', $unique_id );
				}

				$id_number = array(
					'label' => __( 'Id number:', 'woocommerce' ),
					'value'   => get_user_meta( $current_user_id, 'order_unique_id', true )
				);
				array_unshift($total_rows, $id_number);
			}
		}

		return $total_rows;
	}

	function prevent_admin_access_non_admin_users() {
		$player_roles = get_option('role_for_admin_panel');
		if($player_roles){
			$user = get_user_by( 'ID', get_current_user_id(  ) );
			$user_role = $user->roles;
			
			if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && count(array_intersect($user_role, $player_roles)) > 0) {
				wp_redirect( home_url('/arena') );
				exit;
			}
		}
	}

	function prevent_toolbar_non_admin_users(){
		$player_roles = get_option('role_for_admin_panel');
		if($player_roles){
			$user = get_user_by( 'ID', get_current_user_id(  ) );
			$user_role = $user->roles;

			if ( count(array_intersect($user_role, $player_roles)) > 0 ){
				add_filter( 'show_admin_bar', '__return_false' );
			}
		}
	}

	function login_page_scripts() {
		$registration = false;
		if(isset($_GET['action']) && $_GET['action'] == 'register'){
			$registration = true;
		}
		if(!$registration){
			wp_enqueue_style( 'customizer-login', plugin_dir_url( __FILE__ ) . 'css/wc-customizer-login.css' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'customizer-login', plugin_dir_url( __FILE__ ) . 'js/wc-customizer-login.js' );
		}
	}

	function wc_customiser_login_access($user_id){
		wp_clear_auth_cookie();
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);
		
		return true;
	}
	
	function login_page_processing(){
		global $wc_errors;
		if(isset($_POST['wc_login'])){
			if(isset($_POST['pubgm_id']) && !empty($_POST['pubgm_id']) && isset($_POST['password']) && !empty($_POST['password'])){
				$pubgm_id = intval($_POST['pubgm_id']);
				$password = sanitize_text_field($_POST['password']);

				try {
					global $wpdb;
					$user_id = $wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'pubgmid' AND meta_value = $pubgm_id");
					$user_id = intval($user_id);
					
					$user = get_user_by( 'ID', $user_id );

					if ( wp_check_password( $password, $user->user_pass, $user->ID ) ) {

						if(isset($_POST['remember_me'])){
							setcookie('pubgm_id', $pubgm_id, time() + (10 * 365 * 24 * 60 * 60), '/');
							setcookie('password', $password, time() + (10 * 365 * 24 * 60 * 60), '/');
						}
						
						$this->wc_customiser_login_access($user->ID);

						if(is_user_logged_in(  )){
							if(current_user_can( 'administrator' )){
								wp_safe_redirect( home_url( '/wp-admin' ) );
								exit;
							}else{
								wp_safe_redirect( home_url( '/arena' ) );
								exit;
							}
						}
						
					}else{
						$wc_errors = 'Invalid credentials.';
					}

				} catch (Exception $e) {
					$wc_errors = $e->getMessage();
				}

			}else{
				$wc_errors = 'Missing required fields.';
			}
		}
	}

	function login_head_login_page(){
		global $wc_errors;
		$registration = false;
		if(isset($_GET['action']) && $_GET['action'] == 'register'){
			$registration = true;
		}

		if(!$registration){
		?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title><?php echo get_bloginfo( 'name' ) ?></title>
			</head>
			<body>
				<div id="wp_login">
					<div class="logo">
						<?php $logo_url = get_option('login_logo') ?>
						<a href="<?php echo esc_url(get_site_url( '/' )) ?>">
							<img src="<?php echo esc_url( $logo_url ); ?>" alt="logo">
						</a>
					</div>

					<?php
					if(!empty($wc_errors)){
						echo "<span class=\"alert\"><strong class=\"error_code\">ERROR: </strong> $wc_errors</span>";
					}
					?>

					<div class="login_form">

						<form method="post">
							<?php
							$pubgm_id = '';
							$password = '';
							if(isset($_COOKIE['pubgm_id'])){
								$pubgm_id = $_COOKIE['pubgm_id'];
							}
							if(isset($_COOKIE['password'])){
								$password = $_COOKIE['password'];
							}
							?>
							<div class="inputs">
								<label for="pubgm_id">PubgmId</label>
								<input type="number" name="pubgm_id" id="pubgm_id" value="<?php echo $pubgm_id ?>">
							</div>
							<div class="inputs">
								<label for="pass">Password</label>
								<input type="password" name="password" id="pass" value="<?php echo $password ?>">
							</div>
							<div class="inputs">
								<div class="rememberme"><label for="remember_me"> Remember me</label>
								<input type="checkbox" name="remember_me" id="remember_me"></div>
							</div>

							<div class="inputs">
								<input type="submit" name="wc_login" value="Login">
							</div>
						</form>
						
						<div class="dont-have-account">
							<span>Don't have account? <a href="<?php echo esc_url(wp_registration_url()) ?>">Sign up</a></span>
						</div>
					</div>
				</div>
			</body>
			</html>
			<?php
			exit;
		}
	}
}
