<?php echo $header; ?>
<link type="text/css" href="view/stylesheet/onepagecheckout.css" rel="stylesheet" media="screen" />
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<button onclick="$('.stay').val(1);" type="submit" form="form-order_success_page" data-toggle="tooltip" title="<?php echo $button_save; ?> & stay" class="btn btn-success1"><i class="fa fa-save"></i> <?php echo $button_save; ?> & stay </button>
		</div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-OnePageCheackout" class="form-horizontal">
     <input type="hidden" name="stay" class="stay" value="0"/>
    <div class="panel panel-default" id="onepagecheackout">
      <div class="panel-heading clearfix">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			<div class="pull-right">
				<b>Stores : </b><select onchange="location = this.options[this.selectedIndex].value;" name="store_id">
				<option value="<?php echo $store_action.'&store_id=0'; ?>"><?php echo $text_default; ?></option>
				<?php foreach($stores as $store){ 
					if($store['store_id']==$store_id){
						 $select = 'selected=selected';
					}else{
						 $select = '';
					}
				?>
				<option <?php echo $select; ?> value="<?php echo $store_action .'&store_id='. $store['store_id']; ?>"><?php echo $store['name']; ?></option>
				<?php } ?>
				</select>
			</div>
      </div>
      <div class="panel-body">
					<ul class="nav nav-tabs">
						<li class="dropdown active"><a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i> <?php echo $entry_general; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i>  <?php echo $entry_general; ?></a></li>
							<li><a href="#tab-social_login" data-toggle="tab"><i class="fa fa-sign-in aria-hidden="true"></i> <?php echo $entry_social_login; ?></a></li>
						</ul>
						</li>
						<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-eye"></i> Layout Setting <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#tab-layout" data-toggle="tab"><i class="fa fa-eye"></i> Layout Setting</a></li>
								<li><a href="#tab-shopping-cart-setting" data-toggle="tab"><i class="fa fa-shopping-cart"></i> <?php echo $entry_shopping_cart; ?></a></li>
								<li><a href="#tab-delivery_method-setting" data-toggle="tab"><i class="fa fa-truck" aria-hidden="true"></i> <?php echo $entry_delivery_method; ?></a></li>
								<li><a href="#tab-payment_method-setting" data-toggle="tab"><i class="fa fa-credit-card" aria-hidden="true"></i> <?php echo $entry_payment_method; ?></a></li>
								<li><a href="#tab-delivery-date-time" data-toggle="tab"><i class="fa fa-clock-o" aria-hidden="true"></i> Estimated Delivery Date</a></li>
								<li><a href="#tab-confirm_order-setting" data-toggle="tab"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?php echo $entry_confirm_order; ?></a></li>
						</ul>
						</li>
						<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-check-square-o"></i> <?php echo $field_manage; ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#tab-personaldetails-setting" data-toggle="tab"><i class="fa fa-cogs"></i> <?php echo $entry_personaldetails; ?></a></li>
								<li><a href="#tab-payment-setting" data-toggle="tab"><i class="fa fa-cogs"></i> <?php echo $entry_paymentdetails; ?></a></li>
								<li><a href="#tab-delivery-setting" data-toggle="tab"><i class="fa fa-cogs"></i> <?php echo $entry_delivery_setting; ?></a></li>
								<li><a target="_new" href="<?php echo $customfeilds; ?>"><i class="fa fa-cogs"></i> OpenCart Custom Feilds Settings</a></li>
							</ul>
						</li>
						<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-language"></i> <?php echo $entry_language; ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#tab-general-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_general; ?></a></li>
								<li><a href="#tab-personaldetails-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_personaldetails; ?></a></li>
								<li><a href="#tab-paymentdetails-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_paymentdetails; ?></a></li>
								<li><a href="#tab-delivery_detail-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_delivery_setting; ?></a></li>
								<li><a href="#tab-login-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_login; ?></a></li>
								<li><a href="#tab-delivery_method-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_delivery_method; ?></a></li>
								<li><a href="#tab-payment_method-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_payment_method; ?></a></li>
								<li><a href="#tab-delivery-language-date-time" data-toggle="tab"><i class="fa fa-language" aria-hidden="true"></i> Estimated Delivery Date</a></li>
								<li><a href="#tab-shopping-cart-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_shopping_cart; ?></a></li>
								<li><a href="#tab-confirm_order-language-setting" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $entry_confirm_order; ?></a></li>
							</ul>
						</li>
						<li><a target="_blank" href="<?php echo $order_success; ?>"><i class="fa fa-file-o" aria-hidden="true"></i> Order Success Page</a></li>
						<li><a href="#tab-restore" data-toggle="tab"><i class="fa fa-undo" aria-hidden="true"></i> Restore Settings</a></li>
						<li><a href="#tab-analytics" data-toggle="tab"><i class="fa fa-line-chart"></i> Analytics</a></li>
						<li><a href="#tab-support" data-toggle="tab"><i class="fa fa-user"></i> Support</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane" id="tab-restore">
							<p>If You want to Restore Module Settings to Demo Then It will use this featured.</p>
							<a href="<?php echo $restoresetting; ?>" class="btn btn-primary">Click Here to Restore</a>
						</div>
						<div class="tab-pane" id="tab-analytics">
						<?php if(version_compare(VERSION,'2.3.0.0','>=')){ ?>
						   <?php foreach ($rows as $row) { ?>

									<div class="row">

									  <?php foreach ($row as $dashboard_1) { ?>

									  <?php $class = 'col-lg-' . $dashboard_1['width'] . ' col-md-3 col-sm-6'; ?>

									  <?php foreach ($row as $dashboard_2) { ?>

									  <?php if ($dashboard_2['width'] > 3) { ?>

									  <?php $class = 'col-lg-' . $dashboard_1['width'] . ' col-md-12 col-sm-12'; ?>

									  <?php } ?>

									  <?php } ?>

									  <div class="<?php echo $class; ?>"><?php echo $dashboard_1['output']; ?></div>

									  <?php } ?>

									</div>

								<?php } ?>
								<?php }else{ ?>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6"><?php echo $order; ?></div>
									<div class="col-lg-6 col-md-6 col-sm-6"><?php echo $sale; ?></div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $map; ?></div>
									<div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $chart; ?></div>
								</div>
								<?php } ?>
						</div>
						<div class="tab-pane" id="tab-layout">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $help_register_account; ?> "><?php echo $entry_register_account ; ?></span></label>
								<div class="col-sm-10">
									<div class="btn-group" data-toggle="buttons">
										<?php 
										if(!isset($onepagecheckout_manage['personaldetails']['register_status'])) { 
											$onepagecheckout_manage['personaldetails']['register_status'] = '1';
										}
										?>
										<label class="btn btn-success btn-sm button-account-type <?php echo (!empty($onepagecheckout_manage['personaldetails']['register_status'])) ? 'active' : ''; ?>">	
											<input type="radio"  name="onepagecheckout_manage[personaldetails][register_status]" <?php echo (!empty($onepagecheckout_manage['personaldetails']['register_status'])) ? 'checked="checked"' : ''; ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
										</label>											
										<label class="btn btn-success btn-sm button-account-type <?php echo (empty($onepagecheckout_manage['personaldetails']['register_status'])) ? 'active' : ''; ?>">
											<input type="radio" name="onepagecheckout_manage[personaldetails][register_status]" <?php echo (empty($onepagecheckout_manage['personaldetails']['register_status'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off"><?php echo $text_no; ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $help_guest_checkout; ?> "><?php echo $entry_guest_checkout ; ?></span></label>
								<div class="col-sm-10">
									<div class="btn-group" data-toggle="buttons">
										<?php 
										if(!isset($onepagecheckout_manage['personaldetails']['guest_status'])) { 
											$onepagecheckout_manage['personaldetails']['guest_status'] = '1';
										}
										?>
										<label class="btn btn-success btn-sm  <?php echo (!empty($onepagecheckout_manage['personaldetails']['guest_status'])) ? 'active' : ''; ?>">	
											<input type="radio"  name="onepagecheckout_manage[personaldetails][guest_status]" <?php echo (!empty($onepagecheckout_manage['personaldetails']['guest_status'])) ? 'checked="checked"' : ''; ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
										</label>											
										<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['personaldetails']['guest_status'])) ? 'active' : ''; ?>">
											<input type="radio" name="onepagecheckout_manage[personaldetails][guest_status]" <?php echo (empty($onepagecheckout_manage['personaldetails']['guest_status'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off"><?php echo $text_no; ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $help_enable_login; ?> "><?php echo $entry_enable_login; ?></span></label>
								<div class="col-sm-10">
								<?php 
									if(!isset($onepagecheckout_manage['login']['enable_login'])){ 
											$onepagecheckout_manage['login']['enable_login'] = '1';
										}	?>
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['login']['enable_login']) && $onepagecheckout_manage['login']['enable_login']=='1'){ ?> active <?php } ?>" >	
											<input type="radio"  name="onepagecheckout_manage[login][enable_login]" <?php if(isset($onepagecheckout_manage['login']['enable_login']) && $onepagecheckout_manage['login']['enable_login']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
										</label>
										
										<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['login']['enable_login']) || $onepagecheckout_manage['login']['enable_login']=='0'){ ?> active <?php } ?>">
											<input type="radio"  name="onepagecheckout_manage[login][enable_login]" <?php if(!isset($onepagecheckout_manage['login']['enable_login']) || $onepagecheckout_manage['login']['enable_login']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">	
								<label class="col-sm-2 control-label" for="input-account_open"><span data-toggle="tooltip" title="" data-original-title="Default select section"><?php echo $entry_account_open; ?></span></label>
								<div class="col-sm-3">
									<div class="btn-group btn-group-justified" data-toggle="buttons">	
										<?php if(!isset($onepagecheckout_manage['general']['account_open'])) {
												$onepagecheckout_manage['general']['account_open'] = 'register';
											} 
										?>
										<label class="btn btn-success btn-sm <?php if(isset($onepagecheckout_manage['general']['account_open']) && $onepagecheckout_manage['general']['account_open']=='register') { ?> active <?php } ?>">
										<input type="radio" name="onepagecheckout_manage[general][account_open]" <?php if(isset($onepagecheckout_manage['general']['account_open']) && $onepagecheckout_manage['general']['account_open']=='register') { ?> checked="checked" <?php } ?> value="register" autocomplete="off" /><?php echo $entry_register; ?>
										</label>
										<label class="btn btn-success btn-sm <?php if(isset($onepagecheckout_manage['general']['account_open']) && $onepagecheckout_manage['general']['account_open']=='guest') { ?> active <?php } ?>">
										<input type="radio" name="onepagecheckout_manage[general][account_open]" <?php if(isset($onepagecheckout_manage['general']['account_open']) && $onepagecheckout_manage['general']['account_open']=='guest') { ?> checked="checked" <?php } ?> value="guest" autocomplete="off" /><?php echo $entry_guest; ?>
										</label>
										<label class="btn btn-success btn-sm <?php if(isset($onepagecheckout_manage['general']['account_open']) && $onepagecheckout_manage['general']['account_open']=='login') { ?> active <?php } ?>">
										<input type="radio" name="onepagecheckout_manage[general][account_open]" <?php if(isset($onepagecheckout_manage['general']['account_open']) && $onepagecheckout_manage['general']['account_open']=='login') { ?> checked="checked" <?php } ?> value="login" autocomplete="off" /><?php echo $entry_login; ?>
										</label>		
									</div>	 
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><span data-toggle="tooltip" title="" data-original-title="Set the Field Layout. Row = the labels will be on one line with the input. Block style = the labels will be above the inputs.">Field Layout</span></label>
								<div class="col-sm-3">
									<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_field_layout)){ ?> active <?php } ?>">	
												<input type="radio"  name="onepagecheckout_field_layout" <?php if(!empty($onepagecheckout_field_layout)){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_row; ?>
											</label>
											<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_field_layout)){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_field_layout" <?php if(empty($onepagecheckout_field_layout)){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_block; ?>
											</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><span data-toggle="tooltip" title="" data-original-title="Stop Cart Page and redirect to checkout page">Stop Cart Page</span></label>
								<div class="col-sm-3">
									<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_auto_redirect)){ ?> active <?php } ?>">	
												<input type="radio"  name="onepagecheckout_auto_redirect" <?php if(!empty($onepagecheckout_auto_redirect)){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_auto_redirect)){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_auto_redirect" <?php if(empty($onepagecheckout_auto_redirect)){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><span data-toggle="tooltip" title="" data-original-title="Show Countries which you want">Show Countries:</span></label>
								<div class="col-sm-6">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($countries as $country) { ?>
										<div class="checkbox">
										  <label>
											<?php if (in_array($country['country_id'], $onepagecheckout_selected_countries)) { ?>
											<input type="checkbox" name="onepagecheckout_selected_countries[]" value="<?php echo $country['country_id']; ?>" checked="checked" />
											<?php echo $country['name']; ?>
											<?php } else { ?>
											<input type="checkbox" name="onepagecheckout_selected_countries[]" value="<?php echo $country['country_id']; ?>" />
											<?php echo $country['name']; ?>
											<?php } ?>
										  </label>
										</div>
										<?php } ?>
										
									</div>
									<a onclick="$(this).parent().find('input[type=checkbox]').prop('checked', true );" class="btn btn-primary btn-sm">Selected All</a>
									<a onclick="$(this).parent().find('input[type=checkbox]').prop('checked', false );" class="btn btn-danger btn-sm">Unselected All</a>
								</div>
							</div>
							<div class="form-group">
							  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Show Customer Groups which you want">Show Customer Groups</span></label>
							  <div class="col-sm-6">
							   <div class="well well-sm" style="height: 150px; overflow: auto;"> 
								<?php foreach ($customer_groups as $customer_group) { ?>
								<div class="checkbox">
								  <label>
									<?php if (in_array($customer_group['customer_group_id'], $onepagecheckout_customer_group_display)) { ?>
									<input type="checkbox" name="onepagecheckout_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
									<?php echo $customer_group['name']; ?>
									<?php } else { ?>
									<input type="checkbox" name="onepagecheckout_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
									<?php echo $customer_group['name']; ?>
									<?php } ?>
								  </label>
								</div>
								<?php } ?>
								</div>
								<a onclick="$(this).parent().find('input[type=checkbox]').prop('checked', true );" class="btn btn-primary btn-sm">Selected All</a>
								<a onclick="$(this).parent().find('input[type=checkbox]').prop('checked', false );" class="btn btn-danger btn-sm">Unselected All</a>
							  </div>
							</div>
							<div class="form-group">
							   <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Customer Groups Input Type">Display Input Type:</span></label>
							   <div class="col-sm-6">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_customer_group_type)){ ?> active <?php } ?>">	
											<input type="radio"  name="onepagecheckout_customer_group_type" <?php if(!empty($onepagecheckout_customer_group_type)){ ?> checked="checked" <?php } ?> value="1" autocomplete="off">Radio Button
										</label>
										<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_customer_group_type)){ ?> active <?php } ?>">
											<input type="radio"  name="onepagecheckout_customer_group_type" <?php if(empty($onepagecheckout_customer_group_type)){ ?> checked="checked" <?php } ?> value="0" autocomplete="off">select Box
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane active" id="tab-general">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Checkout status"><?php echo $entry_status; ?></span></label>
								<div class="col-sm-3">
									<select name="onepagecheckout_status" id="input-status" class="form-control">
										<?php if ($onepagecheckout_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Opencart has so many payment extensions and every payment extension has there own way. There are no common standard for this. and some extension use different type of things to use like divs, button, input buttons as button with different IDs and clasess for payment confirm button. you need to add another trigger class or an id of the tag. List them here separating by comma , for example .content-confirm-order input[type=submit] .content-confirm-order .button, .content-confirm-order .btn, .content-confirm-order #button-confirm">Payment Tiggers</span></label>
								<div class="col-sm-3">
									<input class="form-control" type="text" name="onepagecheckout_payment_trigger_button" value="<?php echo $onepagecheckout_payment_trigger_button; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-country"><span data-toggle="tooltip" title="" data-original-title="Set Default Country"><?php echo $entry_country; ?></span></label>
								<div class="col-sm-3">
								  <select name="onepagecheckout_country_id" id="input-country" class="form-control">
									<?php foreach ($countries as $country) { ?>
									<?php if ($country['country_id'] == $onepagecheckout_country_id) { ?>
									<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
									<?php } ?>
									<?php } ?>
								  </select>
								</div>
							</div>
						    <div class="form-group">
								<label class="col-sm-2 control-label" for="input-zone"><span data-toggle="tooltip" title="" data-original-title="Set Default zone"><?php echo $entry_zone; ?></span></label>
								<div class="col-sm-3">
								  <select name="onepagecheckout_zone_id" id="input-zone" class="form-control">
								  </select>
								</div>
						   </div>
							<div class="form-group">
							  <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
							  <div class="col-sm-3">
								<select name="onepagecheckout_customer_group_id" id="input-customer-group" class="form-control">
								  <?php foreach ($customer_groups as $customer_group) { ?>
								  <?php if ($customer_group['customer_group_id'] == $onepagecheckout_customer_group_id) { ?>
								  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
								  <?php } else { ?>
								  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
								  <?php } ?>
								  <?php } ?>
								</select>
							  </div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-postal_code"><span data-toggle="tooltip" title="<?php echo $help_postal; ?>"><?php echo $entry_postal_code; ?></span></label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="onepagecheckout_postal_code" value="<?php echo $onepagecheckout_postal_code ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="Do not use spaces, instead replace spaces with - and make sure the SEO URL is globally unique.">SEO URL</span></label>
								<div class="col-sm-3">
								  <input type="text" class="form-control" name="keyword" value="<?php echo $keyword ?>"/>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-delivery_method-setting">
							<fieldset>
								<legend><?php echo $entry_delivery_method; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="Default selected Shipping Method"><?php echo $entry_delivery_method; ?></span></label>
									<div class="col-md-5">
										<select class="form-control" name="onepagecheckout_default_shipping">
										<?php foreach($delivery_methods as $delivery_method){
											if($delivery_method['code']==$onepagecheckout_default_shipping){
												$select ="selected=selected";
											}else{
												$select ='';
											}	
										?>
										  <option <?php echo $select; ?> value="<?php  echo $delivery_method['code']; ?>"><?php echo $delivery_method['title']; ?></option>
										<?php } ?>
										</select>
									</div>
								</div>
								
								<!--26-01-2017-->
								<div class="form-group">
									<label class="col-sm-2 control-label">Icon W x H</label>
									 <div class="col-sm-3">
										<input class="form-control" type="text" name="onepagecheckout_delivery_method_width" value="<?php echo $onepagecheckout_delivery_method_width; ?>"/>
									  </div>
									 <div class="col-sm-3">
										<input class="form-control" type="text" name="onepagecheckout_delivery_method_height" value="<?php echo $onepagecheckout_delivery_method_height; ?>"/>
									  </div>
								</div>
								<!--26-01-2017-->
								<div class="form-group">
									<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Reload Payment Methods When customer choose shipping option">Reload Payment</span></label>
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_payment_method_load_payment_method)) { ?> active <?php } ?>">	
											<input type="radio"  name="onepagecheckout_payment_method_load_payment_method" <?php if(!empty($onepagecheckout_payment_method_load_payment_method)) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
										</label>
										<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_payment_method_load_payment_method)) { ?> active <?php } ?>">
											<input type="radio"  name="onepagecheckout_payment_method_load_payment_method" <?php if(empty($onepagecheckout_payment_method_load_payment_method)) { ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
										</label>
									</div>
								</div>
								<?php if($delivery_methods){ ?> 
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="text-left"><?php echo $entry_delivery_method; ?></th>
											<th class="text-center"><?php echo $entry_image; ?></th>
											<th class="text-right"><?php echo $entry_status; ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($delivery_methods as $delivery_method){ ?>
										<tr>
											<td class="text-left"><?php echo $delivery_method['title']; ?></td>
											<td class="text-center">
												<div class="col-sm-12"><a href="" id="thumb-logo<?php echo $delivery_method['code']; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $delivery_method['thumb']; ?> " alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
													<input type="hidden" name="onepagecheckout_manage[delivery_method][<?php echo $delivery_method['code']; ?>][image]" value="<?php echo isset($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['image']) ? $onepagecheckout_manage['delivery_method'][$delivery_method['code']]['image'] : ''; ?>" id="input-logo<?php echo $delivery_method['code']; ?>" />
												</div>
											</td>
											<td class="text-right">
												<div class="col-md-12">
													<div class="form-group">
														<div class="btn-group" data-toggle="buttons">
															<?php if(!isset($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['status'])) {
																	$onepagecheckout_manage['delivery_method'][$delivery_method['code']]['status'] = 'register';
																} 
															?>
															<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['status'])) { ?> active <?php } ?>">	
																<input type="radio"  name="onepagecheckout_manage[delivery_method][<?php echo $delivery_method['code']; ?>][status]" <?php if(!empty($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
															</label>
															<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['status'])) { ?> active <?php } ?>">
																<input type="radio"  name="onepagecheckout_manage[delivery_method][<?php echo $delivery_method['code']; ?>][status]" <?php if(empty($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['status'])) { ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
															</label>
														</div>
													</div>
												</div>
											</td>
										</tr>	
									<?php } ?>
									</tbody>
								</table>
								<?php } ?>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-payment_method-setting">
								<fieldset>
									<legend><?php echo $entry_payment_method; ?></legend>
									<div class="form-group">
									<label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="Default selected Payment Method"><?php echo $entry_payment_method; ?></span></label>
										<div class="col-md-5">
											<select class="form-control" name="onepagecheckout_default_payment">
											<?php foreach($payment_methods as $payment_method){
												if($payment_method['code']==$onepagecheckout_default_payment){
													$select ="selected=selected";
												}else{
													$select ='';
												}	
											?>
											  <option <?php echo $select; ?> value="<?php  echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></option>
											<?php } ?>
											</select>
										</div>
									</div>
									<!--26-01-2017-->
									<div class="form-group">
										<label class="col-sm-2 control-label">Icon W x H</label>
										 <div class="col-sm-3">
											<input class="form-control" type="text" name="onepagecheckout_payment_method_width" value="<?php echo $onepagecheckout_payment_method_width; ?>"/>
										  </div>
										 <div class="col-sm-3">
											<input class="form-control" type="text" name="onepagecheckout_payment_method_height" value="<?php echo $onepagecheckout_payment_method_height; ?>"/>
										  </div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Reload Carts When customer choose payment option">Reload Cart</span></label>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_payment_method_load_cart)) { ?> active <?php } ?>">	
												<input type="radio"  name="onepagecheckout_payment_method_load_cart" <?php if(!empty($onepagecheckout_payment_method_load_cart)) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_payment_method_load_cart)) { ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_payment_method_load_cart" <?php if(empty($onepagecheckout_payment_method_load_cart)) { ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
									<!--26-01-2017-->
									<?php if($payment_methods) { ?>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="text-left"><?php echo $entry_payment_method; ?></th>
												<th class="text-center"><?php echo $entry_image; ?></th>
												<th class="text-right"><?php echo $entry_status; ?></th>
											</tr>
										</thead>
										<tbody>
										<?php foreach($payment_methods as $payment_method){ ?>
										<tr>
											<td class="text-left"><?php echo $payment_method['title']; ?></td>
											<td class="text-center">
												<div class="col-sm-12"><a href="" id="thumb-logo<?php echo $payment_method['code']; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $payment_method['thumb']; ?> " alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
													<input type="hidden" name="onepagecheckout_manage[payment_method][<?php echo $payment_method['code']; ?>][image]" value="<?php echo isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['image']) ? $onepagecheckout_manage['payment_method'][$payment_method['code']]['image'] : ''; ?>" id="input-logo<?php echo $payment_method['code']; ?>" />
												</div>
											</td>
											<td class="text-right">
												<div class="col-md-12">
													<div class="form-group">
													<?php 
													if(!isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['status'])) { 
															$onepagecheckout_manage['payment_method'][$payment_method['code']]['status'] = '1';
														}	?>
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['status']) && $onepagecheckout_manage['payment_method'][$payment_method['code']]['status']=='1'){ ?> active <?php } ?>" >	
																<input type="radio"  name="onepagecheckout_manage[payment_method][<?php echo $payment_method['code']; ?>][status]" <?php if(isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['status']) && $onepagecheckout_manage['payment_method'][$payment_method['code']]['status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
															</label>
															
															<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['status']) || $onepagecheckout_manage['payment_method'][$payment_method['code']]['status']=='0'){ ?> active <?php } ?>">
																<input type="radio"  name="onepagecheckout_manage[payment_method][<?php echo $payment_method['code']; ?>][status]" <?php if(!isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['status']) ){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
															</label>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php } ?>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-delivery-date-time">
							<div class="form-group">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Enable or Disabled Delivery Date & Time">Status</span></label>
								<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_delivery_status)) { ?> active <?php } ?>">	
										<input type="radio"  name="onepagecheckout_delivery_status" <?php if(!empty($onepagecheckout_delivery_status)) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
									</label>
									<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_delivery_status)) { ?> active <?php } ?>">
										<input type="radio"  name="onepagecheckout_delivery_status" <?php if(empty($onepagecheckout_delivery_status)) { ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Required Delivery Date & Time">Required Delivery</span></label>
								<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_delivery_required)) { ?> active <?php } ?>">	
										<input type="radio"  name="onepagecheckout_delivery_required" <?php if(!empty($onepagecheckout_delivery_required)) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
									</label>
									<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_delivery_required)) { ?> active <?php } ?>">
										<input type="radio"  name="onepagecheckout_delivery_required" <?php if(empty($onepagecheckout_delivery_required)) { ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
									</label>
								</div>
							</div>
							<div class="form-group">
							  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Availability Days Start From Current Date to next end days">Availability Days</span></label>
							  <div class="col-sm-10">
								  <div class="col-sm-6">
										<div class="input-group"><input class="form-control" type="text" name="onepagecheckout_delivery_start_days" value="<?php echo $onepagecheckout_delivery_start_days; ?>"/><span class="input-group-addon">Start X Days</span></div>
								  </div>
								  <div class="col-sm-6">
									<div class="input-group"><input class="form-control" type="text" name="onepagecheckout_delivery_end_days" value="<?php echo $onepagecheckout_delivery_end_days; ?>"/><span class="input-group-addon">End X Days</span></div>
								  </div>
							  </div>
							</div>
							<div class="form-group">
							   <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Disabled Delivery Dates <br/> Note: Date Format Should be YYYY-MM-DD,YYYY-MM-DD">Disabled Delivery Dates</span></label>
							   <div class="col-sm-10">
								 <textarea class="form-control" name="onepagecheckout_delivery_disable_days"><?php echo $onepagecheckout_delivery_disable_days; ?></textarea>
							   </div>
							</div>
							<div class="form-group">
							   <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Select Weekend">Weekends</span></label>
							   <div class="col-sm-10">
								 <select multiple class="form-control" name="onepagecheckout_delivery_weekend[]">
									<?php foreach($weeks as $key => $week){
										if(in_array($key,$onepagecheckout_delivery_weekend)){				
									?>
									<option selected="selected" value="<?php echo $key; ?>"><?php echo $week; ?></option>
									<?php }else{ ?>
									<option value="<?php echo $key; ?>"><?php echo $week; ?></option>
									<?php } ?>
									<?php } ?>
								 </select>
							   </div>
							</div>
						</div>
						<div class="tab-pane" id="tab-confirm_order-setting">
							<fieldset>
								<legend><?php echo $entry_confirm_order; ?></legend>
								<div class="form-group">
								<label class="col-md-2 control-label"><span data-toggle="tooltip" title="" data-original-title="Confirm Order auto trigger">Auto Trigger Confirm Button</span></label>
								<div class="col-sm-10">
									<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_autotrigger)){ ?> active <?php } ?>">	
												<input type="radio"  name="onepagecheckout_autotrigger" <?php if(!empty($onepagecheckout_autotrigger)){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php if(empty($onepagecheckout_autotrigger)){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_autotrigger" <?php if(empty($onepagecheckout_autotrigger)){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
									</div>
									<hr/>
									<div style="font-size:14px;" class="alert alert-info">
										Auto Trigger feature is very appropriate for one-page checkout extension. In Default process, Customer has to click on checkout button and then confirm it again but auto trigger undoubtedly triggers without clicking the second confirm button. The customer just needs to select the payment method and click the checkout button. Order will be placed automatically
									</div>
								</div>
							  </div>
							  <div class="form-group">
								<label class="col-sm-2 control-label " for="input-autotrigger"><span data-toggle="tooltip" title="" data-original-title="Choose payment methods which you want apply auto trigger condition.">Auto Trigger Payments</span></label>
								<div class="col-sm-9">
									<select class="form-control" multiple name="onepagecheckout_trigger_payment_method[]">
										<?php foreach($payment_methods as $payment_method){ ?>
										<?php if(in_array($payment_method['code'],$onepagecheckout_trigger_payment_method)){ ?>
										<option selected="selected" value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></option>
										<?php }else{ ?>
										<option value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							  </div>
							  <div class="form-group">	
									<label class="col-sm-2 control-label " for="input-terms">
									<span data-toggle="tooltip" title="" data-original-title="Forces people to agree to terms before a customer can checkout."><?php echo $entry_agree; ?></span></label>
									<div class="col-sm-3">
										<select name="onepagecheckout_manage[confirm_order][terms]" class="form-control">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
											<?php if (isset($onepagecheckout_manage['confirm_order']['terms']) && $information['information_id'] == $onepagecheckout_manage['confirm_order']['terms']) { ?>
											<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group register_newsletter">
									<label class="col-sm-2 control-label" for="input-checkout-term-auto"><span data-toggle="tooltip" title="" data-original-title="Auto Checked Checkout Terms">Auto Checked </span></label>
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<?php 
											if(!isset($onepagecheckout_manage['confirm_order']['checkout_terms'])){
												$onepagecheckout_manage['confirm_order']['checkout_terms'] = '1';
											}
											?>
											<label class="btn btn-success btn-sm <?php echo (!empty($onepagecheckout_manage['confirm_order']['checkout_terms'])) ? 'active' : ''; ?>">	
												<input type="radio"  name="onepagecheckout_manage[confirm_order][checkout_terms]" <?php echo (!empty($onepagecheckout_manage['confirm_order']['checkout_terms'])) ? 'checked="checked"' : ''; ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>											
											<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['confirm_order']['checkout_terms'])) ? 'active' : ''; ?>">
												<input type="radio" name="onepagecheckout_manage[confirm_order][checkout_terms]" <?php echo (empty($onepagecheckout_manage['confirm_order']['checkout_terms'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-order-commet-status"><span data-toggle="tooltip" title="" data-original-title="Enable Order Comment Section"><?php echo $entry_comment_status; ?></span></label>
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<?php 
											if(!isset($onepagecheckout_manage['confirm_order']['comment_status'])) { 
												$onepagecheckout_manage['confirm_order']['comment_status'] = '1';
											}
											?>
											<label class="btn btn-success btn-sm  <?php if(!empty($onepagecheckout_manage['confirm_order']['comment_status'])) { ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[confirm_order][comment_status]" <?php if(!empty($onepagecheckout_manage['confirm_order']['comment_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>										
											<label class="btn btn-success btn-sm  <?php if(empty($onepagecheckout_manage['confirm_order']['comment_status']) || $onepagecheckout_manage['confirm_order']['comment_status'] =='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[confirm_order][comment_status]" <?php if(!isset($onepagecheckout_manage['confirm_order']['comment_status']) || $onepagecheckout_manage['confirm_order']['comment_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-require-status"><span data-toggle="tooltip" title="" data-original-title="Forces people to add Order Comments"><?php echo $entry_require_comment_status; ?></span></label>
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<?php 
											if(!isset($onepagecheckout_manage['confirm_order']['require_comment_status'])) { 
												$onepagecheckout_manage['confirm_order']['require_comment_status'] = '1';
											}
											?>
											<label class="btn btn-success btn-sm  <?php if(!empty($onepagecheckout_manage['confirm_order']['require_comment_status'])) { ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[confirm_order][require_comment_status]" <?php if(!empty($onepagecheckout_manage['confirm_order']['require_comment_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>										
											<label class="btn btn-success btn-sm  <?php if(empty($onepagecheckout_manage['confirm_order']['require_comment_status']) || $onepagecheckout_manage['confirm_order']['require_comment_status'] =='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[confirm_order][require_comment_status]" <?php if(!isset($onepagecheckout_manage['confirm_order']['require_comment_status']) || $onepagecheckout_manage['confirm_order']['require_comment_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-shopping-button"><span data-toggle="tooltip" title="" data-original-title="Enable Continue Shopping Button in Checkout page"><?php echo $entry_shopping_button; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['confirm_order']['shopping_button_status'])) { 
												$onepagecheckout_manage['confirm_order']['shopping_button_status'] = '1';
											} ?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['confirm_order']['shopping_button_status'])) { ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[confirm_order][shopping_button_status]" <?php if(!empty($onepagecheckout_manage['confirm_order']['shopping_button_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>										
											<label class="btn btn-success btn-sm  <?php if(empty($onepagecheckout_manage['confirm_order']['shopping_button_status']) || $onepagecheckout_manage['confirm_order']['shopping_button_status'] =='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[confirm_order][shopping_button_status]" <?php if(!isset($onepagecheckout_manage['confirm_order']['shopping_button_status']) || $onepagecheckout_manage['confirm_order']['shopping_button_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-social_login">
						<fieldset>
								<legend><?php echo $entry_facebook_login; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $entry_shopping_cart_order ; ?>" ><?php echo $entry_status; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['social_login']['facebook_login_status'])) { 
												$onepagecheckout_manage['social_login']['facebook_login_status'] = '1';
											} ?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['social_login']['facebook_login_status']) && $onepagecheckout_manage['social_login']['facebook_login_status']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[social_login][facebook_login_status]" <?php if(!empty($onepagecheckout_manage['social_login']['facebook_login_status']) && $onepagecheckout_manage['social_login']['facebook_login_status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['social_login']['facebook_login_status']) || $onepagecheckout_manage['social_login']['facebook_login_status']=='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[social_login][facebook_login_status]" <?php if(empty($onepagecheckout_manage['social_login']['facebook_login_status']) || $onepagecheckout_manage['social_login']['facebook_login_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Add Redirect URL">Redirect URL</span></label>
								<div class="col-sm-7">
								<b><?php echo $callback; ?></b>
									<input class="form-control" type="hidden" name="onepagecheckout_facebook_callback" value="<?php echo $callback; ?>"/>
									
								</div>
							</div>
								<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Add Facebook App ID">APP ID</span></label>
								<div class="col-sm-3">
									<input class="form-control" type="text" name="onepagecheckout_facebook_appid" value="<?php echo $onepagecheckout_facebook_appid; ?>" placeholder="App ID"/>
								</div>
								<div style="float:left; margin-top:9px; margin-left:5px;"><a target="_new" href="https://developers.facebook.com/">Click Here</a> to create new facebook app. </div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Add Facebook Secret ID">App Secret </span></label>
								<div class="col-sm-3">
									<input class="form-control" type="text" name="onepagecheckout_facebook_key" value="<?php echo $onepagecheckout_facebook_key; ?>" placeholder="App Secret"/>
								</div>
							</div>
							<legend><?php echo $entry_google_login; ?></legend>
							<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $entry_shopping_cart_order ; ?>" ><?php echo $entry_status; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['social_login']['google_login_status'])) { 
												$onepagecheckout_manage['social_login']['google_login_status'] = '1';
											} ?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['social_login']['google_login_status']) && $onepagecheckout_manage['social_login']['google_login_status']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[social_login][google_login_status]" <?php if(!empty($onepagecheckout_manage['social_login']['google_login_status']) && $onepagecheckout_manage['social_login']['google_login_status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['social_login']['google_login_status']) || $onepagecheckout_manage['social_login']['google_login_status']=='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[social_login][google_login_status]" <?php if(empty($onepagecheckout_manage['social_login']['google_login_status']) || $onepagecheckout_manage['social_login']['google_login_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Add Redirect URL">Redirect URL</span></label>
								<div class="col-sm-7">
								<b><?php echo $googlecallback; ?></b>
									<input class="form-control" type="hidden" name="onepagecheckout_google_callback" value="<?php echo $googlecallback; ?>"/>
									
								</div>
							</div>
								<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Add Google App ID">APP ID</span></label>
								<div class="col-sm-3">
									<input class="form-control" type="text" name="onepagecheckout_google_appid" value="<?php echo $onepagecheckout_google_appid; ?>" placeholder="App ID"/>
								</div>
								<div style="float:left; margin-top:9px; margin-left:5px;"><a target="_new" href="https://console.cloud.google.com/">Click Here</a> to create new Google app. </div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="" data-original-title="Add Google Secret ID">App Secret </span></label>
								<div class="col-sm-3">
									<input class="form-control" type="text" name="onepagecheckout_google_key" value="<?php echo $onepagecheckout_google_key; ?>" placeholder="App Secret"/>
								</div>
							</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-shopping-cart-setting">
							<fieldset>
								<legend><?php echo $entry_shopping_cart; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $entry_shopping_cart_order ; ?>" ><?php echo $entry_status; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['shopping_cart']['shopping_cart_status'])) { 
												$onepagecheckout_manage['shopping_cart']['shopping_cart_status'] = '1';
											} ?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['shopping_cart_status']) && $onepagecheckout_manage['shopping_cart']['shopping_cart_status']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[shopping_cart][shopping_cart_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['shopping_cart_status']) && $onepagecheckout_manage['shopping_cart']['shopping_cart_status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['shopping_cart_status']) || $onepagecheckout_manage['shopping_cart']['shopping_cart_status']=='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[shopping_cart][shopping_cart_status]" <?php if(empty($onepagecheckout_manage['shopping_cart']['shopping_cart_status']) || $onepagecheckout_manage['shopping_cart']['shopping_cart_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-weight"><span  data-toggle="tooltip" title="<?php echo $entry_show_weight ; ?>" ><?php echo $show_weight; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['shopping_cart']['show_product_weight'])) { 
												$onepagecheckout_manage['shopping_cart']['show_product_weight'] = '1';
											} ?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_weight']) && $onepagecheckout_manage['shopping_cart']['show_product_weight']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_weight]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['show_product_weight']) && $onepagecheckout_manage['shopping_cart']['show_product_weight']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_weight']) || $onepagecheckout_manage['shopping_cart']['show_product_weight']=='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_weight]" <?php if(empty($onepagecheckout_manage['shopping_cart']['show_product_weight']) || $onepagecheckout_manage['shopping_cart']['show_product_weight']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-qnty_update"><span  data-toggle="tooltip" title="<?php echo $entry_show_qnty_update ; ?>"><?php echo $show_qnty_update; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['shopping_cart']['show_product_qnty_update'])) { 
												$onepagecheckout_manage['shopping_cart']['show_product_qnty_update'] = '1';
											} ?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_qnty_update']) && $onepagecheckout_manage['shopping_cart']['show_product_qnty_update']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_qnty_update]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_qnty_update']) && $onepagecheckout_manage['shopping_cart']['show_product_qnty_update']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_qnty_update']) || $onepagecheckout_manage['shopping_cart']['show_product_qnty_update']=='0'){ ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_qnty_update]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_qnty_update']) || $onepagecheckout_manage['shopping_cart']['show_product_qnty_update']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-image_width"><?php echo $show_image_width; ?></label>
									<?php 
										if(!isset($onepagecheckout_manage['shopping_cart']['show_product_image_width'])) { 
												$onepagecheckout_manage['shopping_cart']['show_product_image_width'] = '50';
											}
										if(!isset($onepagecheckout_manage['shopping_cart']['show_product_image_height'])) { 
												$onepagecheckout_manage['shopping_cart']['show_product_image_height'] = '50';
											}	
											?>
									<div class="col-sm-3">
										<input type="text"  value="<?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_image_width'])){ echo $onepagecheckout_manage['shopping_cart']['show_product_image_width']; } ?>"  name="onepagecheckout_manage[shopping_cart][show_product_image_width]" class="form-control" Placeholder="<?php  echo $entry_width; ?>"; >
									</div>
									<div class="col-sm-3">
										<input type="text"  value="<?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_image_height'])){ echo $onepagecheckout_manage['shopping_cart']['show_product_image_height']; } ?>"  name="onepagecheckout_manage[shopping_cart][show_product_image_height]" class="form-control" Placeholder="<?php  echo $entry_height; ?>"; >
									</div>
								</div>
								<h3>Desktop View</h3>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="text-left"><?php echo $entry_show_image; ?></th>
											<th class="text-center"><?php echo $entry_show_product_name; ?></th>
											<th class="text-center"><?php echo $entry_show_model; ?></th>
											<th class="text-center"><?php echo $entry_show_quantity; ?></th>
											<th class="text-center"><?php echo $entry_show_unit_price; ?></th>
											<th class="text-right"><?php echo $entry_show_total_price; ?></th>
											<th class="text-center hide">Colspan</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['show_product_image'])) { 
															$onepagecheckout_manage['shopping_cart']['show_product_image'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_image']) && $onepagecheckout_manage['shopping_cart']['show_product_image']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_image]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_image']) && $onepagecheckout_manage['shopping_cart']['show_product_image']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_image']) || $onepagecheckout_manage['shopping_cart']['show_product_image']=='0') { ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_image]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_image']) || $onepagecheckout_manage['shopping_cart']['show_product_image']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['show_product_title'])) { 
															$onepagecheckout_manage['shopping_cart']['show_product_title'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_title']) && $onepagecheckout_manage['shopping_cart']['show_product_title']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_title]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_title']) && $onepagecheckout_manage['shopping_cart']['show_product_title']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_title']) || $onepagecheckout_manage['shopping_cart']['show_product_title']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_title]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_title']) || $onepagecheckout_manage['shopping_cart']['show_product_title']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['show_product_model'])) { 
															$onepagecheckout_manage['shopping_cart']['show_product_model'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_model']) && $onepagecheckout_manage['shopping_cart']['show_product_model']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_model]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_model']) && $onepagecheckout_manage['shopping_cart']['show_product_model']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_model']) || $onepagecheckout_manage['shopping_cart']['show_product_model']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_model]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_model']) || $onepagecheckout_manage['shopping_cart']['show_product_model']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['show_product_quantity'])) { 
															$onepagecheckout_manage['shopping_cart']['show_product_quantity'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_quantity']) && $onepagecheckout_manage['shopping_cart']['show_product_quantity']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_quantity]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_quantity']) && $onepagecheckout_manage['shopping_cart']['show_product_quantity']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_quantity']) || $onepagecheckout_manage['shopping_cart']['show_product_quantity']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_quantity]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_quantity']) || $onepagecheckout_manage['shopping_cart']['show_product_quantity']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['show_product_unit'])) { 
															$onepagecheckout_manage['shopping_cart']['show_product_unit'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_unit']) && $onepagecheckout_manage['shopping_cart']['show_product_unit']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_unit]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_unit']) && $onepagecheckout_manage['shopping_cart']['show_product_unit']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_unit']) || $onepagecheckout_manage['shopping_cart']['show_product_unit']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_unit]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_unit']) || $onepagecheckout_manage['shopping_cart']['show_product_unit']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-right">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['show_product_total_price'])) { 
															$onepagecheckout_manage['shopping_cart']['show_product_total_price'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_total_price']) && $onepagecheckout_manage['shopping_cart']['show_product_total_price']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_total_price]" <?php if(isset($onepagecheckout_manage['shopping_cart']['show_product_total_price']) && $onepagecheckout_manage['shopping_cart']['show_product_total_price']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_total_price']) || $onepagecheckout_manage['shopping_cart']['show_product_total_price']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][show_product_total_price]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['show_product_total_price']) || $onepagecheckout_manage['shopping_cart']['show_product_total_price']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="hide"><input class="form-control" type="text" name="onepagecheckout_manage[shopping_cart][colspan]" value="<?php echo isset($onepagecheckout_manage['shopping_cart']['colspan']) ?  $onepagecheckout_manage['shopping_cart']['colspan'] : '6' ;?>"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
							<fieldset>
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-left"><?php echo $enty_module_name; ?></td>
											<th class="text-center"><?php echo $entry_logged; ?></th>
											<th class="text-center"><?php echo $entry_register_account; ?></th>
											<th class="text-right"><?php echo $entry_guest_order; ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left"><?php echo $entry_coupon; ?></td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['coupon_login_status'])) { 
															$onepagecheckout_manage['shopping_cart']['coupon_login_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['coupon_login_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][coupon_login_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['coupon_login_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['coupon_login_status']) || $onepagecheckout_manage['shopping_cart']['coupon_login_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][coupon_login_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['coupon_login_status']) || $onepagecheckout_manage['shopping_cart']['coupon_login_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>	
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['coupon_register_status'])) { 
															$onepagecheckout_manage['shopping_cart']['coupon_register_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['coupon_register_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][coupon_register_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['coupon_register_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['coupon_register_status']) || $onepagecheckout_manage['shopping_cart']['coupon_register_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][coupon_register_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['coupon_register_status']) || $onepagecheckout_manage['shopping_cart']['coupon_register_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-right">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['coupon_guest_status'])) { 
															$onepagecheckout_manage['shopping_cart']['coupon_guest_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['coupon_guest_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][coupon_guest_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['coupon_guest_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['coupon_guest_status']) || $onepagecheckout_manage['shopping_cart']['coupon_guest_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][coupon_guest_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['coupon_guest_status']) || $onepagecheckout_manage['shopping_cart']['coupon_guest_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td class="text-left"><?php echo $entry_reward ?></td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['reward_login_status'])) { 
															$onepagecheckout_manage['shopping_cart']['reward_login_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['reward_login_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][reward_login_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['reward_login_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['reward_login_status']) || $onepagecheckout_manage['shopping_cart']['reward_login_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][reward_login_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['reward_login_status']) || $onepagecheckout_manage['shopping_cart']['reward_login_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>	
											<td class="text-center">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['reward_register_status'])) { 
															$onepagecheckout_manage['shopping_cart']['reward_register_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['reward_register_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][reward_register_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['reward_register_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['reward_register_status']) || $onepagecheckout_manage['shopping_cart']['reward_register_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][reward_register_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['reward_register_status']) || $onepagecheckout_manage['shopping_cart']['reward_register_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-right">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['reward_guest_status'])) { 
															$onepagecheckout_manage['shopping_cart']['reward_guest_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['reward_guest_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][reward_guest_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['reward_guest_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['reward_guest_status']) || $onepagecheckout_manage['shopping_cart']['reward_guest_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][reward_guest_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['reward_guest_status']) || $onepagecheckout_manage['shopping_cart']['reward_guest_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td class="text-left"><?php echo $entry_voucher; ?></td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['voucher_login_status'])) { 
															$onepagecheckout_manage['shopping_cart']['voucher_login_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['voucher_login_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][voucher_login_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['voucher_login_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['voucher_login_status']) || $onepagecheckout_manage['shopping_cart']['voucher_login_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][voucher_login_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['voucher_login_status']) || $onepagecheckout_manage['shopping_cart']['voucher_login_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>	
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['voucher_register_status'])) { 
															$onepagecheckout_manage['shopping_cart']['voucher_register_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['voucher_register_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][voucher_register_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['voucher_register_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['voucher_register_status']) || $onepagecheckout_manage['shopping_cart']['voucher_register_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][voucher_register_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['voucher_register_status']) || $onepagecheckout_manage['shopping_cart']['voucher_register_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-right">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart']['voucher_guest_status'])) { 
															$onepagecheckout_manage['shopping_cart']['voucher_guest_status'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm <?php if(!empty($onepagecheckout_manage['shopping_cart']['voucher_guest_status'])) { ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart][voucher_guest_status]" <?php if(!empty($onepagecheckout_manage['shopping_cart']['voucher_guest_status'])) { ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart']['voucher_guest_status']) || $onepagecheckout_manage['shopping_cart']['voucher_guest_status']=='0') { ?> active <?php } ?>">
															<input type="radio" name="onepagecheckout_manage[shopping_cart][voucher_guest_status]" <?php if(!isset($onepagecheckout_manage['shopping_cart']['voucher_guest_status']) || $onepagecheckout_manage['shopping_cart']['voucher_guest_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								
								<h3>Mobile View</h3>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="text-left"><?php echo $entry_show_image; ?></th>
											<th class="text-center"><?php echo $entry_show_product_name; ?></th>
											<th class="text-center"><?php echo $entry_show_model; ?></th>
											<th class="text-center"><?php echo $entry_show_quantity; ?></th>
											<th class="text-center"><?php echo $entry_show_unit_price; ?></th>
											<th class="text-right"><?php echo $entry_show_total_price; ?></th>
											<th class="hide text-center">Colspan</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_image'])) { 
															$onepagecheckout_manage['shopping_cart_mb']['show_product_image'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_image']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_image']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_image]" <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_image']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_image']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_image']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_image']=='0') { ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_image]" <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_image']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_image']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_title'])) { 
															$onepagecheckout_manage['shopping_cart_mb']['show_product_title'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_title']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_title']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_title]" <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_title']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_title']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_title']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_title']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_title]" <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_title']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_title']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_model'])) { 
															$onepagecheckout_manage['shopping_cart_mb']['show_product_model'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_model']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_model']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_model]" <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_model']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_model']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_model']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_model']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_model]" <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_model']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_model']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_quantity'])) { 
															$onepagecheckout_manage['shopping_cart_mb']['show_product_quantity'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_quantity]" <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_quantity]" <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-center">
												<div class="col-sm-12">
												<?php 
													if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_unit'])) { 
															$onepagecheckout_manage['shopping_cart_mb']['show_product_unit'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_unit']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_unit']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_unit]" <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_unit']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_unit']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_unit']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_unit']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_unit]" <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_unit']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_unit']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="text-right">
												<div class="col-sm-12">
													<?php 
													if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_total_price'])) { 
															$onepagecheckout_manage['shopping_cart_mb']['show_product_total_price'] = '1';
														} ?>
													<div class="btn-group" data-toggle="buttons">
														<label class="btn btn-success btn-sm  <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']=='1'){ ?> active <?php } ?>" >	
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_total_price]" <?php if(isset($onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']) && $onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
														</label>
														
														<label class="btn btn-success btn-sm  <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']=='0'){ ?> active <?php } ?>">
															<input type="radio"  name="onepagecheckout_manage[shopping_cart_mb][show_product_total_price]" <?php if(!isset($onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']) || $onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
														</label>
													</div>
												</div>
											</td>
											<td class="hide"><input class="form-control" type="text" name="onepagecheckout_manage[shopping_cart_mb][colspan]" value="<?php echo isset($onepagecheckout_manage['shopping_cart_mb']['colspan']) ?  $onepagecheckout_manage['shopping_cart_mb']['colspan'] : '6' ;?>"/></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-personaldetails-setting">
							<fieldset>
								<legend><?php echo $entry_personaldetails; ?></legend>
								
								<div class="form-group register_newsletter">
									<label class="col-sm-2 control-label" for="input-newsletter"><?php echo $entry_newsletter; ?></label>
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<?php 
											if(!isset($onepagecheckout_manage['personaldetails']['newsletter_status'])) { 
												$onepagecheckout_manage['personaldetails']['newsletter_status'] = '1';
											}
											?>
											<label class="btn btn-success btn-sm <?php echo (!empty($onepagecheckout_manage['personaldetails']['newsletter_status'])) ? 'active' : ''; ?>">	
												<input type="radio"  name="onepagecheckout_manage[personaldetails][newsletter_status]" <?php echo (!empty($onepagecheckout_manage['personaldetails']['newsletter_status'])) ? 'checked="checked"' : ''; ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>											
											<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['personaldetails']['newsletter_status'])) ? 'active' : ''; ?>">
												<input type="radio" name="onepagecheckout_manage[personaldetails][newsletter_status]" <?php echo (empty($onepagecheckout_manage['personaldetails']['newsletter_status'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">	
									<label class="col-sm-2 control-label " for="input-terms"><span data-toggle="tooltip" title="" data-original-title="Forces people to agree to terms before an account can be created.">Account Terms</span></label>
									<div class="col-sm-3">
										<select name="onepagecheckout_manage[personaldetails][terms]" class="form-control">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
											<?php if (isset($onepagecheckout_manage['personaldetails']['terms']) && $information['information_id'] == $onepagecheckout_manage['personaldetails']['terms']) { ?>
											<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group register_newsletter">
									<label class="col-sm-2 control-label" for="input-checkout-term-auto"><span data-toggle="tooltip" title="" data-original-title="Auto Checked account Terms">Auto Checked </span></label>
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<?php 
											if(!isset($onepagecheckout_manage['personaldetails']['account_terms'])){
												$onepagecheckout_manage['personaldetails']['account_terms'] = '1';
											}
											?>
											<label class="btn btn-success btn-sm <?php echo (!empty($onepagecheckout_manage['personaldetails']['account_terms'])) ? 'active' : ''; ?>">	
												<input type="radio"  name="onepagecheckout_manage[personaldetails][account_terms]" <?php echo (!empty($onepagecheckout_manage['personaldetails']['account_terms'])) ? 'checked="checked"' : ''; ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>											
											<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['personaldetails']['account_terms'])) ? 'active' : ''; ?>">
												<input type="radio" name="onepagecheckout_manage[personaldetails][account_terms]" <?php echo (empty($onepagecheckout_manage['personaldetails']['account_terms'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table id="table-personal" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-left"><?php echo $entry_field_name ?></th>
												<th class="text-right"><?php echo $entry_status; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($onepagecheckout_manage['personaldetails']['fields'] as $key => $fields) { ?>
											<tr class="row-group">
												<td class="text-left">
													<i class="fa fa-arrows" aria-hidden="true"></i>
													<span><?php echo isset($onepagecheckout_manage['personaldetails']['fields'][$key]['label']) ? $onepagecheckout_manage['personaldetails']['fields'][$key]['label'] : ''; ?></span>
													<input type="hidden" name="onepagecheckout_manage[personaldetails][fields][<?php echo $key; ?>][sort_order]" value="<?php echo isset($onepagecheckout_manage['personaldetails']['fields'][$key]['sort_order']) ? $onepagecheckout_manage['personaldetails']['fields'][$key]['sort_order'] : '0'; ?>" class="form-control mydragsort" />
													<input type="hidden" name="onepagecheckout_manage[personaldetails][fields][<?php echo $key; ?>][label]" value="<?php echo isset($onepagecheckout_manage['personaldetails']['fields'][$key]['label']) ? $onepagecheckout_manage['personaldetails']['fields'][$key]['label'] : ''; ?>" class="form-control" />
												</td>
												<td class="text-right">
													<div class="btn-group" data-toggle="buttons">	
														<?php 
														if(!isset($onepagecheckout_manage['personaldetails']['fields'][$key]['show'])) { 
															$onepagecheckout_manage['personaldetails']['fields'][$key]['show'] = '2';
														}
														?>
														<label class="btn btn-success btn-sm <?php echo (isset($onepagecheckout_manage['personaldetails']['fields'][$key]['show']) && $onepagecheckout_manage['personaldetails']['fields'][$key]['show']=='1') ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[personaldetails][fields][<?php echo $key; ?>][show]" <?php echo (isset($onepagecheckout_manage['personaldetails']['fields'][$key]['show']) && $onepagecheckout_manage['personaldetails']['fields'][$key]['show']=='1') ? 'checked="checked"' : ''; ?> value="1" autocomplete="off" /><?php echo $text_yes; ?>
														</label>
														<label class="btn btn-success btn-sm <?php echo (isset($onepagecheckout_manage['personaldetails']['fields'][$key]['show']) && $onepagecheckout_manage['personaldetails']['fields'][$key]['show']=='2') ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[personaldetails][fields][<?php echo $key; ?>][show]" <?php echo (isset($onepagecheckout_manage['personaldetails']['fields'][$key]['show']) && $onepagecheckout_manage['personaldetails']['fields'][$key]['show']=='2') ? 'checked="checked"' : ''; ?> value="2" autocomplete="off" /><?php echo $entry_and_required; ?>
														</label>
													
														<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['personaldetails']['fields'][$key]['show'])) ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[personaldetails][fields][<?php echo $key; ?>][show]" <?php echo (empty($onepagecheckout_manage['personaldetails']['fields'][$key]['show'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off" /><?php echo $text_no; ?>
														</label>
														
													</div>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-payment-setting">
							<fieldset>
								<legend><?php echo $entry_payment_details_setting; ?></legend>
								<div class="form-group hide">
									<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="<?php echo $entry_payment_details_order; ?> "><?php echo $entry_status; ?></span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['payment_details']['payment_details_status'])){ 
											$onepagecheckout_manage['payment_details']['payment_details_status'] = '1';
										}	?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(isset($onepagecheckout_manage['payment_details']['payment_details_status']) && $onepagecheckout_manage['payment_details']['payment_details_status']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[payment_details][payment_details_status]" <?php if(isset($onepagecheckout_manage['payment_details']['payment_details_status']) && $onepagecheckout_manage['payment_details']['payment_details_status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm <?php if(!isset($onepagecheckout_manage['payment_details']['payment_details_status']) || $onepagecheckout_manage['payment_details']['payment_details_status']=='0') { ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[payment_details][payment_details_status]" <?php if(!isset($onepagecheckout_manage['payment_details']['payment_details_status']) || $onepagecheckout_manage['payment_details']['payment_details_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table id="table-payment_details" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-left"><?php echo $entry_field_name; ?></th>
												<th class="text-right"><?php echo $entry_status; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($onepagecheckout_manage['payment_details']['fields'] as $key => $fields) { ?>
											<tr class="row-group">
												<td class="text-left">
													<i class="fa fa-arrows" aria-hidden="true"></i>
													<span><?php echo isset($onepagecheckout_manage['payment_details']['fields'][$key]['label']) ? $onepagecheckout_manage['payment_details']['fields'][$key]['label'] : ''; ?></span>
													<input type="hidden" name="onepagecheckout_manage[payment_details][fields][<?php echo $key; ?>][sort_order]" value="<?php echo isset($onepagecheckout_manage['payment_details']['fields'][$key]['sort_order']) ? $onepagecheckout_manage['payment_details']['fields'][$key]['sort_order'] : '0'; ?>" class="form-control mydragsort" />
													<input type="hidden" name="onepagecheckout_manage[payment_details][fields][<?php echo $key; ?>][label]" value="<?php echo isset($onepagecheckout_manage['payment_details']['fields'][$key]['label']) ? $onepagecheckout_manage['payment_details']['fields'][$key]['label'] : ''; ?>" class="form-control" />
												</td>
												<td class="text-right">
													<div class="btn-group" data-toggle="buttons">	
														<?php 
														if(!isset($onepagecheckout_manage['payment_details']['fields'][$key]['show'])) { 
															$onepagecheckout_manage['payment_details']['fields'][$key]['show'] = '2';
														}
														?>
														<label class="btn btn-success btn-sm <?php echo (isset($onepagecheckout_manage['payment_details']['fields'][$key]['show']) && $onepagecheckout_manage['payment_details']['fields'][$key]['show']=='1') ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[payment_details][fields][<?php echo $key; ?>][show]" <?php echo (isset($onepagecheckout_manage['payment_details']['fields'][$key]['show']) && $onepagecheckout_manage['payment_details']['fields'][$key]['show']=='1') ? 'checked="checked"' : ''; ?> value="1" autocomplete="off" /><?php echo $text_yes; ?>
														</label>
														<label class="btn btn-success btn-sm <?php echo (isset($onepagecheckout_manage['payment_details']['fields'][$key]['show']) && $onepagecheckout_manage['payment_details']['fields'][$key]['show']=='2') ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[payment_details][fields][<?php echo $key; ?>][show]" <?php echo (isset($onepagecheckout_manage['payment_details']['fields'][$key]['show']) && $onepagecheckout_manage['payment_details']['fields'][$key]['show']=='2') ? 'checked="checked"' : ''; ?> value="2" autocomplete="off" /><?php echo $entry_and_required; ?>
														</label>
														<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['payment_details']['fields'][$key]['show'])) ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[payment_details][fields][<?php echo $key; ?>][show]" <?php echo (empty($onepagecheckout_manage['payment_details']['fields'][$key]['show'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off" /><?php echo $text_no; ?>
														</label>		
													</div>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-delivery-setting">
							<fieldset>
								<legend><?php echo $entry_delivery_setting; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="With this feature, you can enable or disable the delivery address section.<br/> Note: When you will disable the delivery address then payment address and shipping address always same.">Delivery Address</span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['delivery']['delivery_status'])){ 
												$onepagecheckout_manage['delivery']['delivery_status'] = '1';
											}	?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(isset($onepagecheckout_manage['delivery']['delivery_status']) && $onepagecheckout_manage['delivery']['delivery_status']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[delivery][delivery_status]" <?php if(isset($onepagecheckout_manage['delivery']['delivery_status']) && $onepagecheckout_manage['delivery']['delivery_status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm <?php if(!isset($onepagecheckout_manage['delivery']['delivery_status']) || $onepagecheckout_manage['delivery']['delivery_status']=='0') { ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[delivery][delivery_status]" <?php if(!isset($onepagecheckout_manage['delivery']['delivery_status']) || $onepagecheckout_manage['delivery']['delivery_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status"><span  data-toggle="tooltip" title="My delivery and billing addresses are the same.">Auto Checked</span></label>
									<div class="col-sm-10">
										<?php 
										if(!isset($onepagecheckout_manage['delivery']['delivery_auto_status'])){ 
												$onepagecheckout_manage['delivery']['delivery_auto_status'] = '1';
											}	?>
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php if(isset($onepagecheckout_manage['delivery']['delivery_auto_status']) && $onepagecheckout_manage['delivery']['delivery_auto_status']=='1'){ ?> active <?php } ?>" >	
												<input type="radio"  name="onepagecheckout_manage[delivery][delivery_auto_status]" <?php if(isset($onepagecheckout_manage['delivery']['delivery_auto_status']) && $onepagecheckout_manage['delivery']['delivery_auto_status']=='1'){ ?> checked="checked" <?php } ?> value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											
											<label class="btn btn-success btn-sm <?php if(!isset($onepagecheckout_manage['delivery']['delivery_auto_status']) || $onepagecheckout_manage['delivery']['delivery_auto_status']=='0') { ?> active <?php } ?>">
												<input type="radio"  name="onepagecheckout_manage[delivery][delivery_auto_status]" <?php if(!isset($onepagecheckout_manage['delivery']['delivery_auto_status']) || $onepagecheckout_manage['delivery']['delivery_auto_status']=='0'){ ?> checked="checked" <?php } ?> value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table id="table-delivery" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-left"><?php echo $entry_field_name; ?></th>
												<th class="text-right"><?php echo $entry_status; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($onepagecheckout_manage['delivery']['fields'] as $key => $fields) { ?>
											<tr class="row-group">
												<td class="text-left">
													<i class="fa fa-arrows" aria-hidden="true"></i>
													<span><?php echo isset($onepagecheckout_manage['delivery']['fields'][$key]['label']) ? $onepagecheckout_manage['delivery']['fields'][$key]['label'] : ''; ?></span>
													<input type="hidden" name="onepagecheckout_manage[delivery][fields][<?php echo $key; ?>][sort_order]" value="<?php echo isset($onepagecheckout_manage['delivery']['fields'][$key]['sort_order']) ? $onepagecheckout_manage['delivery']['fields'][$key]['sort_order'] : '0'; ?>" class="form-control mydragsort" />
													<input type="hidden" name="onepagecheckout_manage[delivery][fields][<?php echo $key; ?>][label]" value="<?php echo isset($onepagecheckout_manage['delivery']['fields'][$key]['label']) ? $onepagecheckout_manage['delivery']['fields'][$key]['label'] : ''; ?>" class="form-control" />
												</td>
												<td class="text-right">
													<div class="btn-group" data-toggle="buttons">	
														<?php 
														if(!isset($onepagecheckout_manage['delivery']['fields'][$key]['show'])) { 
															$onepagecheckout_manage['delivery']['fields'][$key]['show'] = '2';
														}
														?>
														<label class="btn btn-success btn-sm <?php echo (isset($onepagecheckout_manage['delivery']['fields'][$key]['show']) && $onepagecheckout_manage['delivery']['fields'][$key]['show']=='1') ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[delivery][fields][<?php echo $key; ?>][show]" <?php echo (isset($onepagecheckout_manage['delivery']['fields'][$key]['show']) && $onepagecheckout_manage['delivery']['fields'][$key]['show']=='1') ? 'checked="checked"' : ''; ?> value="1" autocomplete="off" /><?php echo $text_yes; ?>
														</label>
														<label class="btn btn-success btn-sm <?php echo (isset($onepagecheckout_manage['delivery']['fields'][$key]['show']) && $onepagecheckout_manage['delivery']['fields'][$key]['show']=='2') ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[delivery][fields][<?php echo $key; ?>][show]" <?php echo (isset($onepagecheckout_manage['delivery']['fields'][$key]['show']) && $onepagecheckout_manage['delivery']['fields'][$key]['show']=='2') ? 'checked="checked"' : ''; ?> value="2" autocomplete="off" /><?php echo $entry_and_required; ?>
														</label>
														<label class="btn btn-success btn-sm <?php echo (empty($onepagecheckout_manage['delivery']['fields'][$key]['show'])) ? 'active' : ''; ?>">
														<input type="radio" name="onepagecheckout_manage[delivery][fields][<?php echo $key; ?>][show]" <?php echo (empty($onepagecheckout_manage['delivery']['fields'][$key]['show'])) ? 'checked="checked"' : ''; ?> value="0" autocomplete="off" /><?php echo $text_no; ?>
														</label>		
													</div>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>						
						<div class="tab-pane" id="tab-login-setting">
							<fieldset>
								<legend><?php echo $entry_login; ?></legend>
								
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-general-language-setting">
							<fieldset>
									<legend><?php echo $entry_general; ?></legend>
							</fieldset>
							<ul class="nav nav-tabs" id="general-language">
								<?php foreach ($languages as $language) { ?>
								<li>
									<a href="#checkout-language<?php echo $language['language_id']; ?>" data-toggle="tab">
										<?php if(VERSION >= '2.2.0.0') { ?>
										<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
										<?php } else { ?> 
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
										<?php } ?>
										<?php echo $language['name']; ?>
									</a>
								</li>
								<?php } ?>
							</ul>
							<div class="tab-content">
							<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="checkout-language<?php echo $language['language_id']; ?>">	
									<div class="form-group ">	
										<label class="col-sm-2 control-label " for="input-register-tab">
											Register Tab Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[general][register][<?php echo $language['language_id']; ?>]" placeholder="register tab name" value="<?php if(isset($onepagecheckout_manage['general']['register'][$language['language_id']])){ echo $onepagecheckout_manage['general']['register'][$language['language_id']]; } ?>">
											</div>
									</div>
									<div class="form-group ">	
										<label class="col-sm-2 control-label " for="input-guest">
											Guest Tab Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[general][guest][<?php echo $language['language_id']; ?>]" placeholder="guest tab name" value="<?php if(isset($onepagecheckout_manage['general']['guest'][$language['language_id']])){ echo $onepagecheckout_manage['general']['guest'][$language['language_id']]; } ?>">
											</div>
									</div>
									<div class="form-group">	
										<label class="col-sm-2 control-label " for="input-login">
											Login Tab Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[general][login][<?php echo $language['language_id']; ?>]" placeholder="login tab name" value="<?php if(isset($onepagecheckout_manage['general']['login'][$language['language_id']])){ echo $onepagecheckout_manage['general']['login'][$language['language_id']]; } ?>">
											</div>
									</div>
									<div class="form-group ">	
										<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_heading_title; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[general][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading_title; ?>" value="<?php if(isset($onepagecheckout_manage['general']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['general']['heading_title'][$language['language_id']]; } ?>">
											</div>
									</div>
									<div class="form-group ">	
										
											<label class="col-sm-2 control-label " for="input-description<?php echo $language['language_id']; ?>" >
											<?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea class="form-control summernote"  name="onepagecheckout_manage[general][description][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" >
												<?php if(isset($onepagecheckout_manage['general']['description'][$language['language_id']])){ echo $onepagecheckout_manage['general']['description'][$language['language_id']]; } ?>
												</textarea>
											</div>
									</div>
									<div class="form-group ">	
										
											<label class="col-sm-2 control-label " for="input-description_bottom<?php echo $language['language_id']; ?>" >
											<?php echo $entry_description_bottom; ?></label>
											<div class="col-sm-10">
												<textarea class="form-control summernote"  name="onepagecheckout_manage[general][description_bottom][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_description_bottom; ?>" id="input-description_bottom<?php echo $language['language_id']; ?>" >
												<?php if(isset($onepagecheckout_manage['general']['description_bottom'][$language['language_id']])){ echo $onepagecheckout_manage['general']['description_bottom'][$language['language_id']]; } ?>
												</textarea>
											</div>
									</div>
								</div>
							<?php } ?>
						</div>
						</div>
						<div class="tab-pane" id="tab-personaldetails-language-setting">
							<fieldset>
								<legend><?php echo $entry_personaldetails; ?></legend>
								<ul class="nav nav-tabs" id="checkout-personal">
									<?php foreach ($languages as $language) { ?>
									<li>
										<a href="#checkout-personal<?php echo $language['language_id']; ?>" data-toggle="tab">
											<?php if(VERSION >= '2.2.0.0') { ?>
											<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
											<?php } else { ?> 
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
											<?php } ?>
											<?php echo $language['name']; ?>
										</a>
									</li>
									<?php } ?>
								</ul>	
								<div class="tab-content">
									<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="checkout-personal<?php echo $language['language_id']; ?>">	
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[register][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['register']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['register']['heading_title'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th class="text-left"><?php echo $entry_field_name; ?></th>
														<th class="text-left"><?php echo $entry_label; ?></th>
														<th class="text-left"><?php echo $entry_placeholder; ?></th>
														<th class="text-left"><?php echo $entry_error; ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($onepagecheckout_manage['personaldetails']['fields'] as $key => $fields) { ?>
													<tr>
														<td class="text-left"><?php echo isset($onepagecheckout_manage['personaldetails']['fields'][$key]['label']) ? $onepagecheckout_manage['personaldetails']['fields'][$key]['label'] : ''; ?></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[register][<?php echo $key; ?>][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_label; ?>" value="<?php if(isset($onepagecheckout_manage['register'][$key]['label'][$language['language_id']])){ echo $onepagecheckout_manage['register'][$key]['label'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[register][<?php echo $key; ?>][placeholder][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_placeholder; ?>" value="<?php if(isset($onepagecheckout_manage['register'][$key]['placeholder'][$language['language_id']])){ echo $onepagecheckout_manage['register'][$key]['placeholder'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[register][<?php echo $key; ?>][error][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_error; ?>" value="<?php if(isset($onepagecheckout_manage['register'][$key]['error'][$language['language_id']])){ echo $onepagecheckout_manage['register'][$key]['error'][$language['language_id']]; } ?>"></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<?php }?>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-login-language-setting">
							<fieldset>
								<legend><?php echo $entry_login; ?></legend>
								<ul class="nav nav-tabs" id="checkout-language6">
									<?php foreach ($languages as $language) { ?>
									<li>
										<a href="#checkout-language6<?php echo $language['language_id']; ?>" data-toggle="tab">
											<?php if(VERSION >= '2.2.0.0') { ?>
											<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
											<?php } else { ?> 
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
											<?php } ?>
											<?php echo $language['name']; ?>
										</a>
									</li>
									<?php } ?>
								</ul>	
								<div class="tab-content">
									<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="checkout-language6<?php echo $language['language_id']; ?>">
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[login][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['login']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['login']['heading_title'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_btn_text; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[login][button_text][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_btn_text; ?>" value="<?php if(isset($onepagecheckout_manage['login']['button_text'][$language['language_id']])){ echo $onepagecheckout_manage['login']['button_text'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_wrong; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[login][wrong_message][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_wrong; ?>" value="<?php if(isset($onepagecheckout_manage['login']['wrong_message'][$language['language_id']])){ echo $onepagecheckout_manage['login']['wrong_message'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_approved_message; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[login][approved_message][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_approved_message; ?>" value="<?php if(isset($onepagecheckout_manage['login']['approved_message'][$language['language_id']])){ echo $onepagecheckout_manage['login']['approved_message'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th class="text-left"><?php echo $entry_field_name; ?></th>
														<th class="text-left"><?php echo $entry_label; ?></th>
														<th class="text-left"><?php echo $entry_placeholder; ?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-left"><?php echo $entry_email; ?></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[login][email][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_email_label; ?>" value="<?php if(isset($onepagecheckout_manage['login']['email']['label'][$language['language_id']])){ echo $onepagecheckout_manage['login']['email']['label'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[login][email][placeholder][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_email_placeholder; ?>" value="<?php if(isset($onepagecheckout_manage['login']['email']['placeholder'][$language['language_id']])){ echo $onepagecheckout_manage['login']['email']['placeholder'][$language['language_id']]; } ?>"></td>
													</tr>
													<tr>
														<td class="text-left"><?php echo $entry_password; ?></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[login][password][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_password_label; ?>" value="<?php if(isset($onepagecheckout_manage['login']['password']['label'][$language['language_id']])){ echo $onepagecheckout_manage['login']['password']['label'][$language['language_id']]; } ?>"></td>
														<td class="text-center"><input type="text" class="form-control" name="onepagecheckout_manage[login][password][placeholder][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_password_placeholder; ?>" value="<?php if(isset($onepagecheckout_manage['login']['password']['placeholder'][$language['language_id']])){ echo $onepagecheckout_manage['login']['password']['placeholder'][$language['language_id']]; } ?>"></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<?php } ?>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-delivery_detail-language-setting">
							<fieldset>
								<legend><?php echo $entry_delivery_setting; ?></legend>
								<ul class="nav nav-tabs" id="checkout-deliverydetail">
									<?php foreach ($languages as $language) { ?>
									<li>
										<a href="#checkout-deliverydetail<?php echo $language['language_id']; ?>" data-toggle="tab">
											<?php if(VERSION >= '2.2.0.0') { ?>
											<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
											<?php } else { ?> 
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
											<?php } ?>
											<?php echo $language['name']; ?>
										</a>
									</li>
								<?php } ?>
								</ul>
								<div class="tab-content">
									<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="checkout-deliverydetail<?php echo $language['language_id']; ?>">
										<div class="form-group ">	
											<label class="col-sm-2 control-label " for="input-heading"><?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[delivery_detail][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['delivery_detail']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['delivery_detail']['heading_title'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th class="text-left"><?php echo $entry_field_name; ?></th>
														<th class="text-left"><?php echo $entry_label; ?></th>
														<th class="text-left"><?php echo $entry_placeholder; ?></th>
														<th class="text-left"><?php echo $entry_error; ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($onepagecheckout_manage['delivery']['fields'] as $key => $fields) { ?>
													<tr>
														<td class="text-left"><?php echo isset($onepagecheckout_manage['delivery']['fields'][$key]['label']) ? $onepagecheckout_manage['delivery']['fields'][$key]['label'] : ''; ?></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[delivery_detail][<?php echo $key; ?>][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_label; ?>" value="<?php if(isset($onepagecheckout_manage['delivery_detail'][$key]['label'][$language['language_id']])){ echo $onepagecheckout_manage['delivery_detail'][$key]['label'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[delivery_detail][<?php echo $key; ?>][placeholder][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_placeholder; ?>" value="<?php if(isset($onepagecheckout_manage['delivery_detail'][$key]['placeholder'][$language['language_id']])){ echo $onepagecheckout_manage['delivery_detail'][$key]['placeholder'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[delivery_detail][<?php echo $key; ?>][error][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_error; ?>" value="<?php if(isset($onepagecheckout_manage['delivery_detail'][$key]['error'][$language['language_id']])){ echo $onepagecheckout_manage['delivery_detail'][$key]['error'][$language['language_id']]; } ?>"></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<?php } ?>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-paymentdetails-language-setting">
							<fieldset>
								<legend><?php echo $entry_payment_details_setting; ?></legend>
								<ul class="nav nav-tabs" id="checkout-paymentdetail">
									<?php foreach ($languages as $language) { ?>
									<li>
										<a href="#checkout-paymentdetail<?php echo $language['language_id']; ?>" data-toggle="tab">
											<?php if(VERSION >= '2.2.0.0') { ?>
											<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
											<?php } else { ?> 
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
											<?php } ?>
											<?php echo $language['name']; ?>
										</a>
									</li>
								<?php } ?>
								</ul>
								<div class="tab-content">
									<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="checkout-paymentdetail<?php echo $language['language_id']; ?>">
										<div class="form-group ">	
											<label class="col-sm-2 control-label " for="input-heading"><?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[payment_details_language][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['payment_details_language']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['payment_details_language']['heading_title'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th class="text-left"><?php echo $entry_field_name; ?></th>
														<th class="text-left"><?php echo $entry_label; ?></th>
														<th class="text-left"><?php echo $entry_placeholder; ?></th>
														<th class="text-left"><?php echo $entry_error; ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($onepagecheckout_manage['payment_details']['fields'] as $key => $fields) { ?>
													<tr>
														<td class="text-left"><?php echo isset($onepagecheckout_manage['payment_details']['fields'][$key]['label']) ? $onepagecheckout_manage['payment_details']['fields'][$key]['label'] : ''; ?></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[payment_details_language][<?php echo $key; ?>][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_label; ?>" value="<?php if(isset($onepagecheckout_manage['payment_details_language'][$key]['label'][$language['language_id']])){ echo $onepagecheckout_manage['payment_details_language'][$key]['label'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[payment_details_language][<?php echo $key; ?>][placeholder][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_placeholder; ?>" value="<?php if(isset($onepagecheckout_manage['payment_details_language'][$key]['placeholder'][$language['language_id']])){ echo $onepagecheckout_manage['payment_details_language'][$key]['placeholder'][$language['language_id']]; } ?>"></td>
														<td class="text-left"><input type="text" class="form-control" name="onepagecheckout_manage[payment_details_language][<?php echo $key; ?>][error][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_error; ?>" value="<?php if(isset($onepagecheckout_manage['payment_details_language'][$key]['error'][$language['language_id']])){ echo $onepagecheckout_manage['payment_details_language'][$key]['error'][$language['language_id']]; } ?>"></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<?php } ?>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-delivery_method-language-setting">
							<fieldset>
								<legend><?php echo $entry_delivery_method; ?></legend>
								<ul class="nav nav-tabs" id="checkout-language3">
									<?php foreach ($languages as $language) { ?>
									<li>
										<a href="#checkout-language3<?php echo $language['language_id']; ?>" data-toggle="tab">
											<?php if(VERSION >= '2.2.0.0') { ?>
											<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
											<?php } else { ?> 
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
											<?php } ?>
											<?php echo $language['name']; ?>
										</a>
									</li>
									<?php } ?>
								</ul>
								<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="checkout-language3<?php echo $language['language_id']; ?>">
									<div class="form-group">	
											<label class="col-sm-2 control-label" for="input-heading"><?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[delivery_method][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['delivery_method']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['delivery_method']['heading_title'][$language['language_id']]; } ?>">
											</div>
									</div>
									<?php if($delivery_methods){ ?> 
									<table class="table table-bordered">
										<thead>
											<tr>
												<th><?php echo $entry_delivery_method; ?></th>
												<th><?php echo $entry_label; ?></th>
											</tr>
										</thead>
										<tbody>
											
											<?php foreach($delivery_methods as $delivery_method){ ?>
											<tr>
												<td><?php echo $delivery_method['title']; ?></td>
												
												<td>
													<div class="col-md-12">
														<div class="form-group">
															<input type="text" class="form-control"  value="<?php if(isset($onepagecheckout_manage['delivery_method'][$delivery_method['code']]['label'][$language['language_id']])){ echo $onepagecheckout_manage['delivery_method'][$delivery_method['code']]['label'][$language['language_id']]; }else{ echo $delivery_method['title']; } ?>" name="onepagecheckout_manage[delivery_method][<?php echo $delivery_method['code']; ?>][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_label; ?>" >
														</div>
													</div>
												</td>
											</tr>
										
										<?php } ?>
										</tbody>
									</table>
									<?php } ?>
								</div>
								<?php } ?>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-delivery-language-date-time">
							<fieldset>
								<legend>Estimated Delivery Date</legend>
							     <ul class="nav nav-tabs" id="checkout-delivery-language-date-time">
									<?php foreach ($languages as $language) { ?>
									<li>
										<a href="#checkout-delivery-language-date-time<?php echo $language['language_id']; ?>" data-toggle="tab">
											<?php if(VERSION >= '2.2.0.0') { ?>
											<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
											<?php } else { ?> 
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
											<?php } ?>
											<?php echo $language['name']; ?>
										</a>
									</li>
									<?php } ?>
								</ul>
								<div class="tab-content">
									<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="checkout-delivery-language-date-time<?php echo $language['language_id']; ?>">
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-heading"><?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[delivery][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['delivery']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['delivery']['heading_title'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-label"><?php echo $entry_label; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[delivery][label][<?php echo $language['language_id']; ?>]" placeholder="label" value="<?php if(isset($onepagecheckout_manage['delivery']['label'][$language['language_id']])){ echo $onepagecheckout_manage['delivery']['label'][$language['language_id']]; } ?>">
											</div>
										</div>
										<div class="form-group">	
											<label class="col-sm-2 control-label " for="input-required">Error Massege</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[delivery][required][<?php echo $language['language_id']; ?>]" placeholder="Error Massege" value="<?php if(isset($onepagecheckout_manage['delivery']['required'][$language['language_id']])){ echo $onepagecheckout_manage['delivery']['required'][$language['language_id']]; } ?>">
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-payment_method-language-setting">
							<fieldset>
								<legend><?php echo $entry_payment_method; ?></legend>
							
							<ul class="nav nav-tabs" id="checkout-paymentmethod">
								<?php foreach ($languages as $language) { ?>
								<li>
									<a href="#checkout-paymentmethod<?php echo $language['language_id']; ?>" data-toggle="tab">
										<?php if(VERSION >= '2.2.0.0') { ?>
										<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
										<?php } else { ?> 
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
										<?php } ?>
										<?php echo $language['name']; ?>
									</a>
								</li>
								<?php } ?>
							</ul>
							<div class="tab-content">
							<?php foreach ($languages as $language) { ?>
							<div class="tab-pane" id="checkout-paymentmethod<?php echo $language['language_id']; ?>">
							<div class="form-group">	
								<label class="col-sm-2 control-label " for="input-heading"><?php echo $entry_heading; ?></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="onepagecheckout_manage[payment_method][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['payment_method']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['payment_method']['heading_title'][$language['language_id']]; } ?>">
								</div>
							</div>
							<?php if($payment_methods){ ?> 
							<table class="table table-bordered">
								<thead>
									<tr>
										<th><?php echo $entry_payment_method; ?></th>
										<th><?php echo $entry_label; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($payment_methods as $payment_method){ ?>
									
									<tr>
										<td><?php echo $payment_method['title']; ?></td>
										
										<td>
											<div class="col-md-12">
												<div class="form-group">
													<input type="text" class="form-control"  value="<?php if(isset($onepagecheckout_manage['payment_method'][$payment_method['code']]['label'][$language['language_id']])) { echo $onepagecheckout_manage['payment_method'][$payment_method['code']]['label'][$language['language_id']]; }else{ echo $payment_method['title']; } ?>" name="onepagecheckout_manage[payment_method][<?php echo $payment_method['code']; ?>][label][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_label; ?>" >
												</div>
											</div>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
							<?php } ?>
							</div>
							<?php } ?>
							</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-confirm_order-language-setting">
							<fieldset>
								<legend><?php echo $entry_confirm_order; ?></legend>
							
							<ul class="nav nav-tabs" id="checkout-confirm">
								<?php foreach ($languages as $language) { ?>
								<li>
									<a href="#checkout-confirm<?php echo $language['language_id']; ?>" data-toggle="tab">
										<?php if(VERSION >= '2.2.0.0') { ?>
										<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
										<?php } else { ?> 
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
										<?php } ?>
										<?php echo $language['name']; ?>
									</a>
								</li>
								<?php } ?>
							</ul>
							<div class="tab-content">
							<?php foreach($languages as $language){ ?>
							<div class="tab-pane" id="checkout-confirm<?php echo $language['language_id']; ?>">
							<!--XML-->
							<div class="form-group">	
								<label class="col-sm-2 control-label " for="input-confirm-comment">Comment Label</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="onepagecheckout_manage[confirm_order][comment_label][<?php echo $language['language_id']; ?>]" placeholder="Comment Label" value="<?php if(isset($onepagecheckout_manage['confirm_order']['comment_label'][$language['language_id']])) { echo $onepagecheckout_manage['confirm_order']['comment_label'][$language['language_id']]; } ?>">
								</div>
						    </div>
							<div class="form-group">	
								<label class="col-sm-2 control-label " for="input-confirm-comment">Comment Placeholder</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="onepagecheckout_manage[confirm_order][comment_placeholder][<?php echo $language['language_id']; ?>]" placeholder="Comment Placeholder" value="<?php if(isset($onepagecheckout_manage['confirm_order']['comment_placeholder'][$language['language_id']])) { echo $onepagecheckout_manage['confirm_order']['comment_placeholder'][$language['language_id']]; } ?>">
								</div>
						    </div>
							<!--XML-->
							<div class="form-group ">	
						
								<label class="col-sm-2 control-label " for="input-confirm-button"><?php echo $entry_confirm_button; ?></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="onepagecheckout_manage[confirm_order][confirm_button][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_confirm_button; ?>" value="<?php if(isset($onepagecheckout_manage['confirm_order']['confirm_button'][$language['language_id']])) { echo $onepagecheckout_manage['confirm_order']['confirm_button'][$language['language_id']]; } ?>">
								</div>
						
							</div>
							<div class="form-group ">	
						
								<label class="col-sm-2 control-label " for="input-confirm-button"><?php echo $entry_comment_error; ?></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="onepagecheckout_manage[confirm_order][comment_error][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_comment_error; ?>" value="<?php if(isset($onepagecheckout_manage['confirm_order']['comment_error'][$language['language_id']])) { echo $onepagecheckout_manage['confirm_order']['comment_error'][$language['language_id']]; } ?>">
								</div>
						
							</div>
							<div class="form-group ">	
							
								<label class="col-sm-2 control-label " for="input-shopping-button"><?php echo $entry_shopping_button; ?></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="onepagecheckout_manage[confirm_order][shopping_button][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_shopping_button; ?>" value="<?php if(isset($onepagecheckout_manage['confirm_order']['shopping_button'][$language['language_id']])) { echo $onepagecheckout_manage['confirm_order']['shopping_button'][$language['language_id']]; } ?>">
								</div>
					
							</div>
							</div>
							<?php } ?>
							</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-shopping-cart-language-setting">
							<fieldset>
								<legend><?php echo $entry_shopping_cart; ?></legend>
							
							<ul class="nav nav-tabs" id="checkout-language8">
								<?php foreach ($languages as $language) { ?>
								<li>
									<a href="#checkout-language8<?php echo $language['language_id']; ?>" data-toggle="tab">
										<?php if(VERSION >= '2.2.0.0') { ?>
										<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
										<?php } else { ?> 
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
										<?php } ?>
										<?php echo $language['name']; ?>
									</a>
								</li>
								<?php } ?>
							</ul>	
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="checkout-language8<?php echo $language['language_id']; ?>">
										<div class="form-group ">	
											<label class="col-sm-2 control-label " for="input-heading">
											<?php echo $entry_heading; ?></label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name="onepagecheckout_manage[shopping_cart][heading_title][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $placeholder_heading; ?>" value="<?php if(isset($onepagecheckout_manage['shopping_cart']['heading_title'][$language['language_id']])){ echo $onepagecheckout_manage['shopping_cart']['heading_title'][$language['language_id']]; } ?>">
											</div>
										</div>
									</div>
									<div class="form-group">	
										<label class="col-sm-2 control-label " for="input-confirm-comment">Clear cart</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="onepagecheckout_manage[shopping_cart][clear_cart_text][<?php echo $language['language_id']; ?>]" placeholder="Alert Message" value="<?php if(isset($onepagecheckout_manage['shopping_cart']['clear_cart_text'][$language['language_id']])) { echo $onepagecheckout_manage['shopping_cart']['clear_cart_text'][$language['language_id']]; } ?>">
										</div>
									</div>
									<div class="form-group">	
										<label class="col-sm-2 control-label " for="input-confirm-comment">Alert Message</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="onepagecheckout_manage[shopping_cart][alert_message][<?php echo $language['language_id']; ?>]" placeholder="Alert Message" value="<?php if(isset($onepagecheckout_manage['shopping_cart']['alert_message'][$language['language_id']])) { echo $onepagecheckout_manage['shopping_cart']['alert_message'][$language['language_id']]; } ?>">
										</div>
									</div>
								<?php } ?>
							</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-support">
							<p class="text-center">For Support and Query Feel Free to contact:<br /><strong>extensionsbazaar@gmail.com</strong></p>
						</div>
					</div>
			</div>
    </div>
</form>
</div>
</div>
<?php if(version_compare(VERSION,'2.2.0.0','>=')){ ?>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php }else { ?>
<?php foreach ($languages as $language) { ?>
<script type="text/javascript">
$('#input-description<?php echo $language['language_id']; ?>').summernote({
	height: 150
});
$('#input-description_bottom<?php echo $language['language_id']; ?>').summernote({
	height: 150
});
</script>
<?php } ?>
<?php } ?>
<script type="text/javascript">
$('#general-language a:first').tab('show');
$('#checkout-language a:first').tab('show');
$('#checkout-language1 a:first').tab('show');
$('#checkout-language2 a:first').tab('show');
$('#checkout-language3 a:first').tab('show');
$('#checkout-language4 a:first').tab('show');
$('#checkout-language5 a:first').tab('show');
$('#checkout-language6 a:first').tab('show');
$('#checkout-language7 a:first').tab('show');
$('#checkout-language8 a:first').tab('show');
$('#checkout-language9 a:first').tab('show');
$('#checkout-language10 a:first').tab('show');
$('#checkout-language11 a:first').tab('show');
$('#checkout-personal a:first').tab('show');
$('#checkout-deliverydetail a:first').tab('show');
$('#checkout-paymentdetail a:first').tab('show');
$('#checkout-deliverymethod a:first').tab('show');
$('#checkout-paymentmethod a:first').tab('show');
$('#checkout-confirm a:first').tab('show');
$('#checkout-delivery-language-date-time a:first').tab('show');
//--></script>
<script type="text/javascript">
// Drag Personal Details
$(document).ready(function() {
$("#table-personal tbody").sortable({
	cursor: "move",
	stop: function() {
		$('#table-personal tbody .row-group').each(function() {
			$(this).find('.mydragsort').val($(this).index());
		});
	}
});

// Drag Delivery Details	
$("#table-delivery tbody").sortable({
	cursor: "move",
	stop: function() {
		$('#table-delivery tbody .row-group').each(function() {
			$(this).find('.mydragsort').val($(this).index());
		});
	}
});
	
// Drag Payment Details	
$("#table-payment_details tbody").sortable({
	cursor: "move",
	stop: function() {
		$('#table-payment_details tbody .row-group').each(function() {
			$(this).find('.mydragsort').val($(this).index());
		});
	}
});
});
</script>
  <script type="text/javascript"><!--
$('select[name=\'onepagecheckout_country_id\']').on('change', function() {
	$.ajax({
		<?php if(version_compare(VERSION,'2.1.0.1','>=')){ ?>
		url: 'index.php?route=localisation/country/country&token=<?php echo $token; ?>&country_id=' + this.value,
		<?php } else { ?>
		url: 'index.php?route=setting/setting/country&token=<?php echo $token; ?>&country_id=' + this.value,
		<?php } ?>
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'onepagecheckout_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
          			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $onepagecheckout_zone_id; ?>') {
            			html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'onepagecheckout_zone_id\']').html(html);
			
			$('#button-save').prop('disabled', false);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'onepagecheckout_country_id\']').trigger('change');
//--></script>
<script>
$('.button-account-type').click(function() {
	setTimeout(function() {
		var account_type = $('input[name=\'onepagecheckout_manage[personaldetails][register_status]\']:checked').val();
		if(account_type == '1') {
			$('.register_newsletter').removeClass('hide');
		}else{
			$('.register_newsletter').addClass('hide');
		}
	}, 300);
});
$(document).ready(function() {
	$('.active.button-account-type').trigger('click');
});
</script>
<style>
.btn-success1{
    background-color:#8fbb6c;
    border-color:#7aae50;
    color:#fff;
}
</style>
<?php echo $footer; ?>