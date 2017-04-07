<section class="grid_12">
    <div class="block-border">
        <form class="block-content" id="" method="post" action="" style="background: #E6E6E6;">
            <h1>List Categories</h1>
            <div id='form-first'>
                <input type='text' id='symbol' name='symbol' />
                <select id='duration' name='duration'>
                    <option value='1826'>5 Years</option>
                    <option value='7300'>Max</option>
                </select>
                <input type='button' id='show' name='show' value='Show' />
            </div>
            <div id='form-second'></div>
            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div style="clear: left;"></div>
            </div>
            <table class="table table-category-list table-ajax no-margin" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">
                            Open
                        </th>
                        <th scope="col">
                            High
                        </th>
                        <th scope="col">
                            Low
                        </th>
                        <th scope="col">
                            Close
                        </th>
                        <th scope="col">
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </form>
    </div>
</section>
<script>
    $(function(){
        $(".delete").click(function () {
            var $this=this
            if (confirm('Are you sure')) {
                var id=$($this).attr("category_id");
                $.ajax({
                    type: "POST",
                    url: "<?php echo admin_url() ?>category/delete",
                    data: "id=" + id,
                    success: function(msg){
                        if(msg>=1){
                            $($this).parents('tr').fadeOut('slow');
                        }else{
                            alert('the child category still exists');
                        }
                    }
                });
            }
        });

        $(".fancybox").fancybox({
            openEffect	: 'elastic',
            closeEffect	: 'elastic',
            helpers : {
                title : {
                    type : 'inside'
                },
                overlay : {
                    opacity: '0.1',
                    css : {
                        'background-color' : '#000'
                    }
                }
            }
        });
        $('.thumbnails').live("mouseenter", function() {
            var html='<img class="show-thumb-hover" src="'+$(this).attr('src')+'" />';
            $(this).parent().append(html);
            $('.show-thumb-hover').fadeIn('slow');
            $(this).parent().addClass('relative');
        }).live("mouseleave", function() {
            $('.show-thumb-hover').fadeOut('slow').remove();
            $(this).parent().removeClass('relative');
        });
        // su kien change select category
        $('select[name="category"]').change(function(){
            var id = $(this).val();
            window.location = $base_url + 'backend/category/index/' + id;
        });
    });
</script>
<script>
    $(document).ready(function(){
        $("#show").click(function(){
            var test={};
            var symbol = $("#symbol").val();
            var duration = $("#duration").val();
            if(symbol != ''){
                oTable = $('.table-category-list').dataTable({
                    "iDisplayLength":10,
                    "iDisplayStart": 0,
                    "bProcessing": true,
                    "sAjaxSource": $admin_url+"observatory/download_reuters",
                    "fnServerData": function ( sSource, aoData, fnCallback ) {
                        /* Add some extra data to the sender */
                        aoData.push( { "name": "symbol", "value": symbol }, { "name": "duration", "value": duration } );
                        $.getJSON( sSource, aoData, function (json) {
                            /* Do whatever additional processing you want on the callback, then tell DataTables */
                            fnCallback(json)
                        } );
                    },

                    "fnRender": function(obj) {
                        var returnButton = "<input class='approveButton' type='button' name='abc' value='Play'></input>";
                        return returnButton;
                    },

                    "aoColumns": [
                        { "mData": "id"},
                        { "mData": "open" },
                        { "mData": "high" },
                        { "mData": "low" },
                        { "mData": "close" },
                        { "mData": "date" }
                    ],
                    "sPaginationType": "full_numbers",
                    sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',



                    /*
                     * Callback to apply template setup
                     */
                    fnDrawCallback: function()
                    {
                        this.parent().applyTemplateSetup();
                        $(this).slideDown(200);
                    },
                    fnInitComplete: function(oSettings, json)
                    {
                        saveData(json);
                        $("#form-first").fadeOut();
                        $("#form-second").html("<input type='submit' name='export' value='Export' />");
                        this.parent().applyTemplateSetup();
                        $(this).width($(this).parents('.block-border').width());
                        $(this).slideDown(200);
                    }
                });
                var saveData = function(json){

                };

                $('.dataTables_info').addClass('message no-margin');
            }
            return false;
        });
    });
</script>