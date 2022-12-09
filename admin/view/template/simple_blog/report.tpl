<?php echo $header; ?>
    <?php echo $column_left; ?>
    
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">             
                <h1><?php echo $heading_title; ?></h1>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>                
            </div>
        </div>
        
        <div class="container-fluid">
            <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>
            
            <?php if ($success) { ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
                </div>
                
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                
                                <tr>
                                    <td class="text-left">
                                        <?php if ($sort == 'sbad.article_title') { ?>
        		                			<a href="<?php echo $sort_article_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_article_name; ?></a>
        		                		<?php } else { ?>
        		                			<a href="<?php echo $sort_article_title; ?>"><?php echo $column_article_name; ?></a>
        		                		<?php } ?>
                                    </td>
			                
                                    <td class="text-left">
                                        <?php if ($sort == 'sbau.author_name') { ?>
        		                			<a href="<?php echo $sort_author_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author_name; ?></a>
        		                		<?php } else { ?>
        		                			<a href="<?php echo $sort_author_name; ?>"><?php echo $column_author_name; ?></a>
        		                		<?php } ?>
                                    </td>	
			                        
                                    <td class="text-right">
                                        <?php if ($sort == 'sbv.view') { ?>
        		                			<a href="<?php echo $sort_view; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_viewed; ?></a>
        		                		<?php } else { ?>
        		                			<a href="<?php echo $sort_view; ?>"><?php echo $column_viewed; ?></a>
        		                		<?php } ?>
                                    </td>
                                    
    				                <td class="text-right"><?php echo $column_percent; ?></td>
                                </tr>
                                
                            </thead>
                            
                            <tbody>
                                <?php if ($blog_views) { ?>
		          			        <?php foreach($blog_views as $blog_view) { ?>
                                        <tr>
                                            <td class="text-left"><?php echo $blog_view['article_title']; ?></td>
    	            						<td class="text-left"><?php echo $blog_view['author_name']; ?></td>
    	            						<td class="text-right"><?php echo $blog_view['viewed']; ?></td>
                    						<td class="text-right"><?php echo $blog_view['percent']; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
    			          				<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
    			          			</tr>
                                <?php } ?>
                            </tbody>
                            
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left"><?php echo $pagination; ?></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right"><?php echo $results; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
<?php echo $footer; ?>