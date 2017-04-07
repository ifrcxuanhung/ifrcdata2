define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/check/check-in-1.html'
    ], function($, _, Backbone, checkinTemplate){
        var actionHistoryView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                'click .action-view-table' : 'doViewTable',
                'click .action-history-calculation-all' :'doActionHistoryCalculationAll',
                'click .action-history-preparation-all' :'doActionHistoryPreparationAll',
                'click .action-history-extractdata-all' :'doActionHistoryExtractdataAll'
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
            doActionHistoryCalculationAll:function(event){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"backhistory_calculation",
                    data:'',
                    success: function(data){
                        openModal('Back History Calculation all','Done');
                    }
                });
            },
            doActionHistoryPreparationAll:function(event){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"backhistory_preparation",
                    data:'',
                    success: function(data){
                        openModal('Back History Preparation all','Done');
                    }
                });
            },
            doActionHistoryExtractdataAll:function(event){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"backhistory_extractdata",
                    data:'',
                    success: function(data){
                        openModal('Back History Extract Data all','Done');
                    }
                });
            },
            index: function(){
                $('.action-history-step').click(function(){
                    var $step=$(this).attr('step');
                    var $types=$(this).attr('types');
                    var $content=$(this).parent().parent().find('.check-contant-result');
                    $content.fadeOut('slow').html('');
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"action_history/check",
                        data:'action='+$step,
                        success: function(data){
                            data = JSON.parse(data);
                            if($types!='check-in'){
                                var $html='<ul class="keywords" style="float:right; margin-left:30px;">';
                                data.each(function($result){
                                    $html+='<li style="margin:0px 3px"><a href="javascript:void(0)" table="'+$result+'" class="action-view-table" style="color:#fff">'+$result+'</a></li>';
                                });
                                $html+='</ul>';
                            }else{
                                var $widthModal=$('body').width()-100;
                                var $contents={};
                                $contents.data=data;
                                var compiledTemplate = _.template( checkinTemplate, $contents );
                                openModal('Check dates in tables',compiledTemplate,$widthModal);
                            }
                            $content.fadeIn('slow').html($html);
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
        return actionHistoryView = new actionHistoryView;
    });
