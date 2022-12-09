<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<button onclick="$('.stay').val(1);" type="submit" form="form-order_success_page" data-toggle="tooltip" title="<?php echo $button_save; ?> & stay" class="btn btn-success1"><i class="fa fa-save"></i> <?php echo $button_save; ?> & stay </button>
		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Back to Onepagecheckout" class="btn btn-default"><i class="fa fa-reply"></i> Back</a></div>
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
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order_success_page" class="form-horizontal">
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i> <?php echo $tab_general; ?></a></li>
            <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-eye"></i> <?php echo $tab_page; ?> Setting <span class="caret"></span></a> 
				<ul class="dropdown-menu">
					<li><a href="#tab-order" data-toggle="tab"><i class="fa fa-eye"></i> Default Layout</a></li>
				</ul>
			</li>
            <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-language"></i> <?php echo $tab_language; ?>  <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="#tab-general_setting" data-toggle="tab"><i class="fa fa-language"></i> General Setting</a></li>
					<li><a href="#tab-page_setting" data-toggle="tab"><i class="fa fa-language"></i> Page Setting</a></li>
				</ul>
			</li>
			<li><a href="#tab-promote_product" data-toggle="tab"><i class="fa fa-tags fw"></i>  <?php echo $tab_promote_product; ?></a></li>
			<li><a href="#tab-analytics" data-toggle="tab"><i class="fa fa-line-chart"></i>  <?php echo $tab_analytics; ?></a></li>
            <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-external-link"></i>  <?php echo $tab_support; ?></a></li>
          </ul>
		  <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-5">
				  <select name="order_success_page_status" id="input-status" class="form-control">
					<?php if ($order_success_page_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				  <input type="hidden" name="stay" class="stay" value="0"/>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-order_order"><span  data-toggle="tooltip" title="<?php echo $help_order_invoice; ?>"><?php echo $entry_order_invoice; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_order_invoice ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_order_invoice ? 'checked=checked' : ''); ?>  name="order_success_page_order_invoice"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_order_invoice ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_order_invoice ? 'checked=checked' : ''); ?>  name="order_success_page_order_invoice"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-order_order"><span  data-toggle="tooltip" title="<?php echo $help_print_invoice; ?>"><?php echo $entry_print_invoice; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_print_invoice ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_print_invoice ? 'checked=checked' : ''); ?>  name="order_success_page_print_invoice"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_print_invoice ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_print_invoice ? 'checked=checked' : ''); ?>  name="order_success_page_print_invoice"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			    <div class="form-group">
				<label class="col-sm-2 control-label" for="input-shipping-address"><span  data-toggle="tooltip" title="<?php echo $help_bank_details; ?> "><?php echo $entry_bank_details; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_bank_details_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_bank_details_status ? 'checked=checked' : ''); ?>  name="order_success_page_bank_details_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_bank_details_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_bank_details_status ? 'checked=checked' : ''); ?>  name="order_success_page_bank_details_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-order">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-order_no"><span  data-toggle="tooltip" title="<?php echo $help_order_details; ?>"><?php echo $entry_order_details; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_order_details ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_order_details ? 'checked=checked' : ''); ?>  name="order_success_page_order_details"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_order_details ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_order_details ? 'checked=checked' : ''); ?>  name="order_success_page_order_details"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-order-details"><span  data-toggle="tooltip" title="<?php echo $help_order_comment; ?> "><?php echo $entry_order_comments; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_comment_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_comment_status ? 'checked=checked' : ''); ?>  name="order_success_page_comment_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_comment_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_comment_status ? 'checked=checked' : ''); ?>  name="order_success_page_comment_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-payment-address"><span  data-toggle="tooltip" title="<?php echo $help_payment_address; ?> "><?php echo $entry_payment_address; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_payment_address_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_payment_address_status ? 'checked=checked' : ''); ?>  name="order_success_page_payment_address_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_payment_address_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_payment_address_status ? 'checked=checked' : ''); ?>  name="order_success_page_payment_address_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-shipping-address"><span  data-toggle="tooltip" title="<?php echo $help_shipping_address; ?> "><?php echo $entry_shipping_address; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_shipping_address_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_shipping_address_status ? 'checked=checked' : ''); ?>  name="order_success_page_shipping_address_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_shipping_address_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_shipping_address_status ? 'checked=checked' : ''); ?>  name="order_success_page_shipping_address_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-image_height"><?php echo $entry_image_height; ?></label>
				<div class="col-sm-10">
					<div class="col-sm-3">
						<input type="text" name="order_success_page_width" class="form-control" value="<?php echo $order_success_page_width; ?>"/> 
				    </div>
					<div class="col-sm-3">
					  <input type="text" name="order_success_page_height" class="form-control" value="<?php echo $order_success_page_height; ?>"/>
				    </div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-title-background-color">Title Background Color</label>
				<div class="col-sm-10">
					<div class="col-sm-3">
						<input type="text" name="order_success_page_title_backgound" class="form-control color" value="<?php echo $order_success_page_title_backgound; ?>"/> 
				    </div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-title-color">Title Color</label>
				<div class="col-sm-10">
					<div class="col-sm-3">
						<input type="text" name="order_success_page_title_color" class="form-control color" value="<?php echo $order_success_page_title_color; ?>"/> 
				    </div>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-left"><?php echo $entry_show_image; ?></th>
								<th class="text-left">Product Name</th>
								<th class="text-left">Model</th>
								<th class="text-left">Sku</th>
								<th class="text-left">Quantity</th>
								<th class="text-left">Unit Price</th>
								<th class="text-left">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_image_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_image_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_image_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_image_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_image_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_image_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_name_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_name_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_name_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_name_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_name_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_name_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_model_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_model_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_model_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_model_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_model_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_model_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_sku_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_sku_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_sku_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_sku_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_sku_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_sku_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_qty_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_qty_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_qty_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_qty_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_qty_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_qty_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_unit_price_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_unit_price_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_unit_price_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_unit_price_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_unit_price_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_unit_price_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($order_success_page_product_total_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($order_success_page_product_total_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_total_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$order_success_page_product_total_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$order_success_page_product_total_status ? 'checked=checked' : ''); ?>  name="order_success_page_product_total_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-general_setting">
				<ul class="nav nav-tabs" id="language">
					<?php foreach ($languages as $language) { ?>
					<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><?php if(VERSION >= '2.2.0.0') { ?>
						<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
						<?php } else { ?> 
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
						<?php } ?><?php echo $language['name']; ?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_order_heading; ?></label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_order_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_order_heading' . $language['language_id']}) ? ${'order_success_page_order_heading' . $language['language_id']} : ''; ?>"/>
							</div>
							
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order-message">Successfull Message for Guest User</label>
							<div class="col-sm-7">
							  <textarea name="order_success_page_guest_message<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset(${'order_success_page_guest_message' . $language['language_id']}) ? ${'order_success_page_guest_message' . $language['language_id']} : ''; ?></textarea>
							</div>
							<div class="col-sm-3">
								<h3>Short codes</h3>
								{order_id} = Order Id<br/>
								{first_name} = First Name<br/>
								{last_name} = Last Name<br/>
								{account} = Account Page<br/>
								{order_history} = Order History Page<br/>
								{downloads} = Download Page<br/>
								{contact_us} = Contact Us<br/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order-message">Successfull Message for Registered User</label>
							<div class="col-sm-7">
							  <textarea name="order_success_page_register_message<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset(${'order_success_page_register_message' . $language['language_id']}) ? ${'order_success_page_register_message' . $language['language_id']} : ''; ?></textarea>
							</div>
							<div class="col-sm-3">
								<h3>Short codes</h3>
								{order_id} = Order Id<br/>
								{first_name} = First Name<br/>
								{last_name} = Last Name<br/>
								{account} = Account Page<br/>
								{order_history} = Order History Page<br/>
								{downloads} = Download Page<br/>
								{contact_us} = Contact Us<br/>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			
			<div class="tab-pane" id="tab-page_setting">
				<ul class="nav nav-tabs" id="languagetext">
					<?php foreach ($languages as $language) { ?>
					<li><a href="#languagetext<?php echo $language['language_id']; ?>" data-toggle="tab"><?php if(VERSION >= '2.2.0.0') { ?>
						<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
						<?php } else { ?> 
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
						<?php } ?> <?php echo $language['name']; ?></a></li>
					<?php } ?>
				</ul>
			    <div class="tab-content">
					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane" id="languagetext<?php echo $language['language_id']; ?>">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_order_details_heading; ?></label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_order_details_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_order_details_heading' . $language['language_id']}) ? ${'order_success_page_order_details_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_order_comments; ?> Heading</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_order_comment_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_order_comment_heading' . $language['language_id']}) ? ${'order_success_page_order_comment_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_payment_address; ?> Heading</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_payment_address_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_payment_address_heading' . $language['language_id']}) ? ${'order_success_page_payment_address_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_shipping_address; ?> Heading</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_shipping_address_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_shipping_address_heading' . $language['language_id']}) ? ${'order_success_page_shipping_address_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_bank_details; ?> Heading</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_bank_details_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_bank_details_heading' . $language['language_id']}) ? ${'order_success_page_bank_details_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Image Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_image_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_image_title' . $language['language_id']}) ? ${'order_success_page_image_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Product Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_product_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_product_title' . $language['language_id']}) ? ${'order_success_page_product_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Model Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_model_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_model_title' . $language['language_id']}) ? ${'order_success_page_model_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Sku Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_sku_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_sku_title' . $language['language_id']}) ? ${'order_success_page_sku_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Quantity Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_qty_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_qty_title' . $language['language_id']}) ? ${'order_success_page_qty_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Unit Price Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_unit_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_unit_title' . $language['language_id']}) ? ${'order_success_page_unit_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Total Title</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_total_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_total_title' . $language['language_id']}) ? ${'order_success_page_total_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Print Invoice Text</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_print_invoice_text<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_print_invoice_text' . $language['language_id']}) ? ${'order_success_page_print_invoice_text' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Continue Button Text</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_continue_text<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_continue_text' . $language['language_id']}) ? ${'order_success_page_continue_text' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_promote_products; ?> Heading</label>
							<div class="col-sm-7">
							  <input type="text" class="form-control" name="order_success_page_shipping_promote_product_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'order_success_page_shipping_promote_product_heading' . $language['language_id']}) ? ${'order_success_page_shipping_promote_product_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="tab-pane" id="tab-promote_product">
				 <div class="form-group">
				<label class="col-sm-2 control-label" for="input-payment-address"><span  data-toggle="tooltip" title="<?php echo $help_promote_products; ?> "><?php echo $entry_promote_products; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($order_success_page_promote_products ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($order_success_page_promote_products ? 'checked=checked' : ''); ?>  name="order_success_page_promote_products"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$order_success_page_promote_products ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$order_success_page_promote_products ? 'checked=checked' : ''); ?>  name="order_success_page_promote_products"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-promote-product"><span  data-toggle="tooltip" title="<?php echo $help_products; ?> "><?php echo $entry_products; ?></span></label>
				<div class="col-sm-4">
					<input type="text" name="promote_products" value="" placeholder="<?php echo $entry_products; ?>" id="input-product" class="form-control" />
					<div id="promote-product" class="well well-sm" style="height: 150px; overflow: auto;">
					<?php foreach ($products as $product) { ?>
					<div id="promote-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
					  <input type="hidden" name="order_success_page_product[]" value="<?php echo $product['product_id']; ?>" />
					</div>
					<?php } ?>
				  </div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-image_height"><?php echo $entry_image_height; ?></label>
				<div class="col-sm-10">
					<div class="col-sm-2">
						<input type="text" name="order_success_page_promote_product_width" class="form-control" value="<?php echo $order_success_page_promote_product_width; ?>"/> 
				    </div>
					<div class="col-sm-2">
					  <input type="text" name="order_success_page_promote_product_height" class="form-control" value="<?php echo $order_success_page_promote_product_height; ?>"/>
				    </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-analytics">
				<div class="form-group">
				<label class="col-sm-2 control-label" for="input-google_analytics"><?php echo $entry_google_analytics; ?></label>
				<div class="col-sm-7">
					<textarea class="form-control" rows="5" name="order_success_page_google_analytics"><?php echo $order_success_page_google_analytics;?></textarea>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-support">
			  <p class="text-center">For Support and Query Feel Free to contact:<br /><strong>extensionsbazaar@gmail.com</strong></p>
			</div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('input[name=\'promote_products\']').autocomplete({
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\'promote_products\']').val('');
		
		$('#promote-product' + item['value']).remove();
		
		$('#promote-product').append('<div id="promote-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="order_success_page_product[]" value="' + item['value'] + '" /></div>');	
	}
});
	
$('#promote-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script><!--
$(document).ready(function() {
	// Override summernotes image manager
	$('.summernote').each(function() {
		var element = this;
		
		$(element).summernote({
			disableDragAndDrop: true,
			height: 200,
			emptyPara: '',
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			],
			buttons: {
    			image: function() {
					var ui = $.summernote.ui;

					// create button
					var button = ui.button({
						contents: '<i class="note-icon-picture" />',
						tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$('#modal-image').remove();
						
							$.ajax({
								url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
								dataType: 'html',
								beforeSend: function() {
									$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
									$('#button-image').prop('disabled', true);
								},
								complete: function() {
									$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
									$('#button-image').prop('disabled', false);
								},
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
									
									$('#modal-image').modal('show');
									
									$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
										e.preventDefault();
										
										$(element).summernote('insertImage', $(this).attr('href'));
																	
										$('#modal-image').modal('hide');
									});
								}
							});						
						}
					});
				
					return button.render();
				}
  			}
		});
	});
	
});
//--></script> 
<link href="view/javascript/colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet">
<script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#languagetext a:first').tab('show');
//--></script>
<script src="view/javascript/colorpicker/js/colorpicker-color.js"></script>
<script src="view/javascript/colorpicker/js/colorpicker.js"></script>
<script src="view/javascript/colorpicker/js/docs.js"></script>
<script>
$(function(){
 $('.color').colorpicker();
});
</script>

<style>
.btn-success1{
    background-color:#8fbb6c;
    border-color:#7aae50;
    color:#fff;
}
.dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus{
	background-color:#75a74d;
}
.dropdown-menu > li > a {
	padding: 6px 20px;
}
.nav-tabs .dropdown-menu{
	min-width: 267px;
}

.form-horizontal .control-label {
	padding-top: 5px;
}

.btn-success {
	background-color: #fff;
	color: #000;
	border-color: #ddd;
}
.btn-success1{
    background-color:#8fbb6c;
    border-color:#7aae50;
    color:#fff;
}
.btn-success1:hover{
    color:#fff;
}
</style>

<?php echo $footer; ?>