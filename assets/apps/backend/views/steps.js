define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var stepsView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            // openModal('Update Indexes', $html, 400);
            },
            events: {
                "click #save": "excute",

            },

            excute: function(e){
                $this = $(e.currentTarget);
                // var start = $("#startdate").val().split('/');
                // var end = $("#enddate").val().split('/');
                // start = Date.UTC(start[2], start[0], start[1]);
                // end = Date.UTC(end[2], end[0], end[1]);

                $("#file-daily").hide();
                $(".disable-form").show();
                $(".disable-form").append("<img src='" + $base_url + "assets/templates/backend/images/mask-loader.gif' style='position: absolute; top: 50%; left: 50%;' />");
                //$(".progress-my").progressbar({value: 50});
                var start = $("#startdate").val();
                var end = $("#enddate").val();
                //while(Date.UTC($start) <= Date.UTC($end)){
                $.ajax({
                    url: $admin_url + 'steps/process_shares_daily',
                    type: 'post',
                    data: 'start=' + start + '&end=' + end,
                    async: false,
                    success: function(rs){
                        alert("Finish!");
                        window.location.href = $admin_url;
                    }
                });
            //}
            },
            index: function(){
                $(document).ready(function()
                {
                    console.log('index');
                });
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
                            window.location.href = $admin_url;
                        }
                    }
                });
            },
            shares_daily: function(){
                $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "minDate", selected);
                            $("#enddate").val($("#startdate").val());
                        }
                    });
                    $("#enddate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "maxDate", selected);
                        }
                    });


                });
            },
            update_indexes: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 1', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_indexes',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });


            },
            update_calendar: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 2', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_calendar',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            update_prices_all: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 3', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_check_setting',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                var value = datatemplate.report.value;
                                if(value != 0){
                                    $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>Do you want empty table?</div>"
                                        +"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
                                    $('#accept').click(function(){
                                        //$('#lean_overlay').show('0',function(){
                                        $("#modal").remove();
                                        stepsView.openModal('Step 3', $html, 400);
                                        $("#modal").show(0, function(){
                                            $.ajax({
                                                url: $admin_url + 'steps/process_prices_all',
                                                type: 'post',
                                                async: false,
                                                success: function(data){
                                                    var datatemplate={};
                                                    datatemplate.report=JSON.parse(data);
                                                    var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                    $('.caculation-report').html(compiledTemplate).fadeIn();
                                                }
                                            });
                                        });
                                    })
                                    $('#de-accept').click(function(){
                                        window.location.href = $admin_url;
                                    })
                                }else{
                                    $(document).ready(function(){
                                        //$('#lean_overlay').show('0',function(){
                                        stepsView.openModal('Step 3', $html, 400);
                                        $("#modal").show(0, function(){
                                            $.ajax({
                                                url: $admin_url + 'steps/process_prices_all',
                                                type: 'post',
                                                async: false,
                                                success: function(data){
                                                    var datatemplate={};
                                                    datatemplate.report=JSON.parse(data);
                                                    var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                    $('.caculation-report').html(compiledTemplate).fadeIn();
                                                }
                                            });
                                        });
                                    });
                                }
                            }
                        });
                    });

                });

            },

            import_prices: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 3 - Import Prices', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_import_prices',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },

            import_prices_missing: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 3 - Import Missing', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_import_prices_missing',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },

            update_shares_import_all: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Import', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_shares_import_all',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_shares_import_shares: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Import', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_shares_import_shares',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_shares_import_references: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Import', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_shares_import_references',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_shares_update_all: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Update', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_update_shares_update_all',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_shares_update_shli: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Update', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_update_shares_update_shli',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_shares_update_shou: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Update', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_update_shares_update_shou',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_shares_clean: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5 Clean', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_shares_clean',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });
            },
            update_missing_shares: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_missing_shares',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            update_adjusted_close: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 3', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_adjusted_close',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
			
			ownership_all: function(){
                var downloadView;
                require(['views/download'], function(obj){
                    downloadView = obj;                    
                });
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 6', $html, 400);
                    $("#modal").show(0, function(){
                        $dir = '\\\\LOCAL\\IFRCDATA\\DOWNLOADS\\VNDB\\METASTOCK\\OWNERSHIP\\SOURCES\\';
                        list = ['STBALLTW', 'STPOWNERTW', 'CAFOWNERTW', 'CPHALLTW'];
                        downloadView.download_list(list, '', '', $dir);

                        $.ajax({
                            url: $admin_url + 'steps/process_ownership_all',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
			
            import_ownership: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 6', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_import_ownership',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },

            update_free_float: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 6', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_free_float',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            update_anomalies_share: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 5', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_anomalies_share',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            update_dividend_all: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 4.1', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_update_dividend_all',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            update_dividend_import: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 4.2', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_update_dividend_import',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            update_dividend_clean: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    stepsView.openModal('Step 4.3', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'steps/process_update_dividend_clean',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });

                });

            },
            download_dividend: function(){
                $(document).ready(function(){
                    $("#lean_overlay").show(100, function(){
                        stepsView.download_dividend_process();
                        $("#lean_overlay").hide();
                    });
                })
            },
            download_dividend_process: function(){
                var codes = ['CPHDIVCAST', 'FPTDIVHW'];
                $.each(codes, function(k, code){
                    $.ajax({
                        url: $admin_url + 'download_temp/getInfo',
                        type: 'post',
                        data: 'code=' + code,
                        async: false,
                        success: function(rs){
                            if(rs == 0){
                                alert('No suitable data in database');
                            }else{
                                options = JSON.parse(rs);
                            }
                            console.log(options);
                        }
                    });
                    $(options).each(function(i){
                        if(options[i].multipages == 0){
                            options[i].multipages = 1;
                        }
                        //abd
                        for(var j = 1; j <= options[i].multipages; j++){
                            $.ajax({
                                url: $admin_url + 'download/download_links',
                                type: 'post',
                                data: {
                                    options: options[i],
                                    page: j,
                                    start: '',
                                    end: '',
                                    ticker: (typeof ticker == 'undefined') ? '' : ticker
                                    },
                                async: false,
                                success: function(rs){
                                    if(rs != ''){
                                        rs = JSON.parse(rs);
                                        if(typeof rs.ticker == 'object'){
                                            ticker = rs.ticker;
                                        }
                                    }

                                }
                            });
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
        return stepsView = new stepsView;
    });
