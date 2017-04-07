<script>
$(document).ready(function(){
	$("#lean_overlay").show(100, function(){
		var from = 0;
		var len = 100;
		var check = true;
		var duration = 0;
		while(check){
			$.ajax({
				url: $admin_url + 'news/hsx_history/' + from + '/' + len,
				async: false,
				success: function(rs){
					rs = JSON.parse(rs);
					from += len;
					if(rs.stt == 'end'){
						check = false;
						console.log(duration);
						$("#lean_overlay").hide();
					}else{
						duration += rs.duration;
					}
				}
			});
		}
	});
});
</script>