$( function() {
    var availableTags = [
      "Омск город",
      "омск поселок",
      "омар",
      "опель"
    ];
    $( "#search-city" ).autocomplete({
      source: availableTags,
      appendTo: "#search-block"
    });
  } );
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
		$('.content.prod > div').removeClass('wrap');
		$('#brand_img-zoom').css('display', 'none');
	} else {
		$('.content.prod > div').addClass('wrap');
		$('.main-prod, .sidebar, .content.prod, footer').css('display', 'block');
        $('#mob-zoom_box').css('display', 'none');
	}
});

$(document).ready(function() {
	$('#size-mobile .size_inner li a').click(function() {
		$("#size-mobile .size_inner li a").removeClass("active");
		$(this).addClass("active");
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

$(document).ready(function() {
	$('.promo-box .promo-link').click(function() {
		$(this).hide();
		$('.promo-box input').show();
		$('.promo-box .promo-ok').show();
		return false;
	});
	$('.promo-box .promo-ok').click(function() {
		$(this).hide();
		$('.promo-box input').hide();
		$('.promo-box span').css('display', 'block');
		return false;
	});
});

/*
$(document).ready(function() {
	$('.cartmobile_inner .login_btn').click(function() {
		$(this).hide();
		$('.cartmobile_inner .button_group .registr_btn').hide();
		$('.cartmobile_inner .button_group .personal_btn').css('display', 'block');
		return false;
	});
});
*/

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


$(document).ready(function(){
    $(".fancybox").fancybox();
});

// Список li разбить на колонки
$(function(){
var updateColl;
jQuery.fn.liColl = function(options){
    // настройки по умолчанию
    var o = jQuery.extend({
        n_coll: 2, //колличество колонок
        c_unit: '%', //Единица измерения
        c_width: 300, //Ширина колонок
        p_left: 5 //отступ слева %
    },options);
    updateColl = function update(listWrap){
        listWrap.children('.coll_s').each(function(){
            $(this).children().unwrap();
        });
        listWrap.liColl(options);
    };
    return this.each(function(){
        element = jQuery(this).css({overflow:'hidden'});
        nc = o.n_coll;
        pl = o.p_left;
        i = 1;
        c_un = 'px';
        if(options.c_unit != c_un){
            coll_w = Math.floor(100/nc);
            coll_w = coll_w - pl;
        }else{
            coll_w = options.c_width - pl;
        }
        num_1 = element.children('li').length;
        create();
        function create(){
            n_end = Math.ceil(num_1/nc);
            var cc = jQuery('<div />').addClass("coll_s c_" + i).css({width:coll_w+options.c_unit,paddingLeft:pl+options.c_unit,float:'left',clear:'right'});
            //element_2.append();
            element.children('li').slice(0,n_end).wrapAll(cc);
            if(num_1 != n_end){
                i++;
                nc--;
                num_1 = num_1 - n_end;
                create();
            }
        }
    });
};

    $('.city-list').liColl({
        c_unit: 'px', // '%' или 'px' При указании '%' — ширина 'c_width' игнорируется
        n_coll: 4, //колличество колонок
        c_width: 140, //Ширина колонок в 'px'
        p_left: 0 //отступ слева %
    });

});

$(document).ready(function() {
	$('.list-lang li a').click(function() {
		$(".language-img").removeClass('lang-rus lang-eng lang-dt lang-fr lang-tr lang-kz');
		$(".language-img").addClass($(this).attr("id"));
		return false;
	});
});

$(window).on('resize load', function() {
   	var windowWidth = $(window).width();
    if (windowWidth <= 520) {
    		$('.inner_head.prod .header_title').prependTo('.main-prod');
    } else {
    	$('.main-prod .header_title').prependTo('.inner_head.prod .wrap');
    }
});
$(document).ready(function() {
	$('#registr-btn').click(function() {
		$('#box-cart').stop(true, true).fadeOut();
		$('#box-registr, .registr-block').stop(true, true).fadeIn();
		$('.cartmobile_inner').addClass('registr-open');
	});
	$('#enter-btn').click(function() {
		$('#box-cart').stop(true, true).fadeOut();
		$('#box-registr, .enter-block').stop(true, true).fadeIn();
		$('.cartmobile_inner').addClass('registr-open');
	});
	$('#backup-link').click(function() {
		$('.enter-block').stop(true, true).fadeOut();
		$('.backup-block').stop(true, true).fadeIn();
	});
	$('#backup-link_back').click(function() {
		$('.backup-block').stop(true, true).fadeOut();
		$('.enter-block').stop(true, true).fadeIn();
	});
	$('#back-cart').click(function() {
		$('#box-registr, .registr-block, .enter-block').stop(true, true).fadeOut();
		$('#box-cart').stop(true, true).fadeIn();
		$('.cartmobile_inner').removeClass('registr-open');
	});
});


