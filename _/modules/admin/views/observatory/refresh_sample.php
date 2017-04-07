<?php
if(isset($err) && $err != ''){
	echo $err;
}else{
?>
	<script>
	alert("Inserted <?php echo $count; ?> record(s)");
	</script>
<?php
}
?>