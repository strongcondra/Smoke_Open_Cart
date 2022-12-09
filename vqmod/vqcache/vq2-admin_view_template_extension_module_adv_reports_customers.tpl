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
<?php if (file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) { include(DIR_APPLICATION . 'model/module/adv_settings.php'); } ?><?php if (!$ldata) { include(DIR_APPLICATION . 'view/image/adv_reports/line.png'); } ?>
          <ul class="nav nav-tabs">
          <li class="active"><a id="about" href="#tab_about" data-toggle="tab"><?php echo $tab_about; ?></a></li>
      	  </ul>     
          <div class="tab-content">
            <div id="tab_about" class="tab-pane active">
     							
	<div style="background-color:#edf6ff; border:thin solid #69F; margin-bottom:10px;">
      <table class="table table-bordered">
       <tr>
        <td style="width:20%;"><?php echo $adv_text_ext_name; ?></td>
        <td style="width:80%;"><span style="font-size:small; font-weight:bold;"><?php echo $adv_ext_name; ?></span></td>
       </tr>
       <tr>
        <td><?php echo $adv_text_instal_version; ?></td>
        <td><b><?php echo $adv_ext_version; ?></b> [ <?php echo $adv_ext_type; ?> ]</td>
       </tr>
<?php if ($version) { ?>
<?php if ($version != $adv_current_version) { ?>  
       <tr>
        <td><span style="color:red"><strong><?php echo $adv_text_latest_version; ?></strong></span></td>
        <td><div id="adv_new_version"></div> <div id="adv_what_is_new"></div></td>
       </tr>	
<?php } ?>
<?php } ?>
       <tr>
        <td><?php echo $adv_text_ext_compatibility; ?></td>
        <td><?php echo $adv_ext_compatibility; ?></td>
       </tr>
       <tr>
        <td><?php echo $adv_text_ext_url; ?></td>
        <td><span class="wrap-url"><a href="<?php echo $adv_ext_url; ?>" target="_blank"><?php echo $adv_ext_url ?></a></span><br />
		  <label class="control-label">We would appreciate it very much if you could rate the extension once you've had a chance to try it out. Why not tell everybody how great this extension is by leaving a comment as well.</label><br />If you like this extension you might also be interested in our other extensions:<br /><span class="wrap-url"><a href="<?php echo $adv_all_ext_url; ?>" target="_blank"><?php echo $adv_all_ext_url ?></a></span>
		  </td>
       </tr>
        <td><?php echo $adv_text_ext_support; ?></td>
        <td>
          <a href="mailto:<?php echo $adv_ext_support; ?>?subject=<?php echo $adv_ext_subject; ?>" target="_blank"><?php echo $adv_ext_support; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a href="<?php echo $adv_help_url; ?>" target="_blank"><i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> <?php echo $text_asking_help; ?></a>
        </td>
       </tr>
<?php if ($servers) { ?>
       <tr>
        <td><?php echo $adv_text_reg_status; ?></td>
        <td><?php echo $lstatus; ?></td>
       </tr>	
<?php if ($llicense) { ?>	   	   	   
       <tr>
        <td><?php echo $adv_text_reg_info; ?></td>
        <td><?php echo $llicense; ?><?php echo $ldomain; ?></td>
       </tr>		    
<?php } ?>
<?php } ?>	   
       <tr>
        <td><?php echo $adv_text_ext_legal; ?></td>
        <td><?php echo $adv_text_copyright; ?>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a href="<?php echo $adv_legal_notice_url; ?>" target="_blank"><?php echo $text_terms; ?></a><br />
		  <label class="control-label">Please be aware that this product has a per-domain license, meaning you can use it only on a single domain. You will need to purchase a separate license for each domain you wish to use this extension on.</label>
		</td>
       </tr>
      </table>
	 </div>
            
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

<?php if ($version) { ?>
<?php if ($version != $adv_current_version) { ?>   
<script type="text/javascript">
$('#adv_new_version').append('<span style="color:red"><strong><?php echo $version; ?></strong></span>');
$('#adv_what_is_new').append('<?php echo html_entity_decode(str_replace("@@@","<br>",$whats_new), ENT_QUOTES, "UTF-8"); ?> ');
</script>
<?php } ?>
<?php } ?>
            
<?php echo $footer; ?>