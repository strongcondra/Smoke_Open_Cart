<?php echo $header; ?>
<link href="catalog/view/javascript/onepagecheckout/css/base.css" rel="stylesheet">
<link href="catalog/view/javascript/onepagecheckout/css/style.css" rel="stylesheet">
<?php if (defined('JOURNAL_INSTALLED')) { ?>
<div class="extended-container" id="container">
<div class="container j-container" id="onepagecheckout">
<?php } else { ?>
<div id="container">
<div class="extcontainer" id="onepagecheckout">
<?php } ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="extextalert bbalert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="extutton" class="close" data-dismiss="extalert">&times;</button>
  </div>
  <?php } ?>
  <div class="extrow"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
		<h1><?php echo $heading_title; ?></h1>  
		<?php if($header_description) { echo '<div class="margintb">'.$header_description.'</div>'; }; ?>
		<div class="extrow account_option">
			<div class="<?php echo $class1; ?>">
				<?php if(!$logged) { ?>
				<!-- Register , Guest , Login Buttons -->
				<div class="extpanel-group">
					<div class="extbtn-block" data-toggle="buttons">
						<?php if($register_status){ ?>
						<label style="width:<?php echo $tabwidth; ?>" class="btn <?php echo ($account_open == 'register') ? 'active btn-primary' : 'btn-default'; ?>  button-account-type ">
							<input type="radio" name="account_type" value="register" <?php echo ($account_open == 'register') ? 'checked="checked"' : ''; ?> /> <?php echo $register_tab; ?>
						</label>
						<?php } ?>
						<?php if($guest_status){ ?>
						<label style="width:<?php echo $tabwidth; ?>" class="btn <?php echo ($account_open == 'guest') ? 'active btn-primary' : 'btn-default'; ?> button-account-type">
							<input type="radio" name="account_type" value="guest" <?php echo ($account_open == 'guest') ? 'checked="checked"' : ''; ?> /> <?php echo $guest_tab; ?>
						</label>
						<?php } ?>
						<?php if($enable_login){ ?>
						<label style="width:<?php echo $tabwidth; ?>" class="btn <?php echo ($account_open == 'login') ? 'active btn-primary' : 'btn-default'; ?>  button-account-type">
							<input type="radio" name="account_type" value="login" <?php echo ($account_open == 'login') ? 'checked="checked"' : ''; ?> /> <?php echo $login_tab; ?>
						</label>
						<?php } ?>
					</div>
				</div>
				
				<?php if(!$logged){ ?>
				<?php echo $social_login; ?>
				<?php } ?>
				
				<?php } ?>
				<div class="account-content content-register-guest">
				<?php if(!$logged) { ?>
				<!-- Details -->
				<div class="personal-details-content content-personal-details marginbottom"><?php echo $personal_details; ?></div>
				<?php } else{ ?>
				<div class="payment-details-content content-payment-details marginbottom"><?php echo $payment_details; ?></div>
				<?php } ?>
				<?php if($shipping && $delivery_status){ ?>
				<div class="delivery-details-content content-delivery-details"><?php echo $delivery_details; ?></div>
				<?php } ?>
				</div>
				<?php if(!$logged) { ?>
					<div class="account-content content-login"><?php echo $login; ?></div>
				<?php } ?>
			</div>
			<div class="<?php echo $class2; ?>">
				<!--CART START-->
				<?php if($shopping_cart_status){ ?>
				<div class="extrow full-cart format_load">
					<div class="extsm-12">
						<div class="content-shopping-cart">
							<div class="extpanel extpanel-default">
								<div class="extpanel-heading">
									<h4 class="extpanel-title"><i class="fa fa-shopping-cart"></i> <?php echo $text_shopping_cart; ?>	<?php if($weight){ ?>&nbsp;(<span id="loadweight"><?php echo $weight; ?></span>)<?php } ?>
									</h4>
								</div>
								<div class="extpanel-body">
									<div class="shopping-cart-content ext-carts min-hght"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--CART END-->
				<?php } ?>
				<div class="extrow">
					<!-- Shipping -->
					<?php if($shipping){ ?>
					<div class="extsm-6">
						<div class="full-payment-method format_load">
							<div class="content-delivery-method">
								<div class="extpanel extpanel-default">
									<div class="extpanel-heading">
										<h4 class="extpanel-title"><i class="fa fa-send"></i> <?php echo $text_shipping_method; ?></h4>
									</div>
									<div class="miniheightclass extpanel-body delivery-method-content ext-delivery-method"></div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					<!-- Payment Methods -->
					<div class="<?php if($shipping){ ?> extsm-6 <?php }else { ?> extsm-12 <?php } ?>">
						<div class="full-payment-method format_load">
							<div class="content-payment-method">
								<div class="extpanel extpanel-default">
									<div class="extpanel-heading">
										<h4 class="extpanel-title"><i class="fa fa-credit-card"></i> <?php echo $text_payment_methods; ?></h4>
									</div>
									<div class="miniheightclass extpanel-body payment-method-content ext-payment-method"></div>
								</div>
							</div>
						</div>
					</div>
					<?php echo $delivery_date; ?>
				</div>
				<div style="border: 1px solid rgb(221, 221, 221); padding: 10px;" class="confirm-order-content content-confirm-order">
				</div>
			</div>
		</div>
		<?php if($bottom_description) { echo '<div class="margintb">'.$bottom_description.'</div>'; }; ?>
		
		<?php echo $content_bottom; ?>
	</div>
    <?php echo $column_right; ?>
</div>
<?php if(!$logged){ ?>
<script type="text/javascript"><!--
// Account Type Trigger
$(document).delegate('#onepagecheckout .button-account-type', 'click', function(){
	$('#onepagecheckout .button-account-type').removeClass('btn-primary');	
	$('#onepagecheckout .button-account-type').addClass('btn-default');	
	$(this).addClass('btn-primary');	
});

$(document).delegate('#onepagecheckout .button-account-type', 'change', function(){
	setTimeout(function() {
		
		var account_type = ($('#onepagecheckout input[name=\'account_type\']:checked').val()) ? $('#onepagecheckout input[name=\'account_type\']:checked').val() : '';
		if(account_type == 'login') {
			$('#onepagecheckout .social-section').removeClass('hide');
			$('#onepagecheckout .account-content').addClass('hide');
			$('#onepagecheckout .content-login').removeClass('hide');	
		}else{
			$('#onepagecheckout .account-content').addClass('hide');
			$('#onepagecheckout .content-register-guest').removeClass('hide');	
			$('#onepagecheckout .social-section').addClass('hide');	
			if(account_type == 'register') {
				$('#onepagecheckout .register_fields').removeClass('hide');	
				$('#onepagecheckout .social-section').removeClass('hide');
			}else{
				$('#onepagecheckout .register_fields').addClass('hide');	
			}
		}
		LoadCart();
		LoadConfirmation(account_type);
	}, 300);
});
// Account Type Trigger
$('#onepagecheckout .active.button-account-type').trigger('change');
//--></script>
<?php } ?>
<?php if(!$shipping){ ?>
<script type="text/javascript"><!--
LoadCartWithoutloader();
//--></script>
<?php } ?>
<script type="text/javascript"><!--
$('#onepagecheckout .ext-carts').html('<div class="extloader loader cart-loader text-center"><img src="catalog/view/theme/default/image/loader.gif" alt="Loader" /> <span class="sr-only">Loading...</span></div>');
<?php if(!$logged){ ?>
function changeShippingAddress(){
	var shipping_address = $('#onepagecheckout input[name=\'personal_details[shipping_address]\']:checked').val();
	if(shipping_address) {
		$('#onepagecheckout .delivery-details-content').addClass('hide');
	}else{
		$('#onepagecheckout .delivery-details-content').removeClass('hide');
	}
}
	
$('#onepagecheckout input[name=\'personal_details[shipping_address]\']').click(function(){
	changeShippingAddress();
});

$(document).ready(function(){
	changeShippingAddress();
});
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$(document).delegate('#onepagecheckout #button-register, #onepagecheckout #button-guest, #onepagecheckout #button-loggedorder', 'click', function(){
	var button_type = $(this).attr('rel');
	$.ajax({
		url: 'index.php?route=onepagecheckout/validate/validateForm',
		type: 'post',
		dataType: 'json',
		data: $('#onepagecheckout input[type=\'text\'],#onepagecheckout input[type=\'password\'], #onepagecheckout input[type=\'hidden\'], #onepagecheckout input[type=\'radio\']:checked, #onepagecheckout input[type=\'checkbox\']:checked, #onepagecheckout select, #onepagecheckout textarea'),
		beforeSend: function() {
			$('#onepagecheckout #button-'+ button_type).button('loading');
		},
		complete: function() {
			$('#onepagecheckout #button-'+ button_type).button('reset');
		},
		success: function(json) {
			$('.extalert, .text-danger').remove();
      $('.form-group').removeClass('has-error');
			if(json['error']){
				//// Register Or Guest Checkout - Personal Details (Validate)
				if(json['error']['personal_details']){
					for (i in json['error']['personal_details']){
						var element = $('#onepagecheckout .personal-details-content #input-payment-' + i.replace('_', '-'));
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error']['personal_details'][i] + '</div>');
						} else {
							<?php if($error_field_layout){ ?>
							$(element).after('<div class="text-danger">' + json['error']['personal_details'][i] + '</div>');
							<?php } ?>
							$(element).parent().addClass('has-error');
						}
					}
					//$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
				// Delivery Address (Validate)
				if(json['error']['delivery_details']){
					for (i in json['error']['delivery_details']){
						var element = $('#onepagecheckout .delivery-details-content #input-shipping-' + i.replace('_', '-'));
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error']['delivery_details'][i] + '</div>');
						} else {
							<?php if($error_field_layout){ ?>
							$(element).after('<div class="text-danger">' + json['error']['delivery_details'][i] + '</div>');
							<?php } ?>
							$(element).parent().addClass('has-error');
						}
					}
				}
				
				// Payment Address (Validate)
				if(json['error']['payment_details']){
					for (i in json['error']['payment_details']){
						var element = $('#onepagecheckout .payment-details-content #input-payment-' + i.replace('_', '-'));
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error']['payment_details'][i] + '</div>');
						} else {
							<?php if($error_field_layout){ ?>
							$(element).after('<div class="text-danger">' + json['error']['payment_details'][i] + '</div>');
							<?php } ?>
							$(element).parent().addClass('has-error');
						}
					}
				}
				
				if(json['error']['warning']){
					$('.account_option').before('<div class="extalert balert-danger"> <i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="extutton" class="close" data-dismiss="extalert">&times;</button></div>');
					
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
				
				
				
				if(json['error']['payment_method']){
					$('.payment-method-content').before('<div class="extalert balert-danger"> <i class="fa fa-exclamation-circle"></i> ' + json['error']['payment_method']['warning'] + '<button type="extutton" class="close" data-dismiss="extalert">&times;</button></div>');
				}
				
				if(json['error']['shipping_method']){
					$('.delivery-method-content').before('<div class="extalert balert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['shipping_method']['warning'] + '<button type="extutton" class="close" data-dismiss="extalert">&times;</button></div>');
				}
				
				if(json['error']['comment']){
					//$('textarea[name="comment"]').before('<div class="extalert balert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error']['comment'] +'<button type="extutton" class="close" data-dismiss="extalert">&times;</button></div>');
					$('textarea[name="comment"]').after('<div class="text-danger">' + json['error']['comment'] + '</div>');
					$('textarea[name="comment"]').parent().addClass('has-error');
				}
				
				if(json['error']['delivery_date']){
					$('.date-error').after('<div class="text-danger">' + json['error']['delivery_date'] + '</div>');
					$('.date-error').parent().addClass('has-error');
				}
			}
			
			if(json['redirect']){
				  location = 'index.php?route=onepagecheckout/checkout';
			}
			
			if(json['success']=='register'){
				LoadConfirmation('confirm');
			}
			
			if(json['success']=='Guest'){
				LoadConfirmation('confirm');
			}
			
			if(json['success']=='confirm'){
				LoadConfirmation('confirm');
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	<?php if($logged){ ?>
	var account_type = ($('#onepagecheckout input[name=\'account_type\']:checked').val()) ? $('#onepagecheckout input[name=\'account_type\']:checked').val() : '';
	LoadConfirmation(account_type);
	<?php } ?>
});
//--></script>
</div>
</div>
<?php echo $footer; ?>