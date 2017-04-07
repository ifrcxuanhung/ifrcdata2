// Filename: views/sysformat/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var idxPageListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                //"click a.action-delete": "doDelete",
                "mouseover ul.keywords": "doHighLight",
                "mouseout ul.keywords": "doHighLight",
                "click ul.keywords": "openModalBox",
                
            },

            openModalBox: function(event){
                var $this = $(event.currentTarget);
                var code = $($this).attr('code');
                var contents;
                $.ajax({
                    url: $admin_url + 'idx_page/showSpecs',
                    type: 'post',
                    async: false,
                    data: 'code=' + code,
                    success: function(rs){
                        rs = JSON.parse(rs);
                        contents = rs;
                    }
                });
                var span = '<span class="column-sort">' +
                            '<a href="#" title="Sort up" class="sort-up"></a>' +
                            '<a href="#" title="Sort down" class="sort-down"></a>' +
                            '</span>';
                var html =  '<table id="related-code" class="table" style="width: 500px"></table>';
                openModal('Related Code', html);
                if($('#related-code').length > 0){
                    $('#related-code').dataTable({
                        'aaData': contents,
                        'aoColumns': [
                            {'sTitle': 'Code' + span},
                            {'sTitle': 'Name' + span},
                        ],
                        "iDisplayLength": 10,
                        "iDisplayStart": 0,
                        //"sScrollY": "200px",
                        "sPaginationType": "full_numbers",
                        /*
                        * Set DOM structure for table controls
                        * @url http://www.datatables.net/examples/basic_init/dom.html
                        */
                        sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
                        /*
                        * Callback to apply template setup
                        */
                        fnDrawCallback: function()
                        {
                            this.parent().applyTemplateSetup();
                            $($this).slideDown(200);
                        },
                        fnInitComplete: function()
                        {

                            this.parent().applyTemplateSetup();
                            $($this).slideDown(200);
                        }
                    });
                }

                $('#modal div.modal-window').css({'margin-top': '50px'});
            },

            doHighLight: function(event){
                var $this = $(event.currentTarget);
                if($($this).hasClass('ui-state-highlight')){
                    $($this).removeClass('ui-state-highlight');
                }else{
                    $($this).addClass('ui-state-highlight');
                }
            },

            index: function(){

            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return idxPageListView = new idxPageListView;
    });
