<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
        <div class="container-fluid">
          <h1><i class="fa fa-newspaper-o"></i>&nbsp;<?php echo $heading_title; ?></h1>
          <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
    </div>
  <div class="container-fluid">
    <!-- <?php echo (empty($data['easynewslettersubscription']['LicensedOn'])) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNpc2Vuc2Vfc3VwcG9ydF0nKS50cmlnZ2VyKCdjbGljaycpIj5FbnRlciB5b3VyIGxpY2Vuc2UgY29kZTwvYT4NCiAgICA8L2Rpdj4=') : '' ?> -->

    <?php if ($error_warning) { ?>
            <div class="alert alert-danger autoSlideUp"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
             <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <script>$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
    <?php } ?>

    <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="storeSwitcherWidget">
                        <div class="form-group">
                               <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo $store['name']; if($store['store_id'] == 0) echo ' <strong>('.$text_default.')</strong>'; ?>&nbsp;<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <?php foreach ($stores as $st) { ?> <li> <a href="index.php?route=extension/module/easynewslettersubscription&store_id=<?php echo $st['store_id'];?>&token=<?php echo $token; ?>"><?php echo $st['name']; ?></a></li><?php } ?> 
                                </ul>
                         </div>
                    </div>
                    <h3 class="panel-title"><i class="fa fa-list"></i>&nbsp;<span style="vertical-align:middle;font-weight:bold;">Module settings</span></h3>
                </div>

                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                        <input type="hidden" name="easynewslettersubscription_status" value="1" />
                        <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />
                        <div class="tabbable">
                            <div class="tab-navigation form-inline">
                                <ul class="nav nav-tabs mainMenuTabs">
                                    <li class="active"><a href="#orders" data-toggle="tab"><i class="icon-list"></i> Subscribers List</a></li>
                                    <li><a href="#controlpanel" data-toggle="tab"><i class="icon-wrench"></i> Settings</a></li>
                                    <!-- <li><a href="#isense_support" data-toggle="tab"><i class="icon-share"></i> Support</a></li> -->
                                </ul>
                                <div class="tab-buttons">
                                    <button type="submit" class="btn btn-success save-changes"><i class="fa fa-check"></i>&nbsp;<?php echo $save_changes?></button>
                                    <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><span style="top: 2px; !important;" class="glyphicon glyphicon-remove"></span>&nbsp;<?php echo $button_cancel?></a>
                                </div>
                            </div><!-- /.tab-navigation --> 
                            <div class="tab-content">
                              <div id="orders" class="tab-pane active">
                                      <?php require_once(DIR_APPLICATION.'view/template/extension/module/easynewslettersubscription/tab_viewsubscribers.php'); ?>                        
                              </div>
                              <div id="controlpanel" class="tab-pane">
                                      <?php require_once(DIR_APPLICATION.'view/template/extension/module/easynewslettersubscription/tab_settings.php'); ?>                        
                              </div>         
                              <!-- <div id="isense_support" class="tab-pane">
                                      <?php require_once(DIR_APPLICATION.'view/template/extension/module/easynewslettersubscription/tab_support.php'); ?>                        
                              </div> -->
                            </div><!-- /.tab-content -->
                        </div><!-- /.tabbable -->
                    </form>
                </div>
    </div>  

  </div>
</div>
<script>
if (window.localStorage && window.localStorage['currentTab']) {
	$('.mainMenuTabs a[href='+window.localStorage['currentTab']+']').trigger('click');  
}
if (window.localStorage && window.localStorage['currentSubTab']) {
	$('a[href='+window.localStorage['currentSubTab']+']').trigger('click');  
}
$('.fadeInOnLoad').css('visibility','visible');
$('.mainMenuTabs a[data-toggle="tab"]').click(function() {
	if (window.localStorage) {
		window.localStorage['currentTab'] = $(this).attr('href');
	}
});
$('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"])').click(function() {
	if (window.localStorage) {
		window.localStorage['currentSubTab'] = $(this).attr('href');
	}
});
</script>
<?php echo $footer; ?>