<script>
$(document).ready(function(){
    $("#lean_overlay").show(100, function(){
        var count = <?php echo $count; ?>;
        var limit = 0;
        var per = 100;
        while(limit <= count){
            $.ajax({
                url: $admin_url + 'currency/import_table',
                type: 'post',
                data: 'start=' + limit + '&offset=' + per,
                async: false,
                success: function(rs){                     
                    limit += per;
                }
            });
        }
        window.location.href = $admin_url + 'currency/export';
    });
});
</script>