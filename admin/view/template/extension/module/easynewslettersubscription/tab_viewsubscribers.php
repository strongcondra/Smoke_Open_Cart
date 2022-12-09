 <div id="ordersWrapper"> </div>
    <script>
	     $(document).ready(function(){
     $.ajax({
      	url: "index.php?route=extension/module/easynewslettersubscription/getsubscribers&token=<?php echo $token; ?>&page=1",
        type: 'get',
      	dataType: 'html',
      	success: function(data) {		
            	$("#ordersWrapper").html(data);
     	}
    
       });
       });
	  </script>
