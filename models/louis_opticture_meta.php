<?php

class LouisOpticture extends Base_Custom_Data {

    protected static $tableName = 'louis_opticture_meta';

    public static function _model()
    {
        return new Table(self::$tableName);
    }

    public static function getRowById($optimize_id)
    {

        $imageData = wp_get_attachment_image_src( $optimize_id, $default );
        $image = $imageData[0];
        $domain = get_site_url();
        $attach_name = str_replace($domain . '/wp-content/uploads/','', $image);
        return self::getRowByAttachName($attach_name);
    }

    public static function getRowsById($media_id)
    {
       $rows = [];
       $rows = self::_model()->get_by(['attach_id' => $media_id]);
       if(!empty($rows)){
           return $rows;
       }
    }

    public static function getRowByAttachId($attach_id)
    {
        $rows = [];
        $row = self::_model()->get_by(['attach_id' => $attach_id]);
        if(!empty($row)){
            return $row[0];
        }
    }

    public static function getRowByAttachName($attach_name)
    {
        $rows = [];
        $row = self::_model()->get_by(['attach_name' => $attach_name]);
        if(!empty($row)){
            return $row[0];
        }
    }

    public static function updateById($options, $id)
    {
        self::_model()->update($options, ['id' => $id]);
    }


    public static function checkOptimizedImage($attach)
    {
        $row = self::_model()->get_by(['attach_name' => $attach, 'status' => 1]);
        $hasRow = count($row);
        if($hasRow > 0){
            return false;
        }else{
            return true;
        }
    }


    public static function getAllOptimizedImage()
    {
        return self::_model()->get_by(['status' => 1]);
    }

}
