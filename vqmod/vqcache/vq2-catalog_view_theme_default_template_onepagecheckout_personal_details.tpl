<div class="extpanel extpanel-default">
	<div class="extpanel-heading">
		<h4 class="extpanel-title"><i class="fa fa-user"></i> <?php echo $text_personal_details; ?></h4>
	</div>
	<div class="extpanel-body">
		<div id="account">
			<div class="form-group <?php echo ($class1 ? 'extsm-12' : '') ?>" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
				<label class="control-label"><?php echo $entry_customer_group; ?></label>
				<?php if($customer_group_type){ ?>
				<?php foreach ($customer_groups as $customer_group) { ?>
				<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
				<div class="radio">
					<label>
						<input type="radio" name="personal_details[customer_group_id]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
						<?php echo $customer_group['name']; ?></label>
				</div>
				<?php } else { ?>
				<div class="radio">
					<label>
						<input type="radio" name="personal_details[customer_group_id]" value="<?php echo $customer_group['customer_group_id']; ?>" />
						<?php echo $customer_group['name']; ?></label>
				</div>
				<?php } ?>
				<?php } ?>
				<?php }else{ ?>
					<select class="formcontrol" name="personal_details[customer_group_id]">
					<?php foreach ($customer_groups as $customer_group) { ?>
						<?php if ($customer_group['customer_group_id'] == $customer_group_id){
							$select = 'selected=selected';
						}else{
							$select = '';
						}
						?>
						<option <?php echo $select; ?> value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
					</select>
				<?php } ?>
			</div>
			<?php foreach($feilds as $feild){ ?>
			<?php if($feild['key']=='firstname'){ ?>
			<!-- First Name -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-firstname"><?php echo $feild['label']; ?></label>
					<input type="text" name="personal_details[firstname]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-firstname" class="formcontrol" />
				</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='lastname'){ ?>
			<!-- Last Name -->
				<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
					<label class="control-label" for="input-payment-lastname"><?php echo $feild['label']; ?></label>
					<input type="text" name="personal_details[lastname]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-lastname" class="formcontrol" />
				</div>
			<?php } ?>
			<?php if($feild['key']=='email'){ ?>
			<!-- Email -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>" >
				<label class="control-label" for="input-payment-email"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[email]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-email" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='telephone'){ ?>
			<!-- Telephone -->
			<div class="form-group <?php echo $class1; ?>  <?php echo ($feild['required'] ? 'required' : ''); ?>" >
				<label class="control-label" for="input-payment-telephone"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[telephone]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-telephone" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='fax'){ ?>
			<!-- Fax -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>" >
				<label class="control-label" for="input-payment-fax"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[fax]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-fax" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='company'){ ?>
			<!-- Company -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>" >
				<label class="control-label" for="input-payment-company"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[company]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-company" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='address_1'){ ?>
			<!-- Address 1 -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
				<label class="control-label" for="input-payment-address-1"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[address_1]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-address-1" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='address_2'){ ?>
			<!-- Address 2 -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
				<label class="control-label" for="input-payment-address-2"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[address_2]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-address-2" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='city'){ ?>
			<!-- City -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
				<label class="control-label" for="input-payment-city"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[city]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-city" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='postcode'){ ?>
			<!-- Postcode -->
			<div class=" form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
				<label class="control-label" for="input-payment-postcode"><?php echo $feild['label']; ?></label>
				<input type="text" name="personal_details[postcode]" value="<?php echo $postcode; ?>" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-postcode" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<!-- Country -->
			<?php if($feild['key']=='country'){ ?>
			<div class="form-group <?php echo ($feild['chide'] ? 'hide' : ''); ?> <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?>">
				<label class="control-label" for="input-payment-country"><?php echo $feild['label']; ?></label>
				<select name="personal_details[country_id]" id="input-payment-country" class="formcontrol">
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
			<div class="form-group <?php echo ($feild['zhide'] ? 'hide' : ''); ?> <?php echo $class1; ?>    <?php echo ($feild['required'] ? 'required' : ''); ?>">
				<label class="control-label" for="input-payment-zone"><?php echo $feild['label']; ?></label>
				<select name="personal_details[zone_id]" id="input-payment-zone" class="formcontrol">
				</select>
			</div>
			<?php } ?>
			<?php if($feild['key']=='password'){ ?>
			<!-- Password -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?> register_fields">
				<label class="control-label" for="input-payment-password"><?php echo $feild['label']; ?></label>
				<input type="password" name="personal_details[password]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-password" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if($feild['key']=='confirm'){ ?>
			<!-- Confirm Password -->
			<div class="form-group <?php echo $class1; ?> <?php echo ($feild['required'] ? 'required' : ''); ?> register_fields">
				<label class="control-label" for="input-payment-confirm"><?php echo $feild['label']; ?></label>
				<input type="password" name="personal_details[confirm]" value="" placeholder="<?php echo $feild['placeholder']; ?>" id="input-payment-confirm" class="formcontrol" />
			</div>
			<?php } ?>
			
			<?php } ?>
			
			
			<?php foreach ($custom_fields as $custom_field) { ?>
			<?php if ($custom_field['location'] == 'address' || $custom_field['location'] == 'account') { ?>
			
			
			<?php if ($custom_field['type'] == 'select') { ?>
			<!-- Custom Select Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
				<select name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol">
					<option value=""><?php echo $text_select; ?></option>
					<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
					<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'radio') { ?>
			<!-- Custom Radio Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label"><?php echo $custom_field['name']; ?></label>
				<div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
					<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
					<div class="radio">
						<label>
							<input type="radio" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
							<?php echo $custom_field_value['name']; ?></label>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php if ($custom_field['type'] == 'checkbox') { ?>
			<!-- Custom Checkbox Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label"><?php echo $custom_field['name']; ?></label>
				<div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
					<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
							<?php echo $custom_field_value['name']; ?></label>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'text') { ?>
			<!-- Custom Text Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
				<input type="text" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'textarea') { ?>
			<!-- Custom Textarea Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
				<textarea name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol"><?php echo $custom_field['value']; ?></textarea>
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'file') { ?>
			<!-- Custom File Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label"><?php echo $custom_field['name']; ?></label>
				<br />
				<button type="extutton" id="extutton-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="exttn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
				<input type="hidden" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'date') { ?>
			<!-- Custom Date Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
				<div class="input-group date">
					<input type="text" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
					<span class="input-group-btn">
					<button type="extutton" class="exttn btn-default"><i class="fa fa-calendar"></i></button>
					</span></div>
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'time') { ?>
			<!-- Custom Time Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
				<div class="input-group time">
					<input type="text" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
					<span class="input-group-btn">
					<button type="extutton" class="exttn btn-default"><i class="fa fa-calendar"></i></button>
					</span></div>
			</div>
			<?php } ?>
			
			
			<?php if ($custom_field['type'] == 'datetime') { ?>
			<!-- Custom Datetime Option -->
			<div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="<?php echo $class1; ?> form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
				<label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
				<div class="input-group datetime">
					<input type="text" name="personal_details[custom_field][<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="formcontrol" />
					<span class="input-group-btn">
					<button type="extutton" class="exttn btn-default"><i class="fa fa-calendar"></i></button>
					</span></div>
			</div>
			<?php } ?>
			
			
			<?php } ?>
			<?php } ?>
			
		</div>
		
		

			    <!-- Captcha -->
		        <?php echo $captcha; ?>
		        <script>$(document).ready(function(){
                    $('label[for="input-captcha"]').css("width", "100%"); 
                    $("fieldset").css("margin", "10px 2px");
                });</script>
			
		<?php if($text_agree){ ?>
		<div class="checkbox register_fields extsm-12">
			<label>
				<input <?php echo $account_terms; ?> type="checkbox" name="personal_details[agree]" value="1" id="account-term"/>
				<?php echo $text_agree; ?>
			</label>
		</div>
		<?php } ?>
		
		<!-- Newsletter Status -->
		<?php if($newsletter_status){ ?>
		<div class="checkbox register_fields extsm-12">
			<label for="newsletter">
				<input <?php echo $newsletter_terms; ?> type="checkbox" name="personal_details[newsletter]" value="1" id="newsletter" />
				<?php echo $entry_newsletter; ?>
			</label>
		</div>
		<?php } ?>
		
		<!-- Shipping Required -->
		<?php if ($shipping_required) { ?>
		<div class="checkbox extsm-12 <?php echo ($delivery_status ? 'show' : 'hide'); ?> ">
			<label>
				<input type="checkbox" name="personal_details[shipping_address]" value="1" <?php if($delivery_auto_status){ ?> checked="checked" <?php } ?> />
				<?php echo $entry_shipping; ?>
			</label>
		</div>
		<?php } ?>
			
	</div>
</div>
<script>
// Sort the fields
$('#account .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
		$('#account .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#account .form-group').length) {
		$('#account .form-group:first').before(this);
	}
});

<?php if($customer_group_type){ ?>
$('#account input[name=\'personal_details[customer_group_id]\']').on('change', function() {
<?php }else{ ?>
$('#account select[name=\'personal_details[customer_group_id]\']').on('change', function() {
<?php } ?>
	$.ajax({
		url: 'index.php?route=onepagecheckout/checkout/customfield&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('#account .custom-field').hide();
			$('#account .custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('#payment-custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('#payment-custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
<?php if($customer_group_type){ ?>
$('#account input[name=\'personal_details[customer_group_id]\']:checked').trigger('change');
<?php }else{ ?>
$('#account select[name=\'personal_details[customer_group_id]\']').trigger('change');
<?php } ?>
</script>
<script type="text/javascript"><!--
$('#account button[id^=\'button-shipping-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="personal_details[file]" /></form>');

	$('#form-upload input[name=\'personal_details[file]\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'personal_details[file]\']').val() != '') {
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
						$(node).parent().find('input[name^=\'personal_details[custom_field]\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input[name^=\'personal_details[custom_field]\']').attr('value', json['code']);
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
$('#account select[name=\'personal_details[country_id]\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=onepagecheckout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#account select[name=\'personal_details[country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
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

			$('#account select[name=\'personal_details[zone_id]\']').html(html).trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#account select[name=\'personal_details[country_id]\']').trigger('change');


$('#account select[name=\'personal_details[zone_id]\']').on('change', function(){
	if($("input[name='personal_details[shipping_address]']:checked").val()==1){
		LoadShippingMethod();
	}
	LoadPaymentMethod(false);
});

$('#account input[name=\'personal_details[postcode]\']').on('blur', function(){

	if($("input[name='personal_details[shipping_address]']:checked").val()==1){

		LoadShippingMethod();

	}

	LoadPaymentMethod(false);

});
//--></script>