
  <div class="row">
      <div class="col-md-3">
          <label><?php echo __('Chọn kiểu nén ảnh','louis-image-compressors'); ?>:</label>
      </div>
      <div class="col-md-9">
          <div class="louis-compression">
            <div class="louis-compression-options">
              <label class="<?php echo __('Nén Lossy (khuyến nghị): cung cấp tốc độ nén tốt nhất. Đây là tùy chọn được đề xuất cho hầu hết người dùng, tạo ra kết quả giống như bản gốc đối với mắt người.','louis-image-compressors'); ?>">
                <?php echo form_radio(
                        'compressionType',
                        1,
                        get_array_value($lic_settings, 'compressionType') && $lic_settings['compressionType'] == 1,
                        [
                            'class' => 'louis-radio-lossy'
                        ]); ?>
                    <span> Lossy </span>
              </label>
              <label class="<?php echo __('Nén kiểu gần Lossless : tạo ra hình ảnh gần như hoàn hảo từng pixel so với ảnh gốc. Tùy chọn tốt nhất cho các nhiếp ảnh gia và các chuyên gia khác sử dụng hình ảnh chất lượng rất cao trên trang web và muốn nén ảnh tốt nhất trong khi vẫn giữ chất lượng không bị ảnh hưởng.','louis-image-compressors'); ?>">
                  <?php echo form_radio(
                        'compressionType',
                         2,
                         get_array_value($lic_settings, 'compressionType') && $lic_settings['compressionType'] == 2,
                          [
                              'class' => 'louis-radio-near-lossless'
                          ]); ?>
                      <span> NearLossless </span>
                </label>
                <label class="<?php echo __('Nén kiểu Lossless: sử dụng thuật toán cho hình ảnh kết quả giống pixel so với ảnh gốc. Đảm bảo rằng không có một pixel nào trong hình ảnh được tối ưu hóa trông khác so với hình ảnh gốc. Trong một số trường hợp đặc biệt, bạn sẽ cần sử dụng kiểu nén này. Một số bản vẽ kỹ thuật hoặc hình ảnh từ đồ họa vector là những tình huống có thể xảy ra.','louis-image-compressors'); ?>">
                  <?php echo form_radio(
                      'compressionType',
                      3,
                      get_array_value($lic_settings, 'compressionType') && $lic_settings['compressionType'] == 3,
                          [
                              'class' => 'louis-radio-lossless'
                          ]); ?>
                      <span> Lossless </span>
                </label>

                <p class="settings-info louis-radio-info louis-radio-lossy">
                <?php echo __('Nén Lossy (khuyến nghị): cung cấp tốc độ nén tốt nhất. Đây là tùy chọn được đề xuất cho hầu hết người dùng, tạo ra kết quả giống như bản gốc đối với mắt người.','louis-image-compressors'); ?>
                </p>
                <p class="settings-info louis-radio-info louis-radio-near-lossless" style="display: none;">
                <?php echo __('Nén kiểu gần Lossless : tạo ra hình ảnh gần như hoàn hảo từng pixel so với ảnh gốc. Tùy chọn tốt nhất cho các nhiếp ảnh gia và các chuyên gia khác sử dụng hình ảnh chất lượng rất cao trên trang web và muốn nén ảnh tốt nhất trong khi vẫn giữ chất lượng không bị ảnh hưởng.','louis-image-compressors'); ?>
                </p>
                <p class="settings-info louis-radio-info louis-radio-lossless" style="display: none;">
                <?php echo __('Nén kiểu Lossless: sử dụng thuật toán cho hình ảnh kết quả giống pixel so với ảnh gốc. Đảm bảo rằng không có một pixel nào trong hình ảnh được tối ưu hóa trông khác so với hình ảnh gốc. Trong một số trường hợp đặc biệt, bạn sẽ cần sử dụng kiểu nén này. Một số bản vẽ kỹ thuật hoặc hình ảnh từ đồ họa vector là những tình huống có thể xảy ra.','louis-image-compressors'); ?>
                </p>

                <script>
                    // @todo Remove JS from interface
                    function louisCompressionLevelInfo() {
                        jQuery(".louis-compression p").css("display", "none");
                        jQuery(".louis-compression p." + jQuery(".louis-compression-options input:radio:checked").attr('class')).css("display", "block");
                    }

                    jQuery(".louis-compression-options input:radio").change(louisCompressionLevelInfo);
                </script>
            </div>
            </div>
      </div>
  </div>

  <div class="row">
        <div class="col-md-3">
            <label><?php echo __('Nén ảnh thumbnail đi kèm','louis-image-compressors'); ?>:</label>
        </div>
        <div class="col-md-9">
            <div class="checkbox-wrapper">

                  <?php
                    echo form_checkbox(
                        'apply_thumbnail',
                         1,
                        get_array_value($lic_settings, 'apply_thumbnail'),
                        [
                            'class' => 'form-control',
                            'id' => 'apply_thumbnail'
                        ]);
                    ?>
                <label class="checkbox-label"><?php echo sprintf(__('Áp dụng nén cho cả %1$s (Tổng số %2$s ảnh thumbnails cần tối ưu)','louis-image-compressors'), '<strong> ảnh thumbnail</strong>', count((new FileSystem())->getThumbnailFiles())); ?>
                </label>
            </div>
            <p class="description">
                <?php echo __('Chúng tôi khuyên bạn nên tối ưu hóa các ảnh thumbnail vì chúng thường là những hình ảnh được người dùng cuối xem nhiều nhất và có thể tạo ra nhiều lưu lượng truy cập nhất.','louis-image-compressors'); ?>    
            </p>

                <section id="section_thumnails_size" <?php echo get_array_value($lic_settings, 'apply_thumbnail')?'':' style="display:none"'; ?>>
                    <i><?php echo __('Chọn các loại ảnh thumbnail bạn muốn tối ưu','louis-image-compressors'); ?>: </i>
                    <?php $all_image_size = _get_all_image_sizes();
                         $thumbnails = $all_image_size['thumbnail'];
                         $medium = $all_image_size['medium'];
                         $large = $all_image_size['large'];
                    ?>
                    <ul style="padding-left: 15px;">
                    <?php
                    foreach($all_image_size as $size => $value):
                        ?>
                        <li>
                        <?php
                        echo form_checkbox(
                        'apply_'.$size.'_size',
                         "/".$value['width'].'x'.$value['height']."/",
                        get_array_value($lic_settings, 'apply_'.$size.'_size'),
                        [
                            'class' => 'form-control'
                        ]);

                    ?>
                    <label class="checkbox-label"><?php echo $size; ?></label>
                        </li>
                    <?php  endforeach; ?>
                    </ul>

                </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <label><?php echo __('Backup ảnh','louis-image-compressors'); ?>:</label>
        </div>
        <div class="col-md-9">
            <div class="checkbox-wrapper">
                  <?php
                    echo form_checkbox(
                        'backup_original_image',
                        1,
                        get_array_value($lic_settings, 'backup_original_image'),
                        [
                            'class' => 'form-control'
                        ]);
                    ?>
                <label class="checkbox-label"><?php echo __('Lưu và giữ bản sao lưu các hình ảnh gốc trong 1 thư mục riêng biệt','louis-image-compressors'); ?>
                </label>
            </div>
            <p class="description">
            <?php echo __('Bạn cần có bản sao lưu đang hoạt động để có thể khôi phục hình ảnh về bản gốc.','louis-image-compressors'); ?>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <label><?php echo __('Giảm kích thước ảnh lớn','louis-image-compressors'); ?>:</label>
        </div>
        <div class="col-md-9">
            <div class="checkbox-wrapper">
                  <?php
                  $resize_options =  [
                       'class' => 'max_size',
                       'placeholder' => '750',
                       'style' => 'width: 70px'
                    ];
                 $resize_large_image = get_array_value($lic_settings, 'resize_large_image');
                 if(!$resize_large_image){
                     $resize_options['disabled'] = true;
                 }

                    echo form_checkbox(
                        'resize_large_image',
                        1,
                        get_array_value($lic_settings, 'resize_large_image'),
                        [
                            'id' => 'resize_large_image',
                            'class' => 'form-control'
                        ]);
                    ?>
                <label class="checkbox-label"><?php echo __('tối đa','louis-image-compressors'); ?>
                </label>
                    <?php echo form_input('max_width', get_array_value($lic_settings, 'max_width'), $resize_options); ?>


                    <label class="checkbox-label"> <?php echo __('pixels theo chiều rộng','louis-image-compressors'); ?> x</label>

                    <?php echo form_input('max_height', get_array_value($lic_settings, 'max_height'),
                        $resize_options); ?>
               <label class="checkbox-label"> <?php echo __('pixels theo chiều cao (tỷ lệ ảnh được giữ nguyên và hình ảnh không bị cắt)','louis-image-compressors'); ?></label>

            </div>
            <p class="description">
            <?php echo __('Bạn nên sử dụng tính năng này cho các ảnh lớn, như ảnh được chụp bằng điện thoại hay máy ảnh kỹ thuật số. Dung lượng được tiết kiệm có thể tăng lên đến 80% hoặc hơn sau khi thay đổi kích thước.','louis-image-compressors'); ?>
               
            </p>
        </div>
    </div>


