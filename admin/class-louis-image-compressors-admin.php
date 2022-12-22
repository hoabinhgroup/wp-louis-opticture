<?php
// require_once WP_PLUGIN_DIR . '/louis-image-compressors/helpers/core.helper.php';
// require_once WP_PLUGIN_DIR . '/louis-image-compressors/helpers/form.helper.php';
// require_once WP_PLUGIN_DIR . '/louis-image-compressors/classes/optimize-image-handle.class.php';
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://louiscms.com
 * @since      1.0.0
 *
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/admin
 * @author     Tuấn Louis <louis.standbyme@gmail.com>
 */
class Louis_Image_Compressors_Admin {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Louis_Image_Compressors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Louis_Image_Compressors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
         if (isset($_GET['page']) && $_GET['page'] == 'image-compressors'){
        wp_register_style( 'bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', [], '3.3.7', 'all' );
        wp_register_style( 'sweetalert', plugin_dir_url( __FILE__ ) . 'css/sweetalert.min.css', [], '1.1.3', true );

        wp_enqueue_style( 'bootstrap-css' );
        wp_enqueue_style( 'sweetalert' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/louis-image-compressors-admin.css', array(), time(), 'all' );
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Louis_Image_Compressors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Louis_Image_Compressors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if (isset($_GET['page']) && $_GET['page'] == 'image-compressors'){
        wp_register_script( 'bootstrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', [], '3.3.7', 'all' );
        wp_enqueue_script('bootstrap-script');
        }
        wp_register_script( 'form-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', ['jquery'], '1.17.0', true );
        wp_register_script( 'sweetalert2-all-min', plugin_dir_url( __FILE__ ) . 'js/sweetalert2.all.min.js', ['jquery'], '9.17.2', true );
        wp_register_script( 'louis-form', plugin_dir_url( __FILE__ ) . 'js/jquery.louisForm.js', ['jquery'], '1.0', true );
        wp_register_script( 'lic-setting-form', plugin_dir_url( __FILE__ ) . 'js/louis-image-compressors-admin.js', array( 'jquery', 'form-validate', 'louis-form' ), time(), true );
        wp_enqueue_script('form-validate');
        wp_enqueue_script('sweetalert2-all-min');
        wp_enqueue_script('louis-form');

        wp_enqueue_script('lic-setting-form');

        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
            wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-tabs');
            // enqueue any othere scripts/styles you need to use
        }

        wp_register_script( 'ajax_handle_var',  plugin_dir_url( __FILE__ ) . 'js/test.js' );
        $translation_array = array(
            'pluginUrl' => plugin_dir_url( dirname( __FILE__ ) ),
            'ajaxUrl' => admin_url( 'admin-ajax.php'),
            'STATUS_ENQUEUED' => 10,
            'STATUS_SUCCESS' => 2,
            'STATUS_SUCCESS_ALL' => 3,
            'STATUS_UNCHANGED' => 0,
            'STATUS_ERROR' => -1,
            'STATUS_FAIL' => -2,
            'STATUS_QUOTA_EXCEEDED' => -3,
            'STATUS_SKIP' => -4,
            'STATUS_NOT_FOUND' => -5,
            'STATUS_NO_KEY' => -6,
            'STATUS_RETRY' => -7,
            'STATUS_SEARCHING' => -8,
            'STATUS_QUEUE_FULL' => -404,
            'STATUS_MAINTENANCE' => -500,
            'STATUS_CONNECTION_ERROR' => -503,
            'STATUS_NOT_API' => -1000,
            'STATUS_STOP_ALL' => -2000
         );
        wp_localize_script( 'ajax_handle_var', 'louis_opticture', $translation_array );

        wp_enqueue_script( 'ajax_handle_var' );

	}


	public function register_settings_page() {

			add_submenu_page(
			'options-general.php',                             // parent slug
			__( 'Louis Opticture', 'image-compressors' ),      // page title
			__( 'Louis Opticture', 'image-compressors' ),      // menu title
			'manage_options',                        // capability
			'image-compressors',                           // menu_slug
			array( $this, 'display_settings_page' )  // callable function
		);

    }



  public function checkAuthentication()
  {

     // Authentication::checkAuthentication();
         $authCache = new FileCache('authencation');
      $token = Authentication::getToken();
      if($token){
      $arguments = array(
          //'sslverify' => true,
           'headers'   => array(
               'Content-Type' => 'application/json; charset=utf-8',
               'Authorization' => 'Bearer ' . $token
           ),
          'timeout'     => 15,
          'method' => 'POST',
          'body' => json_encode(['checkAuthentication' => true])
      );

      $response = wp_remote_post(API_CHECK_AUTHENTICATION, $arguments);

      if ( is_wp_error( $response ) ) {
          return false;
      // Louis_Response_Handle::setResponse(
      //   Louis_Response_Handle::STATUS_ERROR, Louis_Response_Handle::ERR_AUTHENTICATION);
            error_log('Error Authentication: ' . $response->get_error_message());
      } else {
          $bodyResponse = json_decode($response['body']);
          if($bodyResponse->status){
                $status = 'Authenticated';
                $message = $bodyResponse->message;
                $data = $bodyResponse->data;
          }else{
              if($bodyResponse->message == 'Your session is not valid'){
                    $status = 'reAuthentication';
                    $data = false;
                    $authCache->erase('token');
              }
          }

      }

      }else{
          $status = 'unAuthentication';
          $data = false;
      }

      // return [
      //     'status' => $status,
      //     'data' => $data
      // ];
      return true;
  }


  public function licSettingSubmit()
    {
        $data = $_REQUEST;
        $authCache = new FileCache('file-system');
        $louis_image_compressor_settings = 'louis_image_compressor_settings';

        update_option($louis_image_compressor_settings, $data);
        $authCache->setCache('file-system');
        $authCache->eraseAll();
        wp_send_json([
          'success' => true,
          'message' => 'Saved Settings!',
          'data' => $data
              ]);

    }




    protected function getSettingsSlug() {
        return get_class($this) . 'Settings';
    }


	/**
 * Display the settings page content for the page we have created.
 *
 * @since    1.0.0
 */
public function display_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'TEXT-DOMAIN'));
    }
    $this->checkAuthentication();
    $lic_settings = getLouisImageCompressorSettings();
    $fileSystem = new FileSystem();
    $optimize = new OptimizeImageHandle();
    $cache = new FileCache();
    $authCache = new FileCache('authentication');
    $cache->eraseExpired();
    $percentCompleted = 0;

    $countOptimizeFiles = $fileSystem->getCountOptimizeFiles();

    $countOptimizedFiles = $fileSystem->getCountOptimizedFiles();

    if($countOptimizeFiles){
    $percentCompleted = round(($countOptimizedFiles / $countOptimizeFiles) * 100);
    }
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/index.php';

}

public function register_settings() {

}

}
