jQuery(document).ready(function(){
   // console.log = function() {};
    LouisOpticture.init();
 });

function delayedInit() {
    if(typeof LouisOpticture !== 'undefined' && LouisOpticture.didInit == false) {

        console.error('LouisOpticture: Delayed Init. Check your installation for errors');
        LouisOpticture.init();
    } else {
        setTimeout(delayedInit, 10000);
    }
}
setTimeout(delayedInit, 10000);

var LouisOpticture = function() {

    function init() {
        checkBulkProcessingCallApi();

    }

    function openImageMenu(e) {
            e.preventDefault();
            if(!this.menuCloseEvent) {
                jQuery(window).click(function(e){
                    if (!e.target.matches('.lo-dropbtn')) {
                        jQuery('.lo-dropdown.lo-show').removeClass('lo-show');
                    }
                });
                this.menuCloseEvent = true;
            }
            var shown = e.target.parentElement.classList.contains("lo-show");
            jQuery('.lo-dropdown.lo-show').removeClass('lo-show');
            if(!shown) e.target.parentElement.classList.add("lo-show");
    }


    function checkBulkProcessingCallApi()
    {
        var data = { action  : 'louis_check_bulk_process'};
        jQuery.get(louis_opticture.ajaxUrl, data, function(response) {
            console.log('response', response['data'].optimize);
            if(response["status"] == louis_opticture.STATUS_SUCCESS) {
                if(response['data'].optimize && response['data'].optimize.length > 0){
                 jQuery.each(response['data'].optimize, function(index, value){
                     setTimeout(function(){
                         console.log('value', value);
                         optimize(value, 0);
                     }, 3000 * index);
                 });
                 }

                 if(response['data'].restore && response['data'].restore.length > 0){
                  jQuery.each(response['data'].restore, function(index, value){
                      setTimeout(function(){
                          console.log('value', value);
                          restoreItem(value, 0);
                      }, 3000 * index);
                  });

                  }
            }
        });
    }

    function optimize(id, loop = 0){
        console.log('event', event);
        if(event != undefined){
        event.preventDefault();
        }
        setMessage(id, "<img src='" + louis_opticture.pluginUrl+"admin/img/loader.gif' alt='loading' class='lo-loading-small'>Ảnh đang được tối ưu", "");
        var data = { action  : 'louis_opticture_optimize',
                     optimize_id : id,
                     loop: loop};
        jQuery.get(louis_opticture.ajaxUrl, data, function(response) {
            console.log('loop',loop);
            console.log('response', response);
            console.log('response["status"]', response["status"]);
            console.log('response["message"]', response["message"]);
            if(response["status"] == louis_opticture.STATUS_SUCCESS) {
                 optimize(id, response['data']['loop']);
                 $msg = response['message'];
                 setMessage(id, "<img src='" + louis_opticture.pluginUrl+"admin/img/loader.gif' alt='loading' class='lo-loading-small'>" + $msg, "");
            } else {
                $msg = typeof response["message"] !== "undefined" ? response["message"] : 'Hình ảnh không thể xử lý';
                setMessage(id, $msg, "");
            }
        });
    }

    function restoreItem(id, loop = 0){
        if(event != undefined){
        event.preventDefault();
        }
        setMessage(id, "<img src='" + louis_opticture.pluginUrl+"admin/img/loader.gif' alt='loading' class='lo-loading-small'>Đang khôi phục hình ảnh về ban đầu", "");
        var data = { action  : 'louis_opticture_restore',
         optimize_id : id, loop: loop};
         jQuery.get(louis_opticture.ajaxUrl, data, function(response) {
             console.log('response', response);
             console.log("response['data']['loop']", response['data']['loop']);
             if(response["status"] == louis_opticture.STATUS_SUCCESS) {
                 restoreItem(id, response['data']['loop']);
                 $msg = response['message'];
                 setMessage(id, "<img src='" + louis_opticture.pluginUrl+"admin/img/loader.gif' alt='loading' class='lo-loading-small'>" + $msg, "");
                // setBulkTimer(2000);

             } else {
                 $msg = typeof response["message"] !== "undefined" ? response["message"] : 'Không thể khôi phục';
                 setMessage(id, $msg, "");
                // showStatusAlert(louis_opticture.STATUS_FAIL, $msg);
             }
         });
    }

    function reOptimizeItem(id, type){
        restoreItem(id);
        optimizeItem(id, type);
    }

    function compareItem(id)
    {
        this.comparerData.origUrl == false;
        event.preventDefault();
        if(this.comparerData.cssLoaded === false) {
        jQuery('<link>')
        .appendTo('head')
        .attr({
            type: 'text/css',
            rel: 'stylesheet',
            href: louis_opticture.pluginUrl + 'admin/css/twentytwenty.css'
        });
        this.comparerData.cssLoaded = 2;
        }
        if(this.comparerData.jsLoaded === false) {
        jQuery.getScript(louis_opticture.pluginUrl + 'admin/js/jquery.twentytwenty.js', function(){
            jQuery(".twentytwenty-container").twentytwenty({
                 default_offset_pct: 0.7,
                 orientation: 'horizontal',
                 before_label: 'Original',
                 after_label: 'Louis Opticture',
                 move_slider_on_hover: true,
                 move_with_handle_only: true,
                 click_to_move: false
               });
        });

            this.comparerData.jsLoaded = 1;
        }

        var closePopup = document.getElementById("popupclose");
        var overlay = document.getElementById("overlay");
        var popup = document.getElementById("popup");
        // Close Popup Event
        closePopup.onclick = function() {
          closeDialog();
        };
        overlay.style.display = 'block';
         popup.style.display = 'block';


        if(this.comparerData.origUrl === false) {
            console.log('openmodal', 1);
            jQuery.ajax({
                type: "POST",
                url: louis_opticture.ajaxUrl,
                data: { action : 'louis_comparer_data', id : id },
                success: function(response) {
                   // response = JSON.parse(response);
                    var data = response.data.data;
                    console.log('louis_comparer_data', response.data);
                    console.log('originalImage', response.data['originalImage']);
                    console.log('optImage', response.data['optImage']);
                   // console.log('data', data);
                    // jQuery("#container1 img").first().prop('src', response.originalImage);
                    // jQuery("#container1:nth-child(2) img").prop('src', response.optImage);
                    // jQuery("#compareOptimize").html('<img alt="Trước khi tối ưu" class="louisCompareOriginal" /><img alt="Sau khi tối ưu" class="louisCompareOptimized" />');
                    document.querySelector('.twentytwenty-before-label').setAttribute('data-content', `Original: ${Math.round(response.data['sizeOptImage']/ 1000)}kb`);
                    document.querySelector('.twentytwenty-after-label').setAttribute('data-content', `Optimized: ${Math.round(response.data['sizeOriginalImage']/ 1000)}kb`);
                    jQuery(".louisCompareOriginal").attr('src', response.data['optImage']);
                    setTimeout(function(){
                        jQuery(window).trigger('resize');
                    }, 1000);
                    jQuery(".louisCompareOptimized").load(function(){
                        jQuery(window).trigger('resize');
                    });
                    jQuery(".louisCompareOptimized").attr('src', response.data['originalImage']);


                    // Close Popup Event
                    closePopup.onclick = function() {
                      closeDialog();
                    };
                  //  data = JSON.parse(response);


                }
            });
            this.comparerData.origUrl = false;
        }

    }

    function closeDialog()
    {
        var closePopup = document.getElementById("popupclose");
        var overlay = document.getElementById("overlay");
        var popup = document.getElementById("popup");
        jQuery(".louisCompareOriginal").attr('src', '');
        jQuery(".louisCompareOptimized").attr('src', '');
        overlay.style.display = 'none';
        popup.style.display = 'none';
    }

    function optimizeItem(id, type, index = 0){
        event.preventDefault();
        setMessage(id, "<img src='" + louis_opticture.pluginUrl+"admin/img/loader.gif' alt='loading' class='lo-loading-small'>Đang tối ưu ảnh...", "");
        var data = { action  : 'louis_opticture_optimize',
         optimize_id : id,
         type: type,
         loop: index};
         jQuery.get(louis_opticture.ajaxUrl, data, function(response) {
             console.log('response', response);
             console.log("index", response['data']['loop']);
             if(response["status"] == louis_opticture.STATUS_SUCCESS) {
                 reOptimizeItem(id, type, response['data']['loop']);
                 $msg = response['message'];
                 setMessage(id, "<img src='" + louis_opticture.pluginUrl+"admin/img/loader.gif' alt='loading' class='lo-loading-small'>" + $msg, "");

             } else {
                 $msg = typeof response["message"] !== "undefined" ? response["message"] : 'Không thể tối ưu ảnh';
                 setMessage(id, $msg, "");
                // showStatusAlert(louis_opticture.STATUS_FAIL, $msg);
             }
         });
    }

    function setMessage(id, message, actions){
        var msg = jQuery("#lo-msg-" + id);
        if(msg.length > 0) {
            msg.html("<div class='lo-column-actions'>" + actions + "</div>"
                     + "<div class='lo-column-info'>" + message + "</div>");
            msg.css("color", "");
        }
        msg = jQuery("#lo-cust-msg-" + id);
        if(msg.length > 0) {
            msg.html("<div class='lo-column-info'>" + message + "</div>");
        }
    }

    var bulkTimer;
    function setBulkTimer(time)
    {
       window.clearTimeout(bulkTimer);

       if (time > 0)
       {
        bulkTimer = window.setTimeout(checkBulkProgress, time);
      }
    }


    function showStatusAlert($status, $message, id) {
        var robo = jQuery("li.louis-opticture-toolbar-processing");

        switch($status) {
            case louis_opticture.STATUS_SUCCESS:
        }
        robo.removeClass("louis-opticture-hide");
    }


 return {
            init                : init,
            openImageMenu       : openImageMenu,
            optimize            : optimize,
            optimizeItem        : optimizeItem,
            compareItem        : compareItem,
            reOptimizeItem      : reOptimizeItem,
            setMessage          : setMessage,
            restoreItem         : restoreItem,
            closeDialog         : closeDialog,
            comparerData        : {
                cssLoaded   : false,
                jsLoaded    : false,
                origUrl     : false,
                optUrl      : false,
                width       : 0,
                height      : 0
            },
            // Optin for Helpscout cs
        }
}(); // End
