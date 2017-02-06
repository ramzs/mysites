var global;
var windowHeight, windowWidth;
var currentAnchor = 0;
var headerHeight;

$(window).load(function() {



});

$(document).ready(function(){
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

//    if(isMobile.any()){
//        $('.play_video').hide();
//    }




	/* overlay */
	$('.page_nav').hover(function(){
		$('.overlay').stop(true, true).fadeIn(500);
	},function(){
		$('.overlay').stop(true, true).fadeOut(500);
	});
	//--

    $('.video_block_brand').viewportChecker();

	/* page scrolling */
	$('.page_nav_scale a, .menu_mobile .page_links a, .button_group .btn').click(function(){
        stopVideo();
		var targetId = $(this).attr('href');
		$('html, body').stop(true, true).animate({
				scrollTop: $(targetId).offset().top - headerHeight
		}, {
				duration: 600
		});



        if(targetId == '#page0') {
            if($(targetId).find('.owl-item.active').find('video').length > 0) {
                var video_id = $(targetId).find('.owl-item.active').find('video').attr('id');
                $("#" + video_id).get(0).play();
            }
        }

        if($(targetId).find('.video_wrapper').length > 0) {
            var video_id = $(targetId).find('video').attr('id');
            $("#" + video_id).get(0).play();
        }

//        if($('video').length > 0) {
//            $('video').each(function(idx, elm) {
//                console.log($(elm));
//                if($(elm).is(':visible')) {
//                    console.log('111');
//                }
//            });
//        }
		
		if(windowWidth > 999){
			currentAnchor = parseInt(targetId.split('#page')[1]);
			$('.page_nav_scale a').removeClass('current_page');
			$('.page_nav_scale a#nav'+currentAnchor).addClass('current_page');
		}
		
		return false;
		
	});
	//--

    //$('.menu_mobile, .menu_toggle').css('display', 'none');
    $('.menu_mobile .page_links a').click(function(){
        if(windowWidth < 999){
            $('.menu_mobile').slideToggle(500);
            $('.menu_toggle').toggleClass('close_menu');
        }
    });

	
	/* mobile menu */
	$('.menu_toggle').click(function(){
		if(windowWidth < 999){
			$('.menu_mobile').slideToggle(500);
			$(this).toggleClass('close_menu');
		}
	});
	//--
	
	
	/* fixed header */
	var h_hght = 52; 
	$(function(){
		$(window).scroll(function(){
			var top = $(this).scrollTop();
			var elem = $('.inner_head');
			if (top < h_hght) {
			 elem.removeClass('shown');
			} else {
				elem.addClass('shown');
			}
		});
	})
	//--
	
	
	/* */
	$('.brand_img').hover(function(){
		if(windowWidth > 800){
			var lineHeight = $(this).find('.items_list').height();
			$(this).find('.items_list').css({'line-height': lineHeight+'px', 'opacity': '1'});
			$(this).find('.items_list_content').css('display', 'inline-block');
		}
	},function(){
		if(windowWidth > 800){
			$(this).find('.items_list').css({'opacity': '0'});
			$(this).find('.items_list_content').css('display', 'none');
		}
	});
	//--
	
	
	/* */
	$('.items_list').click(function(){
		if(windowWidth < 800){
			if($(this).parent().hasClass('item_visible')){
				$(this).parent().removeClass('item_visible').addClass('item_hidden');
			}
			else
				$(this).parent().removeClass('item_hidden').addClass('item_visible');
		}
	});
	//--
	
	
	/* search box */
	$('.search_icon').click(function(){
		$('.search_box').fadeIn(500);
        $('input[name="search"]').focus();
		return false;
	});
	$('.form_close').click(function(){
		$('.search_box').fadeOut(500);
	});
	//--
	
	
	global = {
			window: $(window)
	};
	
	global.window.resize(onResize);
	onResize();	

	
});



function onResize(){
	
	windowHeight = global.window.height();
	windowWidth = global.window.width();
	
	
	if(windowWidth > 999){
		$('.slider_bg, .bg, .video_box_main').css('height', windowHeight);
		
		$('.menu_mobile, .menu_toggle').css('display', 'none');
		$('.menu_toggle').removeClass('close_menu');
	}
	else{
		$('.video_box_main').css('height', 'auto');
		
		$('.menu_toggle').css('display', 'block');
	}
	
	
	if(windowWidth < 800){
		$('.items_list').parent().removeClass('item_visible').addClass('item_hidden');
	}
	
	$('.search_box').fadeOut(500);
	
	
	var videoWidth = $('.video_box_main video').width();
	var videoPosLeft = Math.floor(videoWidth/2);

	if(windowWidth <= 999){
		headerHeight = $('.main_head').height();
		$('.video_box_main video').css({'left': '0px', 'margin-left': '0px'});
	}
	else{
		headerHeight = 0;
		//$('.video_box_main video').css({'left': '50%', 'margin-left': '-'+videoPosLeft+'px'});
	}
	
}

var TENS = (function($) {
	
	var sync1, sync2;
	
	var init = function() {

		// Main Slider
		sync1 = $(".hero-slider");
		sync2 = $(".thumb-slider");
		 
	  	sync1.owlCarousel({
	    	navigation : true,
	    	singleItem : true,
	    	addClassActive: true,
	   		slideSpeed : 500,
	    	paginationSpeed : 300,
	    	afterAction : syncPosition
			});
	 
			sync2.owlCarousel({
		    pagination:false,
		   	paginationSpeed : 300,
				margin:10,
		    afterInit : function(el){
		      el.find(".owl-item").eq(0).addClass("synced");
		    }
		});

		$(".thumb-slider").on("click", ".owl-item", function(e){
			e.preventDefault();
			var number = $(this).data("owlItem");
			sync1.trigger("owl.goTo",number);

            if($('video').length > 0) {
                $('video').each(function(idx, elm) {
                    var video_id = $(elm).attr('id');
                    $("#" + video_id).get(0).pause();
                });
            }

            var active_item = sync1.find(".owl-item").eq(number);
            if(active_item.find('video').length> 0) {
                var video_id = active_item.find('video').attr('id');
                $("#" + video_id).get(0).play();
            }
        });
		 
		heroslider = $('.hero-slider').data('owlCarousel');

		$(".heroslider-next").click(function(){
            heroslider.next();
            var active_slid = $('.hero-slider').find('.owl-item.active');
            if(active_slid.find('video').length> 0) {
                var video_id = active_slid.find('video').attr('id');
                $("#" + video_id).get(0).play();
            } else {
                stopVideo();
            }
        });
		$(".heroslider-previous").click(function(){
            heroslider.prev();
            var active_slid = $('.hero-slider').find('.owl-item.active');
            if(active_slid.find('video').length> 0) {
                var video_id = active_slid.find('video').attr('id');
                $("#" + video_id).get(0).play();
            } else {
                stopVideo();
            }
        });

        $('.owl-prev').click(function() {
            var active_slid = $('.hero-slider').find('.owl-item.active');
            if(active_slid.find('video').length> 0) {
                var video_id = active_slid.find('video').attr('id');
                $("#" + video_id).get(0).play();
            } else {
                stopVideo();
            }
        });

        $('.owl-next').click(function() {
            var active_slid = $('.hero-slider').find('.owl-item.active');
            if(active_slid.find('video').length> 0) {
                var video_id = active_slid.find('video').attr('id');
                $("#" + video_id).get(0).play();
            } else {
                stopVideo();
            }
        });
		

	};
	
	// SYNCS Main Slider
	var syncPosition = function(el) {
	    
	    var current = this.currentItem;
	    
	    $(".thumb-slider")
	    	.find(".owl-item")
	    	.removeClass("synced")
	    	.eq(current)
	    	.addClass("synced");
	    if($(".thumb-slider").data("owlCarousel") !== undefined){
	    	center(current);
	    }
	};


	// SYNCS Main Slider
	var center = function(number){

		var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
		var num = number;
		var found = false;
		for(var i in sync2visible){
			if(num === sync2visible[i]){
				var found = true;
			}
		}
		
		if(found===false){
			if(num>sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", num - sync2visible.length+2);
			}else{
			
			if(num - 1 === -1){
				num = 0;
		    }
		    sync2.trigger("owl.goTo", num);
			}
		} else if(num === sync2visible[sync2visible.length-1]){
			sync2.trigger("owl.goTo", sync2visible[1]);
		} else if(num === sync2visible[0]){
			sync2.trigger("owl.goTo", num-1);
		}
	    
	};
	
	/* slider thumb-container width */
	var thumb_count = $('.thumbs div.preview_item').length;
	$('.thumbs').css('width', 89*thumb_count);
//    if(thumb_count == 2) {
//        $('.thumbs .owl-wrapper').css('width', 89*thumb_count);
//    }
	//--
	
	
	return {
		init: init
	};

}(jQuery));

$(function() {
	TENS.init();
});


/* scroll top */
$(function() {
	$.fn.scrollToTop = function() {
		$(this).hide().removeAttr("href");
		if ($(window).scrollTop() != "0") {
				$(this).fadeIn("slow")
		}
		var scrollDiv = $(this);
		$(window).scroll(function() {
				if ($(window).scrollTop() == "0") {
						$(scrollDiv).fadeOut("slow")
				} else {
						$(scrollDiv).fadeIn("slow")
				}
		});
		$(this).click(function() {
				$("html, body").animate({
						scrollTop: 0
				}, "slow")
		})
	}
});
$(function() {
	$(".go_top").scrollToTop();
});
//--


/* scroll effects (brand page) */
(function(){
      
	var config = {
		duration   : 500,
		distance   : "20px",
		delay    : 200,
		easing   : 'ease-in-out'
	};
	
	window.sr = ScrollReveal( config );
	
	sr.reveal( ".move", { reset: true } );

})();
//--


/* scroll to anchor (main page) */
$(window).on('load', function () {
	if($('#main_page')[0]){
		if (windowWidth > 999){
			
			pageRefresh();
			
			var anchors = [];
			var isAnimating  = false;
			
			$(function(){
					
					function updateAnchors() {
							anchors = [];
							$('.anchor').each(function(i, element){
									anchors.push( $(element).offset().top );
							});
					}
					
					$('body').on('mousewheel DOMMouseScroll', function (e) {
							e.preventDefault();
							e.stopPropagation();
							if (isAnimating) {
									return false;
							}
							isAnimating = true;
							e.delta = null;

							if (e.originalEvent) {
									if (e.originalEvent.wheelDelta) e.delta = e.originalEvent.wheelDelta / -40;
									if (e.originalEvent.deltaY) e.delta = e.originalEvent.deltaY;
									if (e.originalEvent.detail) e.delta = e.originalEvent.detail;
							}

							if (e.delta <= 0) {
									currentAnchor--;
							} else {
									currentAnchor++;
							}
							
							if ( currentAnchor < 0) {
									currentAnchor = 0;
							}
							else if (currentAnchor > (anchors.length - 1)) {
									currentAnchor = anchors.length - 1;
							}
							
							isAnimating = true;
							$('html, body').animate({
									scrollTop: parseInt(anchors[currentAnchor])
							}, 600, 'swing', function () {
									isAnimating = false;
							});
							
							$('.page_nav_scale a').removeClass('current_page');
							$('.page_nav_scale a#nav'+currentAnchor).addClass('current_page');
                            if(currentAnchor == '0') {
                                if($('#page' + currentAnchor).find('.owl-item.active').find('video').length > 0) {
                                    var video_id = $('#page' + currentAnchor).find('.owl-item.active').find('video').attr('id');
                                    $("#" + video_id).get(0).play();
                                }
                            } else {
                                stopVideo();
                            }

                            if($('#page' + currentAnchor).find('.video_wrapper').length > 0) {
                                var video_id = $('#page' + currentAnchor).find('video').attr('id');
                                $("#" + video_id).get(0).play();
                            }
                            //console.log($('#page' + currentAnchor).find('.video_wrapper'));

					});
			
					updateAnchors();   
					
			});
			
		}
	}
});
//--


function pageRefresh(){
	$('html, body').animate({
			scrollTop: 0
	}, 600 );
}

function stopVideo() {
    if($('video').length > 0) {
        $('video').each(function(idx, elm) {
            var video_id = $(elm).attr('id');
            $("#" + video_id).get(0).pause();
        });
    }
}

function come(elem) {
    var docViewTop = $(window).scrollTop(),
        docViewBottom = docViewTop + $(window).height(),
        elemTop = $(elem).offset().top,
        elemBottom = elemTop + $(elem).height(),
        elemHeight = $(elem).height()
        ;
    console.log((docViewTop + ' - ' + (elemTop-elemHeight)));
    return ((docViewBottom >= elemTop) && (elemBottom < docViewTop));
}

