<?php echo $header; ?>
    <?php echo $column_left; ?>

    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form-simple-blog" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                </div>

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

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
                </div>

                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-simple-blog" class="form-horizontal">

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_status; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <select name="simple_blog_status" class="form-control">
                                    <option value="1" <?php if($simple_blog_status == 1) { echo "selected='selected'"; } ?>><?php echo $text_enabled; ?></option>
    								<option value="0" <?php if($simple_blog_status == 0) { echo "selected='selected'"; } ?>><?php echo $text_disabled; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_simple_blog_seo_keyword; ?>"><?php echo $entry_simple_blog_seo_keyword; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <input type="text" name="simple_blog_seo_keyword" value="<?php echo $simple_blog_seo_keyword; ?>" class="form-control" />
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_simple_blog_heading; ?>"><?php echo $entry_simple_blog_heading; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <input type="text" name="simple_blog_footer_heading" value="<?php echo $simple_blog_footer_heading; ?>" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_blog_module_heading; ?>"><?php echo $entry_blog_module_heading; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <input type="text" name="simple_blog_heading" value="<?php echo $simple_blog_heading; ?>" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_product_related_heading; ?>"><?php echo $entry_product_related_heading; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <input type="text" name="simple_blog_product_related_heading" value="<?php echo $simple_blog_product_related_heading; ?>" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_comment_related_heading; ?>"><?php echo $entry_comment_related_heading; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <input type="text" name="simple_blog_comment_related_heading" value="<?php echo $simple_blog_comment_related_heading; ?>" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_display_category; ?>"><?php echo $entry_display_category; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="checkbox-inline">
                                    <?php if($simple_blog_display_category) { ?>
    	    							<input type="radio" name="simple_blog_display_category" value="1" checked="checked" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_display_category" value="0" /> <?php echo $text_disabled; ?>
    	    						<?php } else { ?>
    	    							<input type="radio" name="simple_blog_display_category" value="1" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_display_category" value="0" checked="checked" /> <?php echo $text_disabled; ?>
    	    						<?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_comment_approval; ?>"><?php echo $entry_comment_approval; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="checkbox-inline">
                                    <?php if($simple_blog_comment_auto_approval) { ?>
    	    							<input type="radio" name="simple_blog_comment_auto_approval" value="1" checked="checked" /> <?php echo $text_yes; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_comment_auto_approval" value="0" /> <?php echo $text_no; ?>
    	    						<?php } else { ?>
    	    							<input type="radio" name="simple_blog_comment_auto_approval" value="1" /> <?php echo $text_yes; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_comment_auto_approval" value="0" checked="checked" /> <?php echo $text_no; ?>
    	    						<?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_show_author; ?>"><?php echo $entry_show_author; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="checkbox-inline">
                                    <?php if($simple_blog_show_authors) { ?>
    	    							<input type="radio" name="simple_blog_show_authors" value="1" checked="checked" /> <?php echo $text_yes; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_show_authors" value="0" /> <?php echo $text_no; ?>
    	    						<?php } else { ?>
    	    							<input type="radio" name="simple_blog_show_authors" value="1" /> <?php echo $text_yes; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_show_authors" value="0" checked="checked" /> <?php echo $text_no; ?>
    	    						<?php } ?>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_author_information; ?>"><?php echo $entry_author_information; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="checkbox-inline">
                                    <?php if($simple_blog_author_information) { ?>
    	    							<input type="radio" name="simple_blog_author_information" value="1" checked="checked" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_author_information" value="0" /> <?php echo $text_disabled; ?>
    	    						<?php } else { ?>
    	    							<input type="radio" name="simple_blog_author_information" value="1" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_author_information" value="0" checked="checked" /> <?php echo $text_disabled; ?>
    	    						<?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_related_article; ?>"><?php echo $entry_related_article; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="checkbox-inline">
                                    <?php if($simple_blog_related_articles) { ?>
    	    							<input type="radio" name="simple_blog_related_articles" value="1" checked="checked" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_related_articles" value="0" /> <?php echo $text_disabled; ?>
    	    						<?php } else { ?>
    	    							<input type="radio" name="simple_blog_related_articles" value="1" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_related_articles" value="0" checked="checked" /> <?php echo $text_disabled; ?>
    	    						<?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_show_social_site_option; ?>"><?php echo $entry_show_social_site_option; ?></label>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="checkbox-inline">
                                    <?php if($simple_blog_share_social_site) { ?>
    	    							<input type="radio" name="simple_blog_share_social_site" value="1" checked="checked" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_share_social_site" value="0" /> <?php echo $text_disabled; ?>
    	    						<?php } else { ?>
    	    							<input type="radio" name="simple_blog_share_social_site" value="1" /> <?php echo $text_enabled; ?>&nbsp;
    	    							<input type="radio" name="simple_blog_share_social_site" value="0" checked="checked" /> <?php echo $text_disabled; ?>
    	    						<?php } ?>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-center"><?php echo $text_article_related; ?></h3>

                        <div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<ul class="nav nav-pills nav-stacked" id="module">
									<?php $module_row = 1; ?>
									<?php foreach ($modules as $module) { ?>
										<li><a href="#tab-module<?php echo $module['key']; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-module<?php echo $module['key']; ?>\']').parent().remove(); $('#tab-module<?php echo $module['key']; ?>').remove(); $('#module a:first').tab('show');"></i> <?php echo $tab_module . ' ' . $module_row; ?></a></li>
										<?php $module_row++; ?>
									<?php } ?>
									<li id="module-add"><a onclick="addModule();"><i class="fa fa-plus-circle"></i> <?php echo $button_module_add; ?></a></li>
								</ul>
							</div>

                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <div class="tab-content">
                                    <?php foreach ($modules as $module) { ?>
                                        <div class="tab-pane" id="tab-module<?php echo $module['key']; ?>">

                                            <div class="form-group">
												<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_article_limit; ?></label>
												<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
													<input type="text" name="simple_blog_module[<?php echo $module['key']; ?>][article_limit]" value="<?php echo $module['article_limit']; ?>" class="form-control" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_category; ?></label>
												<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
													<select name="simple_blog_module[<?php echo $module['key']; ?>][category_id]" class="form-control">
                      									<option value="" disabled="disabled" style="font-weight: bold;"><?php echo $text_category_label; ?></option>
                      									<option value="all" <?php if($module['category_id'] == 'all') { echo "selected='selected'"; } ?>><?php echo $text_latest_article; ?></option>
                      									<option value="popular" <?php if($module['category_id'] == 'popular') { echo "selected='selected'"; } ?>><?php echo $text_popular_article; ?></option>
                      									<option value="" disabled="disabled" style="font-weight: bold;"><?php echo $entry_category; ?></option>
                      									<?php foreach($categories as $category) { ?>
                      										<option value="<?php echo $category['simple_blog_category_id']; ?>" <?php if($category['simple_blog_category_id'] == $module['category_id']) { echo "selected='selected'"; } ?>><?php echo $category['name']; ?></option>
                      									<?php } ?>
                      								</select>
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_status; ?></label>
												<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
													<select name="simple_blog_module[<?php echo $module['key']; ?>][status]" class="form-control">
            							                <?php if ($module['status']) { ?>
            								                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            								                <option value="0"><?php echo $text_disabled; ?></option>
            							                <?php } else { ?>
            								                <option value="1"><?php echo $text_enabled; ?></option>
            								                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            							                <?php } ?>
                          							</select>
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_sort_order; ?></label>
												<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
													<input type="text" name="simple_blog_module[<?php echo $module['key']; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" class="form-control" />
												</div>
											</div>


                                        </div>
                                    <?php } ?>
                                </div>
                            </div>


                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>

    <script type="text/javascript">
        var module_row = <?php echo $module_row; ?>;

		function addModule() {

            var token = Math.random().toString(36).substr(2);

            html  = '<div class="tab-pane" id="tab-module' + token + '">';

            html += '   <div class="form-group">';
            html += '       <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_article_limit; ?></label>';
            html += '       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">';
            html += '           <input type="text" name="simple_blog_module[' + token + '][article_limit]" value="" class="form-control" />';
            html += '       </div>';
            html += '   </div>';


            html += '   <div class="form-group">';
            html += '       <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_category; ?></label>';
            html += '       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">';
            html += '			<select name="simple_blog_module[' + token + '][category_id]" class="form-control">';
			html += '				<option value="" disabled="disabled" style="font-weight: bold;"><?php echo $text_category_label; ?></option>';
			html += '				<option value="all"><?php echo $text_latest_article; ?></option>';
			html += '				<option value="popular"><?php echo $text_popular_article; ?></option>';
			html += '				<option value="" disabled="disabled" style="font-weight: bold;"><?php echo $entry_category; ?></option>';
									<?php foreach($categories as $category) { ?>
										html += '<option value="<?php echo $category['simple_blog_category_id']; ?>"><?php echo $category['name']; ?></option>';
									<?php } ?>
			html += '			</select>';
            html += '       </div>';
            html += '   </div>';



            html += '   <div class="form-group">';
            html += '       <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_status; ?></label>';
            html += '       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">';
            html += '           <select name="simple_blog_module[' + token + '][status]" class="form-control">';
            html += '               <option value="1"><?php echo $text_enabled; ?></option>';
            html += '               <option value="0"><?php echo $text_disabled; ?></option>';
            html += '           </select>';
            html += '       </div>';
            html += '   </div>';

            html += '   <div class="form-group">';
            html += '       <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_sort_order; ?></label>';
            html += '       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">';
            html += '           <input type="text" name="simple_blog_module[' + token + '][sort_order]" value="" class="form-control" />';
            html += '       </div>';
            html += '   </div>';

            html += '</div>';

            $('.tab-content:first-child').prepend(html);

            $('#module-add').before('<li><a href="#tab-module' + token + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-module' + token + '\\\']\').parent().remove(); $(\'#tab-module' + token + '\').remove(); $(\'#module a:first\').tab(\'show\');"></i> <?php echo $tab_module; ?> ' + module_row + '</a></li>');

            $('#module a[href=\'#tab-module' + token + '\']').tab('show');

			module_row++;
		}
    </script>

    <script type="text/javascript">
        $('#module li:first-child a').tab('show');
    </script>


<?php echo $footer; ?>
