<table id="ordersWrapper" class="table table-bordered" width="100%">
<thead>
	<tr>
    	<th width="25%">Subscriber Email</th>
        <th width="15%">Subscriber Name</th>
        <th width="10%">Date</th>
		<th width="5%">Actions</th>
    </tr>
    </thead>
    <tbody>
<?php foreach($sources as $source) { ?>
<tr>
<td><?php echo $source['customer_email']; ?></td>
<td><?php echo $source['customer_name']; ?></td>
<td><?php echo $source['date_created']; ?></td>
<td><a onclick="removeSubscriber('<?php echo $source['subscribe_id']; ?>')" class="btn btn-small btn-danger"><i class="icon-remove"></i> Remove</a></td>
</tr>
      
<?php } ?>
</tbody>

<tfoot>
<tr><td colspan="4"><div class="row pbListing-pagination">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
        </td>
</tr>
<tr><td colspan="4"><a target="_blank" href="index.php?route=extension/module/easynewslettersubscription/exporttocsv&token=<?php echo $token; ?>" class="btn btn-primary"><i class="icon-download-alt"></i> Export to CSV</a></td></tr></tfoot>
</table>

<script>
	function removeSubscriber(subscribeID) {      
				var r=confirm("Are you sure you want to remove the subscriber?");
				if (r==true) {
					$.ajax({
						url: 'index.php?route=extension/module/easynewslettersubscription/removesubscriber&token=<?php echo $token; ?>',
						type: 'post',
						data: {'subscribe_id': subscribeID},
						success: function(response) {
						location.reload();
					}
				});
			 }
			}	
	$(document).ready(function(){
		 $('#ordersWrapper .pagination a').click(function(e){
				e.preventDefault();
				$.ajax({
					url: this.href,
					type: 'get',
					dataType: 'html',
					success: function(data) {				
						$("#ordersWrapper").html(data);
					}
				});
			 });		 
		   });
</script>