<div class="extsm-12">
	<div class="extinput-group">
		<input type="text" name="reward" value="<?php echo $reward; ?>" placeholder="<?php echo $entry_reward; ?>" id="input-reward" class="form-control" />
		<span class="extbtn-block">
			<button type="submit" id="button-reward" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary"><?php echo $button_reward; ?></button>
		</span>
	</div>
</div>
<script type="text/javascript"><!--
$('#onepagecheckout input[name=\'reward\']').keypress(function(event) {
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13') {
		$('#button-reward').trigger('click');
	}
});

$('#button-reward').on('click', function() {
	$.ajax({
		url: 'index.php?route=onepagecheckout/reward/reward',
		type: 'post',
		data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward').button('loading');
		},
		complete: function() {
			$('#button-reward').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#onepagecheckout #collapse-reward label').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['redirect']) {
				// Load Cart
				LoadCart();
			}
		}
	});
});
//--></script>