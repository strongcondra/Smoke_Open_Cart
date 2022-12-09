<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-revolution-slider" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-revolution-slider" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-full-width"><span data-toggle="tooltip" title="<?php echo $help_display_full_width; ?>"><?php echo $entry_display_full_width; ?></span></label>
            <div class="col-sm-10">
              <select name="display_full_width" class="form-control" id="input-full-width">
				<?php if($display_full_width) { ?>
					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
					<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_yes; ?></option>
					<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>				
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
			
			
			
			<table id="rev-slider" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-left"><?php echo $entry_image; ?></td>
						<td class="text-left"><?php echo $entry_heading_detail; ?></td>
						<td class="text-left"><?php echo $entry_caption_detail; ?></td>
						<td class="text-left"><?php echo $entry_button; ?></td>
						<td class="text-left"><?php echo $entry_transition; ?></td>
						<td></td>
					</tr>
				</thead>
				
				<tbody>
					<?php $slider_row = 0; ?>
					<?php foreach ($slider_detail as $slider) { ?>
						<tr id="slider-row<?php echo $slider_row; ?>">
							<td class="text-left">
								<a href="" id="thumb-image<?php echo $slider_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $slider['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
								<input type="hidden" name="slider_detail[<?php echo $slider_row; ?>][image]" value="<?php echo $slider['image']; ?>" id="input-image<?php echo $slider_row; ?>" />
							</td>
							
							<td class="text-left" style="width: 30%;">
								<input type="text" name="slider_detail[<?php echo $slider_row; ?>][heading]" value="<?php echo $slider['heading']; ?>" placeholder="<?php echo $entry_heading_detail; ?>" class="form-control" style="margin-bottom: 20px;" />
								
								<select name="slider_detail[<?php echo $slider_row; ?>][heading_position]" class="form-control" onchange="getHeadingPosition(this.value, <?php echo $slider_row; ?>);">
									<option value="top_left" <?php if($slider['heading_position'] == 'top_left') { echo "selected='selected'"; } ?>><?php echo $text_top_left; ?></option>
									<option value="top_center" <?php if($slider['heading_position'] == 'top_cener') { echo "selected='selected'"; } ?>><?php echo $text_top_center; ?></option>
									<option value="top_right" <?php if($slider['heading_position'] == 'top_right') { echo "selected='selected'"; } ?>><?php echo $text_top_right; ?></option>
									
									<option value="center_left" <?php if($slider['heading_position'] == 'center_left') { echo "selected='selected'"; } ?>><?php echo $text_center_left; ?></option>
									<option value="center_center" <?php if($slider['heading_position'] == 'center_center') { echo "selected='selected'"; } ?>><?php echo $text_center_center; ?></option>
									<option value="center_right" <?php if($slider['heading_position'] == 'center_right') { echo "selected='selected'"; } ?>><?php echo $text_center_right; ?></option>
									
									<option value="bottom_left" <?php if($slider['heading_position'] == 'bottom_left') { echo "selected='selected'"; } ?>><?php echo $text_bottom_left; ?></option>
									<option value="bottom_center" <?php if($slider['heading_position'] == 'bottom_center') { echo "selected='selected'"; } ?>><?php echo $text_bottom_center; ?></option>
									<option value="bottom_right" <?php if($slider['heading_position'] == 'bottom_right') { echo "selected='selected'"; } ?>><?php echo $text_bottom_right; ?></option>
									
									<option value="custom" <?php if($slider['heading_position'] == 'custom') { echo "selected='selected'"; } ?>><?php echo $text_custom; ?></option>
								</select>
								
								<div class="heading-custom-<?php echo $slider_row; ?>" style="margin-top: 20px; <?php if($slider['heading_position'] == 'custom') { echo "display:block";} else { echo "display:none"; } ?>">
									<input type="text" name="slider_detail[<?php echo $slider_row; ?>][heading_x_size]" value="<?php echo $slider['heading_x_size']; ?>" placeholder="<?php echo $entry_custom_position_x; ?>" class="form-control" style="margin-bottom: 20px;" />
									
									<input type="text" name="slider_detail[<?php echo $slider_row; ?>][heading_y_size]" value="<?php echo $slider['heading_y_size']; ?>" placeholder="<?php echo $entry_custom_position_y; ?>" class="form-control" />
								</div>
								
							</td>
							
							<td class="text-left" style="width: 20%;">
								<input type="text" name="slider_detail[<?php echo $slider_row; ?>][caption]" value="<?php echo $slider['caption']; ?>" placeholder="<?php echo $entry_caption_detail; ?>" class="form-control" style="margin-bottom: 20px;" />
								
								<select name="slider_detail[<?php echo $slider_row; ?>][caption_position]" class="form-control" onchange="getCaptionPosition(this.value, <?php echo $slider_row; ?>);">
									<option value="top_left" <?php if($slider['caption_position'] == 'top_left') { echo "selected='selected'"; } ?>><?php echo $text_top_left; ?></option>
									<option value="top_center" <?php if($slider['caption_position'] == 'top_cener') { echo "selected='selected'"; } ?>><?php echo $text_top_center; ?></option>
									<option value="top_right" <?php if($slider['caption_position'] == 'top_right') { echo "selected='selected'"; } ?>><?php echo $text_top_right; ?></option>
									
									<option value="center_left" <?php if($slider['caption_position'] == 'center_left') { echo "selected='selected'"; } ?>><?php echo $text_center_left; ?></option>
									<option value="center_center" <?php if($slider['caption_position'] == 'center_center') { echo "selected='selected'"; } ?>><?php echo $text_center_center; ?></option>
									<option value="center_right" <?php if($slider['caption_position'] == 'center_right') { echo "selected='selected'"; } ?>><?php echo $text_center_right; ?></option>
									
									<option value="bottom_left" <?php if($slider['caption_position'] == 'bottom_left') { echo "selected='selected'"; } ?>><?php echo $text_bottom_left; ?></option>
									<option value="bottom_center" <?php if($slider['caption_position'] == 'bottom_center') { echo "selected='selected'"; } ?>><?php echo $text_bottom_center; ?></option>
									<option value="bottom_right" <?php if($slider['caption_position'] == 'bottom_right') { echo "selected='selected'"; } ?>><?php echo $text_bottom_right; ?></option>
									
									<option value="custom" <?php if($slider['caption_position'] == 'custom') { echo "selected='selected'"; } ?>><?php echo $text_custom; ?></option>
								</select>
								
								<div class="caption-custom-<?php echo $slider_row; ?>" style="margin-top: 20px; <?php if($slider['caption_position'] == 'custom') { echo "display:block";} else { echo "display:none"; } ?>">
									<input type="text" name="slider_detail[<?php echo $slider_row; ?>][caption_x_size]" value="<?php echo $slider['caption_x_size']; ?>" placeholder="<?php echo $entry_custom_position_x; ?>" class="form-control" style="margin-bottom: 20px;" />
									
									<input type="text" name="slider_detail[<?php echo $slider_row; ?>][caption_y_size]" value="<?php echo $slider['caption_y_size']; ?>" placeholder="<?php echo $entry_custom_position_y; ?>" class="form-control" />
								</div>
								
							</td>
							
							<td class="text-left" style="width: 30%;">
								<input type="text" name="slider_detail[<?php echo $slider_row; ?>][button_text]" value="<?php echo $slider['button_text']; ?>" placeholder="<?php echo $entry_button; ?>" class="form-control" style="margin-bottom: 20px;" />
								
								<input type="text" name="slider_detail[<?php echo $slider_row; ?>][link]" value="<?php echo $slider['link']; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" style="margin-bottom: 20px;" />
								
								<select name="slider_detail[<?php echo $slider_row; ?>][button_position]" class="form-control" onchange="getButtonPosition(this.value, <?php echo $slider_row; ?>);">
									<option value="top_left" <?php if($slider['button_position'] == 'top_left') { echo "selected='selected'"; } ?>><?php echo $text_top_left; ?></option>
									<option value="top_center" <?php if($slider['button_position'] == 'top_cener') { echo "selected='selected'"; } ?>><?php echo $text_top_center; ?></option>
									<option value="top_right" <?php if($slider['button_position'] == 'top_right') { echo "selected='selected'"; } ?>><?php echo $text_top_right; ?></option>
									
									<option value="center_left" <?php if($slider['button_position'] == 'center_left') { echo "selected='selected'"; } ?>><?php echo $text_center_left; ?></option>
									<option value="center_center" <?php if($slider['button_position'] == 'center_center') { echo "selected='selected'"; } ?>><?php echo $text_center_center; ?></option>
									<option value="center_right" <?php if($slider['button_position'] == 'center_right') { echo "selected='selected'"; } ?>><?php echo $text_center_right; ?></option>
									
									<option value="bottom_left" <?php if($slider['button_position'] == 'bottom_left') { echo "selected='selected'"; } ?>><?php echo $text_bottom_left; ?></option>
									<option value="bottom_center" <?php if($slider['button_position'] == 'bottom_center') { echo "selected='selected'"; } ?>><?php echo $text_bottom_center; ?></option>
									<option value="bottom_right" <?php if($slider['button_position'] == 'bottom_right') { echo "selected='selected'"; } ?>><?php echo $text_bottom_right; ?></option>
									
									<option value="custom" <?php if($slider['button_position'] == 'custom') { echo "selected='selected'"; } ?>><?php echo $text_custom; ?></option>
								</select>
								
								<div class="button-custom-<?php echo $slider_row; ?>" style="margin-top: 20px; <?php if($slider['button_position'] == 'custom') { echo "display:block";} else { echo "display:none"; } ?>">
									<input type="text" name="slider_detail[<?php echo $slider_row; ?>][button_x_size]" value="<?php echo $slider['button_x_size']; ?>" placeholder="<?php echo $entry_custom_position_x; ?>" class="form-control" style="margin-bottom: 20px;" />
									
									<input type="text" name="slider_detail[<?php echo $slider_row; ?>][button_y_size]" value="<?php echo $slider['button_y_size']; ?>" placeholder="<?php echo $entry_custom_position_y; ?>" class="form-control" />
								</div>
							</td>
							
							<td class="text-left" style="width: 20%;">
								<select name="slider_detail[<?php echo $slider_row; ?>][transition]" class="form-control">
									<option value="random" <?php if($slider['transition'] == 'random') { echo "selected='selected'"; } ?>><?php echo $text_random; ?></option>
									<option value="fade" <?php if($slider['transition'] == 'fade') { echo "selected='selected'"; } ?>><?php echo $text_fade; ?></option>
									<option value="zoomout" <?php if($slider['transition'] == 'zoomout') { echo "selected='selected'"; } ?>><?php echo $text_zoomout; ?></option>
								</select>
							</td>

							<td class="text-left"><button type="button" onclick="$('#slider-row<?php echo $slider_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
							
						</tr>
						<?php $slider_row++; ?>
					<?php } ?>
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="5"></td>
						<td class="text-left"><button type="button" onclick="addSlider();" data-toggle="tooltip" title="<?php echo $button_slider_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					</tr>
				</tfoot>
				
			</table>
			
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	var slider_row = <?php echo $slider_row; ?>;
	
	function addSlider() {
		html  = '<tr id="slider-row' + slider_row + '">';
		
		html += '  <td class="text-left"><a href="" id="thumb-image' + slider_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="slider_detail[' + slider_row + '][image]" value="" id="input-image' + slider_row + '" /></td>';
		
		html += '  <td class="text-left" style="width: 30%;"><input type="text" name="slider_detail[' + slider_row + '][heading]" value="" placeholder="<?php echo $entry_heading_detail; ?>" class="form-control" style="margin-bottom: 20px;" /><select name="slider_detail[' + slider_row + '][heading_position]" class="form-control" onchange="getHeadingPosition(this.value, ' + slider_row + ');"><option value="top_left"><?php echo $text_top_left; ?></option><option value="top_center"><?php echo $text_top_center; ?></option><option value="top_right"><?php echo $text_top_right; ?></option><option value="center_left"><?php echo $text_center_left; ?></option><option value="center_center"><?php echo $text_center_center; ?></option><option value="center_right"><?php echo $text_center_right; ?></option><option value="bottom_left"><?php echo $text_bottom_left; ?></option><option value="bottom_center"><?php echo $text_bottom_center; ?></option><option value="bottom_right"><?php echo $text_bottom_right; ?></option><option value="custom"><?php echo $text_custom; ?></option></select><div class="heading-custom-' + slider_row +'" style="margin-top: 20px; display: none;"><input type="text" name="slider_detail[' + slider_row + '][heading_x_size]" value="" placeholder="<?php echo $entry_custom_position_x; ?>" class="form-control" style="margin-bottom: 20px;" /><input type="text" name="slider_detail[' + slider_row + '][heading_y_size]" value="" placeholder="<?php echo $entry_custom_position_y; ?>" class="form-control" style="margin-bottom: 20px;" /></div></td>';	
		
		html += '<td class="text-left" style="width: 30%;"><input type="text" name="slider_detail[' + slider_row + '][caption]" value="" placeholder="<?php echo $entry_caption_detail; ?>" class="form-control" style="margin-bottom: 20px;" /><select name="slider_detail[' + slider_row + '][caption_position]" class="form-control" onchange="getCaptionPosition(this.value, ' + slider_row + ');"><option value="top_left"><?php echo $text_top_left; ?></option><option value="top_center"><?php echo $text_top_center; ?></option><option value="top_right"><?php echo $text_top_right; ?></option><option value="center_left"><?php echo $text_center_left; ?></option><option value="center_center"><?php echo $text_center_center; ?></option><option value="center_right"><?php echo $text_center_right; ?></option><option value="bottom_left"><?php echo $text_bottom_left; ?></option><option value="bottom_center"><?php echo $text_bottom_center; ?></option><option value="bottom_right"><?php echo $text_bottom_right; ?></option><option value="custom"><?php echo $text_custom; ?></option></select><div class="caption-custom-' + slider_row +'" style="margin-top: 20px; display: none;"><input type="text" name="slider_detail[' + slider_row + '][caption_x_size]" value="" placeholder="<?php echo $entry_custom_position_x; ?>" class="form-control" style="margin-bottom: 20px;" /><input type="text" name="slider_detail[' + slider_row + '][caption_y_size]" value="" placeholder="<?php echo $entry_custom_position_y; ?>" class="form-control" style="margin-bottom: 20px;" /></div></td>';
		
		html += '  <td class="text-left" style="width: 30%;"><input type="text" name="slider_detail[' + slider_row + '][button_text]" value="" placeholder="<?php echo $entry_button; ?>" class="form-control" style="margin-bottom: 20px;" /><input type="text" name="slider_detail[' + slider_row + '][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" style="margin-bottom: 20px;" /><select name="slider_detail[' + slider_row + '][button_position]" class="form-control" onchange="getButtonPosition(this.value, ' + slider_row + ');"><option value="top_left"><?php echo $text_top_left; ?></option><option value="top_center"><?php echo $text_top_center; ?></option><option value="top_right"><?php echo $text_top_right; ?></option><option value="center_left"><?php echo $text_center_left; ?></option><option value="center_center"><?php echo $text_center_center; ?></option><option value="center_right"><?php echo $text_center_right; ?></option><option value="bottom_left"><?php echo $text_bottom_left; ?></option><option value="bottom_center"><?php echo $text_bottom_center; ?></option><option value="bottom_right"><?php echo $text_bottom_right; ?></option><option value="custom"><?php echo $text_custom; ?></option></select><div class="button-custom-' + slider_row +'" style="margin-top: 20px; display: none;"><input type="text" name="slider_detail[' + slider_row + '][button_x_size]" value="" placeholder="<?php echo $entry_custom_position_x; ?>" class="form-control" style="margin-bottom: 20px;" /><input type="text" name="slider_detail[' + slider_row + '][button_y_size]" value="" placeholder="<?php echo $entry_custom_position_y; ?>" class="form-control" style="margin-bottom: 20px;" /></div></td>';	
		
		html += '  <td class="text-left" style="width: 20%;"><select name="slider_detail[' + slider_row + '][transition]" class="form-control"><option value="random"><?php echo $text_random; ?></option><option value="fade"><?php echo $text_fade; ?></option><option value="zoomout"><?php echo $text_zoomout; ?></option></select></td>';
		
		html += '  <td class="text-left"><button type="button" onclick="$(\'#slider-row' + slider_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		
		html += '</tr>';
		
		$('#rev-slider tbody').append(html);
	
		slider_row++;
	}
	
	function getHeadingPosition(value, slider_row) {
		if(value == 'custom') {
			$('.heading-custom-' + slider_row).css('display', 'block');
		} else {
			$('.heading-custom-' + slider_row).css('display', 'none');
		}
	}
	
	function getCaptionPosition(value, slider_row) {
		if(value == 'custom') {
			$('.caption-custom-' + slider_row).css('display', 'block');
		} else {
			$('.caption-custom-' + slider_row).css('display', 'none');
		}
	}
	
	function getButtonPosition(value, slider_row) {
		if(value == 'custom') {
			$('.button-custom-' + slider_row).css('display', 'block');
		} else {
			$('.button-custom-' + slider_row).css('display', 'none');
		}
	}
	
</script>

<?php echo $footer; ?>
