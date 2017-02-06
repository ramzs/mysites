$(document).ready(function() {
/* Mobile Navigation */
	$('#navmobile-btn').click(function(){
		$('#nav-mobile').addClass('mobile-open');
		return false;
	});
	$('#navmobile-close').click(function(){
		$('#nav-mobile').removeClass('mobile-open');
		return false;
	});
	$('#navmobile-close_2').click(function(){
		$('#nav-mobile').removeClass('mobile-open');
		return false;
	});
/*Cart*/
	$('#navmobile-cart').click(function(){
		$('#cart-mobile').addClass('mobile-open');
		return false;
	});
	$('#cartmobile-close').click(function(){
		$('#cart-mobile').removeClass('mobile-open');
		return false;
	});
	/*Menu mobile*/
	$('#mobile-menu-btn').click(function(){
		$('#mobile-menu').addClass('mobile-open');
		return false;
	});
	$('#mobile-menu-close').click(function(){
		$('#mobile-menu').removeClass('mobile-open');
		return false;
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
		return false;
	});
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
      $(".set > span").removeClass("active");
      $(this).addClass("active");
      $('.set-body').slideUp(200);
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
	$('.content.prod img').click(function() {
		$('.content.prod').toggleClass('zoom');
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
});

$(window).ready( function() {
	if (windowWidth <= 998) {
		$('.bxslider').bxSlider();
		$('.bx-slider li div').removeClass('brand_img move');
		$('.bx-slider li div').css('opacity', '1', 'transform', 'translateY(0) scale(1)');
		$('.bx-slider li div').css('transform', 'translateY(0) scale(1)');
		$('.content.prod > div').removeClass('wrap');
	} else {
		$('.bxslider').bxSlider().destroySlider();
		$('.content.prod > div').addClass('wrap');
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
		$('.main-prod .btn_group-mob span').hide();
		$('.main-prod .btn_group-mob .btn_checkout').show();
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
