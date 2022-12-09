<?php if($articles) { ?>
<div class="row">
  <div class="module" id="blog-module">
    <div class="container">
	  <div class="homepage-blog">
		<div class="home-head"><?php echo $heading_title; ?></div>
		<div class="row">
		  <div class="blog-section">
		  <?php foreach ($articles as $article) { ?>
		   <div class="item">
			<div class="blog-layout col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
			  <div class="blog-item">
			  <div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['article_image']; ?>" alt="<?php echo $article['article_title']; ?>" title="<?php echo $article['article_title']; ?>" class="img-responsive" /></a></div>
			  <div class="homepage-blog-post ">
			    <p><?php echo $article['date_added']; ?></p>
				<h1 href="<?php echo $article['href']; ?>"><?php echo $article['article_title']; ?></h1>
				<p><?php echo $article['description']; ?></p>
				<a href="<?php echo $article['href']; ?>">Read More <i class="fa fa-long-arrow-right"></i></a>
		      </div>
		      </div>
	        </div>
		  </div>
	      <?php } ?>
		  </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$('.blog-section').owlCarousel({
    items: 3,
    navigation: true,
	navigationText: ['<i class="fa fa-angle-left fa-7x"></i>', '<i class="fa fa-angle-right fa-7x"></i>'],
    pagination: false,
    itemsDesktop : [1199, 3],
    itemsDesktopSmall : [991, 3],
    itemsTablet : [767, 2],
    itemsTabletSmall : [479, 1],
    itemsMobile : [320, 1]
});
--></script>