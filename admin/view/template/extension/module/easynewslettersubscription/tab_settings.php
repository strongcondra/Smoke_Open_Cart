<table class="table">
  <tbody>
    <tr>
      <td class="col-xs-3">
        <h5><strong><span class="required">*</span> <?php echo $entry_code; ?></strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enable or disable EasyNewsletterSubscription.</span>
      </td>
      <td>
        <div class="col-xs-4">
          <select name="easynewslettersubscription[Enabled]" class="EasyNewsletterSubscriptionEnabled form-control">
              <option value="yes" <?php echo (!empty($data['easynewslettersubscription']['Enabled']) && $data['easynewslettersubscription']['Enabled'] == 'yes') ? 'selected=selected' : '' ?>>Enabled</option>
             <option value="no" <?php echo (empty($data['easynewslettersubscription']['Enabled']) || $data['easynewslettersubscription']['Enabled'] == 'no') ? 'selected=selected' : '' ?>>Disabled</option>
          </select>
        </div>
     </td>
    </tr>
      <tr>
      <td class="col-xs-3">
        <h5><strong>Form Fields</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose which fields to be filled by the customers</span>
      </td>
      <td>
        <div class="col-xs-4">
          <select name="easynewslettersubscription[FormFields]" class="EasyNewsletterSubscriptionFormFields form-control">
              <option value="name_email" <?php echo (!empty($data['easynewslettersubscription']['FormFields']) && $data['easynewslettersubscription']['FormFields'] == 'name_email') ? 'selected=selected' : '' ?>>Name and Email</option>
             <option value="email" <?php echo (!empty($data['easynewslettersubscription']['FormFields']) && $data['easynewslettersubscription']['FormFields'] == 'email') ? 'selected=selected' : '' ?>>Only Email</option>
          </select>
        </div>
     </td>
    </tr>
        <tr>
      <td class="col-xs-3"><h5><strong>Wrap in widget</strong></h5></td>
      <td>
        <div class="col-xs-4">
          <select name="easynewslettersubscription[WrapInWidget]" class="EasyNewsletterSubscriptionWrapInWidget form-control">
              <option value="yes" <?php echo (!empty($data['easynewslettersubscription']['WrapInWidget']) && $data['easynewslettersubscription']['WrapInWidget'] == 'yes') ? 'selected=selected' : '' ?>>Enabled</option>
             <option value="no" <?php echo (!empty($data['easynewslettersubscription']['WrapInWidget']) && $data['easynewslettersubscription']['WrapInWidget'] == 'no') ? 'selected=selected' : '' ?>>Disabled</option>
          </select>
        </div>
     </td>
    </tr>
     <tr>
  <td class="col-xs-3">Custom Text<span class="help">Accepts basic HTML tags</span></td>
  <td>
    <div class="col-xs-12">
      <div class="input-group">
          <?php $i=0; foreach ($languages as $language) { ?>
              <span class="input-group-addon"><img src="language/en-gb/en-<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span> 
              <textarea id="description_<?php echo $language['code']; ?>" name="easynewslettersubscription[CustomText][<?php echo $language['code']; ?>]" style="height:100px;"  class="EasyNewsletterSubscriptionCustomText form-control"><?php echo (isset($data['easynewslettersubscription']['CustomText'][$language['code']])) ? $data['easynewslettersubscription']['CustomText'][$language['code']] : 'Want to keep up to date with all our latest products?<br /><br />Enter your email below to get added to our mailing list.' ?></textarea>
          <?php $i++; } ?>
      </div>
    </div>
  </td>
  </tr>
      <tr>
      <td class="col-xs-3">Unsubscribe link:<span class="help">Adds unsubscribe link to the end of the mail</span></td>
      <td>
        <div class="col-xs-4">
          <select name="easynewslettersubscription[Unsubscribe]" class="EasyNewsletterSubscriptionUnsubscribe form-control">
              <option value="yes" <?php echo (!empty($data['easynewslettersubscription']['Unsubscribe']) && $data['easynewslettersubscription']['Unsubscribe'] == 'yes') ? 'selected=selected' : '' ?>>Enabled</option>
             <option value="no" <?php echo (!empty($data['easynewslettersubscription']['Unsubscribe']) && $data['easynewslettersubscription']['Unsubscribe'] == 'no') ? 'selected=selected' : '' ?>>Disabled</option>
          </select>
        </div>
     </td>
    </tr>
    <tr>
  <td class="col-xs-3">Custom CSS</td>
  <td>
    <div class="col-xs-4">
      <textarea name="easynewslettersubscription[CustomCSS]" class="EasyNewsletterSubscriptionCustomCSS form-control"><?php echo (isset($data['easynewslettersubscription']['CustomCSS'])) ? $data['easynewslettersubscription']['CustomCSS'] : '' ?>
      </textarea>
    </div>
  </td>
  </tr>
    <tr class="EasyNewsletterSubscriptionActiveTR">
       <td colspan="2">
    
  <script type="text/javascript">
  var toggleCSScheckbox = function() {
  	$('input[type=checkbox][id^=buttonPosCheckbox]').each(function(index, element) {
  		if ($(this).is(':checked')) {
  			$($(this).attr('data-textinput')).removeAttr('disabled');
  		} else {
  			$($(this).attr('data-textinput')).attr('disabled','disabled');
  		}
  	});
  }
  var createBinds = function() {
  	$('input[type=checkbox][id^=buttonPosCheckbox]').unbind('change').bind('change', function() {
  		toggleCSScheckbox();
  	});
  	
  	$('.buttonPositionOptionBox').unbind('change').bind('çhange', function() {
  		$($(this).attr('data-checkbox')).removeAttr('checked');
  		toggleCSScheckbox();
  	});
  };
  toggleCSScheckbox();
  createBinds();
  </script>

       </td>
    </tr>
  <tbody>
</table>
<script>
$('.EasyNewsletterSubscriptionLayout input[type=checkbox]').change(function() {
    if ($(this).is(':checked')) { 
        $('.EasyNewsletterSubscriptionItemStatusField', $(this).parent()).val(1);
    } else {
        $('.EasyNewsletterSubscriptionItemStatusField', $(this).parent()).val(0);
    }
});
$('.EasyNewsletterSubscriptionEnabled').change(function() {
    toggleEasyNewsletterSubscriptionActive(true);
});
var toggleEasyNewsletterSubscriptionActive = function(animated) {
   if ($('.EasyNewsletterSubscriptionEnabled').val() == 'yes') {
        if (animated) 
            $('.EasyNewsletterSubscriptionActiveTR').fadeIn();
        else 
            $('.EasyNewsletterSubscriptionActiveTR').show();
    } else {
        if (animated) 
            $('.EasyNewsletterSubscriptionActiveTR').fadeOut();
        else 
            $('.EasyNewsletterSubscriptionActiveTR').hide();
    }
}
toggleEasyNewsletterSubscriptionActive(false);
</script>

<script type="text/javascript">
            <?php foreach ($languages as $language) { ?>
                $('#description_<?php echo $language['code']; ?>').summernote({
          height:100
          });
            <?php } ?>
</script>