<?php echo $header; ?>
    <?php echo $column_left; ?>

    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form-blog-article" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                </div>
                <h1><?php echo $heading_title; ?></h1>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div> <!-- end of page-header class -->

        <div class="container-fluid">
            <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
                </div>

                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog-article" class="form-horizontal">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
        					<li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
        					<li><a href="#tab-related" data-toggle="tab"><?php echo $tab_related; ?></a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general">

                                <ul class="nav nav-tabs" id="language">
                                    <?php foreach ($languages as $language) { ?>
                                        <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                    <?php } ?>
                                </ul>

                                 <div class="tab-content">
                                    <?php foreach ($languages as $language) { ?>
                                        <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">

                                            <div class="form-group required">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_title; ?>"><?php echo $entry_title; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    <input type="text" name="article_description[<?php echo $language['language_id']; ?>][article_title]" value="<?php echo isset($article_description[$language['language_id']]['article_title']) ? $article_description[$language['language_id']]['article_title'] : ''; ?>" class="form-control" />
        											<?php if (isset($error_article_title[$language['language_id']])) { ?>
        												<span class="text-danger"><?php echo $error_article_title[$language['language_id']]; ?></span>
        											<?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group required">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_description; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    <textarea name="article_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($article_description[$language['language_id']]['description']) ? $article_description[$language['language_id']]['description'] : ''; ?></textarea>
        											<?php if (isset($error_description[$language['language_id']])) { ?>
        												<span class="text-danger"><?php echo $error_description[$language['language_id']]; ?></span>
        											<?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_meta_description; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    <textarea name="article_description[<?php echo $language['language_id']; ?>][meta_description]" class="form-control"><?php echo isset($article_description[$language['language_id']]['meta_description']) ? $article_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_meta_keyword; ?></label>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    <textarea name="article_description[<?php echo $language['language_id']; ?>][meta_keyword]" class="form-control"><?php echo isset($article_description[$language['language_id']]['meta_keyword']) ? $article_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                                </div>
                                            </div>


                                        </div>
                                    <?php } ?>
                                 </div>

                            </div> <!-- end of tab-general tab -->

                            <div class="tab-pane" id="tab-data">

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_featured_image; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                                			<img src="<?php echo $thumb2; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                		</a>
                                        <input type="hidden" name="featured_image" value="<?php echo $featured_image; ?>" id="input-image" />
                                    </div>
                                </div>

                                <table id="additional" class="table table-striped table-bordered table-hover">
                                    <thead>
        					        	<tr>
        					            	<td class="text-left"><?php echo $entry_additional_description; ?></td>
        					            	<td></td>
        								</tr>
        							</thead>

                                    <?php $module_row = 0; ?>
                                    <?php foreach ($article_addition_description as $additional_description) { ?>
                                        <tbody id="additional-description<?php echo $module_row; ?>">
                                            <tr>
                                                <td class="text-left">

                                                    <?php foreach ($languages as $language) { ?>
        	 											<div id="">
        	 												<textarea name="article_addition_description[<?php echo $module_row ?>][<?php echo $language['language_id']; ?>][additional]" id="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" class="form-control"><?php if(isset($additional_description[$language['language_id']]['additional'])) {echo $additional_description[$language['language_id']]['additional'];} else { echo ""; }  ?></textarea>
        	 												<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" align="top" />
        	 											</div>
        	 										<?php } ?>

                                                </td>
                                                <td class="text-left"><button type="button" onclick="$('#additional-description<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php $module_row++; ?>
                                    <?php } ?>

                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addDescription();" data-toggle="tooltip" title="<?php echo $button_add_description; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- end of tab-data id -->

                            <div class="tab-pane" id="tab-option">

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_allow_comment; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <select name="allow_comment" class="form-control">
     										<option value="1" <?php if($allow_comment == 1) { echo "selected='selected'"; } ?>><?php echo $text_yes; ?></option>
     										<option value="0" <?php if($allow_comment == 0) { echo "selected='selected'"; } ?>><?php echo $text_no; ?></option>
     									</select>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_author_name; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="author_name" value="<?php echo $author_name; ?>" class="form-control" />
    					            	<input type="hidden" name="simple_blog_author_id" value="<?php echo $simple_blog_author_id; ?>" />
    					            	<?php if($error_author_name) { ?>
    					            		<span class="text-danger"><?php echo $error_author_name; ?></span>
    					            	<?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_keyword; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control" />
    					            	<?php if($error_seo_keyword) { ?>
    					            		<span class="text-danger"><?php echo $error_seo_keyword; ?></span>
    					            	<?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_category; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="well well-sm" style="height: 150px; overflow: auto; margin-bottom:0;">
                                            <?php foreach ($categories as $category) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array($category['simple_blog_category_id'], $article_category)) { ?>
        							                    	<input type="checkbox" name="article_category[]" value="<?php echo $category['simple_blog_category_id']; ?>" checked="checked" />
        							                    	<?php echo $category['name']; ?>
        							                    <?php } else { ?>
        								                    <input type="checkbox" name="article_category[]" value="<?php echo $category['simple_blog_category_id']; ?>" />
        								                    <?php echo $category['name']; ?>
        							                    <?php } ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_store; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="well well-sm" style="height: 150px; overflow: auto; margin-bottom:0;">
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array(0, $article_store)) { ?>
                            							<input type="checkbox" name="article_store[]" value="0" checked="checked" />
                            							<?php echo $text_default; ?>
                            						<?php } else { ?>
        							                    <input type="checkbox" name="article_store[]" value="0" />
        							                    <?php echo $text_default; ?>
                            						<?php } ?>
                                                </label>
                                            </div>

                                            <?php foreach ($stores as $store) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array($store['store_id'], $article_store)) { ?>
        								                    <input type="checkbox" name="article_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
        								                    <?php echo $store['name']; ?>
        							                    <?php } else { ?>
        								                    <input type="checkbox" name="article_store[]" value="<?php echo $store['store_id']; ?>" />
        								                    <?php echo $store['name']; ?>
        							                    <?php } ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_sort_order; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_status; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <select name="status" class="form-control">
    					            		<option value="1" <?php if($status == 1) { echo "selected='selected'"; } ?>><?php echo $text_enabled; ?></option>
    					            		<option value="0" <?php if($status == 0) { echo "selected='selected'"; } ?>><?php echo $text_disabled; ?></option>
    					            	</select>
                                    </div>
                                </div>
                            </div> <!-- end of tab-option id -->

                            <div class="tab-pane" id="tab-related">

                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_article_related_method; ?>"><?php echo $entry_article_related_method; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <select name="related_article" onchange="getRelatedMethod(this.value);" class="form-control">
     										<option value="category_wise" <?php if($related_article == 'category_wise') { echo "selected='selected'"; } ?>><?php echo $entry_category_wise; ?></option>
     										<option value="manufacturer_wise" <?php if($related_article == 'manufacturer_wise') { echo "selected='selected'"; } ?>><?php echo $entry_manufacturer_wise; ?></option>
     										<option value="product_wise" <?php if($related_article == 'product_wise') { echo "selected='selected'"; } ?>><?php echo $entry_product_wise; ?></option>
     									</select>
                                    </div>
                                </div>

                                <div class="form-group" id="category-wise" style="display:none">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_category; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="well well-sm" style="height: 150px; overflow: auto; margin-bottom:0;">
                                            <?php foreach ($default_categories as $category) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category_wise[]" value="<?php echo $category['category_id']; ?>" <?php if(isset($category_ids)) { for($i=0; $i<count($category_ids); $i++) { if($category_ids[$i] == $category['category_id']) { echo "checked='checked'"; } } } ?> />
					                    		         <?php echo $category['name']; ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                                    </div>
                                </div>

                                <div class="form-group" id="manufacturer-wise" style="display:none">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_manufacturer; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="well well-sm" style="height: 150px; overflow: auto; margin-bottom:0;">
                                            <?php foreach ($default_manufacturers as $manufacturer) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="manufacturer_wise[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if(isset($manufacturer_ids)) { for($i=0; $i<count($manufacturer_ids); $i++) { if($manufacturer_ids[$i] == $manufacturer['manufacturer_id']) { echo "checked='checked'"; } } } ?> />
                    							         <?php echo $manufacturer['name']; ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                                    </div>
                                </div>

                                <div class="form-group" id="product-wise" style="display:inherit">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_product; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="product" value="" class="form-control" />
                                        <div id="product-wise-list" class="well well-sm" style="height: 150px; overflow: auto; margin-bottom:0;">
                                            <?php if(isset($products)) { ?>
                                                <?php foreach($products as $product) { ?>
                                                    <div id="product-wise-list<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                                            			<input type="hidden" name="product_wise[]" value="<?php echo $product['product_id']; ?>" />
                                            		</div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <h3 class="text-center"><?php echo $entry_blog_related_articles; ?></h3>
                                </div>

                                <table id="related-article" class="table table-striped table-bordered table-hover">
                                    <thead>
        					        	<tr>
        					            	<td class="text-left"> <span data-toggle="tooltip" title="<?php echo $help_related_article_name; ?>"><?php echo $entry_related_article_name; ?></td>
        					            	<td class="text-left"><?php echo $entry_sort_order; ?></td>
        					            	<td class="text-left"><?php echo $entry_status; ?></td>
        					            	<td></td>
        								</tr>
        							</thead>

                                    <?php $article_row = 0; ?>
                                    <?php foreach($blog_related_articles as $related_articles) { ?>

                                        <tbody id="article-row<?php echo $article_row; ?>">
        									<tr>
        										<td class="text-left">
        											<input type="text" name="blog_related_articles[<?php echo $article_row; ?>][article_title]" value="<?php echo $related_articles['article_title']; ?>" id="article-title-<?php echo $article_row; ?>" onkeyup="getArticles(<?php echo $article_row; ?>, this.value);" class="form-control" />
        											<input type="hidden" name="blog_related_articles[<?php echo $article_row; ?>][simple_blog_article_related_id]" value="<?php echo $related_articles['simple_blog_article_related_id']; ?>" />
        										</td>
        										<td class="text-left">
        											<input type="text" name="blog_related_articles[<?php echo $article_row; ?>][sort_order]" value="<?php echo $related_articles['sort_order']; ?>" class="form-control" />
        										</td>
        										<td class="left">
        											<select name="blog_related_articles[<?php echo $article_row; ?>][status]" class="form-control">
        												<?php if($related_articles['status']) { ?>
        													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
        													<option value="0"><?php echo $text_disabled; ?></option>
        												<?php } else { ?>
        													<option value="1"><?php echo $text_enabled; ?></option>
        													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
        												<?php } ?>
        											</select>
        										</td>
        										<td class="text-left">
                                                    <button type="button" onclick="$('#article-row<?php echo $article_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                                </td>
        									</tr>
								        </tbody>

                                        <?php $article_row++; ?>
                                    <?php } ?>

                                    <tfoot>
        	            				<tr>
        	              					<td colspan="3"></td>
        	              					<td class="text-left">
                                                <button type="button" onclick="addArticles();" data-toggle="tooltip" title="<?php echo $button_add_articles; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                            </td>
        	            				</tr>
        	          				</tfoot>

                                </table>

                            </div>  <!-- end of tab-related id -->
                        </div>
                    </form>
                </div>
        </div>

    </div>
    <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
    <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
    <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
    <script type="text/javascript">

        <?php foreach ($languages as $language) { ?>
            $('#description<?php echo $language['language_id']; ?>').summernote({height: 300});
        <?php } ?>

        $('#language a:first').tab('show');
        $('#option a:first').tab('show');
    </script>

    <script type="text/javascript">
		<?php $module_row = 0; ?>
		<?php foreach ($article_addition_description as $module) { ?>
			<?php foreach ($languages as $language) { ?>
                $('#description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>').summernote({height: 100});
			<?php } ?>
		<?php $module_row++; ?>
		<?php } ?>
	</script>

    <script type="text/javascript">

        $('input[name=\'author_name\']').autocomplete({
        	'source': function(request, response) {
        		$.ajax({
                    url: 'index.php?route=simple_blog/author/autocomplete&token=<?php echo $token; ?>&author_name=' +  encodeURIComponent(request),
        			dataType: 'json',
        			success: function(json) {
        				response($.map(json, function(item) {
        					return {
        						label: item['name'],
        						value: item['simple_blog_author_id']
        					}
        				}));
        			}
        		});
        	},
        	'select': function(item) {
        		$('input[name=\'author_name\']').val(item['label']);
                $('input[name=\'simple_blog_author_id\']').val(item['value']);
        	}
        });

    </script>

    <script type="text/javascript">

        var module_row = <?php echo $module_row; ?>;

        function addDescription() {
            html  = '<tbody id="additional-description' + module_row + '">';

            html += '   <tr>';
            html += '       <td class="text-left">';
            html += '           ';
            <?php foreach ($languages as $language) { ?>
                html += '<div id="">'
				html += '	<textarea name="article_addition_description[' + module_row + '][<?php echo $language['language_id']; ?>][additional]" id="description-' + module_row + '-<?php echo $language['language_id']; ?>" class="form-control"></textarea> <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" align="top" />';
				html += '</div>';
            <?php } ?>
            html += '           ';
            html += '       </td>';

            html += '  <td class="text-left"><button type="button" onclick="$(\'#additional-description' + module_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';

            html += '   </tr>';

            html += '</tbody>';

            $('#additional tfoot').before(html);

            <?php foreach ($languages as $language) { ?>
                $('#description-' + module_row + '-<?php echo $language['language_id']; ?>').summernote({height: 100});
            <?php } ?>

            module_row++;
        }

    </script>


    <script type="text/javascript">
		$(document).ready(function() {
			<?php if ($related_article == 'product_wise') { ?>
				$("#category-wise").css({display: "none"});
				$("#manufacturer-wise").css({display: "none"});
				$("#product-wise").css({display: "inherit"});
			<?php } else if($related_article == 'category_wise') { ?>
				$("#category-wise").css({display: "inherit"});
				$("#manufacturer-wise").css({display: "none"});
				$("#product-wise").css({display: "none"});
			<?php } else if($related_article == 'manufacturer_wise') { ?>
				$("#category-wise").css({display: "none"});
				$("#manufacturer-wise").css({display: "inherit"});
				$("#product-wise").css({display: "none"});
			<?php } ?>
		});
	</script>

    <script type"text/javacript">
		function getRelatedMethod(value) {
			if(value == 'product_wise') {
				$("#category-wise").css({display: "none"});
				$("#manufacturer-wise").css({display: "none"});
				$("#product-wise").css({display: "inherit"});
			} else if(value == 'category_wise') {
				$("#category-wise").css({display: "inherit"});
				$("#manufacturer-wise").css({display: "none"});
				$("#product-wise").css({display: "none"});
			} else if(value == 'manufacturer_wise') {
				$("#category-wise").css({display: "none"});
				$("#manufacturer-wise").css({display: "inherit"});
				$("#product-wise").css({display: "none"});
			}
		}
	</script>

    <script type="text/javascript">

        $('input[name=\'product\']').autocomplete({
        	'source': function(request, response) {
        		$.ajax({
        			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        			dataType: 'json',
        			success: function(json) {
        				response($.map(json, function(item) {
        					return {
        						label: item['name'],
        						value: item['product_id']
        					}
        				}));
        			}
        		});
        	},
        	'select': function(item) {
        		$('input[name=\'product\']').val('');

        		$('#product-wise-list' + item['value']).remove();

        		$('#product-wise-list').append('<div id="product-wise-list' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_wise[]" value="' + item['value'] + '" /></div>');
        	}
        });

        $('#product-wise-list').delegate('.fa-minus-circle', 'click', function() {
        	$(this).parent().remove();
        });

    </script>

    <script type="text/javascript">
		var article_row = <?php echo $article_row; ?>;

		function addArticles() {

			html  = '<tbody id="article-row' + article_row + '">';
			html += '	<tr>';
			html += '		<td class="text-left">';
			html += '			<input type="text" name="blog_related_articles[' + article_row + '][article_title]" value="" id="article-title-' + article_row + '" onkeyup="getArticles(' + article_row + ', this.value);" class="form-control" /> <input type="hidden" name="blog_related_articles[' + article_row + '][simple_blog_article_related_id]" value="0" />';
			html += '		</td>';
			html += '		<td class="text-left">';
			html += '			<input type="text" name="blog_related_articles[' + article_row + '][sort_order]" value="" class="form-control" />';
			html += '		</td>';
			html += '		<td class="text-left">';
			html += '			<select name="blog_related_articles[' + article_row + '][status]" class="form-control">';
			html +='				<option value="1"><?php echo $text_enabled; ?></option>';
			html +='				<option value="0"><?php echo $text_disabled; ?></option>';
			html += '			</select>';
			html += '		</td>';
			html += '		<td class="text-left"><button type="button" onclick="$(\'#article-row' + article_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '	</tr>';
			html += '</tbody>';

			$('#related-article tfoot').before(html);

			article_row++;
		}
	</script>

    <script type="text/javascript">
        function getArticles(article_row, value) {
            $('input[name=\'blog_related_articles[' + article_row + '][article_title]\']').autocomplete({
            	'source': function(request, response) {
            		$.ajax({
                        url: 'index.php?route=simple_blog/article/autocomplete_article&token=<?php echo $token; ?>&simple_blog_article_id=<?php echo $simple_blog_article_id; ?>&filter_name=' +  encodeURIComponent(request),
            			dataType: 'json',
            			success: function(json) {
            				response($.map(json, function(item) {
            					return {
            						label: item['article_title'],
            						value: item['simple_blog_article_id']
            					}
            				}));
            			}
            		});
            	},
            	'select': function(item) {
            		$('input[name=\'filter_name\']').val(item['label']);
                    $('input[name=\'blog_related_articles[' + article_row + '][article_title]\']').val(item['label']);
					$('input[name=\'blog_related_articles[' + article_row + '][simple_blog_article_related_id]\']').val(item['value']);
            	}
            });
        }
    </script>

<?php echo $footer; ?>
