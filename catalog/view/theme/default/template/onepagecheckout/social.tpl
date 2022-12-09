<?php if($fbstatus || $gstatus){ ?>
<div class="extpanel extpanel-default social-section">
	<div class="extpanel-heading">
		<h4 class="extpanel-title"><i class="fa fa-group"></i> <?php echo $text_social_login; ?></h4>
	</div>
	<div class="extpanel-body">
		<div class="row">
			<?php if($fbstatus){ ?>
			<div style="<?php echo $classmargin; ?>" class="<?php echo $class1; ?>">
				<a href="<?php echo $flogin; ?>"><img width="250" src="catalog/view/theme/default/image/facebook_login.jpg" class="img-responsive" alt="Facebook Login"></a>
			</div>
			<?php } ?>
			<?php if($gstatus){ ?>
			<div class="<?php echo $class1; ?>">
				<a href="<?php echo $glogin; ?>"><img width="250" src="catalog/view/theme/default/image/google_login.jpg" class="img-responsive" alt="Google Login"></a>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>