<?php if($sliders) { ?>
	<div class="tp-banner-container rev-slider-container<?php echo $module; ?>">
		<div class="tp-banner rev-slider<?php echo $module; ?>">
			<ul>
				<?php foreach($sliders as $slider) { ?>
					<li data-transition="<?php echo $slider['transition']; ?>" data-slotamount="7" data-masterspeed="1500" class="loaderSlide">
						<img src="<?php echo $slider['image']; ?>"  alt="Test"  data-bgfit="cover">
						<?php foreach($slider['layer'] as $layer) { ?>
							<?php if($layer['type'] == 'large_heading') { ?>
								<div class="tp-caption large_bold_grey skewfromrightshort" <?php echo $layer['string']; ?>><?php echo $layer['text']; ?></div>
							<?php } else if($layer['type'] == 'medium_heading') { ?>
								<div class="tp-caption medium_thin_grey skewfromrightshort" <?php echo $layer['string']; ?>><?php echo $layer['text']; ?></div>
							<?php } else if($layer['type'] == 'button') { ?>
								<div class="tp-caption medium_bg_orange skewfromrightshort" <?php echo $layer['string']; ?>><a href="<?php echo $layer['link']; ?>"><?php echo $layer['text']; ?></a></div>
								
							<?php } ?>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="mr-t-30"></div>
	<script>
		<?php if($display_full_width) { ?>
			var revapi;
			jQuery(document).ready(function() {
                   jQuery('.loaderSlide').removeClass();
				   revapi = jQuery('.rev-slider<?php echo $module; ?>').revolution(
					{
						delay:9000,
						startwidth: <?php echo $width; ?>,
						startheight: <?php echo $height ?>,
						hideThumbs:10,
						fullWidth:"on",
						forceFullWidth:"on",
						navigationType:"both",                  
						navigationArrows:"solo",        
						navigationStyle:"round",                
						touchenabled:"on",                         spinner: 'spinner0',						
						onHoverStop:"on"        

					});

			});	//ready
		<?php } else { ?>
			var revapi;
			jQuery(document).ready(function() {
                   jQuery('.loaderSlide').removeClass();
				   revapi = jQuery('.rev-slider<?php echo $module; ?>').revolution(
					{
						delay:9000,
						startwidth:<?php echo $width; ?>,
						startheight:<?php echo $height ?>,
						hideThumbs:10,
						navigationType:"both",                  
						navigationArrows:"solo",        
						navigationStyle:"round",                
						touchenabled:"on",                           spinner: 'spinner0',						
						onHoverStop:"on"        

					});

			});	//ready
		<?php } ?>
	</script>
<?php } ?><!--<style>.loaderSlide{visibility: hidden;}</style>-->
