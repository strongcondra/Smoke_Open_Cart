<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-faq-manager" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-faq-manager" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="faq_manager_status" id="input-status" class="form-control">
                <?php if ($faq_manager_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
			<div class="row">
				<div class="col-sm-2">
					<ul id="section" class="nav nav-pills nav-stacked">
						<?php $section_row = 0; ?>
						
						<?php foreach ($faq_manager_sections as $section) { ?>
							<li><a href="#tab-section-<?php echo $section_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-section-<?php echo $section_row; ?>\']').parent().remove(); $('#tab-section-<?php echo $section_row; ?>').remove(); $('#section a:first').tab('show');"></i> Section <?php echo $section_row; ?></a></li>
							<?php $section_row++; ?>
						<?php } ?>
						
						<li id="section-add" class="active"><a onclick="addSection();"><i class="fa fa-plus-circle"></i> Add section</a></li> 
					</ul>				
				</div>
				<div class="col-sm-10">
					<div class="tab-content first">
						<?php $section_row = 0; ?>						
												
						<?php foreach ($faq_manager_sections as $section) { ?>
							
							<div class="tab-pane" id="tab-section-<?php echo $section_row; ?>">
								<div class="tab-content">
									
									<div class="form-group required">
										<label class="col-sm-2 control-label"><?php echo $entry_section_name; ?></label>
										<div class="col-sm-10">
											<?php foreach ($languages as $language) { ?>
												<div class="input-group">
													<span class="input-group-addon">
														<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
													</span>
													<input type="text" name="faq_manager_sections[<?php echo $section_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($section['title'][$language['language_id']]) ? $section['title'][$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_section_name; ?>" class="form-control" />
												</div>
												<?php if(isset($error_faq_manager_sections[$section_row]['title'][$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_faq_manager_sections[$section_row]['title'][$language['language_id']]; ?></div>
												<?php } ?>
											<?php } ?>
										</div>
									</div>
									
									
										<?php $group_row = 0; ?>
										<div id="groups-<?php echo $section_row; ?>">
											<div class="panel panel-default">
												<div class="panel-heading"><h3 class="panel-title"><?php echo $entry_section_question_answer; ?></h3></div>
												
												<div class="panel-body">
													<?php if(isset($section['groups']) && $section['groups']) { ?>
														<?php foreach($section['groups'] as $key => $value) { ?>
															<div id="group-row-<?php echo $section_row; ?>-<?php echo $group_row; ?>" class="group">
																<ul class="nav nav-tabs" id="language-<?php echo $section_row; ?>">
																	<?php foreach ($languages as $language) { ?>
																		<li><a href="#tab-section-<?php echo $section_row; ?>-<?php echo $group_row; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
																	<?php } ?>
																</ul>		
																<div class="tab-content" id="other-language-<?php echo $section_row; ?>">
																<?php foreach ($languages as $language) { ?>
																	
																		<div class="tab-pane" id="tab-section-<?php echo $section_row; ?>-<?php echo $group_row; ?>-<?php echo $language['language_id']; ?>">
																			
																			<div class="form-group required">
																				<label class="col-sm-2 control-label"><?php echo $entry_question; ?></label>
																				<div class="col-sm-10">
																					<input type="text" name="faq_manager_sections[<?php echo $section_row; ?>][groups][<?php echo $group_row; ?>][question][<?php echo $language['language_id']; ?>]" value="<?php echo isset($value['question'][$language['language_id']]) ? $value['question'][$language['language_id']] : ''; ?>" class="form-control"/>
																					
																					<?php if(isset($error_faq_manager_sections[$section_row]['groups'][$group_row]['question'][$language['language_id']])) { ?>
																						<div class="text-danger"><?php echo $error_faq_manager_sections[$section_row]['groups'][$group_row]['question'][$language['language_id']]; ?></div>
																					<?php } ?>
																					
																				</div>
																			</div>
																			
																			<div class="form-group">
																				<label class="col-sm-2 control-label"><?php echo $entry_answer; ?></label>
																				<div class="col-sm-10"><textarea name="faq_manager_sections[<?php echo $section_row; ?>][groups][<?php echo $group_row; ?>][answer][<?php echo $language['language_id']; ?>]" id="description-'<?php echo $section_row; ?>-<?php echo $group_row; ?>-<?php echo $language['language_id']; ?>" class="summernote-<?php echo $group_row; ?> form-control"><?php echo isset($value['answer'][$language['language_id']]) ? $value['answer'][$language['language_id']] : ''; ?></textarea>
																				
																				<?php if(isset($error_faq_manager_sections[$section_row]['groups'][$group_row]['answer'][$language['language_id']])) { ?>
																						<div class="text-danger"><?php echo $error_faq_manager_sections[$section_row]['groups'][$group_row]['answer'][$language['language_id']]; ?></div>
																					<?php } ?>
																				
																				</div>
																			</div>
																			
																			<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
																			<script type="text/javascript">
																				$('.summernote-<?php echo $group_row; ?>').summernote({height: 150,code: ''});
																			</script>
																			
																		</div>
																	
																<?php } ?>
																</div>
																<button type="button" class="btn btn-danger pull-right" onclick="removeGroup(<?php echo $section_row; ?>, <?php echo $group_row; ?>);"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button>
																<div class="clearfix"></div>
																<hr />
															</div>
															
															<?php $group_row++; ?>
														<?php } ?>
													
													<?php } ?>
													
													<div id="group-holder-<?php echo $section_row; ?>"></div>
												</div>
												
											</div>
										</div>
									
									<button type="button" class="btn btn-success pull-right" onclick="addGroup(<?php echo $section_row; ?>);"><i class="fa fa-plus-circle"></i> <?php echo $button_add_question; ?></button>
								</div>
								
							</div>
							<?php $section_row++; ?>
						<?php } ?>
					</div>
				</div>
			</div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>

<script type="text/javascript">
	var section_row = <?php echo $section_row; ?>;
	
	function addSection() {
		
		group_row = 0;
		
		html  = '<div class="tab-pane" id="tab-section-' + section_row + '">';
		html += '	<div class="tab-content">';
		html += '		<div class="form-group required">';
		html += '			<label class="col-sm-2 control-label"><?php echo $entry_section_name; ?></label>';
		html += '			<div class="col-sm-10">';
							<?php foreach ($languages as $language) { ?>
		html += '				<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="faq_manager_sections[' + section_row + '][title][<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_section_name; ?>" class="form-control" /></div>';
							<?php } ?>
		html += '			</div>';
		html += '		</div>';
		
		html += '		<div id="groups-' + section_row + '">';
		html += '			<div class="panel panel-default">';
		html += '				<div class="panel-heading"><h3 class="panel-title"><?php echo $entry_section_question_answer; ?></h3></div>';
		html += '				<div class="panel-body">';
		html += '					<div id="group-holder-' + section_row + '"></div>';
		html += '				</div>';
		html += '			</div>';
		html += '		</div>';
		html += '		<button type="button" class="btn btn-success pull-right" onclick="addGroup(' + section_row + ');"><i class="fa fa-plus-circle"></i> <?php echo $button_add_question; ?></button>';
		
		html += '	</div>';
		html += '</div>';
		
		$('.tab-content.first').append(html);
		
		$('#section-add').before('<li><a href="#tab-section-' + section_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-section-' + section_row + '\\\']\').parent().remove(); $(\'#tab-section-' + section_row + '\').remove(); $(\'#section a:first\').tab(\'show\');"></i> Section ' + section_row + '</a></li>');
	
		$('#section a[href=\'#tab-section-' + section_row + '\']').tab('show');
	
		section_row++;
		
	}
	
	function addGroup(section_row) {
				
		group_row = $('#tab-section-' + section_row + ' .group').length;
		
		//alert(group_row);
		
		html  = '<div id="group-row-' + section_row + '-' + group_row + '" class="group">';
		html += '		<ul class="nav nav-tabs" id="language-' + section_row + '">';
		<?php foreach ($languages as $language) { ?>
			html += '<li><a href="#tab-section-' + section_row + '-' + group_row + '-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
		<?php } ?>
		html += '		</ul>';
		
			html += '	<div class="tab-content" id="other-language-' + section_row + '">';
		
		<?php foreach ($languages as $language) { ?>
			
			html += '		<div class="tab-pane" id="tab-section-' + section_row + '-' + group_row + '-<?php echo $language['language_id']; ?>">';
			
			html += '		<div class="form-group required">';
			html += '			<label class="col-sm-2 control-label"><?php echo $entry_question; ?></label>';
			html += '			<div class="col-sm-10"><input type="text" name="faq_manager_sections[' + section_row + '][groups][' + group_row + '][question][<?php echo $language['language_id']; ?>]" class="form-control"/></div>';
			html += '		</div>';

			html += '		<div class="form-group required">';
			html += '			<label class="col-sm-2 control-label"><?php echo $entry_answer; ?></label>';
			html += '			<div class="col-sm-10"><textarea name="faq_manager_sections[' + section_row + '][groups][' + group_row + '][answer][<?php echo $language['language_id']; ?>]" id="description-' + section_row + '-' + group_row + '-<?php echo $language['language_id']; ?>" class="summernote-' + group_row + ' form-control"></textarea></div>';
			html += '		</div>';
			
			html += '		</div>';
			
			
		<?php } ?>
		
			html += '		<button type="button" class="btn btn-danger pull-right" onclick="removeGroup('+ section_row +',' + group_row +');"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button>';
			html += '		<div class="clearfix"></div>';
			html += '		<hr />';
		
			html += '	</div>';
		html += '</div>';
		
		$('#group-holder-' + section_row ).before(html);
		
		$('#other-language-' + section_row + ' .tab-pane:first').tab('show');
		
		$('.summernote-' + group_row ).summernote({
			height: 150,
			code: ''
		});
		$('.tab-pane li:first-child a').tab('show');
		
		group_row++;
	}
	
	function removeGroup(section_row, group_row){
		$('#group-row-' + section_row + '-' + group_row).remove();
	}
	
	$('#section li:first-child a').tab('show');
	$('.tab-pane li:first-child a').tab('show');

</script>


<?php echo $footer; ?>
