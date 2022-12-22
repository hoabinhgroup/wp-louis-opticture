<?php

class FileSetting {

    protected $_louis_optimized_image_ids = 'louis_optimized_image_ids';

    function __construct()
    {

    }

    public function getTest(){
        return 12;
    }

    public function getOptionsOptimizedImage()
    {
        $louis_optimized_image_ids = $this->_louis_optimized_image_ids;

         $option_exists = (get_option($louis_optimized_image_ids, null) !== null);

         if ($option_exists) {
            return get_option($louis_optimized_image_ids);
         }
         return [];
    }

    public function updateOptionsOptimizedImage($OptimizedImageId)
    {
        $louis_optimized_image_ids = $this->_louis_optimized_image_ids;

         $option_exists = (get_option($louis_optimized_image_ids, null) !== null);

         if ($option_exists) {
             update_option($louis_optimized_image_ids, array_merge((array) get_option($louis_optimized_image_ids), [$OptimizedImageId]));
         } else {
             add_option($louis_optimized_image_ids, [$OptimizedImageId]);
         }
         return true;
    }
}
?>
