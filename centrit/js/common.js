$(document).ready(function() {

    $(".popup").magnificPopup();
});
$(function() {
    $("#slider1").responsiveSlides({
      });
 });

$('#num-1').animate({ num: 62 - 3/* - начало */ }, {
    duration: 3000,
    step: function (num){
        this.innerHTML = (num + 3).toFixed(0)
    }
});
$('#num-2').animate({ num: 600 - 3/* - начало */ }, {
    duration: 3000,
    step: function (num){
        this.innerHTML = (num + 3).toFixed(0)
    }
});
$('#num-3').animate({ num: 35 - 3/* - начало */ }, {
    duration: 3000,
    step: function (num){
        this.innerHTML = (num + 3).toFixed(0)
    }
});
$('#num-4').animate({ num: 7 - 3/* - начало */ }, {
    duration: 3000,
    step: function (num){
        this.innerHTML = (num + 3).toFixed(0)
    }
});