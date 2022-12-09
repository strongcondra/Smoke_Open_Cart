$(document).ready(function() {
var checkWidths = jQuery(window).width();

// Fixed Header
	var menu_offset_top = $('.main-header').outerHeight();    
	function processScroll() {
		var scrollTop = $( window ).scrollTop();
		if ((scrollTop >= menu_offset_top)&&checkWidths >= 768) {  
			$('.main-header').addClass('menu-fixed');        

		} else if (scrollTop <= menu_offset_top) {         
			$('.main-header').removeClass('menu-fixed');     
		}
	}
	$( window ).scroll(function() {
		processScroll();
	});
	
	// Back To Top
	$("#back-to-top").on("click", function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});
});