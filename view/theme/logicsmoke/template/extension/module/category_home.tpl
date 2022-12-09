<h3 class="heading-title"><span><?php echo $heading_title; ?></span></h3>
<div class="row">
	<div class="category-home">
	  <?php foreach ($categories as $category) { ?>
	  <div class="category-layout col-lg-4 col-md-4 col-sm-3 col-xs-12">
		<div class="category-thumb transition">
		  <div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" /></a></div>
		  <h4><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
		</div>
	  </div>
	  <?php } ?>
	</div>
</div>
<div class="cat-home-pad"></div>
