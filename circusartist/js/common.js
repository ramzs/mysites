$(document).ready(function() {

	// Carousel
	$('.carousel_in').slick({
    dots: true
  });
	$('.person_carousel').slick({
		slidesToShow: 4,
  	slidesToScroll: 1,
  	responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
	});

  //Fixe menu
  function fixed() {
    $(window).scroll(function() {
      var to_top = $('.to_top');
      var nav = $('.nav');
      var pos = $(window).scrollTop();
      if(pos > 170) {
        nav.addClass('nav_fixed');
        to_top.show();
      }
      else {
        nav.removeClass('nav_fixed');
        to_top.hide();
      }
    });

  }
  fixed();

    // Scrool top

  $('.to_top').click(function() {
    $("html, body").animate({
      scrollTop: "0px"
    }, {
      duration: 500
    });
    return false;
  });
});

$(".popup_form").magnificPopup();

  // Burger menu
  $(".burger-menu").click(function () {
      $('#first-level').slideToggle();
  });

//Menu dropdown
$( '.nav_in li:has(ul)' ).doubleTapToGo();

$(window).ready(function() {
            var windowWidth = $(window).width();
            if (windowWidth <= 766) {
                $(".main_li").click(function(e) {
                      if ($('.drop_menu', this).hasClass('open')) {
                          $('.drop_menu').removeClass('open');
                          $('.main_li').removeClass('focus');
                      } else {
                        $('.main_li').removeClass('focus');
                        $(this).toggleClass('focus');
                        $('.drop_menu').removeClass('open');
                        $('.drop_menu', this).toggleClass('open');
                        return false;
                      }
                });
                $(".drop_menu_three_wrap").click(function(e) {
                  if ($('.drop_menu_three', this).hasClass('open')) {
                      $('.drop_menu_three').removeClass('open');
                      $('a').removeClass('focus');
                      e.stopPropagation();
                  } else {
                    $('a').removeClass('focus');
                    $('a', this).toggleClass('focus');
                    $('.drop_menu_three').removeClass('open');
                    $('.drop_menu_three', this).toggleClass('open');
                    return false;
                  }
          });
          } else {
            $(".main_li").hover(function() {
                  $('.drop_menu', this).stop(true, true).fadeIn("fast");
                  $(this).addClass('active');
              },
              function() {
                  $('.drop_menu', this).stop(true, true).fadeOut("fast");
                  $('.drop_menu_three').removeClass('open');
                  $(this).removeClass('active');
                  $('.drop_menu_three_wrap > a').removeClass('focus');
                  $('.drop_menu_three').removeClass('open');
              });
              $(".drop_menu_three_wrap").hover(function() {
                  if ($('.drop_menu_three', this).hasClass('open')) {
                      $('.drop_menu_three').removeClass('open');
                      $('a').removeClass('focus');
                  } else {
                    $('a').removeClass('focus');
                    $('a', this).toggleClass('focus');
                    $('.drop_menu_three').removeClass('open');
                    $('.drop_menu_three', this).toggleClass('open');
                  }
                  },
                  function() {
                      $('.drop_menu_three').removeClass('open');
                  });
            }
});