<?php
require_once WP_PLUGIN_DIR . '/louis-image-compressors/classes/optimize-image-handle.class.php';
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
class Louis_Image_Api {

    const STATUS_ENQUEUED = 10;
    const STATUS_SUCCESS = 2;
    const STATUS_UNCHANGED = 0;
    const STATUS_ERROR = -1;
    const STATUS_FAIL = -2;
    const STATUS_QUOTA_EXCEEDED = -3;
    const STATUS_SKIP = -4;
    const STATUS_NOT_FOUND = -5;
    const STATUS_NO_AUTH = -6;
    const STATUS_RETRY = -7;
    const STATUS_SEARCHING = -8;
    const STATUS_QUEUE_FULL = -404;
    const STATUS_MAINTENANCE = -500;
    const STATUS_CONNECTION_ERROR = -503;
    const STATUS_NOT_API = -1000;


    const ERR_FILE_NOT_FOUND = -902;
    const ERR_TIMEOUT = -903;
    const ERR_SAVE = -904;
    const ERR_SAVE_BKP = -905;
    const ERR_INCORRECT_FILE_SIZE = -906;
    const ERR_DOWNLOAD = -907;
    const ERR_POSTMETA_CORRUPT = -909;
    const ERR_UNKNOWN = -999;
    const ERR_AUTHENTICATION = -900;

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

    public function setOptionsCallApi($options)
    {
        Authentication::checkAuthentication();
        $fileSystem = new FileSystem();
        $authCache = new FileCache('authentication');
        list($width, $height, $type, $attr) = getimagesize($options['originalImage']);

        return [
              'key' => wp_basename($options['originalImage']),
              'settings' => [
                  'compressionType' => isset($data['type']) ? $data['type'] : get_array_value(getLouisImageCompressorSettings(), 'compressionType'),
                  'max_width' => get_array_value(getLouisImageCompressorSettings(), 'max_width'),
                  'max_height' => get_array_value(getLouisImageCompressorSettings(), 'max_height')
              ],
              'original' =>  [
                  'image' => $options['originalImage'],
                  'originalWidth' => $width,
                  'originalHeight' => $height,
                  'size' => fsize($options['originalImage'])
              ],
              'optimized' => [
                    'size' => null,
                    'image' => null
              ],
              'user' => $authCache->retrieve('user'),
              'domain' => get_site_url()
        ];
    }

    public function optictureOptimize()
    {
        $data = $_REQUEST;
        $loop = $data['loop'];
        $fileSystem = new FileSystem();
        $imageHandle = new OptimizeImageHandle();

        //check token authentication & quota before use
        Authentication::checkAuthentication();

        $allOptimizeItems = $fileSystem->getFileSourcesById($data['optimize_id']);
        // echo '<pre>';
        // print_r($allOptimizeItems);
        // echo '</pre>'; die();

        if(!empty($allOptimizeItems)){
            if($loop >= count($allOptimizeItems)){
               Louis_Response_Handle::setResponse(Louis_Response_Handle::STATUS_SUCCESS_ALL,'Optimize Successfully!');
            }

        $optimizeItem = $allOptimizeItems[$loop];

        $arguments = array(
            //'sslverify' => true,
             'headers'   => array(
                 'Content-Type' => 'application/json; charset=utf-8',
                 'Authorization' => 'Bearer ' . Authentication::getToken()
             ),
            'timeout'     => 45,
            'method' => 'POST',
            'body' => json_encode($this->setOptionsCallApi($optimizeItem))

        );

        $timeout = 45;
        if ( ! ini_get( 'safe_mode' ) ){
            set_time_limit( $timeout + 10 );
        }

        $response = wp_remote_post(API_IMAGE_COMPRESSORS, $arguments);

        //debug($response); die();

        $responseCode = wp_remote_retrieve_response_code( $response );

        $continued = ['loop' => $loop + 1];

        if ( is_wp_error( $response ) ) {
            $status = Louis_Response_Handle::STATUS_ERROR;

            return Louis_Response_Handle::setResponse($status, $response->get_error_message(), $continued);
        }else{
            $bodyResponse = json_decode($response['body']);

            if($responseCode === 200){

            $imageHandle->optimization(array_merge($optimizeItem,
                    [
                        'bufferData' => $bodyResponse->message,
                        'credits' => $bodyResponse->data->data->user->credits
                    ]
                ));

                $status = Louis_Response_Handle::STATUS_SUCCESS;
                $message = 'Optimized...' . $optimizeItem['image_name'];
                return Louis_Response_Handle::setResponse($status, $message, $continued);

                }

                 return Louis_Response_Handle::setResponse($bodyResponse->status, $bodyResponse->message, $continued);

                }

                $status = Louis_Response_Handle::STATUS_ERROR;
                return Louis_Response_Handle::setResponse($status, Louis_Response_Handle::ERR_FILE_NOT_FOUND, $continued);
        }

        $status = Louis_Response_Handle::STATUS_NOT_FOUND;
        return Louis_Response_Handle::setResponse($status, Louis_Response_Handle::ERR_FILE_NOT_FOUND, $continued);

        }

    public function compress_image_item()
    {
        $fileSystem = new FileSystem();
        $loop =  $_POST['data']['loop'];
        $cache = new FileCache();
        $imageHandle = new OptimizeImageHandle();
        $allOriginalImages = $fileSystem->getFiles();
        $totalImageOptimize = $fileSystem->getCountOptimizeFiles();

       //check token authentication & quota before use
       Authentication::checkAuthentication();

        if($fileSystem->getCountOptimizedFiles() >= $totalImageOptimize){
            $cache->eraseAll();
            Louis_Response_Handle::setResponse(
                       Louis_Response_Handle::STATUS_SUCCESS_ALL,
                       'Optimize Successfully 100%');
        }


        if(isset($loop) && Authentication::getToken() && !empty($allOriginalImages)){


        $arguments = array(
            //'sslverify' => true,
             'headers'   => array(
                 'Content-Type' => 'application/json; charset=utf-8',
                 'Authorization' => 'Bearer ' . Authentication::getToken()
             ),
            'timeout'     => 45,
            'method' => 'POST',
            'body' => json_encode($this->setOptionsCallApi($allOriginalImages[0]))
        );

        $timeout = 45;
        if ( ! ini_get( 'safe_mode' ) ){
            set_time_limit( $timeout + 10 );
        }


        $response = wp_remote_post(API_IMAGE_COMPRESSORS, $arguments);
        $responseCode = wp_remote_retrieve_response_code( $response );

        $status = 'success';


        if ( is_wp_error( $response ) ) {

            Louis_Response_Handle::setResponse(
                    Louis_Response_Handle::STATUS_ERROR, $response->get_error_message(), ['loop' => $loop + 1]);

            $cache->eraseAll();

        } else {

            $bodyResponse = json_decode($response['body']);

            if($responseCode === 200){


            $imageHandle->optimization(array_merge($allOriginalImages[0],
                [
                    'bufferData' => $bodyResponse->message,
                    'credits' => $bodyResponse->data->data->user->credits
                ]
            ));

          Louis_Response_Handle::setResponse(Louis_Response_Handle::STATUS_SUCCESS,'Optimize Successful',
           [
            'loop' => $loop + 1,
            'item' => $response['body'],
            'itemsOptimized' => $fileSystem->getCountOptimizedFiles(),
            'total' => $totalImageOptimize,
            'percentCompleted' => round(($fileSystem->getCountOptimizedFiles() / $totalImageOptimize) * 100 ). '%'
            ]);

            }
                Louis_Response_Handle::setResponse($bodyResponse->status, $bodyResponse->message);

               $cache->eraseAll();


            }

            Louis_Response_Handle::setResponse(
                        Louis_Response_Handle::STATUS_NOT_API, Louis_Response_Handle::ERR_API_NOT_CORRECT);

             $cache->eraseAll();

        }

        Louis_Response_Handle::setResponse(Louis_Response_Handle::STATUS_STOP_ALL, 'Error Stop');

    }

    public function checkBulkProcess()
    {
       $data = $_REQUEST;
       $data['optimize'] = null;
       $data['restore'] = null;
       //check queue work
       $cache = new FileCache('queue-work');
       if($cache->isCached('queue-optimize')){
          $cacheOptimize = $cache->retrieve('queue-optimize');
          $data['optimize'] = $cacheOptimize;
          $cache->erase('queue-optimize');
          $status = Louis_Response_Handle::STATUS_SUCCESS;
          $message = 'ok';
       }
       if($cache->isCached('queue-restore')){
             $cacheRestore = $cache->retrieve('queue-restore');
             $data['restore'] = $cacheRestore;
             $cache->erase('queue-restore');
             $status = Louis_Response_Handle::STATUS_SUCCESS;
             $message = 'ok';
          }

       return Louis_Response_Handle::setResponse($status, $message, $data);
    }

    public function optictureRestoreItem()
    {
        $data = $_REQUEST;
        $loop = $data['loop'];
        $imageHandle = new OptimizeImageHandle();

        //check authen
        Authentication::checkAuthentication();

        // get data current image
          $getItemsRestore = LouisOpticture::getRowsById($data['optimize_id']);
            // handle restore
          $response =  $imageHandle->restoreItem($loop, $getItemsRestore);

        return Louis_Response_Handle::setResponse($response['status'], $response['message'], $response['data']);

    }

    public function compareItem()
    {
         $data = $_REQUEST;
         $id = $data['id'];
         $originalImage = wp_get_attachment_image_src($id, 'full')[0];
         $optImage = str_replace('wp-content/uploads','wp-content/uploads/' . LOUIS_OPTICTURE_BACKUP_FOLDER . '/wp-content/uploads', $originalImage);

         $object = LouisOpticture::getRowByAttachId($id);
         $imageHandle = new OptimizeImageHandle();
         $responseData = [
             'originalImage' => $originalImage,
             'optImage' => $optImage,
             'sizeOriginalImage' => $imageHandle->fsize($originalImage),
             'sizeOptImage' => $imageHandle->fsize($optImage),
             'data' => $object
         ];

         return Louis_Response_Handle::setResponse(Louis_Response_Handle::STATUS_SUCCESS_ALL, 'ok', $responseData);
    }


    public function registerSubmit()
    {
        $data = $_REQUEST;
        if(get_array_value($data, 'email') && get_array_value($data, 'password')){


        $arguments = array(
            'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
            'method' => 'POST',
            'body' => json_encode([
              'name' => $data['name'],
              'email' => $data['email'],
              'password' => $data['password'],
              'password_repeat' => $data['password_repeat'],
              'website' => get_site_url()
            ])
        );
/*
        echo "<pre>";
        print_r($arguments);
        echo "</pre>"; die();
*/
        $response = wp_remote_post(API_REGISTER, $arguments);

        if ( is_wp_error( $response ) ) {
            $status = false;
            wp_send_json(
                [
                    'status' => $status,
                    'message' => $response->get_error_message()

                ]);
        }else{

        $response = json_decode($response['body']);
        $data = [];

        if($response->status === 200){
            $status = true;
            $message = $response->message;
            $data = $response->data;
        }else{
            $status = false;
            $message = $response->message;
            if($message->code == 'ER_DUP_ENTRY'){
                $message = "Tài khoản đã tồn tại trong hệ thống";
            }

        }

        }
       }
        wp_send_json([
        'success' => $status,
        'message' => $message,
        'data' => $data
            ]);

    }


      public function loginSubmit()
      {
          $data = $_REQUEST;
          $authCache = new FileCache('authentication');
          if(get_array_value($data, 'email') && get_array_value($data, 'password')){


          $arguments = array(
              'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
              'method' => 'POST',
              'body' => json_encode([
                'email' => $data['email'],
                'password' => $data['password']
              ])
          );
          $response = wp_remote_post(API_LOGIN, $arguments);

          $response = json_decode($response['body']);
          $data = [];

          if($response->status === 200){
              $status = true;
              $message = $response->message;
              $data = $response->data;
              if(!$authCache->isCached('token')){
                  $authCache->store('token', $data->token, 3600 * 24 * 365);
                  $authCache->store('user', $data->user->email, 3600 * 24 * 365);
              }
          }else{
              $status = false;
              $message = $response->message;
          }

          }

          wp_send_json([
          'success' => $status,
          'message' => $message,
          'data' => $data
              ]);

      }


    public function optictureReoptimize()
    {

    }



}
