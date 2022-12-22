<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://louiscms.com
 * @since      1.0.0
 *
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/includes
 * @author     Tuấn Louis <louis.standbyme@gmail.com>
 */
class Louis_Image_Compressors {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Louis_Image_Compressors_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LOUIS_IMAGE_COMPRESSORS_VERSION' ) ) {
			$this->version = LOUIS_IMAGE_COMPRESSORS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'louis-image-compressors';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Louis_Image_Compressors_Loader. Orchestrates the hooks of the plugin.
	 * - Louis_Image_Compressors_i18n. Defines internationalization functionality.
	 * - Louis_Image_Compressors_Admin. Defines all hooks for the admin area.
	 * - Louis_Image_Compressors_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-louis-image-compressors-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-louis-image-compressors-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-louis-response-handle.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-louis-image-compressors-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-louis-image-media-library.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-louis-image-api.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-louis-image-compressors-public.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/core.helper.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/form.helper.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/file.helper.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/cache.class.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/file-cache.class.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/authentication.class.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/file-setting.class.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/file-system.class.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/optimize-image-handle.class.php';

		$this->loader = new Louis_Image_Compressors_Loader();

	}

    public function jal_install () {
            global $wpdb;
            add_option( "jal_db_version", "1.0" );
            $table_name = $wpdb->prefix . "louis_opticture_meta";
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
            id mediumint(15) NOT NULL AUTO_INCREMENT,
            attach_name VARCHAR(355) DEFAULT NULL,
            attach_id mediumint(15) DEFAULT 0,
            parent VARCHAR(355) DEFAULT 0,
            image_name VARCHAR(255) DEFAULT NULL,
            image_type tinyint(4) DEFAULT NULL,
            size VARCHAR(50) DEFAULT NULL,
            status tinyint(11) DEFAULT NULL,
            compression_type tinyint(4) DEFAULT NULL,
            compressed_size int(11) DEFAULT NULL,
            original_size int(11) DEFAULT NULL,
            timeCreated datetime DEFAULT '0000-00-00 00:00:00' NULL,
            timeOptimized datetime DEFAULT '0000-00-00 00:00:00' NULL,
            extra_info longtext DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            add_option( 'jal_db_version', $jal_db_version );
    }

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Louis_Image_Compressors_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Louis_Image_Compressors_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Louis_Image_Compressors_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_settings_page' );

        $plugin_media = new Louis_Media_Library ($this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_media, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_media, 'enqueue_scripts' );

       // $this->loader->add_action( 'admin_menu', $plugin_media, 'register_settings_page' );

		// Hook our settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

        $this->loader->add_action( 'wp_ajax_lic_setting_submit', $plugin_admin, 'licSettingSubmit' );
        $this->loader->add_action( 'wp_ajax_nopriv_lic_setting_submit', $plugin_admin, 'licSettingSubmit' );

        $plugin_api = new Louis_Image_Api ($this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_ajax_compress_image_item', $plugin_api, 'compress_image_item' );
        $this->loader->add_action( 'wp_ajax_nopriv_compress_image_item', $plugin_api, 'compress_image_item' );

        $this->loader->add_action( 'wp_ajax_login_submit', $plugin_api, 'loginSubmit' );
        $this->loader->add_action( 'wp_ajax_nopriv_login_submit', $plugin_api, 'loginSubmit' );

        $this->loader->add_action( 'wp_ajax_register_submit', $plugin_api, 'registerSubmit' );
        $this->loader->add_action( 'wp_ajax_nopriv_register_submit', $plugin_api, 'registerSubmit' );

        $this->loader->add_action( 'wp_ajax_louis_opticture_optimize', $plugin_api, 'optictureOptimize' );
        $this->loader->add_action( 'wp_ajax_nopriv_louis_opticture_optimize', $plugin_api, 'optictureOptimize' );

        $this->loader->add_action( 'wp_ajax_louis_opticture_restore', $plugin_api, 'optictureRestoreItem' );
        $this->loader->add_action( 'wp_ajax_nopriv_louis_opticture_restore', $plugin_api, 'optictureRestoreItem' );

        $this->loader->add_action( 'wp_ajax_louis_check_bulk_process', $plugin_api, 'checkBulkProcess' );
        $this->loader->add_action( 'wp_ajax_nopriv_louis_check_bulk_process', $plugin_api, 'checkBulkProcess' );

        // $this->loader->add_action( 'wp_ajax_louis_opticture_reoptimize', $plugin_api, 'optictureOptimize' );
        // $this->loader->add_action( 'wp_ajax_nopriv_louis_opticture_reoptimize', $plugin_api, 'optictureOptimize' );

        $this->loader->add_action( 'wp_ajax_louis_comparer_data', $plugin_api, 'compareItem' );
        $this->loader->add_action( 'wp_ajax_nopriv_louis_comparer_data', $plugin_api, 'compareItem' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Louis_Image_Compressors_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Louis_Image_Compressors_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
