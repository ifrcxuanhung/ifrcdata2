define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/backhistory_calculation/report.html'],
    function($, _, Backbone, reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var synchronizationView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            openModal: function openModal($title,$content,$width)
            {
                $.modal({
                    content: $content,
                    title: $title,
                    maxWidth: 2500,
                    width: $width,
                    buttons: {
                        'Close': function(win) {
                            win.closeModal();
                        }
                    }
                });
            },
            events:{

            },
            index: function(){
                $(".action-synchronization").click(function(event){
                    event.preventDefault();
                    var searchIDs = $("#complex_form input:checkbox:checked").map(function(){
                      return $(this).val();
                    }).get();
                    if(searchIDs == ''){
                        synchronizationView.openModal('Synchronization', $html, 400);
                        $("#modal").show(0, function(){
                            $('.caculation-report').html('You must choose check!');
                        });
                    }else{
                        $.ajax({
                            url: $admin_url + 'synchronization/check_table',
                            type: 'post',
                            data: 'data_id='+searchIDs,
                            async: false,
                            success: function(data){
                                var respone = JSON.parse(data);
                                if(respone.error.length > 0){
                                    if(respone.error.length == searchIDs.length){
                                        var html = '<ul>';
                                        $.each( respone.error, function( key, value ) {
                                            html += '<li>'+value+'</li>';
                                        });
                                        html += '</ul>';
                                        $.modal({
                                            content: html,
                                            title: 'Warning',
                                            maxWidth: 2500,
                                            width: 400,
                                            buttons: {
                                                'Exit': function() {
                                                    $("#modal").closeModal();
                                                },
                                            }
                                        });
                                    }else{
                                        var html = '<ul>';
                                        $.each( respone.error, function( key, value ) {
                                            html += '<li>'+value+'</li>';
                                        });
                                        html += '</ul>';
                                        html += '<p>Would you continue with tables does exist?</p>';
                                        $.modal({
                                            content: html,
                                            title: 'Warning',
                                            maxWidth: 2500,
                                            width: 400,
                                            buttons: {
                                                'Continue': function() {
                                                    $("#modal").closeModal();
                                                    $.each( respone.position, function( key_p, value_p ) {
                                                        var removeItem = value_p;
                                                        searchIDs = jQuery.grep(searchIDs, function(value) {
                                                          return value != removeItem;
                                                        });
                                                    });
                                                    if(respone.host.length > 0){
                                                        synchronizationView.doTypePassword(respone.host,searchIDs);
                                                    }else{
                                                        synchronizationView.doAjax(searchIDs);
                                                    }
                                                },
                                            }
                                        });
                                        $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class','red');
                                    }
                                }else{
                                    // if(respone.host.length > 0){
                                    //     synchronizationView.doTypePassword(respone.host,searchIDs);
                                    // }else{
                                        synchronizationView.doAjax(searchIDs);
                                    //}
                                }
                            }
                        });
                    }
                });
            },
            // doTypePassword: function($object,$data_id){
            //     $length = $object.length;
            //     $width = 100/$length;
            //     var html = '<div style="width:100%; float:left">';
            //     if($length > 1){
            //         $.each( $object, function( key, value ) {
            //             html += '<div style="width:'+$width+'%; float:left">'+
            //                 '<p style="text-align:center">Host '+value+'</p>'+
            //                 '<p style="text-align:center"><span>Type Password</span><span><input type="password" name="txtpassword" id="txtpassword" style="margin-left:10px; padding:0.583em; background:-moz-linear-gradient(center top , #D4D4D4, #EBEBEB 3px, white 27px) repeat scroll 0 0%, none repeat scroll 0 0 white; border:1px solid #89BAD3; border-radius:0.417em 0.417em 0.417em 0.417em; color:#333333; font-size:1em; line-height:1em" /></span></p>'+
            //                 '<div style="width:'+$width+'%; float:left" id="error"></div>'+
            //             +'</div>';
            //         });
            //     }else{
            //         html += '<div style="width:'+$width+'%; float:left">'+
            //                 '<p style="text-align:center">Host '+$object+'</p>'+
            //                 '<p style="text-align:center"><span>Type Password</span><span><input type="password" name="txtpassword" id="txtpassword" style="margin-left:10px; padding:0.583em; background:-moz-linear-gradient(center top , #D4D4D4, #EBEBEB 3px, white 27px) repeat scroll 0 0%, none repeat scroll 0 0 white; border:1px solid #89BAD3; border-radius:0.417em 0.417em 0.417em 0.417em; color:#333333; font-size:1em; line-height:1em" /></span></p>'+
            //                 '<div style="width:'+$width+'%; float:left" id="error"></div>'+
            //             +'</div>';
            //     }
            //     html += '</div>';
            //     html = html.replace("NaN", "");
            //     $.modal({
            //         content: html,
            //         title: 'Login',
            //         maxWidth: 2500,
            //         width: 400,
            //         buttons: {
            //             'Continue': function() {
            //                 var password = $("#txtpassword").val();
            //                 $.ajax({
            //                     url: $admin_url + 'synchronization/check_password',
            //                     type: 'post',
            //                     data: 'host='+$object+'&password='+password,
            //                     async: false,
            //                     success: function(rs){
            //                         rs = JSON.parse(rs);
            //                         if(rs.check == 1){
            //                             synchronizationView.doAjax($data_id);
            //                         }else{
            //                             $("#error").html('<span style="color:red">Wrong Password, please try again!</span>');
            //                         }
            //                     }
            //                 });
            //             },
            //             'Exit': function() {
            //                 $("#modal").closeModal();
            //             },
            //         }
            //     });
            // },
            doAjax: function($data_id){
                $("#modal").closeModal();
                synchronizationView.openModal('Synchronization', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'synchronization/synchronization_data',
                            type: 'post',
                            data: 'data_id='+$data_id,
                            async: false,
                            success: function(data){
                                var response = jQuery.parseJSON(data);
                                if(typeof response =='object'){
                                    var datatemplate={};
                                    datatemplate.report=JSON.parse(data);
                                    var compiledTemplate = _.template( reportTemplate, datatemplate );
                                    $('.caculation-report').html(compiledTemplate).fadeIn();
                                }else{
                                    $("#modal").closeModal();
                                    synchronizationView.openModal('Synchronization', 'Error, Please contact administrator, thanks you!', 400);
                                }
                            },
                            error: function(jqXHR, error, errorThrown) {
                                $("#modal").closeModal();
                                if(jqXHR.status && jqXHR.status==400){
                                    synchronizationView.openModal('An Error Occurred',jqXHR.responseText,400);
                                }else{
                                    synchronizationView.openModal('An Error Occurred','Please check source again or contact with administrator',400);
                                }
                            }
                        });
                    });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return synchronizationView = new synchronizationView;
    });
