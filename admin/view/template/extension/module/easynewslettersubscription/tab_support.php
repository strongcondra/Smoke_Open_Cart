<div class="container-fluid">
  <div class="row">
      <div class="col-md-4">
        <div class="box-heading">
            <h3><i class="fa fa-user"></i>&nbsp;Your License</h3>
        </div>
      <?php if (empty($data['easynewslettersubscription']['LicensedOn'])): ?>
          <div class="licenseAlerts"></div>
          <div class="licenseDiv"></div>
                <table class="table notLicensedTable">
                  <tr>
                      <td colspan="2">
                            <div class="form-group">
                                <label for="moduleLicense">Please enter your product purchase license code</label>
                                <input type="text" class="licenseCodeBox form-control" placeholder="License Code e.g. XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX" name="easynewslettersubscription[LicenseCode]" id="moduleLicense" value="<?php echo !empty($data['easynewslettersubscription']['LicenseCode']) ? $data['easynewslettersubscription']['LicenseCode'] : ''?>" />
                            </div>
                            <button type="button" class="btn btn-success btnActivateLicense"><i class="icon-ok"></i> Activate License</button>
                          <div class="pull-right"><button type="button" class="btn btn-link small-link" onclick="window.open('http://isenselabs.com/users/purchases/')">Not having a code? Get it from here.&nbsp;<i class="fa fa-external-link"></i></button></div>
                      </td>
                  </tr>
                </table>
        <?php 
                    $hostname = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;
                    $hostname = (strstr($hostname,'http://') === false) ? 'http://'.$hostname: $hostname;
                ?>
        <script type="text/javascript">
                var domain='<?php echo base64_encode($hostname); ?>';
                var domainraw='<?php echo $hostname; ?>';
                var timenow=<?php echo time(); ?>;
                var MID = 'P7DF5M3MXA';
                </script>
                <script type="text/javascript" src="view/javascript/val.js"></script>
        <?php endif; ?>
    
      <?php if (!empty($data['easynewslettersubscription']['LicensedOn'])): ?>
          <input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="<?php echo base64_encode(json_encode($data['easynewslettersubscription']['License'])); ?>" />
          <input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="<?php echo $data['easynewslettersubscription']['LicensedOn']; ?>" />
          <table class="table licensedTable">
                    <tr>
                      <td>License Holder</td>
                      <td><?php echo $data['easynewslettersubscription']['License']['customerName']; ?></td>
                    </tr>
                  <tr>
                    <td>Registered domains</td>
                    <td>
                        <ul class="registeredDomains">
                          <?php foreach ($data['easynewslettersubscription']['License']['licenseDomainsUsed'] as $domain): ?>
                              <li><i class="fa fa-check"></i>&nbsp;<?php echo $domain; ?></li>
                          <?php endforeach; ?>
                        </ul>
                    </td>
                  </tr>
                  <tr>
                    <td>License Expires on</td>
                    <td><?php echo date("F j, Y",strtotime($data['easynewslettersubscription']['License']['licenseExpireDate'])); ?></td>
                  </tr>
                  <tr>
                      <td colspan="2" style="text-align:center;background-color:#EAF7D9;">VALID LICENSE ( <a href="http://isenselabs.com/users/purchases" target="_blank">manage</a> )</td>
                  </tr>
        </table>
        <?php endif; ?>
      </div>
  
    <div class="col-md-8">
        <div class="box-heading">
            <h3><i class="fa fa-users"></i>&nbsp;Get Support</h3>
        </div>
        <div class="box-content">
              <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <img alt="Community support" style="width: 300px;" src="view/image/easynewslettersubscription/community.png">
                                <div class="caption" style="text-align:center;padding-top:0px;">
                                    <h3>Community</h3>
                                    <p>Ask the community about your issue on the iSenseLabs forum. </p>
                                    <p style="padding-top: 5px;"><a href="http://isenselabs.com/forum" target="_blank" class="btn btn-lg btn-default">Browse forums</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <img data-src="holder.js/300x200" alt="Ticket support" style="width: 300px;" src="view/image/easynewslettersubscription/tickets.png">
                                <div class="caption" style="text-align:center;padding-top:0px;">
                                    <h3>Tickets</h3>
                                    <p>Want to comminicate one-to-one with our tech people? Then open a support ticket.</p>
                                    <p style="padding-top: 5px;"><a href="http://isenselabs.com/tickets/open/<?php echo base64_encode('Support Request').'/'.base64_encode('102').'/'. base64_encode($_SERVER['SERVER_NAME']); ?>" target="_blank" class="btn btn-lg btn-default">Open a support ticket</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <img alt="Pre-sale support" style="width: 300px;" src="view/image/easynewslettersubscription/pre-sale.png">
                                <div class="caption" style="text-align:center;padding-top:0px;">
                                    <h3>Pre-sale</h3>
                                    <p>Have a brilliant idea for your webstore? Our team of top-notch developers can make it real.</p>
                                    <p style="padding-top: 5px;"><a href="mailto:sales@isenselabs.com?subject=Pre-sale question" target="_blank" class="btn btn-lg btn-default">Bump the sales</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
            </div>
    </div>
  </div>
</div>