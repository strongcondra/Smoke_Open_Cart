<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
				<div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="headermenu_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($headermenu_description[$language['language_id']]) ? $headermenu_description[$language['language_id']]['title'] : ''; ?>" />
					  <?php if (isset($error_name[$language['language_id']])) { ?>
					  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
					  <?php } ?>
                    </div>
                </div>
				</div>
				<?php } ?> 
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_link; ?></label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="link" value="<?php echo $link; ?>" size="100" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_level1; ?></label>
					<div class="col-sm-10">
						<select class="form-control" name="level1" >
						<option value=""><?php echo $text_select ?> </option> 
						<?php if($headermenu) { foreach($headermenu as $menu){
							if($menu['headermenu_id']==$level1)
								{
									$sel="selected";
								}
							else
								{
									$sel="";
								}
						?>
						<option <?php echo $sel; ?> value="<?php echo $menu['headermenu_id'] ?>">
							<?php echo $menu['title'] ?></option> 
						<?php }} ?>
					    </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_level2; ?></label>
					<div class="col-sm-10">
						<select class="form-control" name="level2" >
							<option value=""><?php echo $text_select ?> </option> 
							<?php if($headermenu1) { foreach($headermenu1 as $menu){
								if($menu['headermenu_id']==$level2){
									$sel="selected";
								} else {
									$sel="";
								}
							?>
							<option <?php echo $sel; ?> value="<?php echo $menu['headermenu_id'] ?>">
								<?php echo $menu['title'] ?>
							</option> 
							<?php }} ?>	
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_column; ?></label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="column" value="<?php echo $column; ?>" size="10" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_position; ?></label>
					<div class="col-sm-10">
						<select class="form-control" name="position">
						  <?php if ($position == 'left') { ?>
						  <option value="left" selected="selected"><?php echo $text_left; ?></option>
						  <option value="right"><?php echo $text_right; ?></option>
						  <?php } else { ?>
						  <option value="left"><?php echo $text_left; ?></option>
						  <option value="right" selected="selected"><?php echo $text_right; ?></option>
						  <?php } ?>
						</select>				
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
					<div class="col-sm-10">
						<select class="form-control" name="status">
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
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="sort_order" value="<?php echo $sort_order; ?>" size="10" />
					</div>
				</div>
			
			
          </div>
          </div>
          </div>
      </form>
	  </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>  
<script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script>
<?php echo $footer; ?>