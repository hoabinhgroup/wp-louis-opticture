<?php echo form_open(admin_url( 'admin-ajax.php' ), [
    'id' => 'register-form'
]); ?>
<p><?php echo __('Đăng ký tài khoản','louis-image-compressors'); ?></p>
<div class="form-group">
    <?php echo form_input('name', '',
        [
            'class' => 'form-control',
            'placeholder' => __('Nhập tên bạn','louis-image-compressors'),
            'data-rule-required' => true,
            'data-msg-required' => __('Xin nhập tên bạn','louis-image-compressors'),
        ]); ?>
</div>
<div class="form-group">
    <?php echo form_input('email', '',
        [
            'class' => 'form-control',
            'placeholder' => __('Nhập email bạn','louis-image-compressors'),
            'data-rule-required' => true,
            'data-msg-required' => __('Xin nhập Email','louis-image-compressors'),
            'data-rule-email' => true,
            'data-msg-email' => __('Định dạng email chưa đúng','louis-image-compressors')
        ]); ?>
</div>
<div class="form-group">
    <?php echo form_password('password', '', [
            'id' => 'register_password',
            'class' => 'form-control',
            'placeholder' => __('Nhập mật khẩu','louis-image-compressors'),
            'data-rule-required' => true,
            'data-msg-required' => __('Xin nhập mật khẩu','louis-image-compressors')
    ]); ?>
</div>
<div class="form-group">
    <?php echo form_password('password_repeat', '', [
            'class' => 'form-control',
            'placeholder' => __('Nhập lại mật khẩu','louis-image-compressors'),
            'data-rule-required' => true,
            'data-msg-required' => __('Xin nhập lại mật khẩu','louis-image-compressors'),
            'data-rule-equalTo' => '#register_password',
            'data-msg-equalTo' => __('Mật khẩu chưa trùng khớp','louis-image-compressors'),
    ]); ?>
</div>
<?php echo form_submit('', __('Đăng ký tài khoản','louis-image-compressors'), ['class' => 'btn btn-sky text-uppercase btn-lg', 'id' => 'register-button']); ?>
<?php echo form_close(); ?>
