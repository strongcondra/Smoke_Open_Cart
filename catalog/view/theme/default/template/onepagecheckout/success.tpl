<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php echo $text_message; ?>
	  <?php if($show_bank_details){ ?>
	  <h3><?php echo $text_instruction; ?></h3>
		<p><b><?php echo $text_description; ?></b></p>
		<div class="well well-sm">
		  <p><?php echo $bank; ?></p>
		  <p><?php echo $text_payment; ?></p>
		</div> 
	  <?php } ?>
	  
      <?php echo $invoice; ?> <br/>
	  
	  <?php if($print_invoice) { ?>
	  <a target="_new" class="btn btn-primary pull-left" href="<?php echo $print_invoice; ?>"><?php echo $text_print_invoice; ?></a>
	  <?php } else { ?>
	  <a target="_new" class="hide" href="<?php echo $print_invoice; ?>"><?php echo $text_print_invoice; ?></a>
	  <?php } ?>
	   <div class="buttons" style="margin:0">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
	  <div class="clearfix"></div>
	  <?php echo $products; ?>
	  <?php echo $order_success_page_google_analytics; ?>
     
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php if(isset($_sasTotal)){ 
$sharestring = '?amount= '. $_sasTotal . '&amp;tracking=' . $_sasOrderId . '&amp;transtype=sale&amp;merchantID=118533&amp;skulist=' . $_sasProducts[0] . '&amp;pricelist=' . $_sasProducts[1] . '&amp;quantitylist=' . $_sasProducts[2] . '&amp;v=opencart3&amp;newcustomer=' . $_sasIsCustomerNew . '&amp;couponcode=' .$_sasCoupons;
?>
<!-- begin ShareASale tracking -->
<img src = "https://shareasale.com/sale.cfm<?php echo $sharestring; ?>" >
<!--- end ShareASale tracking -->
<?php } ?>
<?php echo $footer; ?>