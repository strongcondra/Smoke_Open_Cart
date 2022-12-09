<div class="extpanel extpanel-default">
	<div class="extpanel-heading">
		<h4 class="extpanel-title"><i class="fa fa-user"></i> <?php echo $text_login; ?></h4>
	</div>
	<div class="extpanel-body">
		<div class="row">
			<div class="extsm-12">
				<div class="form-group">
					<label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
					<input type="text" name="email" value="" placeholder="<?php echo $entry_email_placeholder; ?>" id="input-email" class="formcontrol" />
				</div>
				<div class="form-group">
					<label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
					<input type="password" name="password" value="" placeholder="<?php echo $entry_password_placeholder; ?>" id="input-password" class="formcontrol" />
					<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
				<input type="button" value="<?php echo $button_login; ?>" id="button-login" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary button-login" />
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
// Login
$(document).delegate('#onepagecheckout .button-login', 'click', function() {
	$.ajax({
		url: 'index.php?route=onepagecheckout/login/save',
		type: 'post',
		data: $('#onepagecheckout .content-login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#onepagecheckout .button-login').button('loading');
		},
		complete: function() {
				$('#onepagecheckout .button-login').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
					if(json['success']){
						$('#onepagecheckout .content-login .panel-body').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					setTimeout(function(){ 
						 location = json['redirect'];
						}, 1000);
			} else if (json['error']) {
				$('#onepagecheckout .content-login .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('#onepagecheckout .content-login input[name=\'email\']').parent().addClass('has-error');
				$('#onepagecheckout .content-login input[name=\'password\']').parent().addClass('has-error');
			}
		}
	});
});
//--></script>