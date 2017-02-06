$(document).ready(function() {

	$(".popup").magnificPopup();
});
$(document).ready(function(){
	var touch = $('#touch-menu');
	var menu = $('.nav');

	$(touch).on('click', function(e) {
		e.preventDefault();
		menu.slideToggle();
	});
	$(window).resize(function(){
		var wid = $(window).width();
		if(wid > 760 && menu.is(':hidden')) {
			menu.removeAttr('style');
		}
	});

});
$(document).ready(function(){

	$("#back-top").hide();

	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});
$(document).ready(function(){

	$("#back-home").hide();

	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-home').fadeIn();
			} else {
				$('#back-home').fadeOut();
			}
		});

		$('#back-home a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});

var abv = "HTML";
function vkladki(x,y) {
	var mostrar = document.getElementById(x);
	var actual = document.getElementById(abv);
	if(mostrar==actual) {return false;}
	actual.className="skryt";
	mostrar.className="vid";
	abv = x;
	document.getElementById('vkladka1').style.borderBottomColor='#e1e1e1';
	if (self.ramka) ramka.style.borderBottomColor = '#e1e1e1';
	y.style.borderBottomColor = '#fff'; ramka = y;
}
