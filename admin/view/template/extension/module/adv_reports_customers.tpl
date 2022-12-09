<?php echo $header; ?>
<style type="text/css">
.wrap-url {
	-ms-word-break: break-all;
	word-break: break-all;
	word-break: break-word;
	-webkit-hyphens: auto;
   	-moz-hyphens: auto;
	hyphens: auto;
}
</style>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>&nbsp;<button type="button" onclick="window.open('http://www.opencartreports.com/documentation/co/index.html');" title="<?php echo $button_documentation; ?>" class="btn btn-warning"><i class="fa fa-book"></i> <?php echo $button_documentation; ?></button></div>
      <h1><?php echo $heading_title_main; ?></h1>
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
          <ul class="nav nav-tabs">
          <li class="active"><a id="about" href="#tab_about" data-toggle="tab"><?php echo $tab_about; ?></a></li>
      	  </ul>     
          <div class="tab-content">
            <div id="tab_about" class="tab-pane active">
     		<div id="adv_customers"></div>
			<div align="center" class="wrapper col-md-12"><a href="http://www.opencartreports.com" target="_blank"><img class="img-responsive" src="view/image/adv_reports/adv_logo.png" /></a></div> 
			</div>
          </div>
	  </div>
    </div>
  </div>
</div> 
<?php if ($version && $version != $adv_current_version) { ?>  
<script type="text/javascript"><!--
$('#about').append('&nbsp;<i class=\"fa fa-exclamation-circle\"></i>'); 
$('#about').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red','text-decoration': 'blink'});
//--></script> 
<?php } ?>
<?php echo $footer; ?>