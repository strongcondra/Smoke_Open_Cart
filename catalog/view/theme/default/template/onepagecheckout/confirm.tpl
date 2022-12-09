<div class="extrow">
<?php if($button_type != 'confirm'){ ?>
<?php if($comment_status){ ?>
	<div class="extsm-12 margintb">
		<?php if($comment_label){ ?>
		<label><?php echo $comment_label; ?></label>
		<?php } ?>
		<textarea name="comment" class="form-control" placeholder="<?php echo $comment_placeholder; ?>"><?php echo $comment; ?></textarea>
	</div>
<?php } ?>
	<?php if($text_agree){ ?>
	<div class="extsm-12">
		<?php if ($agree) { ?>
		<input type="checkbox" name="agree" value="1" checked="checked" />
		<?php } else { ?>
		<input type="checkbox" <?php echo $checkout_terms; ?> name="agree" value="1" />
		<?php } ?>
		<?php echo $text_agree; ?>
	</div>
	<?php  } ?>
<?php } ?>
	
	
	<?php if($button_type != 'confirm' && $shopping_button_status){ ?>
	<div class="extsm-6">
		
		<div class="buttons">
			<div class="pull-left">
				<a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_shopping; ?></a>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<div class="<?php if($button_type != 'confirm' && $shopping_button_status){ ?> extsm-6 text-right <?php } else { ?> extsm-12 <?php } ?> ">
		<?php if($button_type == 'register') { ?>
		
		<div class="buttons">
			<div class="">
				<button <?php if($button_type != 'confirm' && !$shopping_button_status){ ?> style="width:100%";  <?php } ?> <?php if (!empty($redirect)) { ?> disabled="disabled" <?php } ?> class="btn btn-primary" rel="register" id="button-register"><?php echo $button_checkout_order; ?></button>
			</div>
		</div>
		
		
		<?php } else if($button_type == 'guest') { ?>
		
		<div class="buttons">
			<div class="">
				<button <?php if($button_type != 'confirm' && !$shopping_button_status){ ?> style="width:100%";  <?php } ?> <?php if (!empty($redirect)) { ?> disabled="disabled" <?php } ?> class="btn btn-primary" rel="guest" id="button-guest"><?php echo $button_checkout_order; ?></button>
			</div>
		</div>
		<?php } else if($button_type == 'login') { ?>
		<div class="buttons">
			<div class="">
				<button <?php if($button_type != 'confirm' && !$shopping_button_status){ ?> style="width:100%";  <?php } ?> <?php if (!empty($redirect)) { ?> disabled="disabled" <?php } ?> class="btn btn-primary button-login" rel="login" id="button-checkout-order"><?php echo $button_checkout_order; ?></button>
			</div>
		</div>
			
		<?php } else if($button_type == 'logged'){ ?>
		<div class="buttons">
			<div class="">
				<button <?php if($button_type != 'confirm' && !$shopping_button_status){ ?> style="width:100%";  <?php } ?>  <?php if (!empty($redirect)) { ?> disabled="disabled" <?php } ?> class="btn btn-primary" rel="loggedorder" id="button-loggedorder"><?php echo $button_checkout_order; ?></button>
			</div>
		</div>
		<?php } else if($button_type == 'confirm') { ?>
		<?php if (empty($redirect)) { ?>
			<?php echo $payment; ?>
			<script type="text/javascript"><!--
				<?php if($button_type == 'confirm') { ?>
					<?php if($autotrigger){ ?>
					<?php if(in_array($selectedtriggers,$trigger_payment_method)){ ?>
					$('<?php echo $payment_trigger_button; ?>').trigger('click');
					<?php } ?>
					<?php } ?>
				<?php } ?>
			//--></script>
		<?php }else{ ?>
		<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
		<?php } ?>
		<?php } ?>
	</div>
</div>
<script><!--
$('#onepagecheckout textarea[name="comment"]').on('keyup',function(){
	$.ajax({
		url: 'index.php?route=onepagecheckout/confirm/comment',
		dataType: 'json',
		type: 'post',
		data: $('#onepagecheckout textarea[name="comment"]'),
		success: function(json){

		}
	});
});
$('#onepagecheckout input[name="agree"]').on('click',function(){
	$.ajax({
		url: 'index.php?route=onepagecheckout/confirm/ordertrem',
		dataType: 'json',
		type: 'post',
		data: $('#onepagecheckout input[name="agree"]:checked'),
		success: function(json){

		}
	});
});

$('#button-confirm').parent('div').removeClass('pull-right');
$('#button-confirm').addClass('col-sm-12');
//--></script>