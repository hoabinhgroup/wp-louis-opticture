(function( $ ) {
	'use strict';
    // console.log = function() {};

     $("#lic-setting-form").louisForm({
         action: "lic_setting_submit",
         onSubmit: function(data){
             $("#lic-setting-button").html("<img height='16' src='"+louis_opticture.pluginUrl+"admin/img/loader2.gif' />  Đang lưu...");
             $("#lic-setting-button").attr("disabled", true);

         },
         onSuccess: function(result){
             console.log('onSuccess',result);
             alertSuccess(result.message);
             //$("#louis-booking-form")[0].reset();

             $("#lic-setting-button").attr("disabled", false);
             $("#lic-setting-button").html("Lưu cài đặt");
         },
         onError: function(response) {
             console.log(response);
         },
     });


     $("#register-form").louisForm({
           action: "register_submit",
           swal: true,
           onSubmit: function(data){
               $("#register-button").html("<img height='16' src='"+louis_opticture.pluginUrl+"admin/img/loader2.gif' />  Đang đăng ký...");
               $("#register-button").attr("disabled", true);

           },
           onSuccess: function(result){
               console.log('onSuccess register',result);

              alertSuccess(result.message);

                 $("#register-form")[0].reset();
                 $("#register-button").attr("disabled", false);
                 $("#register-button").html("Đăng ký tài khoản");

           },
           onError: function(response) {
               var error = response;
               console.log('onError',error);
               alertError(error.message);
               $("#register-button").attr("disabled", false);
               $("#register-button").html("Đăng ký tài khoản");

           },
       });


     $("#login-form").louisForm({
          action: "login_submit",
          swal: true,
          onSubmit: function(data){
              $("#login-button").html("<img height='16' src='"+louis_opticture.pluginUrl+"admin/img/loader2.gif' />  Đang đăng nhập...");
              $("#login-button").attr("disabled", true);

          },
          onSuccess: function(result){
              console.log('onSuccess',result);
              $("#authentication-box").hide();
              $("#authentication-logged").html('Tài khoản <strong>' + result.data.user.email + '</strong> đã đăng nhập thành công và sẵn sàng tối ưu hóa ảnh');
              alertSuccess(result.message);
          },
          onError: function(response) {
              var error = response;
              console.log('onError',error);
              alertError(error.message);
          },
      });


              jQuery('#resize_large_image').on('change', function(){
                      if(jQuery(this).is(':checked')){
                          jQuery(".max_size").removeAttr('disabled');
                      }else{
                          jQuery(".max_size").attr('disabled', true);
                      }
              });

              jQuery('#apply_thumbnail').on('change', function(){
                      if(jQuery(this).is(':checked')){
                          jQuery("#section_thumnails_size").show();
                      }else{
                          jQuery("#section_thumnails_size").hide();
                      }
              });


})( jQuery );

    function alertError(message)
    {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi...' ,
            html: message,
            showConfirmButton: false,
            footer: '<a target="_blank" href="https://louiscms.com">Gửi yêu cầu hỗ trợ đến tác giả</a>'
          });
    }

    function alertSuccess(message)
    {
        Swal.fire({
           position: 'center-center',
           icon: 'success',
           title: message,
           showConfirmButton: false,
           timer: 1500
         });
    }

    function updatePercent(percent, speed = 1200){
          jQuery(".meter > span").data("origWidth", jQuery(".meter > span").width())
          .animate({
        width: percent
          }, speed);

          jQuery(".percent-current").text(percent);
      }

      function handle_compress_image(loop) {
    jQuery.ajax( {
      method: 'POST',
      url: louis_opticture.ajaxUrl,
      data: {
        'action' : 'compress_image_item',
        'data' : {
            loop: loop
        }
      },
      beforeSend : function(){
          if(loop == 0){
           jQuery("#optimize-button").html("<img height='16' src='"+louis_opticture.pluginUrl+"admin/img/loader2.gif' />  Đang tối ưu...");
           jQuery("#optimize-button").attr("disabled", true);
           }
        },
      success: function( response ) {
        console.log('handle_compress_image', response );
        console.log("response['status']", response['status'] );
        var optimized_number = jQuery(".optimized-number").text();

        if(response['status'] == louis_opticture.STATUS_SUCCESS){
          handle_compress_image(response['data']['loop']);
          if(response['data']['loop'] && response['data']['total']){
          jQuery(".optimized-number").text(response['data']['itemsOptimized']);

         // updatePercent(percentComplete * 100 + "%");
          updatePercent(response['data']['percentCompleted']);

            }
        }else if(response['status'] == louis_opticture.STATUS_NOT_API){

          console.log('error', response['message'] );
          alertError(response['message']);

         }else if(response['status'] == louis_opticture.STATUS_SKIP){

          console.log('error', response['message'] );

          handle_compress_image(response['data']['loop']);
         }else if(response['status'] == louis_opticture.STATUS_ERROR){

          console.log('error', response['message'] );
         alertError(response['message']);


          handle_compress_image(response['data']['loop']);
          // }else{
          //     jQuery("#optimize-button").attr("disabled", false);
          //     jQuery("#optimize-button").html("Tối ưu hóa");
          // }


        }else if(response['status'] == 'doneAll'){

          jQuery(".optimized-number").text(response['itemsOptimized']);
          console.log('done', response['message'] );
          updatePercent("100%");
          jQuery("#optimize-button").attr("disabled", false);
          jQuery("#optimize-button").html("Tối ưu hóa");
          alertSuccess(response['message'])
        }else{
            alertError(response['message']);
        }
      }
    });


  }

