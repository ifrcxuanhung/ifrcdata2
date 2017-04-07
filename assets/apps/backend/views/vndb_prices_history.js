define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone, reportTemplate){
        var $html='<div class="action-vndb-prices-history-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var vndb_prices_historyView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                'click .action-vndb-prices-history' : 'doPricesHistory',
                'click .action-qidx-mdata' : 'doQidxmdata',
                'click .action-export-qidx-mdata' : 'doExportQidxmdata',
                'click .action-insert-meta-prices' : 'doInsertMetaPrices',
                'click .action-update-references' : 'doUpdateReferences'
            },
            doPricesHistory:function(event){
                openModal('VNDB Prices History',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"vndb_prices_history",
                    data:'',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.action-vndb-prices-history-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            doQidxmdata:function(event){
                openModal('Qidx_mdata',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"vndb_prices_history/qidx_mdata",
                    data:'',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.action-vndb-prices-history-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            doExportQidxmdata:function(event){
                var path = $base_url + 'assets/download/views';
                var file = 'QIDX_MDATA.txt';
                openModal('Export qidx_mdata.txt',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"vndb_prices_history/export_qidx_mdata_txt",
                    data:'',
                    success: function(){
                        $("#modal").hide();
                        window.location.href = $admin_url + 'sysformat/download_file?file=' + file + '&path=' + path + '/' + file;
                    }
                });
            },
            doInsertMetaPrices:function(event){
                openModal('Insert Meta Prices',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"vndb_prices_history/insert_meta_prices",
                    data:'',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.action-vndb-prices-history-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            doUpdateReferences:function(event){
                openModal('Update References',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"vndb_prices_history/update_references",
                    data:'',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.action-vndb-prices-history-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            index: function(){
                $(document).ready(function()
                {
                    console.log('index');
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return new vndb_prices_historyView;
    });
