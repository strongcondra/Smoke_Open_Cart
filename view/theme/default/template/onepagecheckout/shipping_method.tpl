<?php if ($error_warning) { ?>
<div class="extalert balert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if($shipping_methods){ ?>
	<?php foreach ($shipping_methods as $shipping_method){ ?>
		<p><strong><?php echo $shipping_method['title']; ?></strong></p>
		<?php if (!$shipping_method['error']){ ?>
		<?php foreach ($shipping_method['quote'] as $quote){ ?>
		<div class="radio">
			<label>
				<?php if ($quote['code'] == $code || !$code) { ?>
				<?php $code = $quote['code']; //if(!$code) { $code == 'xshipping.xshipping1'; } else { echo $code = $quote['code'];}?>
				<input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
				<?php } else { ?>
				<input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
				<?php } ?>
				<?php echo $quote['title']; ?> - <?php echo $quote['text']; ?> <?php if($shipping_method['image']){ ?>
				<br/>
				<img src="<?php echo $shipping_method['image']; ?>"/>
				<?php  } ?></label>
		</div>
		<?php } ?>
		<?php } else { ?>
		<div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
		<?php } ?>
		<?php } ?>
<?php } ?>
<script>
$('.delivery-method-content input[name=\'shipping_method\'], .delivery-method-content input[name=\'shipping_method\']').on('change', function(){
	$.ajax({
		url: 'index.php?route=onepagecheckout/shipping_method/saveshipping',
		type:'post',
		data:$('.delivery-method-content input[type="radio"]:checked'),
		dataType: 'json',
		success: function(json){
			$('.alert, .text-danger').remove();
			if(json['error']){
				$('.delivery-method-content').before('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			if(json['success']){
				LoadCartWithoutloader();
				<?php if($loadpayments){ ?>
				<?php if($isLogged){ ?>
				LoadPaymentMethod(true);
				<?php }else{ ?>
				LoadPaymentMethod(false);
				<?php } ?>
				<?php } ?>
			}
		}
	})
});

$(document).ready(function(){
 $('.delivery-method-content input[name=\'shipping_method\']:checked, .delivery-method-content select[name=\'shipping_method\']').trigger('change');
});
</script>