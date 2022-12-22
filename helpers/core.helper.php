<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

defined('API_SERVER') || define('API_SERVER', 'http://api.louiscms.com');
defined('API_IMAGE_COMPRESSORS') || define('API_IMAGE_COMPRESSORS', API_SERVER . '/api/users/image-compressors');
defined('API_CHECK_AUTHENTICATION') || define('API_CHECK_AUTHENTICATION', API_SERVER . '/api/users/check-authentication');
defined('API_LOGIN') || define('API_LOGIN', API_SERVER . '/api/users/login');
defined('API_REGISTER') || define('API_REGISTER', API_SERVER . '/api/users/register');
defined('LOUIS_OPTICTURE_BACKUP_FOLDER') || define('LOUIS_OPTICTURE_BACKUP_FOLDER', 'backup-image-compressors');

if (!function_exists("debug")) {
function debug($data) {
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}
}

if (!function_exists("get_array_value")) {
  /**
   * @param $limit
   * @return mixed
   * @author Tuan Louis
   */
  function get_array_value(array $array, $key)
  {
    if (array_key_exists($key, $array)) {
      return $array[$key];
    }
  }
}

function array_multidimensional_unique($input){
     $output = array_map("unserialize",
     array_unique(array_map("serialize", $input)));
   return $output;
}

if (!function_exists("html_escape")) {
  /**
   * Returns HTML escaped variable.
   *
   * @param	mixed	$var		The input string or array of strings to be escaped.
   * @param	bool	$double_encode	$double_encode set to FALSE prevents escaping twice.
   * @return	mixed			The escaped string or array of strings as a result.
   */
  function html_escape($var, $double_encode = true)
  {
    if (empty($var)) {
      return $var;
    }

    if (is_array($var)) {
      foreach (array_keys($var) as $key) {
        $var[$key] = html_escape($var[$key], $double_encode);
      }

      return $var;
    }

    return htmlspecialchars($var, ENT_QUOTES, "UTF-8", $double_encode);
  }
}


if (!function_exists("_parse_form_attributes")) {
  /**
   * Parse the form attributes
   *
   * Helper function used by some of the form helpers
   *
   * @param	array	$attributes	List of attributes
   * @param	array	$default	Default values
   * @return	string
   */
  function _parse_form_attributes($attributes, $default)
  {
    if (is_array($attributes)) {
      foreach ($default as $key => $val) {
        if (isset($attributes[$key])) {
          $default[$key] = $attributes[$key];
          unset($attributes[$key]);
        }
      }

      if (count($attributes) > 0) {
        $default = array_merge($default, $attributes);
      }
    }

    $att = "";

    foreach ($default as $key => $val) {
      if ($key === "value") {
        $val = html_escape($val);
      } elseif ($key === "name" && !strlen($default["name"])) {
        continue;
      }

      $att .= $key . '="' . $val . '" ';
    }

    return $att;
  }
}

if (!function_exists("_attributes_to_string")) {
  /**
   * Attributes To String
   *
   * Helper function used by some of the form helpers
   *
   * @param	mixed
   * @return	string
   */
  function _attributes_to_string($attributes)
  {
    if (empty($attributes)) {
      return "";
    }

    if (is_object($attributes)) {
      $attributes = (array) $attributes;
    }

    if (is_array($attributes)) {
      $atts = "";

      foreach ($attributes as $key => $val) {
        $atts .= " " . $key . '="' . $val . '"';
      }

      return $atts;
    }

    if (is_string($attributes)) {
      return " " . $attributes;
    }

    return false;
  }
}

if (!function_exists("getLouisImageCompressorSettings")) {
function getLouisImageCompressorSettings()
{
   $louis_image_compressor_settings = 'louis_image_compressor_settings';

     $option_exists = (get_option($louis_image_compressor_settings, null) !== null);

     if ($option_exists) {
        return get_option($louis_image_compressor_settings);
     }
     return [];
}
}

if (!function_exists("saveSettings")) {
function saveSettings($key, $value)
{

     $option_exists = (get_option($key, null) !== null);

     if ($option_exists) {
        update_option($key, $value);
     }else{
        add_option($key, $value);
     }
     return true;
}
}


/**
 * Write an entry to a log file in the uploads directory.
 *
 * @since x.x.x
 *
 * @param mixed $entry String or array of the information to write to the log.
 * @param string $file Optional. The file basename for the .log file.
 * @param string $mode Optional. The type of write. See 'mode' at https://www.php.net/manual/en/function.fopen.php.
 * @return boolean|int Number of bytes written to the lof file, false otherwise.
 */
if ( ! function_exists( 'louis_log' ) ) {
  function louis_log( $entry, $mode = 'a', $file = 'louis_opticture' ) {
    // Get WordPress uploads directory.
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    // If the entry is array, json_encode.
    if ( is_array( $entry ) ) {
      $entry = json_encode( $entry );
    }
    // Write the log file.
    $file  = $upload_dir . '/' . $file . '.log';
    $file  = fopen( $file, $mode );
    $bytes = fwrite( $file, current_time( 'mysql' ) . "::" . $entry . "\n" );
    fclose( $file );
    return $bytes;
  }
}
?>
