<section class="wrapper-container">
<div class="row">
      <div class="col-md-6">
          <div class="panel">
          <h3><?php echo esc_html__('Tối ưu hình ảnh trong thư viện','louis-image-compressors'); ?></h3>
         <p style="color: red">
             <div id="authentication-logged">
                 <?php if($authCache->isCached('token')){
                       $user =  $authCache->retrieve('user');
                          ?>
                          <?php 
                         echo sprintf( __( 'Chào %s, bạn có thể tiến hành tối ưu ảnh trong thư viện ngay', 'louis-image-compressors' ), '<strong>'. $user .'!</strong>' ); ?>
     
                          <?php } ?>
                 <div id="authentication-user-logged" class="row">
                    <div class="row">
                        <div class="info-item col-md-4">
                            <div class="heading"><?php echo __('Ảnh đã tối ưu','louis-image-compressors'); ?></div>
                            <p class="heading-number"><?php echo $countOptimizedFiles; ?></p>
                        </div>
                        <div class="info-item col-md-4">
                            <div class="heading"><?php echo __('Ảnh chưa tối ưu','louis-image-compressors'); ?></div>
                            <p class="heading-number"><?php echo $countOptimizeFiles - $countOptimizedFiles; ?></p>
                        </div>
                        <div class="info-item col-md-4">
                            <div class="heading"><?php echo __('Tín dụng','louis-image-compressors'); ?></div>
                            <p class="heading-number"><?php echo get_option('louis_opticture_quota', 0);
                             ?></p>
                        </div>
                    </div>
                 </div>

             </div>
         </p>
          </div>
      </div>
      <div class="col-md-6">
        <h3><?php echo __('Tổng số dung lượng đã giảm','louis-image-compressors'); ?></h3>
        <p><?php echo __('Thống kê dựa trên số ảnh JPEG và PNG trong thư viện','louis-image-compressors'); ?></p>
        <div class="row">
            <div class="col-md-5">
                    <div class="c100 p<?php echo $fileSystem->getPercentSavingImageOptimized(); ?> green">
                          <span><?php echo $fileSystem->getPercentSavingImageOptimized(); ?>%</span>
                          <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                          </div>
                      </div>

            </div>
            <div class="col-md-7">

         <p><span class="currentFileSizes"><?php echo $countOptimizedFiles; ?> </span><?php echo __('Ảnh đã tối ưu','louis-image-compressors'); ?></p>
        <p><span class="initFileSizes"><?php
        echo round($fileSystem->getBackupDirectorySize() / (1024 * 1024), 2);
        ?> MB</span> <?php echo __('dung lượng ban đầu','louis-image-compressors'); ?></p>
        <p><span class="currentFileSizes"><?php
        echo round($fileSystem->getOptimizedImageSize() / (1024 * 1024), 2);
        ?> MB</span> <?php echo __('dung lượng hiện tại','louis-image-compressors'); ?></p>
        <p><?php echo sprintf( __( 'Tiết kiệm được %s dung lượng', 'louis-image-compressors' ), '<span class="currentFileSizes">'.$fileSystem->getPercentSavingImageOptimized() .'%</span>' ); ?></p>
            </div>
        </div>
       </div>
  </div>
<div id="page-wrap">

<div class="form-group" style="margin: 30px;">
    <div class="meter animate">
        <span class="progress-bar-percent" style="width: <?php echo $percentCompleted; ?>%;"><span></span></span>
    </div>
</div>

<div id="progress-number">
<span class="optimized-number"><?php echo $countOptimizedFiles; ?></span> /
<span class="optimize-total"><?php echo $countOptimizeFiles; ?></span> (<span class="percent-current"><?php echo $percentCompleted; ?>%</span>)
</div>

<button id="optimize-button" class="btn btn-sky text-uppercase btn-lg" onClick="handle_compress_image(0)"><?php echo __('Tối ưu hình ảnh trong thư viện','louis-image-compressors'); ?></button>

</div>
</section>
