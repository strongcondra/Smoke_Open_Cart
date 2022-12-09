<div class="container">
    <div class="row">
	  <span class="footer-img"><img src="<?php echo $footer_logo; ?>" class="img-responsive" alt=""/></span>
	</div>
<span style="color: rgb(0, 0, 0); font-family: Arial, Helvetica, FreeSans, sans-serif; font-weight: 700;font-size: 11px;text-align:justify;display:block;">
    Warning: The sale of Tobacco products or electronic smoking devices to persons under 21 is prohibited. This product has not been approved by the U.S. FDA as a cessation device and therefore should not be used to quit smoking. This product is not intended to diagnose, treat, cure or mitigate any disease or medical condition. Do not use this product if you are under the legal age of smoking or if you are sensitive to nicotine or inhalants. Do not use this product if you have or if you are at risk of having any respiratory conditions, heart disease, high blood pressure or diabetes. Consult your doctor before use if you have any medical conditions. Do not use this product if you are pregnant or nursing. Discontinue use of this product immediately if you experience symptoms such as nausea, dizziness, a weak or rapid heartbeat, vomiting, diarrhea or any other negative physical symptom . If any of the aforementioned symptoms occur, seek medical attention immediately.
The electronic cigarette has never been tested or proved to be a cessation device and is not sold or marketed as such.
California Prop 56 tax on your products has not been paid for vapor products purchased through this website. Consumers, retailers and distributors in California are responsible for paying the tax directly to the state.<br>
</span> 
<div>
			
</div>
	
<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
	  <?php if ($categories) { ?>
	  <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
        <h5><?php echo $text_links; ?></h5>
		<?php /*<ul class="list-unstyled foot-cat">
			<li><a href="<?php echo $accessories; ?>"><?php echo $text_accessories; ?></a></li>
			<?php foreach ($categories as $category) { ?>
			<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
			<?php if ($category['children']) { ?>
			  <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
				<?php foreach ($children as $child) { ?>
				<li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
				<?php } ?>
			  <?php } ?>
			<?php } ?>
			<?php } ?>
		</ul>*/?>
		
		<ul class="list-unstyled foot-cat">
			<li><a href="https://www.logicsmoke.com/logic-smoke-kits">Kits &amp; Cartomizers</a></li>
			<li><a href="https://www.logicsmoke.com/disposable-e-cigarette-e-cigars-logic/Disposable-e-Cigars">e Cigars</a></li>
			<li><a href="https://www.logicsmoke.com/disposable-e-cigarette-e-cigars-logic/Disposable-e-Cigarettes">e Cigarettes</a></li>
			<li><a href="https://www.logicsmoke.com/accessories">Accessories</a></li>
			<li><a href="https://www.logicsmoke.com/disposable-e-cigarette-e-cigars-logic">Disposable e Cigs</a></li>
			<li><a href="https://www.logicsmoke.com/logicsmoke-eliquid/10ml-e-Liquid">10ml e Liquid</a></li>
			<li><a href="https://www.logicsmoke.com/logicsmoke-eliquid/30ml-e-Liquid">30ml e Liquid</a></li>
			<li><a href="https://www.logicsmoke.com/logicsmoke-eliquid/30ml-vg-e-liquid">30ml Vg e Liquid</a></li>
			<li><a href="https://www.logicsmoke.com/logicsmoke-eliquid/50ml-e-Liquid">50ml e Liquid</a></li>
		</ul>
		
	  </div>
	  <?php } ?>
      <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
        <h5><?php echo $text_concerns; ?></h5>
        <ul class="list-unstyled">
		  <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
		  <li><a href="<?php echo $customer_services; ?>"><?php echo $text_customer_services; ?></a></li>
		  <li><a href="<?php echo $faq; ?>"><?php echo $text_faq; ?></a></li>
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
		  <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        </ul>
	
		</div>
       <div>
           
       </div>

		<!-- (c) 2005, 2017. Authorize.Net is a registered trademark of CyberSource Corporation --> 
		<div class="AuthorizeNetSeal"> <script>var ANS_customer_id="844268e6-2b0d-4485-b501-c400f317ec86";</script> 
		<!-- <script type="text/javascript" language="javascript" src="https://verify.authorize.net/anetseal/seal.js" ></script> -->
		<a href="https://verify.authorize.net/anetseal/?pid=844268e6-2b0d-4485-b501-c400f317ec86&amp;rurl=https%3A//www.logicsmoke.com/" onmouseover="window.status='http://www.authorize.net/'; return true;" onmouseout="window.status=''; return true;" onclick="window.open('https://verify.authorize.net/anetseal/?pid=844268e6-2b0d-4485-b501-c400f317ec86&amp;rurl=https%3A//www.logicsmoke.com/','AuthorizeNetVerification','width=600,height=430,dependent=yes,resizable=yes,scrollbars=yes,menubar=no,toolbar=no,status=no,directories=no,location=yes'); return false;" target="_blank">
			<img src="catalog/view/theme/logicsmoke/image/secure90x72.png" width="90" height="72" alt="Authorize.Net Merchant - Click to Verify">
			</a>
		</div>
	</div>
	
      </div>
    </div>
    <hr>
	<div style="text-align:center">
		<p class="copyright"><?php echo $powered; ?></p>
	</div>
	</div>
<link href="//fonts.googleapis.com/css?family=Comfortaa%7CRoboto:300,400" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />

</footer>

<!--Start of Tawk.to
Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5e4df1b0298c395d1ce8d31a/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->'
</body></html>