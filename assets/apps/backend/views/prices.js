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
                "click #save": "excute"

            },

            excute: function(e){
                $this = $(e.currentTarget);
				$("#file-daily").hide();
				stepsView.openModal('Prices Switch', $html, 400);
                var start = $("#startdate").val();
				$.ajax({
					url: $admin_url + 'prices/process_check_date',
					data: 'date='+start,
					type: 'post',
					async: false,
					success: function(data){
						var datatemplate={};
						datatemplate.report=JSON.parse(data);
						var compiledTemplate = _.template( reportTemplate, datatemplate );
						var date = datatemplate.report.date;
						var key = datatemplate.report.key;
						if(key != ''){
							if(key == 'Yes'){
								$('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
								+"<ul>"
								+"<li>Data of day "+date+"</li>"
								+"<li>Day: "+datatemplate.report.day+"</li>"
								+"<li>Daily: "+datatemplate.report.daily+"</li>"
								+"<li>History: "+datatemplate.report.history+"</li>"
								+"<li>Do you want continuous ?</li>"
								+"</ul>"
								+"</div>"
								+"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
								$('#accept').click(function(){
									 //$('#lean_overlay').show('0',function(){
									$("#modal").remove();
									stepsView.openModal('Prices Switch', $html, 400);
									$("#modal").show(0, function(){
										$.ajax({
											url: $admin_url + 'prices/process_prices_switch',
											data: 'date=' + date + '&key=' + key,
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
									stepsView.openModal('Prices Switch', $html, 400);
									$("#modal").show(0, function(){
										$.ajax({
											url: $admin_url + 'prices/process_prices_switch',
											type: 'post',
											data: 'date=' + date + '&key=' + key,
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
						}else{
							$('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
							+"<ul>"
							+"<li>Data of day "+date+"</li>"
							+"<li>Day: "+datatemplate.report.day+"</li>"
							+"</ul>"
							+"</div>").fadeIn();
						}
					}
				});
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
			prices_switch: function(){
				 $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "maxDate", selected);
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
