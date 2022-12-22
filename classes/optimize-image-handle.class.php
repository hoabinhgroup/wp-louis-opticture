<?php
include( ABSPATH .'wp-load.php' );
include_once( ABSPATH . '/wp-admin/includes/image.php' );

class OptimizeImageHandle {

    public $_originalImage = '';

    public $_optimizedImage = '';

    function __construct()
    {
        @header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        @header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        @header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        @header("Cache-Control: post-check=0, pre-check=0", false);
        @header("Pragma: no-cache");

    }

    public function getFilename()
    {
        return wp_basename($this->_originalImage);
    }

    public function getFileExtension()
    {
        return pathinfo($this->_originalImage,PATHINFO_EXTENSION );
    }

    public function getFileSize($pathname)
    {
        return filesize($pathname);
    }

    public function fsize($url) {
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

    public function getImageOriginalDirectory()
    {
        $extractUrlImage = explode('/', $this->_originalImage);
        unset($extractUrlImage[0],$extractUrlImage[1],$extractUrlImage[2]);
        $imagename = array_pop($extractUrlImage);
        return join('/', $extractUrlImage);  //wp-content/uploads/2016/12
    }


    public function getPathBackupImageOriginalDirectory()
    {
        return trailingslashit( wp_upload_dir()['basedir'] ) .
            LOUIS_OPTICTURE_BACKUP_FOLDER .
            '/' .
            $this->getImageOriginalDirectory();
    }

    public function getPathOriginalImage()
    {
        return get_home_path() .  $this->getImageOriginalDirectory() .'/'. $this->getFilename();
    }

    public function getUrlBackupImage()
    {

        return wp_upload_dir()['baseurl'] . '/'. LOUIS_OPTICTURE_BACKUP_FOLDER . '/' .  $this->getImageOriginalDirectory() .'/'. $this->getFilename();
    }

    public function createBackupDirectoryImageOriginal()
    {
        //debug($this->getPathBackupImageOriginalDirectory()); die();
        return wp_mkdir_p( $this->getPathBackupImageOriginalDirectory() );
    }

    public function copydt($pathSource, $pathDest) {
        if(copy($pathSource, $pathDest)){
            $dt = filemtime($pathSource);
            if ($dt === FALSE) return FALSE;
            return touch($pathDest, $dt);
        }
        return FALSE;
    }

    public function getContentOriginalImage()
    {
        try {
       return file_get_contents($this->_originalImage);

       } catch (\Exception $e) {
           die($e->getMessage());
       }
    }

    public function backupDirectoryImageOriginal()
    {
        // check setting
        try {
        if(!get_array_value(getLouisImageCompressorSettings(), 'backup_original_image')) return false;
        $this->createBackupDirectoryImageOriginal();
        $this->copydt($this->getPathOriginalImage(), $this->getPathBackupImageOriginalDirectory() .'/'. $this->getFilename());
        return true;

        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function restoreItem($loop, $getItemsRestore){

        if(file_exists($this->getPathOriginalImage())){
        $itemRestore = $getItemsRestore[$loop];
        $orginal_image = wp_upload_dir()['baseurl'] . '/' .  $itemRestore->attach_name;
        $this->_originalImage = $orginal_image;
       // sleep(2);
        $filemtime = filemtime($this->getPathOriginalImage());
        // copy url image backup to main path
        // debug($this->getUrlBackupImage()); 
        // debug($this->getPathOriginalImage()); 
        
        // die();
        $this->save_image($this->getUrlBackupImage(), $this->getPathOriginalImage());
        touch($this->getPathOriginalImage(), $filemtime);

        $original = [
            'attach_id' => $itemRestore->attach_id,
            'parent' => $itemRestore->parent,
            'status' => 0,
            'compressed_size' => 0,
            'original_size' => $this->fsize($orginal_image)
        ];

        LouisOpticture::updateById($original, $itemRestore->id);
         if($loop < count($getItemsRestore)){
         return [
             'status' => Louis_Response_Handle::STATUS_SUCCESS,
             'message' => 'Khôi phục ảnh ...' . $itemRestore->image_name,
             'data' => ['loop' => $loop + 1]
         ];

         }else{
             return [
                  'status' => Louis_Response_Handle::STATUS_SUCCESS_ALL,
                  'message' => 'Hoàn thành khôi phục',
                  'data' => true
              ];

         }
        }

    }


    public function saveOriginalImageDirectory()
   {
      if(ini_get('allow_url_fopen')){
          try {

          if(file_exists($this->getPathOriginalImage())){
             $filemtime = filemtime($this->getPathOriginalImage());
          }
          //allow_url_fopen true
          //copy($this->_optimizedImage, $this->getPathOriginalImage());

        //  $this->save_image($this->_optimizedImage, $this->getPathOriginalImage());
          $this->save_image($this->_optimizedImage, $this->getPathOriginalImage());


          if(file_exists($this->getPathOriginalImage())){
             touch($this->getPathOriginalImage(), $filemtime);
          }

          return true;
          } catch (\Exception $e) {
          louis_log( ['error savefileOptimize: ' => $e->getMessage()] );

        }
      }else{
       try {
            $filemtime = filemtime($this->getPathOriginalImage());
            louis_log( ['OptimizeImageHandle_savefileOptimize: ' => $this->getPathOriginalImage()] );

            $savefileOptimize = fopen($this->getPathOriginalImage(), 'w');
            $image = file_get_contents($this->_optimizedImage);
              fwrite($savefileOptimize, $image);
              fclose($savefileOptimize);
              touch($this->getPathOriginalImage(), $filemtime);
              return true;

              } catch (\Exception $e) {
                  louis_log( ['error savefileOptimize: ' => $e->getMessage()] );
                  die($e->getMessage());
              }
      }

   }

   function save_image($image_url, $image_file){
       if(file_exists($image_file)){
              @unlink($image_file);
         }

       $fp = fopen ($image_file, 'w+');      // open file handle

       $ch = curl_init($image_url);
       // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
       curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       curl_setopt($ch, CURLOPT_TIMEOUT, 5000);      // some large value to allow curl to run for a long time
       curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
       //curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
       curl_exec($ch);

       curl_close($ch);                              // closing curl handle
       fclose($fp);                                  // closing file handle
   }




   public function updateOptimizedImage($original){
       try {
        list($width, $height, $type, $attr) = getimagesize($this->_originalImage);
            $domain = get_site_url();
            $attach_name = $original['attach_name'];
            $table = new Table('louis_opticture_meta');
        
            $object = LouisOpticture::getRowByAttachName($attach_name);
            //debug($object); die();

            //$hasRow = count($object);
            if(!$object){
                $options = [
                   'attach_name' => $attach_name,
                   'attach_id' => $original['attach_id'],
                   'parent' => $original['parent'],
                   'image_name' => $this->getFilename(),
                   'image_type' => $type ? $type: null,
                   'size' => null,
                   'status' => 0,
                   'compression_type' => get_array_value(getLouisImageCompressorSettings(), 'compressionType'),
                   'original_size' => $this->fsize($this->_originalImage),
                   'timeCreated' => current_time('mysql'),
                   'extra_info' => json_encode([
                       'isConverted' => false,
                       'originalWidth' => $width,
                       'originalHeight' => $height
                 ]),
                 ];

              $return_id = $table->save($options);
            }else{

               $table->update(
                   [
                'status' => 1,
                'compressed_size' => $this->fsize($this->_optimizedImage),
                //'compressed_size' => filesize( trailingslashit( wp_upload_dir()['basedir'] ) . $object->attach_name ),
                'timeOptimized' => current_time('mysql'),
                'extra_info' => json_encode([
                       'wasConverted' => true,
                       'originalWidth' => $width,
                       'originalHeight' => $height
                 ]),

                    ], ['id' => $object->id]);

                saveSettings('louis_opticture_quota', $original['credits']);
            }

               } catch (\Exception $e) {
                   die($e->getMessage());
               }


        }

    public function generateOptimizedImage($bufferData)
    {
        //handle bufferData
        $imageDataDecoded = base64_decode($bufferData);
        $image = imagecreatefromstring($imageDataDecoded);
        //debug($image); die();
        if ($image !== false) {
        //@header('Content-Type: image/jpeg');
        imagejpeg($image, $this->getPathOriginalImage());
        } else {
        //err
        imagedestroy($image);
        }
    }


    public function setTimeQueueByImageSize()
    {
        $originalFilesize = $this->fsize($this->_originalImage);
        $rateFilesize = ceil(($originalFilesize / 1000000 )) * 2;
        sleep($rateFilesize);
        return true;
    }


    public function optimization($settings)
    {

        $this->_originalImage =  $settings['originalImage'];
        $this->backupDirectoryImageOriginal();
        $this->updateOptimizedImage($settings);
        $this->generateOptimizedImage($settings['bufferData']);
        $this->_optimizedImage = $this->_originalImage;
        $this->updateOptimizedImage($settings);
    }

}
?>
