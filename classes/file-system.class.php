<?php

class FileSystem {

    protected $_fileSetting;
    protected $_cache;


    function __construct()
    {
        $this->_cache = new FileCache();
    }

    function getFileSettingModel()
    {
        return new FileSetting();
    }

    function cache(){
        return new Cache();
    }

    function optimize()
    {
        return new OptimizeImageHandle();
    }

    function getOptimizedImageIds()
    {
        return $this->getFileSettingModel()->getOptionsOptimizedImage();
    }

    function date_compare($element1, $element2) {
         return $element1['time'] - $element2['time'];
     }

    function getBaseDir()
    {
        return wp_upload_dir()['basedir'];
    }

    function getUrlfromPath($path)
    {
        $upload_dir   = wp_upload_dir();
        $split = explode('wp-content/uploads', $path );
        return $upload_dir['baseurl'] . $split[1];
    }

    function getIdentifierFromPath($path)
    {
        $upload_dir   = wp_upload_dir();
        $split = explode('wp-content/uploads', $path );
        return substr($split[1], 1);
    }

    function getIdentifierFromUrl($url)
    {
        $split = explode('wp-content/uploads/', $url );
        return $split[1];
    }



         function getFileSources($type = 'all')
         {
            $image_ids = get_posts(
            array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'fields'         => 'ids',
            ) );

            $optimizeImages = [];
            if(!empty($image_ids)){
            foreach($image_ids as $key=> $id):
                $fullImage = wp_get_attachment_image_src($id, 'full')[0];
                if($type != 'thumbnail'){
              $optimizeImages[] =  [
                  'originalImage' => $fullImage,
                  'name' => wp_basename($fullImage),
                  'attach_name' => $this->getIdentifierFromUrl($fullImage),
                  'attach_id' => $id,
                  'parent' => 0
                  ];
                  //get not scaled
             if($this->contains($fullImage, ['/-scaled.(?:jpe?g|gif|png)/']))
                  {
                    $fullImageNotScaled = preg_replace('/-scaled/', '', $fullImage);
                    $optimizeImages[] =  [
                      'originalImage' => $fullImageNotScaled,
                      'name' => wp_basename($fullImageNotScaled),
                      'attach_name' => $this->getIdentifierFromUrl($fullImageNotScaled),
                      'attach_id' => $id,
                      'parent' => 0
                      ];
                  }
              }

              if(get_array_value(getLouisImageCompressorSettings(), 'apply_thumbnail')){
                    $allImageSize = array_keys(_get_all_image_sizes());
                    foreach($allImageSize as $size):
                        if(get_array_value(getLouisImageCompressorSettings(), 'apply_'.$size.'_size')){
                            if(wp_get_attachment_image_src($id, $size)[0] && wp_get_attachment_image_src($id, $size)[0] != $fullImage){
                             $optimizeImages[] = [
                               'originalImage' => wp_get_attachment_image_src($id, $size)[0],
                               'name' => wp_basename(wp_get_attachment_image_src($id, $size)[0]),
                               'attach_name' => $this->getIdentifierFromUrl(wp_get_attachment_image_src($id, $size)[0]),
                               'attach_id' => $id,
                               'parent' => $id
                               ];
                             }
                        }
                    endforeach;
              }
            endforeach;
           unset($image_ids);
            }
            return $optimizeImages;

         }

     function getFileSourcesById($id)
     {
         $fullImage = wp_get_attachment_image_src($id, 'full')[0];
            $optimizeImages[] = [
            'originalImage' => $fullImage,
            'image_name' => wp_basename($fullImage),
            'attach_name' => $this->getIdentifierFromUrl($fullImage),
            'attach_id' => $id,
            'parent' => 0
            ];
            //get not scaled
             if($this->contains($fullImage, ['/-scaled.(?:jpe?g|gif|png)/']))
                  {
                    $fullImageNotScaled = preg_replace('/-scaled/', '', $fullImage);
                    $optimizeImages[] =  [
                      'originalImage' => $fullImageNotScaled,
                      'name' => wp_basename($fullImageNotScaled),
                      'attach_name' => $this->getIdentifierFromUrl($fullImageNotScaled),
                      'attach_id' => $id,
                      'parent' => 0
                      ];
                  }

           if(get_array_value(getLouisImageCompressorSettings(), 'apply_thumbnail')){
            $allImageSize = array_keys(_get_all_image_sizes());
            foreach($allImageSize as $size):
                if(get_array_value(getLouisImageCompressorSettings(), 'apply_'.$size.'_size')){
                    if(wp_get_attachment_image_src($id, $size)[0] && wp_get_attachment_image_src($id, $size)[0] != $fullImage){
                     $optimizeImages[] = [
                       'originalImage' => wp_get_attachment_image_src($id, $size)[0],
                       'image_name' => wp_basename(wp_get_attachment_image_src($id, $size)[0]),
                       'attach_name' => $this->getIdentifierFromUrl(wp_get_attachment_image_src($id, $size)[0]),
                       'attach_id' => $id,
                       'parent' => $id
                       ];
                     }
                }
                 endforeach;
           }
           return $optimizeImages;
     }


     function getCacheFileSources(){
         if(!$this->_cache->isCached('cache-file-sources')){

          $getFileSources = $this->getFileSources();

          $this->_cache->store('cache-file-sources', $getFileSources, $this->_cache->_cacheTime);
            return $getFileSources;
          }else{
              return $this->_cache->retrieve('cache-file-sources');
           }
     }

     function getCacheThumbnailFileSources(){
          if(!$this->_cache->isCached('cache-thumbnail-file-sources')){

           $getFileSources = $this->getFileSources('thumbnail');

           $this->_cache->store('cache-thumbnail-file-sources', $getFileSources, $this->_cache->_cacheTime);
             return $getFileSources;
           }else{
               return $this->_cache->retrieve('cache-thumbnail-file-sources');
            }
      }

     function foldersize($path) {
        if ( !is_dir( $path ) ){
            return 0;
        }
       $total_size = 0;
       $files = scandir($path);

       foreach($files as $t) {
         if (is_dir(rtrim($path, '/') . '/' . $t)) {
           if ($t<>"." && $t<>"..") {
               $size = $this->foldersize(rtrim($path, '/') . '/' . $t);

               $total_size += $size;
           }
         } else {
           $size = filesize(rtrim($path, '/') . '/' . $t);
           $total_size += $size;
         }
       }
       return $total_size;
     }

     function hasBackupDirectory()
     {
         if(is_dir($this->getBaseDir().'/' . LOUIS_OPTICTURE_BACKUP_FOLDER)){
             return true;
         }
         return false;
     }


     function getBackupDirectorySize()
     {
         if(!$this->_cache->isCached('backup-size')){
            $backupDirectorySize = $this->foldersize($this->getBaseDir().'/' . LOUIS_OPTICTURE_BACKUP_FOLDER);
            $this->_cache->store('backup-size', $backupDirectorySize, $this->_cache->_cacheTime);
            return $backupDirectorySize;
         }else{
           return $this->_cache->retrieve('backup-size');
         }
     }

     function getOptimizedImageSize()
     {
         if(!$this->_cache->isCached('optimized-size')){
         $size = 0;
         $allOptimizedImage = LouisOpticture::getAllOptimizedImage();
         if(!empty($allOptimizedImage)){
         foreach($allOptimizedImage as $image):
           $size += $image->compressed_size;
         endforeach;
         }
         $this->_cache->store('optimized-size', $size, $this->_cache->_cacheTime);
         return $size;
         }else{
             return $this->_cache->retrieve('optimized-size');
          }
     }

     function getPercentSavingImageOptimized()
     {
         if(!$this->getBackupDirectorySize()) return 0;
        return round((($this->getBackupDirectorySize() - $this->getOptimizedImageSize()) / $this->getBackupDirectorySize()) * 100);
     }

     function getFiles()
     {
        $listOptimizeProgress = [];
        $getFileSources = $this->getCacheFileSources();
        if(!empty($getFileSources)){
        foreach($getFileSources as $key => $url):

            if (LouisOpticture::checkOptimizedImage($url['attach_name'])) {

            $listOptimizeProgress[] = $url;
            }

        endforeach;
        }
        unset($getFileSources);
        return $listOptimizeProgress;
     }

     function getCountOptimizeFiles()
       {
           if(!$this->_cache->isCached('count-optimize')){
           $getFileSources = $this->getCacheFileSources();

           $this->_cache->store('count-optimize', count($getFileSources), $this->_cache->_cacheTime);

             return count($getFileSources);
           }else{
             return $this->_cache->retrieve('count-optimize');
           }

       }

     function getThumbnailFiles()
     {
         if(!$this->_cache->isCached('count-thumbnails')){
         $getFileSources = $this->getCacheThumbnailFileSources();

         $this->_cache->store('count-thumbnails', $getFileSources, $this->_cache->_cacheTime);
         return $getFileSources;
     }else{
         return $this->_cache->retrieve('count-thumbnails');
     }
     }


      function getCountOptimizedFiles()
        {
         return count(LouisOpticture::getAllOptimizedImage());
        }

     function getCacheCountOptimizedFiles()
     {
         if(!$this->_cache->isCached('count-optimized')){
         $this->_cache->store('count-optimized', (!empty(LouisOpticture::getAllOptimizedImage())) ? $this->getCountOptimizedFiles(): 0);
         return (!empty(LouisOpticture::getAllOptimizedImage())) ? $this->getCountOptimizedFiles(): 0;
      }else{
         return $this->_cache->retrieve('count-optimized');
      }
     }

     function getFilters()
       {
           $filters = array_merge($this->getOnlyOriginalFiles(), $this->getOnlyOriginalThumbnails());
           return $filters;
       }


     function getOnlyOriginalFiles()
       {
           return ["/^(?!.*-[0-9]{1,4}x[0-9]{1,4}).*\.(?:jpe?g|gif|png)$/"];

       }

    function getOnlyOriginalThumbnails()
      {

          if(get_array_value(getLouisImageCompressorSettings(), 'apply_thumbnail')){
                if(get_array_value(getLouisImageCompressorSettings(), 'apply_thumbnail_size')){
                  $filters[] = get_array_value(getLouisImageCompressorSettings(), 'apply_thumbnail_size');
                }
                if(get_array_value(getLouisImageCompressorSettings(), 'apply_medium_size')){
                   $apply_medium_size = get_array_value(getLouisImageCompressorSettings(), 'apply_medium_size');
                   $split_medium_size = explode('x', $apply_medium_size);
                   $filters[] = '/(('.substr($split_medium_size[0],1).'x[0-9]{2,4})|([0-9]{2,4}x'.substr($split_medium_size[1],0,-1).')).(jpe?g|png|gif)/';

                 }
                if(get_array_value(getLouisImageCompressorSettings(), 'apply_large_size')){
                    $apply_large_size = get_array_value(getLouisImageCompressorSettings(), 'apply_large_size');
                    $split_large_size = explode('x', $apply_large_size);
                   $filters[] = '/(('.substr($split_large_size[0],1).'x[0-9]{2,4})|([0-9]{2,4}x'.substr($split_large_size[1],0,-1).')).(jpe?g|png|gif)/';
                  }
            }
          return $filters;
      }



     function contains($str, array $arr)
     {
         foreach($arr as $a) {
             if (preg_match($a, $str)) return true;
         }
         return false;
     }
}
?>
