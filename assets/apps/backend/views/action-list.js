define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/check/check-in-1.html',
    'text!templates/check/testing.html',
    'text!templates/check/testing_report.html',
    'text!templates/check/check-in-2.html',
    'text!templates/check/testing_report_checkin.html'
    ], function($, _, Backbone,checkinTemplate,testingTemplate,testingReportTemplate,checkin2Template,testingReportCheckinTemplate){
        var actionListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                'click .action-view-table' : 'doViewTable',
                'click .action-preparation-all' :'doActionPreparationAll',
                'click .action-calculation-all' :'doActionCalculationAll',
                'click .action-adjustment-all':'doActionAdjustmentAll',
                'click .action-auto-testing':'doActionTesting'
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
            doActionCalculationAll:function(event){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"caculation",
                    data:'',
                    success: function(data){
                        openModal('Calculation all','Done');
                    }
                });
            },
            doActionPreparationAll:function(event){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"preparation",
                    data:'',
                    success: function(data){
                        openModal('Preparation all','Done');
                    }
                });
            },
            doActionAdjustmentAll:function(){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"caculation/adjustment",
                    data:'',
                    success: function(data){
                        openModal('Adjustment all','Done');
                    }
                });
            },
            doActionTesting:function(event){
                var $this = $(event.currentTarget);
                var $step=$($this).attr('step');

                var $contents={};
                var compiledTemplate = _.template( testingTemplate, $contents);
                var $widthModal=$('body').width()-100;
                openModal('Auto testing',compiledTemplate);
                $('#action-auto-testing-run').click(function(){
                    var $from =$('#auto-testing-date-from').val();
                    var $to =$('#auto-testing-date-to').val();
                    var $check=1;
                    if($from=='' || parseInt($from) <= 0){
                        $('#auto-testing-date-from').addClass('error');
                        $check=0;
                    }
                    if($to==''|| parseInt($to) < 1){
                        $('#auto-testing-date-to').addClass('error');
                        $check=0;
                    }
                    if($from!='' && $to!=='' && parseInt($to)>=parseInt($from) && $check==1){
                        $('.modal-table-view').html('<img class="image-loading-testing" style="margin-left:180px" src="'+$template_url+'images/loading.gif"/>');
                        if($('.image-loading-testing').length != 0){
                            var $report={};
                            for(var $i=parseInt($from);$i<=parseInt($to);$i++){
                                if($step=='calculation'){
                                    $.ajax({
                                        type:'POST',
                                        url: $admin_url+"testing",
                                        async: false,
                                        data:{
                                            'folder':$i
                                        },
                                        success:function($result){
                                            var $data=JSON.parse($result);
                                            if($data.result=='ok'){
                                                $.ajax({
                                                    type: "POST",
                                                    async: false,
                                                    url: $admin_url+"caculation/all",
                                                    data:'action=all',
                                                    success: function(data){
                                                        $.ajax({
                                                            type: "POST",
                                                            async: false,
                                                            url: $admin_url+"testing/report",
                                                            data:'folder='+$i,
                                                            success: function($resultReport){
                                                                var report=JSON.parse($resultReport);
                                                                $report[$data.folder]=report;
                                                            }
                                                        });
                                                    }
                                                });
                                            }else{
                                                $report[$data.folder]={
                                                    'warning':'folder '+$data.folder+' not found'
                                                };
                                            }
                                        }
                                    });
                                }
                                if($step=='checkin'){
                                    $.ajax({
                                        type:'POST',
                                        url: $admin_url+"testing/checkin",
                                        async: false,
                                        data:{
                                            'folder':$i
                                        },
                                        success:function($result){
                                            var $data=JSON.parse($result);
                                            if($data.result=='ok'){
                                                var checkin1,checkin2;
                                                $.ajax({
                                                    type: "POST",
                                                    async: false,
                                                    url: $admin_url+"action/check",
                                                    data:'action=checkin-1',
                                                    success: function(data){
                                                        checkin1=JSON.parse(data);

                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    async: false,
                                                    url: $admin_url+"action/check",
                                                    data:'action=checkin-2',
                                                    success: function(data){
                                                        checkin2=JSON.parse(data);

                                                    }
                                                });
                                                $report[$i]={
                                                    'checkin-1':checkin1,
                                                    'checkin-2':checkin2
                                                };
                                                checkin1={},checkin2={};
                                            }else{
                                                $report[$data.folder]={
                                                    'warning':'folder '+$data.folder+' not found'
                                                };
                                            }
                                        }
                                    });
                                }
                            }
                            $contents.report=$report;
                            $('#modal').remove();
                            if($step!='checkin'){
                                var compiledTestingReportTemplate=_.template(testingReportTemplate, $contents );
                                openModal('Auto calculation testing',compiledTestingReportTemplate,$widthModal-10);
                            }
                            if($step=='checkin'){
                                var compiledTestingReportTemplate=_.template(testingReportCheckinTemplate, $contents );
                                openModal('Auto checkin testing',compiledTestingReportTemplate,$widthModal-10);
                            }
                            sorttable();
                            $contents={};
                        }
                    }
                });
            },
            index: function(){
                $('.action-calculation-step').click(function(){
                    var $step=$(this).attr('step');
                    var $types=$(this).attr('types');
                    var $content=$(this).parent().parent().find('.check-contant-result');
                    $content.fadeOut('slow').html('');
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"action/check",
                        data:'action='+$step,
                        success: function(data){
                            data=JSON.parse(data);
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
                                if($step!='checkin-2'){
                                    var compiledTemplate = _.template( checkinTemplate, $contents );
                                    openModal('Check dates in tables',compiledTemplate,$widthModal);
                                }else{
                                    var compiledTemplate = _.template( checkin2Template, $contents );
                                    openModal('CHECK AVAILABILITY OF IDX_CODE, STK_CODE, CURRENCIES AND VALUE',compiledTemplate,$widthModal);
                                }
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
        return actionListView = new actionListView;
    });
