<div class="row" style="padding: 15px;">
    <div class="col-md-3">
        <label><?php echo __('Thời gian Cache','louis-image-compressors'); ?>:</label>
    </div>
    <div class="col-md-9">
        <div class="form-group">
        <div class="checkbox-wrapper">
              <?php
                echo form_input('cache_time', 10800, ['class' => 'form-control']);
                ?>
            <label class="checkbox-label"><?php echo __('Thiết lập thời gian lưu bộ nhớ đệm cho hệ thống','louis-image-compressors'); ?> 
            </label>
        </div>
        <p class="description">
            <?php echo __('Thời gian Cache ứng dụng mặc định là 10800 (3h)','louis-image-compressors'); ?>       
        </p>
        </div>
    </div>
</div>

