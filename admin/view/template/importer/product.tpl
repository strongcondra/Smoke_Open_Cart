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
				<h3 class="panel-title"><i class="fa fa-upload"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form id="form-product-import" class="form-horizontal">
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
								<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_file; ?>"><?php echo $entry_file; ?></span></label>
								<div class="col-sm-12">
									<div class="file-area">
										<input type="file" name="find_file" accept=".xls,.xlsx,.csv" id="input-file" required="required" />
										<div class="file-dummy">
											<div class="success"><?php echo $entry_great; ?></div>
											<div class="warning"><i class="fa fa-times-circle" aria-hidden="true"></i> Invalid</div>
											<div class="default"><?php echo $entry_dragfile; ?></div>
											<br/>
											<small class="small"></small>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group required">
								<label class="col-sm-12 control-label" for="input-language"><span data-toggle="tooltip" title="<?php echo $help_language; ?>"><?php echo $entry_language; ?></span></label>
								<div class="col-sm-12">
									<select name="find_language_id" id="input-language" class="form-control selectpicker show-tick">
										<?php foreach ($languages as $language) { ?>
										<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-12 control-label" for="input-store"><span data-toggle="tooltip" title="<?php echo $help_store; ?>"><?php echo $entry_store; ?></span></label>
								<div class="col-sm-12">
									<select name="find_store[]" id="input-store" class="form-control selectpicker show-tick" multiple>
										<option value="0" selected="selected"><?php echo $text_default; ?></option>
										<?php foreach ($stores as $store) { ?>
										<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>							
							<div class="buttons imports">
								<button type="button" id="importer-product" class="btn btn-success btn-block"><i class="fa fa-upload"></i> <?php echo $button_import; ?></button>
								<a href="<?php echo $sample_download; ?>" download class="btn btn-default btn-block"><i class="fa fa-download"></i> <?php echo $button_download; ?></a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="mpteam"></div>
<script type="text/javascript"><!--
$('#input-file').change(function() {
	var fullPath = document.getElementById('input-file').value;
	if (fullPath) {
		var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
		var filename = fullPath.substring(startIndex);
		if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
				filename = filename.substring(1);
		}
		$('.file-dummy').removeClass('warning').find('.warning').html('');
		$('.file-dummy').find('.success').html('<?php echo $entry_great; ?>');
		$('.file-dummy .small').html(filename);
	}
});

// Importer Product
$('#importer-product').click(function() {

	$('.file-dummy').removeClass('warning').find('.warning').html('');

	var customarray = $('#form-product-import input[type=\'text\'], #form-product-import input[type=\'file\'], #form-product-import input[type=\'hidden\'], #form-product-import select, #form-product-import input[type=\'checkbox\']:checked, #form-product-import input[type=\'radio\']:checked').serialize();

	var file_data = $("#form-product-import input[type=\'file\']").prop("files")[0];   
	var form_data = new FormData();                  
	form_data.append("find_file", file_data)
	
	$.ajax({
		url: 'index.php?route=importer/product/import&token=<?php echo $token; ?>&'+ customarray,
		dataType: 'json',
		cache: false,
		contentType: false,
		processData: false,
		type: 'post',		
		data: form_data,		
		beforeSend: function() {
			$('.alert-danger, .alert-success, .text-danger').remove();			
			$('.text-danger').parent().removeClass('has-error');
			
			$('#importer-product').button('loading');
			$('.mpteam').after('<div class="modal-backdrop in mpteam_loader"></div> <div class="loader mpteam_loader"></div>');
		},
		complete: function() {
			$('#importer-product').button('reset');
			$('.mpteam_loader').remove();
		},
		success: function(json) {
			$('.form-group, .col-sm-12').removeClass('has-error');
			
			if (json['error']) {
				if (json['error']['store']) {
					$('#input-store').after('<div class="text-danger">' + json['error']['store'] + '</div>');
				}
				
				if (json['error']['language']) {
					$('#input-language').after('<div class="text-danger">' + json['error']['language'] + '</div>');
				}
				
				if (json['error']['importon']) {
					$('#input-importon').after('<div class="text-danger">' + json['error']['importon'] + '</div>');
				}

				if (json['error']['file']) {
					$('.file-dummy').addClass('warning').find('.warning').html(json['error']['file']);
				}
				
				if(json['error']['warning']) {
					$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error']['warning'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
				
				$('.text-danger').parent().parent().addClass('has-error');
			}
			
			if(json['success']) {
				$('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('.file-dummy').find('.success').html('<i class="fa fa-check-circle"></i> '+ json['success']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if(xhr.responseText) {
				$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ xhr.responseText +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		}
	});
});
//--></script>
</div>
<?php echo $footer; ?>