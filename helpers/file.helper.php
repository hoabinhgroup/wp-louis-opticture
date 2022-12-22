<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

if (!function_exists("getAllDirs")) {
function getAllDirs($directory, $directory_seperator = '/') {
    $dirs = array_map(function ($item) use ($directory_seperator) {

      return $item . $directory_seperator;

    }, array_filter(glob($directory . '*'), 'is_dir'));

    foreach ($dirs AS $dir) {

      $dirs = array_merge($dirs, getAllDirs($dir, $directory_seperator));

    }
    return $dirs;
  }
 }

 if (!function_exists("fsize")) {
function fsize($url) {
   $fp = fopen($url,"r");
   $inf = stream_get_meta_data($fp);
   fclose($fp);
   foreach($inf["wrapper_data"] as $v) {
     if (stristr($v, "content-length")) {
       $v = explode(":", $v);
       return trim($v[1]);
     }
   }
   return 0;
 }
}

 if (!function_exists("save_image")) {
 function save_image($image_url, $image_file){
       $fp = fopen ($image_file, 'w+');              // open file handle

       $ch = curl_init($image_url);
       // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
       curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       curl_setopt($ch, CURLOPT_TIMEOUT, 5000);      // some large value to allow curl to run for a long time
       curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
       // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
       curl_exec($ch);

       curl_close($ch);                              // closing curl handle
       fclose($fp);                                  // closing file handle
   }
}

//getAllImgs(getAllDirs($upload_dir['basedir'].'/', ''),'/');
if (!function_exists("getAllImgs")) {
  function getAllImgs($directory, $baseurl) {

    $resizedFilePath = array();
    foreach ($directory AS $dir) {
      foreach (glob($dir . '*.jpg') as $filename) {
		  $split = explode('wp-content/uploads', $filename );
		  $directImageUrl = $baseurl . $split[1];
          array_push($resizedFilePath, $directImageUrl );
      }
    }
    return $resizedFilePath;
  }
}

if (!function_exists("_get_all_image_sizes")) {
 function _get_all_image_sizes() {
     global $_wp_additional_image_sizes;

     $default_image_sizes = get_intermediate_image_sizes();

     foreach ( $default_image_sizes as $size ) {
         $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
         $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
        // $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
     }

     if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
         $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
     }

     return $image_sizes;
 }
}

if (!function_exists("_get_all_thumbnail_regular_image_sizes")) {
 function _get_all_thumbnail_regular_image_sizes() {
     global $_wp_additional_image_sizes;

      $default_image_sizes = get_intermediate_image_sizes();

      foreach ( $default_image_sizes as $size ) {
          if(in_array($size, ['thumbnail','medium','large'])){
          $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
          $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
          }
      }
      return $image_sizes;
 }
}
