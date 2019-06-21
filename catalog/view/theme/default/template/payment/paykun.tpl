<?php echo $paykunData; ?>
<div class="buttons">
	<div class="right">
		<input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
	</div>
</div>

<script type="text/javascript">
	$('#button-confirm').bind('click', function() {
		document.server_request.submit();
	});
</script>
