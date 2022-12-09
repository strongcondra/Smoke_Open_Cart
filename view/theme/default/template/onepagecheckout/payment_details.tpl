<div class="extpanel extpanel-default">
	<div class="extpanel-heading">
		<h4 class="extpanel-title"><i class="fa fa-user"></i> <?php echo $entry_heading; ?></h4>
	</div>
	<div class="extpanel-body">
		<form>
		<?php if ($addresses) { ?>
			<div class="radio">
				<label>
					<input type="radio" name="payment_details[payment_address]" value="existing" checked="checked" />
					<?php echo $text_address_existing; ?>
				</label>
			</div>
			<div id="payment-existing">
				<select name="payment_details[address_id]" class="formcontrol">
					<?php foreach ($addresses as $address) { ?>
					<?php if ($address['address_id'] == $address_id) { ?>
					<option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>,<?php echo ($showcustomfeildsaddress ? $showcustomfeildsaddress : '') ?> <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>,<?php echo $showcustomfeildsaddress; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
			</div>
			<div class="radio">
				<label><input type="radio" name="payment_details[payment_address]" value="new" />
					<?php echo $text_address_new; ?></label>
			</div>
			<?php }else{ ?>
				<input style="display:none" type="radio" checked="checked" name="payment_details[payment_address]" value="new" />
			<?php } ?>
			<br />
			
			
			<div id="payment-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
				<?php foreach($feilds as $feild){ ?>
				
				<?php if($feild['key']=='firstname'){ ?>
				<!-- First Name -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-firstname"><?php echo $feild['label']; ?></label>
						<input type="text" name="payment_details[firstname]" value="<?php echo $firstname; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-firstname" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if($feild['key']=='lastname'){ ?>
				<!-- Last Name -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-lastname"><?php echo $feild['label']; ?></label>
					<input type="text" name="payment_details[lastname]" value="<?php echo $lastname; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-lastname" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if($feild['key']=='company'){ ?>
				<!-- Company -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-company"><?php echo $feild['label']; ?></label>
					<input type="text" name="payment_details[company]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-company" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if($feild['key']=='address_1'){ ?>
				<!-- Address 1 -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-address-1"><?php echo $feild['label']; ?></label>
					 <input type="text" name="payment_details[address_1]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-address-1" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if($feild['key']=='address_2'){ ?>
				<!-- Address 2 -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-address-2"><?php echo $feild['label']; ?></label>
					<input type="text" name="payment_details[address_2]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-address-2" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if($feild['key']=='city'){ ?>
				<!-- City -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-city"><?php echo $feild['label']; ?></label>
					<input type="text" name="payment_details[city]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-city" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if($feild['key']=='postcode'){ ?>
				<!-- Postal Code -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-postcode"><?php echo $feild['label']; ?></label>
					<input type="text" name="payment_details[postcode]" value="<?php echo $postcode; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-postcode" class="formcontrol" />
				</div>
				<?php } ?>
				
				
				<!-- Country -->
				<?php if($feild['key']=='country'){ ?>
				<div class="form-group <?php echo ($feild['chide'] ? 'hide' : ''); ?> <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-country"><?php echo $feild['label']; ?></label>
					<select name="payment_details[country_id]" id="input-payment-country" class="formcontrol">
						<option value=""><?php echo $feild['placeholder']; ?></option>
						<?php foreach ($countries as $country) { ?>
						<?php if ($country['country_id'] == $country_id) { ?>
						<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<?php } ?>
				<?php if($feild['key']=='zone'){ ?>
				<div class="form-group <?php echo ($feild['zhide'] ? 'hide' : ''); ?> <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-zone"><?php echo $feild['label']; ?></label>
					<select name="payment_details[zone_id]" id="input-payment-zone" class="formcontrol"></select>
				</div>
				<?php } ?>
				<?php } ?>
				
				<?php foreach ($custom_fields as $custom_field) { ?>
				<?php if ($custom_field['location'] == 'address') { ?>
				
				<?php if ($custom_field['type'] == 'select') { ?>
				<!-- Custom Select -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<select name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol">
							<option value=""><?php echo $text_select; ?></option>
							<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
							<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
							<?php } ?>
					</select>
				 </div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'radio') { ?>
				<!-- Custom Radio -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
							<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
							<div class="radio">
								<label>
									<input type="radio" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
									<?php echo $custom_field_value['name']; ?></label>
							</div>
							<?php } ?>
						</div>
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'checkbox') { ?>
				<!-- Custom Checkbox -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
							<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
									<?php echo $custom_field_value['name']; ?></label>
							</div>
							<?php } ?>
						</div>
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'text') { ?>
				<!-- Custom Text -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<input type="text" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'textarea') { ?>
				<!-- Custom Textarea -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<textarea name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol"><?php echo $custom_field['value']; ?></textarea>
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'file') { ?>
				<!-- Custom File -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
						<input type="hidden" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'date') { ?>
				<!-- Date Custom -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group date">
							<input type="text" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
							<span class="input-group-btn">
							<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span></div>
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'time') { ?>
				<!-- Custom time -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group time">
						<input type="text" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
						<span class="input-group-btn">
							<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
						</span>
					</div>
				</div>
				<?php } ?>
				
				<?php if ($custom_field['type'] == 'datetime') { ?>
				<!-- Custom Datetime -->
				<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group datetime">
						<input type="text" name="payment_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
						<span class="input-group-btn">
							<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
						</span>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
				<?php } ?>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
$('input[name=\'payment_details[payment_address]\']').on('change', function() {
	if (this.value == 'new') {
		$('#payment-existing').hide();
		$('#payment-new').show();
		$('#payment-new select[name=\'payment_details[zone_id]\']').trigger('change');
	} else {
		$('#payment-existing').show();
		$('#payment-new').hide();
		$('#payment-existing select[name=\'payment_details[address_id]\']').trigger('change');
	}
});
//--></script>
<script type="text/javascript"><!--
$('#payment-new .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment-new .form-group').length-2) {
		$('#payment-new .form-group').eq(parseInt($(this).attr('data-sort'))+2).before(this);
	}

	if ($(this).attr('data-sort') > $('#payment-new .form-group').length-2) {
		$('#payment-new .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#payment-new .form-group').length-2) {
		$('#payment-new .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#payment-new .form-group').length-2) {
		$('#payment-new .form-group:first').before(this);
	}
});
//--></script>
<script type="text/javascript"><!--
$('#payment-new button[id^=\'button-payment-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="payment_details[file]" /></form>');

	$('#form-upload input[name=\'payment_details[file]\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'payment_details[file]\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input[name^=\'payment_details[custom_field]\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input[name^=\'payment_details[custom_field]\']').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<script type="text/javascript"><!--
$('#payment-existing select[name=\'payment_details[address_id]\']').on('change', function(){
	var postdata = $('.payment-details-content input[type=\'text\'],.payment-details-content input[type=\'checkbox\']:checked, .payment-details-content input[type=\'radio\']:checked, .payment-details-content input[type=\'hidden\'],.payment-details-content select');
	var url = 'index.php?route=onepagecheckout/payment_method&type=payment_details';
	$.ajax({
		url:url,
		type:'post',
		data:postdata,
		dataType: 'html',
		success: function(html){
			$('#onepagecheckout .payment-method-content').html(html);
		}
	});
	
	<?php if(!$delivery_status){ ?>
		Loadshippingmethodwithpaymentaddress();
	<?php } ?>
});

$('#payment-existing select[name=\'payment_details[address_id]\']').trigger('change');

$('#payment-new select[name=\'payment_details[country_id]\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=onepagecheckout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#payment-new select[name=\'payment_details[country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-new input[name=\'payment_details[postcode]\']').parent().parent().addClass('required');
			} else {
				$('#payment-new input[name=\'payment_details[postcode]\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $zoneplaceholder; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#payment-new select[name=\'payment_details[zone_id]\']').html(html).trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#payment-new select[name=\'payment_details[country_id]\']').trigger('change');

$('#payment-new select[name=\'payment_details[zone_id]\']').on('change', function(){
	if($("input[name='payment_details[payment_address]']:checked").val()=='new'){
	  LoadPaymentMethod(true);
	  <?php if(!$delivery_status){ ?>
		Loadshippingmethodwithpaymentaddress();
	  <?php } ?>
	}
	<?php if (!$addresses) { ?>
	LoadPaymentMethod(true);
		<?php if(!$delivery_status){ ?>
		Loadshippingmethodwithpaymentaddress();
		<?php } ?>
	<?php } ?>
});

$('#payment-new input[name=\'payment_details[postcode]\']').on('keyup', function(){

	if($("input[name='payment_details[payment_address]']:checked").val()==1){

		LoadPaymentMethod(false);
		<?php if(!$delivery_status){ ?>
		Loadshippingmethodwithpaymentaddress();
		<?php } ?>
	}
});
//--></script>