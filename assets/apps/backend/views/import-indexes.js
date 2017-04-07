define([
    'jquery',
    'underscore',
    'backbone',
    'browserPlus',
    'plupload',
    'pluploadQueue'
    ], function($, _, Backbone){
        var importIndexesView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                'click .action-view-table' : 'doViewTable',
                'click .action-import' : 'doImport'
            },
            doViewTable:function(event){
                require(['views/sysformat/list'], function(sysformatView){
                    var $this = $(event.currentTarget);
                    var title = $($this).attr('table');
                    var content;
                    content = '<div class="modal-table-view"><table id="modal-table-view-list" align="center"></table></div>';
                    var $widthModal=$('body').width()-100;
                    openModal(title, content,$widthModal);
                    if($('#modal-table-view-list').length>0){
                        sysformatView.index('#modal-table-view-list',title,($widthModal-10));
                    }
                });
                return false;
            },
            index: function(){
                $(document).ready(function()
                {
                    $('.folder-import').val('cache/upload');
                    loadCss($base_url+'assets/bundles/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css');
                    var uploader= $("#uploader").pluploadQueue({
                        // General settings
                        runtimes : 'html5,gears,silverlight,browserplus,flash',
                        url : $admin_url+'import/upload',
                        max_file_size : '1000mb',
                        unique_names : true,
                        filters : [
                        {
                            title : "txt,csv,zip",
                            extensions : "txt,csv,zip"
                        }
                        ],
                        // Flash settings
                        flash_swf_url : $base_url+'assets/bundles/plupload/js/plupload.flash.swf'
                    }).pluploadQueue();
                    // Client side form validation
                    uploader.bind('UploadProgress', function() {
                        if (uploader.total.uploaded == uploader.files.length) {
                            $(".plupload_buttons").css("display", "inline");
                            $(".plupload_upload_status").css("display", "inline");
                            $(".plupload_start").addClass("plupload_disabled");
                        }
                    });
                    uploader.bind('UploadFile',function(up, file){
                        importIndexesView.doCheckTable(file,'cache/upload');
                    });

                    uploader.bind('UploadComplete',function(){
                        $('body,html').animate({
                            scrollTop: $(window).scrollTop() + $(window).height()
                        }, 800);
                    });
                    uploader.bind('QueueChanged', function() {
                        $(".plupload_start").removeClass("plupload_disabled");
                    });

                });
            },
            all:function(){
                // xy ly cho action all
                $('#uploader').hide();
                $('.action-import').parent().addClass('custom-btn');
                $('.folder-import').val('data_upload_indexes');
                $.ajax({
                    type: "POST",
                    url: $admin_url+"import/listfile",
                    data:'',
                    success: function(msg){
                        msg=JSON.parse(msg);
                        msg.each(function($result){
                            importIndexesView.doCheckTable($result,'data_upload_indexes');
                        });
                    }
                });
            },
            doCheckTable:function($file,$folder){
                /// check xem table co ton tai trong datatbase ko
                $.ajax({
                    type: "POST",
                    url: $admin_url+"import/checktable",
                    data: "file="+$file.name+'&folder='+$folder+'&table='+$file.table,
                    success: function(msg){
                        var $html;
                        msg=JSON.parse(msg);
                        msg.each(function($result){
                            if($result.status==1){
                                $html+="<tr><td>"+$file.name
                                +"</td><td class='file'>"+$result.file
                                +"</td><td><strong class='name-table'>"+$result.name
                                +'</strong></td><td><input name="empty" type="checkbox"';
                                if($file.empty=='1'){
                                    $html+=' checked="checked"';
                                }
                                if($file.empty=='0'){
                                    $html+='';
                                }
                                if($file.empty==undefined){
                                    $html+=' checked="checked"';
                                }
                                $html+='/></td><td><input name="action" type="checkbox" checked="checked"/></td><td class="import-result"></td></tr>';
                            }else{
                                $html+="<tr><td>"+$file.name
                                +"</td><td>"+$result.file
                                +"</td><td class='message error'> Table <strong>"+$result.name
                                +'</strong> does not exist</td><td></td><td></td><td></td></tr>';
                            }
                        });
                        $('.table-action-inport tbody').append($html);
                    }
                });
            },
            doImport : function(){
                //su ly import vao database
                var $rows=$('.table-action-inport tbody tr');
                if($rows.length>0){
                    var $folder=$('.folder-import').val();
                    $rows.each(function(){
                        var $row=this;
                        var $action=$($row).find('input[name=action]').is(':checked');
                        if($action==true){
                            var $emty=$($row).find('input[name=empty]').is(':checked');
                            var $loading='<p class="message loading"> import data</p>';
                            $($row).find('.import-result').html($loading);
                            var $table=$($row).find('.name-table').text();
                            var $file=$($row).find('.file').text();
                            $.ajax({
                                type: "POST",
                                url: $admin_url+"import/insert",
                                data: "table="+$table+'&empty='+$emty+'&file='+$file+'&folder='+$folder,
                                success: function(results){
                                    $($row).find('input[name=action]').remove();
                                    var $results=JSON.parse(results);
                                    var $html='';
                                    $results.each(function($result){
                                        if($result.status==1){
                                            $html+='<p class="message '+($result.error?'error':'success')+'">';
                                            $html+='<strong>'+$result.msg+'</strong><br/>';
                                            if($result.error){
                                                $html+='<strong>'+$result.error+'</strong><br/>';
                                            }
                                            $html+='Num rows of file : <strong>'+$result.numRows+'</strong><br/>'
                                            +'Total rows import : <strong>'+$result.totalImport+'</strong><br/>'
                                            +'Total rows Table : <strong>'+$result.totalTable+'</strong><br/>'
                                            +'View Table : <a class="action-view-table" table="'+$table+'" ><img src="'+$base_url+'assets/templates/backend/images/icons/view-icon.png" title="view" /></a>';
                                            $html+='</p>';
                                        }else{
                                            $html+='<p class="message error">'
                                            +'<strong>'+$result.msg+'</strong>'
                                            +'</p>';
                                        }
                                    });
                                    $($row).find('.import-result').html($html);
                                }
                            });
                        }
                    });
                }else{
                    alert('You must upload file to import');
                }
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return importIndexesView = new importIndexesView;
    });
