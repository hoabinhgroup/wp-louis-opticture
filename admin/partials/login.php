<?php echo form_open(admin_url( 'admin-ajax.php' ), [
    'id' => 'login-form'
]); ?>
<p><?php echo __('Nếu bạn đã có tài khoản xin đăng nhập để tiến hành tối ưu ảnh','louis-image-compressors'); ?></p>
<div class="form-group">
    <?php echo form_input('email', '', [
        'class' => 'form-control',
        'placeholder' =>  __('Nhập email bạn','louis-image-compressors'),
        'data-rule-required' => true,
        'data-msg-required' => __('Xin nhập email','louis-image-compressors'),
        'data-rule-email' => true,
        'data-msg-email' => __('Định dạng email chưa đúng','louis-image-compressors')
    ]); ?>
</div>
<div class="form-group">
    <?php echo form_password('password', '', [
        'class' => 'form-control',
        'placeholder' => __('Nhập mật khẩu','louis-image-compressors'),
        'data-rule-required' => true,
        'data-msg-required' => __('Xin nhập mật khẩu','louis-image-compressors')
    ]); ?>
</div>
<?php echo form_submit('', 'Đăng nhập', ['class' => 'btn btn-sky text-uppercase btn-lg', 'id' => 'login-button']); ?>
<?php echo form_close(); ?>
