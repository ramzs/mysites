$(function() {

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch(err) {

	}

	$("img, a").on("dragstart", function(event) { event.preventDefault(); });

});

$(window).load(function() {

	$(".loader_inner").fadeOut();
	$(".loader").delay(400).fadeOut("slow");

});

$(document).ready(function () {
    $(".btn-select").each(function (e) {
        var value = $(this).find("ul li.selected img").attr("src", ($(this).attr('src')));
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value img").attr("src", ($(this).attr('src')));
    });
});
$(document).on('click', '.btn-select', function (e) {
    var ul = $(this).find("ul");
    if ($(this).hasClass("active")) {
        if (ul.find("li img").is(e.target)) {
            var target = $(e.target);
            target.parent().addClass("selected").siblings().removeClass("selected");
            var value = target.attr("src", ($(this).attr('src')));
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value img").attr("src", ($('li.selected img').attr('src')));
        }
        ul.hide();
        $(this).removeClass("active");
    }
    else {
        $('.btn-select').not(this).each(function () {
            $(this).removeClass("active").find("ul").hide();
        });
        ul.slideDown(300);
        $(this).addClass("active");
    }
});
$(document).on('click', function (e) {
    var target = $(e.target).closest(".btn-select");
    if (!target.length) {
        $(".btn-select").removeClass("active").find("ul").hide();
    }
});