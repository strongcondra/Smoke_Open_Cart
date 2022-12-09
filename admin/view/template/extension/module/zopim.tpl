<?php
#################################################################
## Open Cart Module:  ZOPIM LIVE CHAT WIDGET 			       ##
##-------------------------------------------------------------##
## Copyright © 2016 MB "Programanija" All rights reserved.     ##
## http://www.opencartextensions.eu						       ##
## http://www.programanija.com 	    					       ##
##-------------------------------------------------------------##
## Permission is hereby granted, when purchased, to  use this  ##
## mod on one domain. This mod may not be reproduced, copied,  ##
## redistributed, published and/or sold.				       ##
##-------------------------------------------------------------##
## Violation of these rules will cause loss of future mod      ##
## updates and account deletion				      			   ##
#################################################################
?>

<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-zopim" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title_m; ?></h1>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-zopim" class="form-horizontal">
         
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="zopim_code"><?php echo $entry_zopim_code; ?></label>
            <div class="col-sm-10">
              <textarea name="zopim_code" id="zopim-code" class="form-control"><?php echo $zopim_code; ?></textarea>

              <?php if ($error_zopim_code) { ?>
              <div class="text-danger"><?php echo $error_zopim_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
            <div class="col-sm-10">
              <select name="zopim_status" id="input-zopim" class="form-control">
                <option value="<?php echo $text_off;?>" <?php echo ($zopim_status == $text_off ? ' selected="selected"' : '')?>><?php echo $text_off; ?></option>
                <option value="<?php echo $text_on;?>" <?php echo ($zopim_status == $text_on ? ' selected="selected"' : '')?>><?php echo $text_on; ?></option>
              </select>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 