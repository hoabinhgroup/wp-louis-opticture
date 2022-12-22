<style>
.lo-loading-small{
    width: 26px;
    vertical-align: middle;
    margin-right: 10px;
}
.lo-column-info {
    position: relative;
}
.lo-dropbtn.button {
    box-sizing: content-box;
    padding: 0 5px;
    font-size: 20px;
    line-height: 20px;
    cursor: pointer;
    z-index: 9999;
    position: relative;
}
.lo-dropdown-content a:hover {
    background-color: #f1f1f1;
}

.lo-dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}
.column-wp-louis-opticture .lo-dropdown {
    max-width: 140px;
    float: right;
    text-align: right;
    position: relative;
}
.lo-column-info{
    width: 300px;
}
.lo-column-info .lo-dropdown {
    position: absolute;
    right: 10px;
    top: 0;
}
.lo-dropdown-content {
    display: none;
    right: 0;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 190px;
    box-shadow: 0px 8px 16px 0px rgb(0 0 0 / 20%);
    z-index: 1;
}
.lo-dropdown.lo-show .lo-dropdown-content{
    display: block !important;
}

.twentytwenty-before-label{
    opacity: 1 !important;
}
.twentytwenty-after-label{
   opacity: 1 !important;
}

#overlay {
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    background: #dcdbdb;
    width: 100%;
    height: 100%;
    opacity: 0.5;
    z-index: 100;
    left: 0px;
}

#popup {
    display: none;
    position: fixed;
    top: 40%;
    left: 50%;
    background: #fff;
    width: 1000px;
    height: auto;
    margin-left: 80px;
    margin-top: -250px;
    transform: translateX(-50%);
    z-index: 200;
}

#popupclose {
    float: right;
    padding: 10px;
    cursor: pointer;
    position: relative;
    top: -16px;
    right: -12px;
    background: #fff;
    border: 1px solid #ccc;
    z-index: 999;
    border-radius: 50%;
    width: 15px;
    height: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.popupcontent {
    padding: 10px;
}
</style>
<?php
$compression_type = 'Lossy';
if(!empty($row)){
    $data = $row;
    if($data->compression_type == 2){
      $compression_type = 'NearLossless';
    }elseif($data->compression_type == 3){
      $compression_type = 'Lossless';
    }
    if($data->status == 1){
        if($data->compressed_size == 0){
        $message = __('Lỗi file ảnh tối ưu rỗng','louis-image-compressors');
        }else{
         $percent = round((($data->original_size - $data->compressed_size) / $data->original_size) * 100);
         $message = '<p>' . sprintf(__('Đã giảm %1$s dung lượng (%2$s)','louis-image-compressors'), '<strong>'.$percent.'%</strong>', $compression_type ) . '</p>';
        }
        ?>


        <div class="lo-dropdown">
           <button onclick="LouisOpticture.openImageMenu(event);" class="lo-dropbtn button dashicons dashicons-menu " title="LouisOpticture Actions"></button>
           <div id="lo-dd-<?php echo $columnID; ?>" class="lo-dropdown-content">
               <a onclick="LouisOpticture.compareItem(<?php echo $columnID; ?>);" href class="0"><?php echo __('So sánh','louis-image-compressors'); ?></a>
               <!-- <a onclick="LouisOpticture.reOptimizeItem(<?php echo $columnID; ?>, 3);" href class="reoptimize-lossless">Tối ưu Lossless</a>
               <a onclick="LouisOpticture.reOptimizeItem(<?php echo $columnID; ?>, 2);" href class="reoptimize-glossy">Tối ưu NearLossless</a> -->
               <a onclick="LouisOpticture.restoreItem(<?php echo $columnID; ?>);" href class="restore"><?php echo __('Khôi phục ảnh','louis-image-compressors'); ?></a>
           </div> <!--lo-dropdown-content-->

           </div>

        <?php
    }else{
        $message = '<a href onClick="LouisOpticture.optimize(' . $columnID .')" class=" button-smaller button-primary optimize ">'.__('Tối ưu ngay','louis-image-compressors').'</a>';
    }
}else{
    $message = '<a href onClick="LouisOpticture.optimize(' . $columnID .')" class=" button-smaller button-primary optimize ">'. __('Tối ưu ngay','louis-image-compressors') .'</a>';

}

   ?>
<div class="lo-column-info" id="lo-msg-<?php echo $columnID; ?>">
<?php echo $message; ?>
</div>

<?php include_once("modal.php"); ?>

