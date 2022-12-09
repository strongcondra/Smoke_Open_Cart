<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-download"></i> <?php echo $text_form; ?></h3>
		</div>
      	<div class="panel-body">
        	<form id="form-product-export" class="form-horizontal">        	
				<div class="form-group">
					<label class="control-label col-sm-2" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
					<div class="col-sm-10">
						<input type="text" name="product_name" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
						<div id="export-product" class="well well-sm" style="height: 135px; overflow: auto;">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="input-store"><?php echo $entry_store; ?></label>
					<div class="col-sm-10">
						<select name="find_store_id" id="input-store" class="form-control">
							<option value=""><?php echo $text_all_store; ?></option>
							<option value="0"><?php echo $text_default; ?></option>
							<?php foreach ($stores as $store) { ?>
							<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="input-language"><?php echo $entry_language; ?></label>
					<div class="col-sm-10">
						<select name="find_language_id" id="input-language" class="form-control">
							<?php foreach ($languages as $language) { ?>
							<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="input-format"><?php echo $entry_format; ?></label>
					<div class="col-sm-10">
						<select name="find_format" class="form-control">
							<option value="xls"><?php echo $text_xls; ?></option>														
							<option value="xlsx"><?php echo $text_xlsx; ?></option>														
							<option value="csv"><?php echo $text_csv; ?></option>														
						</select>
					</div>
				</div>
				<div class="buttons exports">
					<label class="control-label col-sm-2">&nbsp;</label>
					<button type="button" class="btn btn-primary" id="exporter-product"><i class="fa fa-download" aria-hidden="true"></i> <?php echo $button_export; ?></button>
				</div>
        </form>
      </div>
    </div>
  </div>
	<div class="mpteam"></div>
<script type="text/javascript"><!--
// Exporter Product
$('#exporter-product').click(function() {
$.ajax({
		url: 'index.php?route=exporter/product/export&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#form-product-export input[type=\'text\'], #form-product-export input[type=\'hidden\'], #form-product-export select, #form-product-export input[type=\'checkbox\']:checked, #form-product-export input[type=\'radio\']:checked'),
		dataType: 'json',
		beforeSend: function() {
			$('.alert-danger, .alert-success').remove();			
			$('#exporter-product').button('loading');
			$('.mpteam').after('<div class="modal-backdrop in mpteam_loader"></div> <div class="loader mpteam_loader"></div>');
		},
		complete: function() {
			$('#exporter-product').button('reset');
			$('.mpteam_loader').remove();
		},
		success: function(json) {
			if(json['error']) {
				$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
			
			if(json['href']) {
				window.location = json['href'];
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if(xhr.responseText) {
				$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ xhr.responseText +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		}
	});
});

// Product
$('input[name=\'product_name\']').autocomplete({
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
		$('input[name=\'product_name\']').val('');
		
		$('#export-product' + item['value']).remove();
		
		$('#export-product').append('<div id="export-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="find_product[]" value="' + item['value'] + '" /></div>');	
	}
});
$('#export-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Manufacturer
$('input[name=\'manufacturer_name\']').autocomplete({
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\'manufacturer_name\']').val('');
		
		$('#export-manufacturer' + item['value']).remove();
		
		$('#export-manufacturer').append('<div id="export-manufacturer' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="find_manufacturer[]" value="' + item['value'] + '" /></div>');	
	}
});	
$('#export-manufacturer').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Category
$('input[name=\'category_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category_name\']').val('');

		$('#export-category' + item['value']).remove();

		$('#export-category').append('<div id="export-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="find_category[]" value="' + item['value'] + '" /></div>');
	}
});
$('#export-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
</div>
<?php echo $footer; ?>
