function getURLVar(t){var e=[],a=String(document.location).split("?");if(a[1]){var o=a[1].split("&");for(i=0;i<o.length;i++){var c=o[i].split("=");c[0]&&c[1]&&(e[c[0]]=c[1])}return e[t]?e[t]:""}}$(document).ready(function(){$(".text-danger").each(function(){var t=$(this).parent().parent();t.hasClass("form-group")&&t.addClass("has-error")}),$("#form-currency .currency-select").on("click",function(t){t.preventDefault(),$("#form-currency input[name='code']").val($(this).attr("name")),$("#form-currency").submit()}),$("#form-language .language-select").on("click",function(t){t.preventDefault(),$("#form-language input[name='code']").val($(this).attr("name")),$("#form-language").submit()}),$(".button-search").bind("click",function(){var t=$("base").attr("href")+"index.php?route=product/search",e=$("#search input[name='search']").val();e&&(t+="&search="+encodeURIComponent(e)),location=t}),$("#search input[name='search']").on("keydown",function(t){13==t.keyCode&&$("#search input[name='search']").parent().find(".button-search").trigger("click")}),$(".search_input").focus(function(){$("#search").stop(!0,!0).addClass("active")}),$(".search_input").focusout(function(){$("#search").stop(!0,!0).removeClass("active"),$("#ajax_search_results").hide(200)}),$("#search input[name='search']").on("keydown",function(t){13==t.keyCode&&$("header #search input[name='search']").parent().find("button").trigger("click")}),$("#search input[name='search']").on("keydown",function(t){13==t.keyCode&&$("header #search input[name='search']").parent().find("button").trigger("click")}),$(".search-safari").hide(),$(".search-w").on("click",function(){$(".search-safari").slideToggle()}),$(".search-w").on("click",function(t){t.stopPropagation()}),$(".search-safari").on("click",function(t){t.stopPropagation()}),$("body").on("click",function(t){$(".search-safari").hide()}),$("#menu .dropdown-menu").each(function(){var t=$("#menu").offset(),e=$(this).parent().offset().left+$(this).outerWidth()-(t.left+$("#menu").outerWidth());e>0&&$(this).css("margin-left","-"+(e+10)+"px")}),$("#menu li a").each(function(t){this.href.trim()==window.location?$(this).parent().addClass("actives"):$(this).parent().removeClass("actives")}),$("#list-view").click(function(){$("#content .product-grid > .clearfix").remove(),$("#content .row > .product-grid").attr("class","product-layout product-list col-xs-12"),$("#grid-view").removeClass("active"),$("#list-view").addClass("active"),localStorage.setItem("display","list")}),$("#grid-view").click(function(){var t=$("#column-right, #column-left").length;2==t?$("#content .product-list").attr("class","product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12"):1==t?$("#content .product-list").attr("class","product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12"):$("#content .product-list").attr("class","product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12"),$("#list-view").removeClass("active"),$("#grid-view").addClass("active"),localStorage.setItem("display","grid")}),"list"==localStorage.getItem("display")?($("#list-view").trigger("click"),$("#list-view").addClass("active")):($("#grid-view").trigger("click"),$("#grid-view").addClass("active")),$(document).on("keydown","#collapse-checkout-option input[name='email'], #collapse-checkout-option input[name='password']",function(t){13==t.keyCode&&$("#collapse-checkout-option #button-login").trigger("click")}),$("[data-toggle='tooltip']").tooltip({container:"body"}),$(document).ajaxStop(function(){$("[data-toggle='tooltip']").tooltip({container:"body"})})});var cart={add:function(t,e){$.ajax({url:"index.php?route=checkout/cart/add",type:"post",data:"product_id="+t+"&quantity="+(void 0!==e?e:1),dataType:"json",beforeSend:function(){$("#cart > a").button("loading")},complete:function(){$("#cart > a").button("reset")},success:function(t){$(".alert, .text-danger").remove(),t.redirect&&(location=t.redirect),t.success&&($("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+t.success+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'),setTimeout(function(){$("#cart > a").html('<i class="fa fa-shopping-cart fa-5x"></i> <span id="cart-total" class="cart-totals"> '+t.total+"</span>")},100),$("html, body").animate({scrollTop:0},"slow"),$("#cart > ul").load("index.php?route=common/cart/info ul li"),$(".alert-success").delay(5e3).fadeOut("slow"))},error:function(t,e,a){alert(a+"\r\n"+t.statusText+"\r\n"+t.responseText)}})},update:function(t,e){$.ajax({url:"index.php?route=checkout/cart/edit",type:"post",data:"key="+t+"&quantity="+(void 0!==e?e:1),dataType:"json",beforeSend:function(){$("#cart > a").button("loading")},complete:function(){$("#cart > a").button("reset")},success:function(t){setTimeout(function(){$("#cart > a").html('<i class="fa fa-shopping-cart fa-5x"></i> <span id="cart-total" class="cart-totals"> '+t.total+"</span>")},100),"checkout/cart"==getURLVar("route")||"checkout/checkout"==getURLVar("route")?location="index.php?route=checkout/cart":$("#cart > ul").load("index.php?route=common/cart/info ul li")},error:function(t,e,a){alert(a+"\r\n"+t.statusText+"\r\n"+t.responseText)}})},remove:function(t){$.ajax({url:"index.php?route=checkout/cart/remove",type:"post",data:"key="+t,dataType:"json",beforeSend:function(){$("#cart > a").button("loading")},complete:function(){$("#cart > a").button("reset")},success:function(t){setTimeout(function(){$("#cart > a").html('<i class="fa fa-shopping-cart fa-5x"></i> <span id="cart-total" class="cart-totals"> '+t.total+"</span>")},100),"checkout/cart"==getURLVar("route")||"checkout/checkout"==getURLVar("route")?location="index.php?route=checkout/cart":$("#cart > ul").load("index.php?route=common/cart/info ul li")},error:function(t,e,a){alert(a+"\r\n"+t.statusText+"\r\n"+t.responseText)}})}},voucher={add:function(){},remove:function(t){$.ajax({url:"index.php?route=checkout/cart/remove",type:"post",data:"key="+t,dataType:"json",beforeSend:function(){$("#cart > a").button("loading")},complete:function(){$("#cart > a").button("reset")},success:function(t){setTimeout(function(){$("#cart > a").html('<i class="fa fa-shopping-cart fa-5x"></i> <span id="cart-total" class="cart-totals"> '+t.total+"</span>")},100),"checkout/cart"==getURLVar("route")||"checkout/checkout"==getURLVar("route")?location="index.php?route=checkout/cart":$("#cart > ul").load("index.php?route=common/cart/info ul li")},error:function(t,e,a){alert(a+"\r\n"+t.statusText+"\r\n"+t.responseText)}})}},wishlist={add:function(t){$.ajax({url:"index.php?route=account/wishlist/add",type:"post",data:"product_id="+t,dataType:"json",success:function(t){$(".alert").remove(),t.redirect&&(location=t.redirect),t.success&&$("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+t.success+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'),$("#wishlist-total span").html(t.total),$("#wishlist-total").attr("title",t.total),$("html, body").animate({scrollTop:0},"slow")},error:function(t,e,a){alert(a+"\r\n"+t.statusText+"\r\n"+t.responseText)}})},remove:function(){}},compare={add:function(t){$.ajax({url:"index.php?route=product/compare/add",type:"post",data:"product_id="+t,dataType:"json",success:function(t){$(".alert").remove(),t.success&&($("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+t.success+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'),$("#compare-total").html(t.total),$("html, body").animate({scrollTop:0},"slow"))},error:function(t,e,a){alert(a+"\r\n"+t.statusText+"\r\n"+t.responseText)}})},remove:function(){}};$(document).delegate(".agree","click",function(t){t.preventDefault(),$("#modal-agree").remove();var e=this;$.ajax({url:$(e).attr("href"),type:"get",dataType:"html",success:function(t){html='<div id="modal-agree" class="modal">',html+='  <div class="modal-dialog">',html+='    <div class="modal-content">',html+='      <div class="modal-header">',html+='        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>',html+='        <h4 class="modal-title">'+$(e).text()+"</h4>",html+="      </div>",html+='      <div class="modal-body">'+t+"</div>",html+="    </div",html+="  </div>",html+="</div>",$("body").append(html),$("#modal-agree").modal("show")}})}),function(t){t.fn.autocomplete=function(e){return this.each(function(){this.timer=null,this.items=new Array,t.extend(this,e),t(this).attr("autocomplete","off"),t(this).on("focus",function(){this.request()}),t(this).on("blur",function(){setTimeout(function(t){t.hide()},200,this)}),t(this).on("keydown",function(t){switch(t.keyCode){case 27:this.hide();break;default:this.request()}}),this.click=function(e){e.preventDefault(),value=t(e.target).parent().attr("data-value"),value&&this.items[value]&&this.select(this.items[value])},this.show=function(){var e=t(this).position();t(this).siblings("ul.dropdown-menu").css({top:e.top+t(this).outerHeight(),left:e.left}),t(this).siblings("ul.dropdown-menu").show()},this.hide=function(){t(this).siblings("ul.dropdown-menu").hide()},this.request=function(){clearTimeout(this.timer),this.timer=setTimeout(function(e){e.source(t(e).val(),t.proxy(e.response,e))},200,this)},this.response=function(e){if(html="",e.length){for(i=0;i<e.length;i++)this.items[e[i].value]=e[i];for(i=0;i<e.length;i++)e[i].category||(html+='<li data-value="'+e[i].value+'"><a href="#">'+e[i].label+"</a></li>");var a=new Array;for(i=0;i<e.length;i++)e[i].category&&(a[e[i].category]||(a[e[i].category]=new Array,a[e[i].category].name=e[i].category,a[e[i].category].item=new Array),a[e[i].category].item.push(e[i]));for(i in a)for(html+='<li class="dropdown-header">'+a[i].name+"</li>",j=0;j<a[i].item.length;j++)html+='<li data-value="'+a[i].item[j].value+'"><a href="#">&nbsp;&nbsp;&nbsp;'+a[i].item[j].label+"</a></li>"}html?this.show():this.hide(),t(this).siblings("ul.dropdown-menu").html(html)},t(this).after('<ul class="dropdown-menu"></ul>'),t(this).siblings("ul.dropdown-menu").delegate("a","click",t.proxy(this.click,this))})}}(window.jQuery);