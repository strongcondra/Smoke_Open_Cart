<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div>
<?php if($logo_status){ ?>
<a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="margin-bottom: 20px; border: none;" /></a>
<?php } ?>
 <?php if($order_success_page_order_details){ ?>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color:<?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;" colspan="2"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
          <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
          <?php } ?></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b><?php echo $text_email; ?></b> <?php echo $email; ?><br />
          <b><?php echo $text_telephone; ?></b> <?php echo $telephone; ?><br />
          <b><?php echo $text_ip; ?></b> <?php echo $ip; ?><br />
          <b><?php echo $text_order_status; ?></b> <?php echo $order_status; ?><br /></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
  
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
	    <?php if($order_success_page_payment_address_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_payment_address; ?></td>
		<?php } ?>
		
        <?php if ($shipping_address && $order_success_page_shipping_address_status) { ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_shipping_address; ?></td>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <tr>
	    <?php if($order_success_page_payment_address_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $payment_address; ?></td>
		<?php } ?>
		
        <?php if ($shipping_address && $order_success_page_shipping_address_status) { ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $shipping_address; ?></td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
		<?php $colspan=0; ?>
		<?php if($order_success_page_product_image_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: center; padding: 7px; color:<?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_image; ?></td>
		<?php $colspan++; } ?>
	    <?php if($order_success_page_product_name_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color:<?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_product; ?></td>
		<?php $colspan++; } ?>
		<?php if($order_success_page_product_model_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_model; ?></td>
		<?php $colspan++; } ?>
		<?php if($order_success_page_product_sku_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_sku; ?></td>
		<?php $colspan++; } ?>
		<?php if($order_success_page_product_qty_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: right; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_quantity; ?></td>
		<?php $colspan++;  } ?>
		<?php if($order_success_page_product_unit_price_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: right; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_price; ?></td>
		<?php $colspan++; } ?>
		<?php if($order_success_page_product_total_status){ ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: right; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_total; ?></td>
		<?php $colspan++; } ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
	   <?php if($order_success_page_product_image_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align:center; padding: 7px;">
			<?php if($product['image']){ ?>
			<img src="<?php echo $product['image']; ?>"/>
			<?php } ?>
		</td>
		<?php } ?>
	   <?php if($order_success_page_product_name_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
		 <?php } ?>
		<?php if($order_success_page_product_model_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['model']; ?></td>
		<?php } ?>
		<?php if($order_success_page_product_sku_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['sku']; ?></td>
		<?php } ?>
		<?php if($order_success_page_product_qty_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['quantity']; ?></td>
		<?php } ?>
		<?php if($order_success_page_product_unit_price_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['price']; ?></td>
		<?php } ?>
		<?php if($order_success_page_product_total_status){ ?>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['total']; ?></td>
		<?php } ?>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $voucher['description']; ?></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">1</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $voucher['amount']; ?></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="<?php echo $colspan-1; ?>"><b><?php echo $total['title']; ?>:</b></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
  <?php if ($comment && $order_success_page_comment_status) { ?>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color:  <?php echo ($order_success_page_title_backgound ? $order_success_page_title_backgound : '#EFEFEF'); ?>; font-weight: bold; text-align: left; padding: 7px; color: <?php echo ($order_success_page_title_color ? $order_success_page_title_color : '#222222'); ?>;"><?php echo $text_instruction; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $comment; ?></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
</div>
</body>
</html>
<?php if($print_status){ ?>
<script>
window.print(); 
</script>
<?php } ?>