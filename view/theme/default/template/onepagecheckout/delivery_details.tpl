<div class="extpanel extpanel-default">
	<div class="extpanel-heading">
		<h4 class="extpanel-title"><i class="fa fa-truck fa-flip-horizontal"></i> <?php echo $entry_heading; ?></h4>
	</div>
	<div class="extpanel-body">
		<form>
			<?php if ($addresses && $isLogged){ ?>
			<div class="radio">
				<label>
					<input type="radio" name="delivery_details[shipping_address]" value="existing" checked="checked" />
					<?php echo $text_address_existing; ?></label>
			</div>
			<div id="shipping-existing">
				<select name="delivery_details[address_id]" class="form-control">
					<?php foreach ($addresses as $address) { ?>
					<?php if ($address['address_id'] == $address_id) { ?>
					<option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>,<?php echo ($showcustomfeildsaddress ? $showcustomfeildsaddress : '') ?> <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>,<?php echo ($showcustomfeildsaddress ? $showcustomfeildsaddress : '') ?> <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
			</div>
			<div class="radio">
				<label><input type="radio" name="delivery_details[shipping_address]" value="new" /><?php echo $text_address_new; ?></label>
			</div>
			<br/>
			<?php }else { ?>
			<input style="display:none" type="radio" checked="checked" name="delivery_details[shipping_address]" value="new" />
			<?php } ?>
			<div id="shipping-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
				<?php foreach($feilds as $feild){ ?>				 
				 
				<?php if($feild['key']=='firstname'){ ?>
					<!-- First Name -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-firstname"><?php echo $feild['label']; ?></label>
							<input type="text" name="delivery_details[firstname]" value="<?php echo $firstname; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-firstname" class="formcontrol" />
					</div>
				<?php } ?>
				 
				 
				 <?php if($feild['key']=='lastname'){ ?>
				 
					<!-- Last Name -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-lastname"><?php echo $feild['label']; ?></label>
						<input type="text" name="delivery_details[lastname]" value="<?php echo $lastname; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-lastname" class="formcontrol" />
					</div>
					<?php } ?>
					<?php if($feild['key']=='company'){ ?>
					<!-- Company -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-company"><?php echo $feild['label']; ?></label>
						<input type="text" name="delivery_details[company]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-company" class="formcontrol" />
					</div>
					<?php } ?>
					
					<?php if($feild['key']=='address_1'){ ?>
					<!-- Address 1 -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-address-1"><?php echo $feild['label']; ?></label>
						 <input type="text" name="delivery_details[address_1]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-address-1" class="formcontrol" />
					</div>
					<?php } ?>
					
					<?php if($feild['key']=='address_2'){ ?>
					<!-- Address 2 -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-address-2"><?php echo $feild['label']; ?></label>
						<input type="text" name="delivery_details[address_2]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-address-2" class="formcontrol" />
					</div>
					<?php } ?>
					
					<?php if($feild['key']=='city'){ ?>
					<!-- City -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-city"><?php echo $feild['label']; ?></label>
						<input type="text" name="delivery_details[city]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-city" class="formcontrol" />
					</div>
					<?php } ?>
					
					<?php if($feild['key']=='postcode'){ ?>
					<!-- Postal Code -->
					<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-postcode"><?php echo $feild['label']; ?></label>
						<input type="text" name="delivery_details[postcode]" value="<?php echo $postcode; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-shipping-postcode" class="formcontrol" />
					</div>
					<?php } ?>
					
					
					<!-- Country -->
					<?php if($feild['key']=='country'){ ?>
					<div class="form-group <?php echo ($feild['chide'] ? 'hide' : ''); ?> <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-country"><?php echo $feild['label']; ?></label>
						<select name="delivery_details[country_id]" id="input-shipping-country" class="formcontrol">
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
					<!-- Zone -->
					<?php if($feild['key']=='zone'){ ?>
					<div class="form-group <?php echo ($feild['zhide'] ? 'hide' : ''); ?> <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
						<label class="control-label" for="input-shipping-zone"><?php echo $feild['label']; ?></label>
						<select name="delivery_details[zone_id]" id="input-shipping-zone" class="formcontrol"></select>
					</div>
					<?php } ?>
					<?php } ?>
					
					
					<?php foreach ($custom_fields as $custom_field) { ?>
					<?php if ($custom_field['location'] == 'address') { ?>
					
					<?php if ($custom_field['type'] == 'select') { ?>
					
					<!-- Custom Select -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<select name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
								<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
								<?php } ?>
						</select>
					</div>
					<?php } ?>				
					
					<?php if ($custom_field['type'] == 'radio') { ?>
					<!-- Radio Custom -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
								<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
								<div class="radio">
									<label>
										<input type="radio" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
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
						<div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
								<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
										<?php echo $custom_field_value['name']; ?></label>
								</div>
								<?php } ?>
							</div>
					</div>
					<?php } ?>
					
					<?php if ($custom_field['type'] == 'text') { ?>
					<!-- Custom Text -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<input type="text" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
					</div>
					<?php } ?>
					
					<?php if ($custom_field['type'] == 'textarea') { ?>
					<!-- Custom Textarea -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<textarea name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol"><?php echo $custom_field['value']; ?></textarea>
					</div>
					<?php } ?>
					
					<?php if ($custom_field['type'] == 'file') { ?>
					<!-- Custom File -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<button type="button" id="button-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
							<input type="hidden" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
					</div>
					<?php } ?>
					
					<?php if ($custom_field['type'] == 'date') { ?>
					<!-- Custom Date -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
							<div class="input-group date">
								<input type="text" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
								<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span></div>
					</div>
					<?php } ?>
					
					<?php if ($custom_field['type'] == 'time') { ?>
					<!-- Custom Time -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group time">
								<input type="text" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
								<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
						</div>
					</div>
					<?php } ?>
					
					<?php if ($custom_field['type'] == 'datetime') { ?>
					<!-- Custom Datetime -->
					<div class="<?php echo $class1; ?> form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group datetime">
							<input type="text" name="delivery_details[custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
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
$('.delivery-details-content input[name=\'delivery_details[shipping_address]\']').on('change', function() {
	if (this.value == 'new') {
		$('#shipping-existing').hide();
		$('#shipping-new').show();
		$('#shipping-new select[name=\'delivery_details[zone_id]\']').trigger('change');
	} else {
		$('#shipping-existing').show();
		$('#shipping-new').hide();
		$('#shipping-existing select[name=\'delivery_details[address_id]\']').trigger('change');
	}
});
//--></script>
<script type="text/javascript"><!--
$('#shipping-new .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#shipping-new .form-group').length-2) {
		$('#shipping-new .form-group').eq(parseInt($(this).attr('data-sort'))+2).before(this);
	}

	if ($(this).attr('data-sort') > $('#shipping-new .form-group').length-2) {
		$('#shipping-new .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#shipping-new .form-group').length-2) {
		$('#shipping-new .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#shipping-new .form-group').length-2) {
		$('#shipping-new .form-group:first').before(this);
	}
});
//--></script>
<script type="text/javascript"><!--
$('#shipping-new button[id^=\'button-shipping-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="delivery_details[file]" /></form>');

	$('#form-upload input[name=\'delivery_details[file]\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'delivery_details[file]\']').val() != '') {
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
						$(node).parent().find('input[name^=\'delivery_details[custom_field]\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input[name^=\'delivery_details[custom_field]\']').attr('value', json['code']);
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
<?php if($isLogged){ ?>
$('#shipping-existing select[name=\'delivery_details[address_id]\']').on('change', function(){
	var postdata = $('.delivery-details-content input[type=\'text\'],.delivery-details-content input[type=\'checkbox\']:checked, .delivery-details-content input[type=\'radio\']:checked, .delivery-details-content input[type=\'hidden\'],.delivery-details-content select');
	var url = 'index.php?route=onepagecheckout/shipping_method&type=delivery_details';
	$.ajax({
		url:url,
		type:'post',
		data:postdata,
		dataType: 'html',
		success: function(html){
			$('#onepagecheckout .delivery-method-loader').remove();
			$('#onepagecheckout .delivery-method-content').html(html);
		}
	});
});
$('#shipping-existing select[name=\'delivery_details[address_id]\']').trigger('change');
<?php } ?>
$('#shipping-new select[name=\'delivery_details[country_id]\']').on('change', function(){
	$.ajax({
		url: 'index.php?route=onepagecheckout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function(){
			$('#shipping-new select[name=\'delivery_details[country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json){
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

			$('#shipping-new select[name=\'delivery_details[zone_id]\']').html(html).trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError){
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#shipping-new select[name=\'delivery_details[country_id]\']').trigger('change');

$('#shipping-new select[name=\'delivery_details[zone_id]\']').on('change', function(){
	var shipping_address = false;
	<?php if(!$isLogged){ ?>
		var val = $("input[name='personal_details[shipping_address]']:checked").val();
		if(!$("input[name='personal_details[shipping_address]']:checked").val()){
			shipping_address = true;
		}
	<?php } else { ?>
		var val = $("input[name='delivery_details[shipping_address]']:checked").val();
		shipping_address = false;
		if(val=='new'){
		  shipping_address = true;
		}
	<?php } ?>
	if(shipping_address==true){
		LoadShippingMethod();
	}
});



$('#shipping-new input[name=\'delivery_details[postcode]\']').on('keyup', function(){
	
	var shipping_address = false;

	<?php if(!$isLogged){ ?>

		var val = $("input[name='personal_details[shipping_address]']:checked").val();

		if(!$("input[name='personal_details[shipping_address]']:checked").val()){

			shipping_address = true;

		}

	<?php } else { ?>

		var val = $("input[name='delivery_details[shipping_address]']:checked").val();

		shipping_address = false;

		if(val=='new'){

		  shipping_address = true;

		}

	<?php } ?>

	if(shipping_address==true){

		LoadShippingMethod();

	}
});
//--></script>