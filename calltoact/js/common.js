$(document).ready(function() {
		$(".popup").magnificPopup();

		$.slidebars();

		$("a.scrollto").click(function () {
      var elementClick = $(this).attr("href");
      var destination = $(elementClick).offset().top;
      jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
      return false;
    });
     // Cache selectors
    var lastId,
        topMenu = $("#top-menu"),
        topMenuHeight = topMenu.outerHeight()+15,
        // All list items
        menuItems = topMenu.find("a"),
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function(){
          var item = $($(this).attr("href"));
          if (item.length) { return item; }
        });

        // Bind to scroll
        $(window).scroll(function(){
           // Get container scroll position
           var fromTop = $(this).scrollTop()+topMenuHeight;

           // Get id of current scroll item
           var cur = scrollItems.map(function(){
             if ($(this).offset().top < fromTop)
               return this;
           });
       // Get the id of the current element
       cur = cur[cur.length-1];
       var id = cur && cur.length ? cur[0].id : "";


    });
        $('input[type=checkbox]').css({'opacity': 0});
		$('input[type=checkbox]').css({'opacity': 0}).wrap('<span class="wrap-checkbox"></span>');
		$('.wrap-checkbox').click(function() {
		  $(this).toggleClass('active');
		});
		$('select').css({'color': '#777777'});
		$('select').click(function() {
		  $(this).css({'color': '#000'});
	});
});

function DropDown(el) {
        this.dd = el;
        this.initEvents();
      }
      DropDown.prototype = {
        initEvents : function() {
          var obj = this;

          obj.dd.on('click', function(event){
            $(this).toggleClass('active');
            event.stopPropagation();
          });
        }
      }

      $(function() {

        var dd = new DropDown( $('#foot-menu') );

        $(document).click(function() {
          // all dropdowns
          $('.wrapper-dropdown').removeClass('active');
        });

});

$(function() {
  var Accordion = function(el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;

    // Variables privadas
    var links = this.el.find('.link');
    // Evento
    links.on('click', {
      el: this.el,
      multiple: this.multiple
    }, this.dropdown)
  }

  Accordion.prototype.dropdown = function(e) {
    var $el = e.data.el;
    $this = $(this),
      $next = $this.next();

    $next.slideToggle();
    $this.parent().toggleClass('open');

    if (!e.data.multiple) {
      $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
    };
  }

  var accordion = new Accordion($('#accordion'), false);
});