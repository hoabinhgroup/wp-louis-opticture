<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://louiscms.com
 * @since      1.0.0
 *
 * @package    Louis_Image_Compressors
 * @subpackage Louis_Image_Compressors/admin/partials
 */

?>

<?php include_once('progress.php'); ?>

<?php if(!$authCache->isCached('token')){ ?>
<section id="authentication-box">

<div id="authentication-user" class="row">
  <div class="col-md-3">
      <div class="auth-logo text-center">
      <img width="100" src='<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'img/auth.png'; ?>'>
      <p>Xác thực người dùng</p>
      </div>
  </div>
  <div class="col-md-9">
      <div class="row">
        <div class="col-md-6">
            <div id="authentication-register">
                <?php include_once('register.php'); ?>
            </div>
        </div>
        <div class="col-md-6">
                <?php include_once('login.php'); ?>
        </div>
      </div>
  </div>
</div>

</section>
<?php } ?>

<section class="wrapper-container">
<?php echo form_open(admin_url( 'admin-ajax.php' ), [
    'id' => 'lic-setting-form',
    'class' => 'mt20'
]); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#general"><?php echo __('Cài đặt chung','louis-image-compressors'); ?></a></li>
  <li><a data-toggle="tab" href="#advanced"><?php echo __('Nâng cao','louis-image-compressors'); ?></a></li>
  <!-- <li><a data-toggle="tab" href="#stat">Thống kê</a></li> -->
</ul>

<div class="tab-content">
  <div id="general" class="tab-pane fade in active"><?php include_once('general.php'); ?></div>
  <div id="advanced" class="tab-pane fade"><?php include_once('advanced.php'); ?></div>
  <!-- <div id="stat" class="tab-pane fade">
    <p>Tính năng sẽ sớm được ra mắt...</p>
  </div> -->
</div>

 <div class="row">
    <div class="col-md-3">

    </div>
    <div class="col-md-9">
        <?php echo form_submit('',  __('Lưu cài đặt','louis-image-compressors'), ['class' => 'btn btn-sky text-uppercase btn-lg', 'id' => 'lic-setting-button']); ?>
    </div>
    </div>

 <?php echo form_close(); ?>


</section>


