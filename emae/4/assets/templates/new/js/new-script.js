(function($) {
		$(function() {
			$('input, select').styler({
			});
		});
})(jQuery);

$(document).ready(function () {
    $(".btn-sel-flag").each(function (e) {
        var value = $(this).find("ul li.selected img").attr("src", ($(this).attr('src')));
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value img").attr("src", ($('li.selected img', this).attr('src')));
    });
});
$(document).on('click', '.btn-sel-flag', function (e) {
    var ul = $(this).find("ul");
    if ($(this).hasClass("active")) {
        if (ul.find("li img").is(e.target)) {
            var target = $(e.target);
            target.parent().addClass("selected").siblings().removeClass("selected");
            var value = target.attr("src", ($(this).attr('src')));
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value img").attr("src", ($('li.selected img', this).attr('src')));
        }
        ul.hide();
        $(this).removeClass("active");
    }
    else {
        $('.btn-sel-flag').not(this).each(function () {
            $(this).removeClass("active").find("ul").hide();
        });
        ul.slideDown(300);
        $(this).addClass("active");
    }
});
$(document).on('click', function (e) {
    var target = $(e.target).closest(".btn-sel-flag");
    if (!target.length) {
        $(".btn-sel-flag").removeClass("active").find("ul").hide();
    }
});

$(document).ready(function(e) {
/* Mobile Navigation */
	$('#navmobile-btn').click(function(){
		$('#nav-mobile').addClass('mobile-open');
		$('body').addClass('overflow');
		return false;
	});
	$('#navmobile-close, #nav-mobile .block-menu li a').click(function(){
		$('#nav-mobile').removeClass('mobile-open');
		$('body').removeClass('overflow');
		e.stopPropagation();
	});
	$('#navmobile-close_2').click(function(){
		$('#nav-mobile').removeClass('mobile-open');
		$('body').removeClass('overflow');
		return false;
	});
/*Cart*/
	$('#navmobile-cart, #navmobile-cart_mob').click(function(){
		$('#cart-mobile').addClass('mobile-open');
		$('body').addClass('overflow');
		return false;
	});
	$('#cartmobile-close').click(function(){
		$('#cart-mobile').removeClass('mobile-open');
		$('body').removeClass('overflow');
		return false;
	});
	/*Menu mobile*/
	$('#mobile-menu-btn').click(function(){
		$('#mobile-menu').addClass('mobile-open');
		$('body').addClass('overflow');
		return false;
	});
	$('#mobile-menu-close, #mobile-menu li a').click(function(e){
		$('#mobile-menu').removeClass('mobile-open');
		$('body').removeClass('overflow');
		e.stopPropagation();
	});
	/*search mobile*/
	$('.search-mobile').click(function() {
		$('.search-box').fadeToggle(500);
		$(this).toggleClass("active");
	});
	//Size change mobile
	$('#btn-add_size').click(function(){
		$('#size-mobile').addClass('mobile-open');
		$('.bx-controls.bx-has-pager').hide();
		$('.main-prod .color-change.mob').hide();
		return false;
	});
	$('#size-close').click(function(){
		$('#size-mobile').removeClass('mobile-open');
		$('.bx-controls.bx-has-pager').show();
		$('.main-prod .color-change.mob').show();
		return false;
	});
	//Info mobile
	$('#infomobile-btn').click(function(){
		$('#info-mobile').addClass('mobile-open');
		$('.bx-controls.bx-has-pager').hide();
		$('.main-prod .color-change.mob').hide();
		$('.main-prod .info-link').hide();
		$('.main-prod .zoom-mob').hide();
		$('body').addClass('overflow');
		return false;
	});
	$('#info-close').click(function(){
		$('#info-mobile').removeClass('mobile-open');
		$('.bx-controls.bx-has-pager').show();
		$('.main-prod .color-change.mob').show();
		$('.main-prod .info-link').show();
		$('.main-prod .zoom-mob').show();
		$(".set.info > span").show();
		$(".set.info > span").removeClass('active');
		$('.set-body').slideUp(200);
        $('.info_inner').removeClass('mob-inner');
	    $('.set.info .arrow').show();
	    $('body').removeClass('overflow');
		return false;
	});
});
$(document).on('click', function (e) {
	    var target = $(e.target).closest(".navmobile_inner, .cartmobile_inner, .mobile-menu_inner");
	    if (!target.length) {
	        $("#nav-mobile").removeClass("mobile-open");
	        $("#cart-mobile").removeClass("mobile-open");
	        $("#mobile-menu").removeClass("mobile-open");
	        $('body').removeClass('overflow');
	 }
});
(function($) {
	$(function() {

	  $('.tabs-menu').on('click', 'li:not(.active)', function() {
	    $(this)
	      .addClass('active').siblings().removeClass('active')
	      .closest('.mobile-menu').find('.menu-box').removeClass('active').eq($(this).index()).addClass('active');
	  });

	});
})(jQuery);

$(document).ready(function() {
  $(".set > span").on("click", function() {
    if ($(this).hasClass('active')) {
      $(this).removeClass("active");
      $(this).siblings('.set-body').slideUp(200);
    } else {
      $(".set > span", this).removeClass("active");
      $(this).addClass("active");
      $('.set-body', this).slideUp(200);
      $(this).siblings('.set-body').slideDown(200);
    }
  });
});

$(document).ready(function () {
    $('.color-change a').click(function () {
    	$('.color-change a').removeClass('active');
		$(this).toggleClass('active');
		return false;
	});
});
$(document).ready(function() {
	$('.main-prod img').click(function() {
		$('.sidebar, .main-prod').css('display', 'none');
        $('#brand_img-zoom img').attr("src", ($(this).attr('src')));
        $('#brand_img-zoom').css('display', 'block');
        return false;
	});
	$('#brand_img-zoom img').click(function() {
		$('.sidebar, .main-prod').css('display', 'block');
        $('#brand_img-zoom').css('display', 'none');
        return false;
	});

	$('.zoom-mob').click(function() {
		$('.main-prod_mob, .content.prod, footer').css('display', 'none');
		$('#mob-zoom_box img').attr("src", ($('.bx-slider li img').attr('src')));
		$('#mob-zoom_box').css('display', 'block');
        return false;
	});
	$('#mob-zoom_box .zoom-mob_close').click(function() {
		$('.main-prod_mob, .content.prod, footer').css('display', 'block');
        $('#mob-zoom_box').css('display', 'none');
        return false;
	});
});

$(window).on('resize load', function() {
	var windowWidth = $(window).width();
    if (windowWidth <= 998) {
		$('.bx-slider li').css('width', '100%');
		$('.content.prod > div').removeClass('wrap');
		$('#brand_img-zoom').css('display', 'none');
	} else {
		$('.content.prod > div').addClass('wrap');
		$('.main-prod, .sidebar, .content.prod, footer').css('display', 'block');
        $('#mob-zoom_box').css('display', 'none');
	}
});

$(document).ready(function() {
	$('.bx-slider').bxSlider({
	  mode: 'fade',
	  speed: 800,
	  captions: false,
	  controls: false,
	  touchEnabled: true,
	  swipeThreshold: 30,
	  adaptiveHeight: true
	});
});

$(document).ready(function() {
	$('#size-mobile .size_inner li a').click(function() {
		$('.main-prod_mob .btn_group-mob span').hide();
		$('.main-prod_mob .btn_group-mob .btn_checkout').show();
		$('#size-mobile').removeClass('mobile-open');
		return false;
	});
});

$(document).ready(function() {
  $(".set.info > span").on("click", function() {
    if ($(this).hasClass('active')) {
      $('.set.info > span').not(this).hide();
      $('.info_inner').addClass('mob-inner');
	$('.set.info .arrow').hide();
    } else {
      $(".set.info > span").show();
      $('.info_inner').removeClass('mob-inner');
	  $('.set.info .arrow').show();
    }
  });
});

$(document).ready(function() {
	$('.bonus-block .promo-box .promo-link').click(function() {
		$(this).hide();
		$('.bonus-block .promo-box input').show();
		$('.bonus-block .promo-box .promo-ok').show();
		return false;
	});
	$('.bonus-block .promo-box .promo-ok').click(function() {
		$(this).hide();
		$('.bonus-block .promo-box input').hide();
		$('.bonus-block .promo-box span').css('display', 'block');
		return false;
	});
});

$(document).ready(function() {
	$('.cartmobile_inner .login_btn').click(function() {
		$(this).hide();
		$('.cartmobile_inner .button_group .registr_btn').hide();
		$('.cartmobile_inner .button_group .personal_btn').css('display', 'block');
		return false;
	});
});

$(document).ready(function() {
	$('#cart-more_btn').click(function() {
		$('.content.cart-page_mob').hide();
		$('.inner_head.cart-page .cart-checkout').hide();
		$('.content.contact-page_mob').show();
		$('.inner_head.cart-page .cart-contact').css('display', 'inline-block');
		return false;
	});
	$('#cart-oform_btn').click(function() {
		$('.content.contact-page_mob').hide();
		$('.inner_head.cart-page .cart-contact').hide();
		$('.content.delivery-page_mob').show();
		$('.inner_head.cart-page .cart-delivery').css('display', 'inline-block');
		return false;
	});
	$('#btn-confirm').click(function() {
		$('.content.delivery-page_mob').hide();
		$('.inner_head.cart-page .cart-delivery').hide();
		$('.content.thank-page_mob').show();
		$('.inner_head.cart-page .cart-checkout').css('display', 'inline-block');
		return false;
	});
});