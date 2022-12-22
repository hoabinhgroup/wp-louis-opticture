<?php
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
class Louis_Media_Library {

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


        add_filter( 'manage_media_columns', [ $this, 'column_id'] );

        add_filter( 'manage_media_custom_column', [ $this,'column_id_row'], 10, 2 );

        add_filter( 'bulk_actions-upload', [ $this,'handle_upload_bulk_actions'] );

        add_filter( 'handle_bulk_actions-upload', [ $this,'louis_bulk_action_handler'], 10, 3 );

        add_action('admin_notices', function() {
            if (!empty($_REQUEST['louis_bulk_optimized'])) {
                $num_changed = (int) $_REQUEST['louis_bulk_optimized'];
                printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Optimizing %d items.', 'txtdomain') . '</p></div>', $num_changed);

            }
            if (!empty($_REQUEST['louis_bulk_restored'])) {
                $num_changed = (int) $_REQUEST['louis_bulk_restored'];
                printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Restoring %d items.', 'txtdomain') . '</p></div>', $num_changed);

            }
        });
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        wp_register_script( 'louis-opticture', plugin_dir_url( __FILE__ ) . 'js/louis-opticture.js', [], '1.1.32', 'all' );
        wp_enqueue_script('louis-opticture');

        // wp_register_script( 'ajax_handle_louis_opticture',  plugin_dir_url( __FILE__ ) . 'js/louis-opticture.js' );
        // $translation_array = array(
        //     'pluginUrl' => plugin_dir_url( dirname( __FILE__ ) ),
        //     'ajaxUrl' => admin_url( 'admin-ajax.php')
        //  );
        // wp_localize_script( 'ajax_handle_louis_opticture', 'louis_opticture', $translation_array );
        //
        // wp_enqueue_script( 'ajax_handle_louis_opticture' );

	}


    function column_id_row($columnName, $columnID){
        if($columnName == 'wp-louis-opticture'){
           ob_start();
           $row = LouisOpticture::getRowById($columnID);
           require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/media-library/actions.php';
           echo ob_get_clean();
        }
    }

    function column_id($columns) {
        $columns['wp-louis-opticture'] = __('Tối ưu hóa ảnh','louis-image-compressors');
        return $columns;
    }

    function handle_upload_bulk_actions( $actions ) {
        $delete = false;
        if ( ! empty( $actions['delete'] ) ) {
            $delete = $actions['delete'];
            unset( $actions['delete'] );
        }
        $actions['louis_optimize'] = __('Tối ưu ảnh','louis-image-compressors');
        $actions['louis_restore'] = __('Khôi phục ảnh','louis-image-compressors');
        if ( $delete )
        $actions['delete'] = $delete;
        return $actions;
    }

    function louis_bulk_action_handler($redirect, $doaction, $object_ids)
    {

        $redirect = remove_query_arg(
            array( 'louis_bulk_optimized', 'louis_bulk_restored' ),
            $redirect
        );
        $cache = new FileCache('queue-work');

       if ( 'louis_optimize' === $doaction ) {
            $louis_optimize = [];
           foreach ( $object_ids as $post_id ) {
               // post id to queue work
               $louis_optimize[] = $post_id;
           }
           $cache->store('queue-optimize', $louis_optimize);
           $redirect = add_query_arg(
               'louis_bulk_optimized', // just a parameter for URL
               count( $object_ids ), // how many posts have been selected
               $redirect
           );
           }

       if ( 'louis_restore' === $doaction ) {
           $louis_restore = [];
          foreach ( $object_ids as $post_id ) {
              // post id to queue work
              $louis_restore[] = $post_id;
          }
          $cache->store('queue-restore', $louis_restore);
          $redirect = add_query_arg(
                 'louis_bulk_restored', // just a parameter for URL
                 count( $object_ids ), // how many posts have been selected
                 $redirect
             );
          }



    return $redirect;
}

}
