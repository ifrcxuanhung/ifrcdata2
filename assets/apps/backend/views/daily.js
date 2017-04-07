define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var dailyView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
                // openModal('Update Indexes', $html, 400);				
            },
           events: {
                "click #save": "excute",
				"click #save_1": "excute_1",
				"click #save_2": "excute_2",
            },

            excute: function(e){
                $this = $(e.currentTarget);
				$("#file-daily").hide();
				dailyView.openModal('Daily Switch', $html, 400);
                var start = $("#startdate").val();
				$.ajax({
					url: $admin_url + 'daily/process_check_date',
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
								+"<li>Day: "+datatemplate.report.day+"</li>"
								+"<li>Data of day "+date+"</li>"
								+"<li>Daily: "+datatemplate.report.daily+"</li>"
								+"<li>History: "+datatemplate.report.history+"</li>"
								+"<li>Do you want continuous ?</li>"
								+"</ul>"
								+"</div>"
								+"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();	
								$('#accept').click(function(){
									 //$('#lean_overlay').show('0',function(){
									$("#modal").remove();										
									dailyView.openModal('Daily Switch', $html, 400); 
									$("#modal").show(0, function(){
										$.ajax({
											url: $admin_url + 'daily/process_daily_switch',
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
									dailyView.openModal('Daily Switch', $html, 400);
									$("#modal").show(0, function(){
										$.ajax({
											url: $admin_url + 'daily/process_daily_switch',
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
			daily_all: function(){
                $(document).ready(function(){
					$("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "maxDate", selected);
                        }
                    });
                    $("#enddate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "maxDate", selected);
                        }
                    });
					$("#startdate").datepicker("setDate", '2013-01-02');
					$("#enddate").datepicker("setDate", new Date());
                });
            },
			excute_2: function(e){
                $this = $(e.currentTarget);
				$("#file-daily").hide();
				var start = $("#startdate").val();
				var end = $("#enddate").val();
				var process = $('input:radio[name=process]:checked').val();
				var check = $('input:radio[name=check]:checked').val();
				if(end < start){								
					dailyView.openModal('Report', $html, 400); 
					$("#modal").show(0, function(){
						$('.caculation-report').html("<ul class='blocks-list'><li>"
						+"<a href='#' class='float-left'><img src='"+ $base_url +"assets/templates/backend/images/icons/fugue/status.png' width='16' height='16'>Message</a>"
						+"<div class='columns'>"
						+"<p class='colx2-right' style='color:red'>Error Date, Please run again!</p>"
						+"</div>"
						+"</li></ul>").fadeIn();
					})
				}else{									
					dailyView.openModal('Report', $html, 700); 
					$("#modal").show(0, function(){
						$.ajax({
							url: $admin_url + 'daily/process_daily_all',
							type: 'post',
							data: 'startdate=' + start + '&enddate=' + end + '&process=' + process + '&check=' + check,
							async: false,
							success: function(data){
								var datatemplate={};
								datatemplate.report=JSON.parse(data);
								var compiledTemplate = _.template( reportTemplate, datatemplate );
								var date_gd = datatemplate.report.date_gd;
								html ='<ul class="blocks-list">'
								+'<li><p>Count trading day: '+date_gd+'</p></li>'
								+'<li><table style = "width:100%"><tr style="border-bottom:1px solid #999; font-weight:bold; font-size: 14px"><td></td><td style="border-left:1px solid #999">REF</td><td style="border-left:1px solid #999">DATE REF</td><td style="border-left:1px solid #999">PRI</td><td style="border-left:1px solid #999">DATE PRI</td><td style="border-left:1px solid #999">DAILY</td><td style="border-left:1px solid #999">DATE DAILY</td><td style="border-left:1px solid #999">SHLI</td><td style="border-left:1px solid #999">SHOU</td><td style="border-left:1px solid #999">LAST</td><td style="border-left:1px solid #999">CHECK</td></tr>';
								$.each(datatemplate.report.report, function(i, value){
									var check = '';
									var border = '';
									if(i != 'sum'){
										border = "border-bottom:1px solid #999";
									}
									if((date_gd == value[1] && date_gd == value[3] && date_gd == value[5]) && (value[2] == value[4]) && (value[6] == value[7] && value[7] == value[8]  && value[7] == 0)){
										check = '<p style="font-weight:bold">OK</p>';
									}else{
										check = '<p style="font-weight:bold; color:red">NOT OK</p>';
									}
									html += '<tr style="' + border + '"><td style="border-right:1px solid #999; font-weight:bold; font-size: 14px">' + i.toUpperCase().replace('_', ' ') + '</td>';
									$.each(value, function(k, v){
										var color = 'black';
										if(v != date_gd && (k == 1 || k == 3 || k == 5)){
											color = 'red';
										}
										html += '<td style="border-left:1px solid #999; color:'+color+'" class="date'+k+'">'+v+'</td>';
									});
									html += '<td style="border-left:1px solid #999">'+check+'</td></tr>';
								});
								html += '</table></li>';
								html += '<li style="margin:10px 0px"><button id="accept" style="float:left;margin-right:5%;height:30px;line-height:10px;">Yes</button><button id="de-accept" style="float:left;height:30px;line-height:10px;margin-right:50px">No</button><p style="float:left; line-height:30px; font-size:14px">Do you want continue?</p></li></ul>';
								$('.caculation-report').html(html).fadeIn();
								var tr = $('.caculation-report tr');
								$.each(tr, function(k, v){
									var ref = $(this).children('td.date0');
									var pri = $(this).children('td.date2');
									var daily = $(this).children('td.date4');
									var shli = $(this).children('td.date6');
									var shou = $(this).children('td.date7');
									var last = $(this).children('td.date8');
									if(pri.text() != daily.text()){
										pri.css('color', 'red');
										daily.css('color', 'red');
									}
									if(shli.text() != 0){
										shli.css('color', 'red');
									}
									if(shou.text() != 0){
										shou.css('color', 'red');
									}
									if(last.text() != 0){
										last.css('color', 'red');
									}
								});
								$('#accept').click(function(){
									 //$('#lean_overlay').show('0',function(){
									$("#modal").remove();										
									dailyView.openModal('Export Daily', $html, 400); 
									$("#modal").show(0, function(){
										$.ajax({
											url: $admin_url + 'daily/process_export_daily',
											type: 'post',
											data: 'startdate=' + start + '&enddate=' + end + '&check=' + check,
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
							}
						});
					});	
				}
            },
			daily_switch: function(){
				 $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "minDate", selected);
                        }
                    });
                });
			},
			merges_daily: function(){
				$(document).ready(function(){
                     //$('#lean_overlay').show('0',function(){
					dailyView.openModal('Daily Merges', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'daily/process_check_merges',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
								var check_day = datatemplate.report.message_day;
								var check_line = datatemplate.report.message_line;
								$('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
								+"<ul>"
								+"<li>Check Date: "+check_day
								+"<ul>"
								+"<li> - Date of Reference: "+datatemplate.report.date_ref+"</li>"
								+"<li> - Date of Prices: "+datatemplate.report.date_pri+"</li>"
								+"</ul></li>"
								+"<li>Check Ticker: "+check_line
								+"<ul>"
								+"<li> - Total ticker of Reference: "+datatemplate.report.ticker_ref+"</li>"
								+"<li> - Total ticker of Prices: "+datatemplate.report.ticker_pri+"</li>"
								+"</ul></li>"
								+"<li style='margin:10px 0px'><button id='accept' style='float:left;margin-right:5%;height:30px;line-height:10px;'>Yes</button><button id='de-accept' style='height:30px;line-height:10px;'>No</button></li>"
								+"</ul>"
								+"</div>").fadeIn();
								$('#accept').click(function(){
									 //$('#lean_overlay').show('0',function(){
									$("#modal").remove();										
									dailyView.openModal('Daily Merges', $html, 400); 
									$("#modal").show(0, function(){
										$.ajax({
											url: $admin_url + 'daily/process_merges_daily',
											type: 'post',
											async: false,
											success: function(data){
												var datatemplate={};
												datatemplate.report=JSON.parse(data);
												var compiledTemplate = _.template( reportTemplate, datatemplate );
												if(datatemplate.report[0].report == 0){
													$('.caculation-report').html(compiledTemplate
													+"<span style = 'font-weight:bold'>Data is enough</span>").fadeIn();
												}else{
													$('.caculation-report').html(compiledTemplate
													+"<span style = 'font-weight:bold'>Data is NOT enough, "+datatemplate.report[0].report+" lines</span>").fadeIn();
												}
											}
										});
									});
								})
								$('#de-accept').click(function(){
									window.location.href = $admin_url;
								})
                            }
                        });
                    });
				});
			},
			both_merges_switch: function(){
				 $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "maxDate", selected);
                        }
                    });
                });
			},
			excute_1: function(e){
                $this = $(e.currentTarget);
				
				$("#file-daily").hide();
				dailyView.openModal('Daily Both Merges And Switch', $html, 400);
                var start = $("#startdate").val();
				$("#modal").remove();										
				dailyView.openModal('Switch Prices', $html, 400); 
				$("#modal").show(0, function(){
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
										$.ajax({
											url: $admin_url + 'prices/process_prices_switch',
											data: 'date=' + date + '&key=' + key,
											type: 'post',
											async: false,
											complete: function(){
												$("#modal").remove();										
												dailyView.openModal('Daily Both Merges And Switch', $html, 400); 
												$("#modal").show(0, function(){
													$.ajax({
														url: $admin_url + 'daily/process_check_date',
														data: 'date='+start,
														type: 'post',
														async: false,
														success: function(data){
															var datatemplate={};
															datatemplate.report=JSON.parse(data);
															var compiledTemplate = _.template( reportTemplate, datatemplate );
															var date = datatemplate.report.date;
															var key = datatemplate.report.key;
															if(key == 'Yes'){
																$('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
																+"<ul>"
																+"<li>Data of day "+date+"</li>"
																+"<li>Daily: "+datatemplate.report.daily+"</li>"
																+"<li>History: "+datatemplate.report.history+"</li>"
																+"<li>Do you want continuous ?</li>"
																+"</ul>"
																+"</div>"
																+"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();	
																$('#accept').click(function(){
																	 //$('#lean_overlay').show('0',function(){
																	$("#modal").remove();										
																	dailyView.openModal('Daily Both Merges And Switch', $html, 400); 
																	$("#modal").show(0, function(){
																		$.ajax({
																			url: $admin_url + 'daily/process_both_merges_switch',
																			data: 'date=' + date + '&key=' + key,
																			type: 'post',
																			async: false,
																			success: function(data){
																				var datatemplate={};
																				datatemplate.report=JSON.parse(data);
																				var compiledTemplate = _.template( reportTemplate, datatemplate );
																				if(datatemplate.report[0].report == 0){
																					$('.caculation-report').html(compiledTemplate
																					+"<span style = 'font-weight:bold'>Data is enough</span>").fadeIn();
																				}else{
																					$('.caculation-report').html(compiledTemplate
																					+"<span style = 'font-weight:bold'>Data is NOT enough, "+datatemplate.report[0].report+" lines</span>").fadeIn();
																				}
																			}
																		});
																	});
																})
																$('#de-accept').click(function(){
																	window.location.href = $admin_url;
																})
															}else{
																$.ajax({
																	url: $admin_url + 'daily/process_both_merges_switch',
																	type: 'post',
																	data: 'date=' + date + '&key=' + key,
																	async: false,
																	success: function(data){
																		var datatemplate={};
																		datatemplate.report=JSON.parse(data);
																		var compiledTemplate = _.template( reportTemplate, datatemplate );
																		if(datatemplate.report[0].report == 0){
																			$('.caculation-report').html(compiledTemplate
																			+"<span style = 'font-weight:bold'>Data is enough</span>").fadeIn();
																		}else{
																			$('.caculation-report').html(compiledTemplate
																			+"<span style = 'font-weight:bold'>Data is NOT enough, "+datatemplate.report[0].report+" lines</span>").fadeIn();
																		}
																	}
																});
															}
														}
													});
												})
											}
										});
									 })
									$('#de-accept').click(function(){
										window.location.href = $admin_url;
									})
								}else{
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
					})
				})
				/*$.ajax({
					url: $admin_url + 'daily/process_check_date',
					data: 'date='+start,
					type: 'post',
					async: false,
					success: function(data){
						var datatemplate={};
						datatemplate.report=JSON.parse(data);
						var compiledTemplate = _.template( reportTemplate, datatemplate );
						var date = datatemplate.report.date;
						var key = datatemplate.report.key;
						if(key == 'Yes'){
							$('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
							+"<ul>"
							+"<li>Data of day "+date+"</li>"
							+"<li>Daily: "+datatemplate.report.daily+"</li>"
							+"<li>History: "+datatemplate.report.history+"</li>"
							+"<li>Do you want continuous ?</li>"
							+"</ul>"
							+"</div>"
							+"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();	
							$('#accept').click(function(){
								 //$('#lean_overlay').show('0',function(){
								$("#modal").remove();										
								dailyView.openModal('Daily Both Merges And Switch', $html, 400); 
								$("#modal").show(0, function(){
									$.ajax({
										url: $admin_url + 'daily/process_both_merges_switch',
										data: 'date=' + date + '&key=' + key,
										type: 'post',
										async: false,
										success: function(data){
											var datatemplate={};
											datatemplate.report=JSON.parse(data);
											var compiledTemplate = _.template( reportTemplate, datatemplate );
											if(datatemplate.report[0].report == 0){
												$('.caculation-report').html(compiledTemplate
												+"<span style = 'font-weight:bold'>Data is enough</span>").fadeIn();
											}else{
												$('.caculation-report').html(compiledTemplate
												+"<span style = 'font-weight:bold'>Data is NOT enough, "+datatemplate.report[0].report+" lines</span>").fadeIn();
											}
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
								dailyView.openModal('Daily Both Merges And Switch', $html, 400);
								$("#modal").show(0, function(){
									$.ajax({
										url: $admin_url + 'daily/process_both_merges_switch',
										type: 'post',
										data: 'date=' + date + '&key=' + key,
										async: false,
										success: function(data){
											var datatemplate={};
											datatemplate.report=JSON.parse(data);
											var compiledTemplate = _.template( reportTemplate, datatemplate );
											if(datatemplate.report[0].report == 0){
												$('.caculation-report').html(compiledTemplate
												+"<span style = 'font-weight:bold'>Data is enough</span>").fadeIn();
											}else{
												$('.caculation-report').html(compiledTemplate
												+"<span style = 'font-weight:bold'>Data is NOT enough, "+datatemplate.report[0].report+" lines</span>").fadeIn();
											}
										}
									});
								});
							});
						}
					}
				});*/
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return dailyView = new dailyView;
    });
