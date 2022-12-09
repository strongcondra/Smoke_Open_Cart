<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Comfortaa|Roboto:300,400" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/logicsmoke/stylesheet/style.css" rel="stylesheet">
<link href="catalog/view/theme/logicsmoke/stylesheet/age-verification.css" rel="stylesheet">

			<style>
			header {
				opacity: 1!important;
			}
			.dropdown-menu{
				padding: 20px 0!important;
			}
			.dropdown-menu li{
				padding:3px 10px;
			}
			#menu .dropdown-inner a{
				padding: 0px!important;
			}
			#menu .dropdown-inner li a:hover {
				color: #333;
			}
			.dropdown-menu li>a:hover {
				background-color: #fff;
				background-image: none;
			}
			#menu .dropdown-inner img{
				border: 1px solid #f2f2f2;
			}
			</style>
			
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<script src="catalog/view/javascript/jquery/custom.js" type="text/javascript"></script>
<script src="catalog/view/javascript/jquery/jquery.cookie.js" type="text/javascript"></script>
<script src="catalog/view/javascript/jquery/age-verification.js" type="text/javascript"></script>
</head>
<body class="<?php echo $class; ?>">
    <div class="css-22mbi3 e18v05qz0">WARNING: This product contains nicotine. Nicotine is an addictive chemical.</div>
<div style="color: Black !important; width:90% !important;
           margin: 0 auto; padding-top: 1%; text-align: center !important;">
    <b>VAPE MAIL BAN UPDATE!</b> Due to recent Federal Regulations LogicSmoke will only be able to ship to selected location.</br> Shipping Now to: Arizona, Florida, Idaho, Illinois, Missouri, Montana, Nebraska, Oklahoma

Please visit State Regulations for more info.</div>
<nav id="top">
  <div class="container">
    <?php // echo $currency; ?>
    <?php // echo $language; ?>
	<div id="top-links" class="nav pull-left">
		<ul class="list-inline">
		<li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
		<li><a href="<?php echo $contact; ?>"><i class="fa fa-envelope"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $email; ?></span></li>
		</ul>
	</div>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
		<li> | </li>
		<?php if($config_facebook) { ?>
			<li><a href="https://facebook.com/<?php echo $config_facebook; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
		<?php } ?>
		<?php if($config_twitter) { ?>
			<li><a href="https://twitter.com/<?php echo $config_twitter; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
		<?php } ?>
		<?php if($config_instagram) { ?>
			<li><a href="https://instagram.com/<?php echo $config_instagram; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
		<?php } ?>
		<?php if($config_linkedin) { ?>
			<li><a href="https://linkedin.com/<?php echo $config_linkedin; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
		<?php } ?>
		<?php if($config_youtube) { ?>
			<li><a href="https://youtube.com/<?php echo $config_youtube; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
		<?php } ?>
		<?php if($config_pinterest) { ?>
			<li><a href="https://pinterest.com/<?php echo $config_pinterest; ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
		<?php } ?>
		<?php if($config_vimeo) { ?>
			<li><a href="https://vimeo.com/<?php echo $config_vimeo; ?>" target="_blank"><i class="fa fa-vimeo"></i></a></li>
		<?php } ?>
		<?php if($config_google_plus) { ?>
			<li><a href="https://plus.google.com/u/0/<?php echo $config_google_plus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
		<?php } ?>
      </ul>
    </div>
  </div>
</nav>
<header>
<div class="main-header">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 main-menus">
		<?php if ($categories) { ?>
		<div class="main-menu">
		  <nav id="menu" class="navbar">
			<div class="navbar-header">
			  <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
			  <ul class="nav navbar-nav">
				<?php foreach($headermenuleft as $header){ ?> 
					<li class="dropdown"><a class="dropdown-toggle" href="<?php echo $header['link'] ?>"><?php echo $header['title']; ?></a>
						<?php if($header['sub_title']){ ?>
							<div class="dropdown-menu">	
								<div class="dropdown-inner">
									<ul class="list-unstyled">
										<?php foreach($header['sub_title'] as $subtitle){ ?>
											<li>
												<?php if(isset($subtitle['href'])){ ?>					
													<a href="<?php echo $subtitle['href']; ?>"><?php echo $subtitle['title']; ?></a>
												<?php } else { ?>
												<a href="<?php echo $subtitle['link']?>"><?php echo $subtitle['title']; ?></a>	
												<?php } ?>
												<?php if($header['sub_title']){?>
													<ul>
														<?php foreach($subtitle['sub_title'] as $subtitle){ ?>
															<li>
																<?php if(isset($subtitle['href'])){ ?>					
																	<a href="<?php echo $subtitle['href']; ?>"><?php echo $subtitle['title']; ?></a>
																<?php } else { ?>
																	<a href="<?php echo $subtitle['link']?>"><?php echo $subtitle['title']; ?></a>	
																<?php } ?>
															</li>
														<?php } ?>
													</ul>				
												<?php } ?>	
											</li>
										<?php } ?>
									</ul>				
								</div>
							</div>
						<?php } ?>		
					</li>			
				<?php  } ?>
				<?php foreach ($categories as $category) { ?>
				<?php if ($category['children']) { ?>
				<li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
				  <div class="dropdown-menu">
					<div class="dropdown-inner">
					  <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
					  <ul class="list-unstyled">
						<?php foreach ($children as $child) { ?>
						<li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
						<?php } ?>
					  </ul>
					  <?php } ?>
					</div>
				  </div>
				</li>
				<?php } else { ?>
				<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
				<?php } ?>
				<?php } ?>
				<?php foreach($headermenuright as $header){ ?> 
					<li class="dropdown"><a class="dropdown-toggle" href="<?php echo $header['link'] ?>"><?php echo $header['title']; ?></a>
						<?php if($header['sub_title']){ ?>
							<div class="dropdown-menu">	
								<div class="dropdown-inner">
									<ul class="list-unstyled">
										<?php foreach($header['sub_title'] as $subtitle){ ?>
											<li>
												<?php if(isset($subtitle['href'])){ ?>					
													<a href="<?php echo $subtitle['href']; ?>"><?php echo $subtitle['title']; ?></a>
												<?php } else { ?>
												<a href="<?php echo $subtitle['link']?>"><?php echo $subtitle['title']; ?></a>	
												<?php } ?>
												<?php if($header['sub_title']){?>
													<ul>
														<?php foreach($subtitle['sub_title'] as $subtitle){ ?>
															<li>
																<?php if(isset($subtitle['href'])){ ?>					
																	<a href="<?php echo $subtitle['href']; ?>"><?php echo $subtitle['title']; ?></a>
																<?php } else { ?>
																	<a href="<?php echo $subtitle['link']?>"><?php echo $subtitle['title']; ?></a>	
																<?php } ?>
															</li>
														<?php } ?>
													</ul>				
												<?php } ?>	
											</li>
										<?php } ?>
									</ul>				
								</div>
							</div>
						<?php } ?>		
					</li>			
				<?php  } ?>
			  </ul>
			</div>
		  </nav>
		</div>
		<?php } ?>
	  </div>
      <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12 headerCS"><?php echo $cart; ?></div>
    </div>
  </div>
</div>
</header>