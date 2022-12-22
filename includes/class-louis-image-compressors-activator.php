<?php

/**
 * Fired during plugin activation
 *
 * @link       https://louiscms.com
 * @since      1.0.0
 *
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/includes
 * @author     Tuấn Louis <louis.standbyme@gmail.com>
 */
class Louis_Image_Compressors_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $all_thumbnail_sizes = _get_all_thumbnail_regular_image_sizes();
        $thumbnail = $all_thumbnail_sizes['thumbnail'];
        $medium = $all_thumbnail_sizes['medium'];
        $large = $all_thumbnail_sizes['large'];

        Louis_Image_Compressors::jal_install();
        //save first settings
        if($thumbnail && $medium && $large){
        add_option('louis_image_compressor_settings', [
            'compressionType' => 1,
            'apply_thumbnail' => 0,
            'apply_thumbnail_size' => "/".$thumbnail['width']."x".$thumbnail['height']."/",
            'apply_medium_size' => "/".$medium['width']."x".$medium['height']."/",
            'apply_large_size' => "/".$large['width']."x".$large['height']."/",
            'backup_original_image' => 1
        ]);
        }
	}

}
