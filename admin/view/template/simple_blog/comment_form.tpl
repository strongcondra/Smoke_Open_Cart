<?php echo $header; ?>
    <?php echo $column_left; ?>
    
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form-blog-comment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog-comment" class="form-horizontal">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                            <li><a href="#tab-comment" data-toggle="tab"><?php echo $tab_comment; ?></a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general">
                                
                                <div class="form-group required">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_author; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="author_name" value="<?php echo $author_name; ?>" class="form-control" />
     									<?php if($error_author) { ?>
    										<span class="text-danger"><?php echo $error_author; ?></span>
    									<?php } ?>    
                                    </div>
                                </div>  
                                
                                <div class="form-group required">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_article; ?>"><?php echo $entry_article; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <input type="text" name="article_title" value="<?php echo $article_title; ?>" class="form-control" />
     									<?php if($error_article_title) { ?>
    										<span class="text-danger"><?php echo $error_article_title; ?></span>
    									<?php } ?>  
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
                                
                            </div> <!-- end of tab-general tab -->
                            
                            <div class="tab-pane" id="tab-comment">
                                
                                <div class="form-group required">
                                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $entry_comment; ?></label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <textarea name="comment" class="form-control"><?php echo $comment; ?></textarea>
     									<?php if($error_comment) { ?>
    										<span class="text-danger"><?php echo $error_comment; ?></span>
    									<?php } ?>
                                    </div>
                                </div>  
                                
                                
                                <table id="comment-reply" class="table table-striped table-bordered table-hover">
                                    <thead>
        					        	<tr>
        					            	<td class="text-left"><?php echo $entry_author; ?></td>
        					            	<td class="text-left"><?php echo $entry_reply_comment; ?></td>
        					            	<td class="text-left"><?php echo $entry_status; ?></td>
        					            	<td></td>
        								</tr>
        							</thead>   
                                    
                                    <?php $module_row = 0; ?>							
                                    <?php foreach($comment_reply as $reply) { ?>
                                        
                                        <tbody id="reply-<?php echo $module_row; ?>">
                                            <tr>
                                                <td class="text-left">
                                                    <input type="text" name="comment_reply[<?php echo $module_row; ?>][author]" value="<?php echo $reply['author']; ?>" class="form-control" />
        	 										<?php if(isset($error_reply_author[$module_row])) { ?>
        	 											<span class="text-danger"><?php echo $error_reply_author[$module_row]; ?></span>
        	 										<?php } ?>
        										</td>										
        										<td class="text-left">
        											<textarea name="comment_reply[<?php echo $module_row; ?>][comment]" class="form-control"><?php echo $reply['comment']; ?></textarea>
        	 										<?php if(isset($error_reply_comment[$module_row])) { ?>
        	 											<span class="text-danger"><?php echo $error_reply_comment[$module_row]; ?></span>
        	 										<?php } ?>
        										</td>										
        										<td class="left">
        											<select name="comment_reply[<?php echo $module_row; ?>][status]" class="form-control">
        	 											<option value="1" <?php if($reply['status'] == 1) { echo "selected='selected'"; } ?>><?php echo $text_enabled; ?></option>
        	 											<option value="0" <?php if($reply['status'] == 0) { echo "selected='selected'"; } ?>><?php echo $text_disabled; ?></option>
        	 										</select>
        										</td>										
        										<td class="text-left">
                                                    <button type="button" onclick="$('#reply-<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                                </td>										
        									</tr>
								        </tbody>
                                        
                                        <?php $module_row++; ?>
                                    <?php } ?>
                                    
                                    <tfoot>
        	            				<tr>
        	              					<td colspan="3"></td>
        	              					<td class="text-left">
                                                <button type="button" onclick="addReply();" data-toggle="tooltip" title="<?php echo $button_add_reply; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                            </td>
        	            				</tr>
        	          				</tfoot>                                    
                                </table>
                            </div> <!-- end of tab-general tab -->
                        </div>                    
                    </form>
                </div>
            </div>
        </div>        
    </div>
    
    <script type="text/javascript">
		var module_row = <?php echo $module_row; ?>;
		
		function addReply() {
			
			html  = '<tbody id="reply-' + module_row + '">';
			html += '	<tr>';
			html += '		<td class="text-left">';
			html += '			<input type="text" name="comment_reply[' + module_row + '][author]" value="" class="form-control" />';
			html += '		</td>';			
			html += '		<td class="text-left">';
			html += '			<textarea name="comment_reply[' + module_row + '][comment]" class="form-control"></textarea>';
			html += '		</td>';			
			html += '		<td class="text-left">';
			html += '			<select name="comment_reply[' + module_row + '][status]" class="form-control">';
			html +='				<option value="1"><?php echo $text_enabled; ?></option>';
			html +='				<option value="0"><?php echo $text_disabled; ?></option>';
			html += '			</select>';
			html += '		</td>';			
			html += '		<td class="text-left"><button type="button" onclick="$(\'#reply-' + module_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '	</tr>';
			html += '</tbody>';
			
			$('#comment-reply tfoot').before(html);
            
			module_row++;
		}		
	</script>
    
    <script type="text/javascript">
        $('input[name=\'article_title\']').autocomplete({
        	'source': function(request, response) {
        		$.ajax({
                    url: 'index.php?route=simple_blog/article/autocomplete&token=<?php echo $token; ?>&article_name=' +  encodeURIComponent(request),
        			dataType: 'json',
        			success: function(json) {
        				response($.map(json, function(item) {
        					return {
        						label: item['name'],
        						value: item['simple_blog_article_id']
        					}
        				}));
        			}
        		});
        	},
        	'select': function(item) {
        		$('input[name=\'article_title\']').val(item['label']);
        	}
        });   
    </script>
    
<?php echo $footer; ?>