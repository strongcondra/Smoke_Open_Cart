<div class="extsm-6">
	<div class="extinput-group">
		<input type="text" name="voucher" value="<?php echo $voucher; ?>" placeholder="<?php echo $entry_voucher; ?>" id="input-voucher" class="formcontrol" />
		<span class="extbtn-block">
			<button type="submit" id="button-voucher" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary"><?php echo $button_voucher; ?></button>
		</span>
	</div>
</div>
<script type="text/javascript"><!--
$('#onepagecheckout input[name=\'voucher\']').keypress(function(event) {
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13') {
		$('#button-voucher').trigger('click');
	}
});
$('#button-voucher').on('click', function() {
  $.ajax({
    url: 'index.php?route=onepagecheckout/voucher/voucher',
    type: 'post',
    data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
    dataType: 'json',
    beforeSend: function() {
      $('#button-voucher').button('loading');
    },
    complete: function() {
      $('#button-voucher').button('reset');
    },
    success: function(json) {
      $('.alert').remove();
	  $('.text-danger').remove();
		if (json['error']){
			$('#onepagecheckout input[name=\'voucher\']').after('<div class="text-danger"> ' + json['error'] + '</div>');
			$('#onepagecheckout input[name=\'voucher\']').parent().addClass('has-error');
			$('#onepagecheckout input[name=\'voucher\']').parent().find('button').removeClass('btn-primary');
			$('#onepagecheckout input[name=\'voucher\']').parent().find('button').addClass('btn-danger');
		}
			
		if (json['redirect']) {
		// Load Cart
			LoadCart();
		}
    }
  });
});
//--></script>