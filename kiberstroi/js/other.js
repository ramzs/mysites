




function Delete(N,volume)
{
	if(confirm("Вы действительно хотите удалить товар из корзины?"))
	{
		parent.location='/basket.php?action=delete&id='+N+'&volume='+volume;
	}
	else
	{
		return false;
	}
}
function clear_cookie()
{
	if(confirm("Вы действительно хотите очистить корзину?"))
	{
		document.cookie = 'basket=0; path=/; expires=Wed, 1 Jan 1970 00:00:01 GMT';
		location.reload();
	}
	else
	{
		return false;
	}
}
function fclear(idf) {
	idf.value="";
}

$("#send_basket_cookies").click(function () {
	var name_form = $('#name_basket').val();
	var phone_form = $('#phone_basket').val();
	$.post( "/inc/actions.php", { 'action' : 'send_basket_cookies', 'name': name_form, 'phone': phone_form}, function( data ) {
		$('td.required-form').html('<div style="color:#FFF; font-size: 16px;"><br /><br />Заявка отправлена. Мы позвоним Вам в течении 10 минут.</div>');
	});
});

$("#btn-slide").click(function () {
	if ( $("#panel").is(":hidden") )
	{
		$("#panel").slideDown("slow");
	}
	else
	{
		$("#panel").slideUp("slow");
	}
	return false;
});

$("#buy_now").click(function () {
	if ( $("#buy_now_div").is(":hidden") )
		$("#buy_now_div").slideDown("slow");
	else
		$("#buy_now_div").slideUp("slow");
	return false;
});

$('#my_order').submit(function(e){
	e.preventDefault();
	var name_form = $("#name_form").val();
	var phone_form = $("#phone_form").val();
	var mail_form = $("#mail_form").val();
	var adress_form = $("#adress_form").val();
	var m_data=$(this).serialize();

	if (name_form!='' && phone_form!='' && mail_form!='' && adress_form!='')
	{
		$('#buy_now_div').html('<img src="/images/loader.gif" alt="" style="display:block; margin: 50px auto;" />');
		$.post( "/inc/actions.php", { 'action' : 'calc_submit', 'data' : m_data, 'name': name_form}, function( data ) {
			$('#buy_now_div').remove();

			jQuery.noticeAdd({
				text: 'Заказ успешно отправлен!',
				stay: false
			});
		});
	}
	else
	{
		jQuery.noticeAdd({
			text: 'Извините, но необходимо заполнить все поля',
			stay: false
		});
	}
});
function goBack() {
	window.history.back()
}
$(document).ready(function(){

	$('#change_pay').change(function(){
		var pay = $("#change_pay :selected").val();
		if (pay==4)
		{
			$(".myhide").show();
			$(".name_ooo").attr("required", "required");
			$(".rekviz").attr("required", "required");
		}
		else
		{
			$(".myhide").hide();
			$(".name_ooo").removeAttr("required");
			$(".rekviz").removeAttr("required");
		}
	});

	$('#change_town').change(function(){
		var town = $("#change_town :selected").val();
		var dostavka = $("#insert_before").attr("data-price");
		if (town==2)
		{
			var mytext = parseInt($("#total_summ").text())+parseInt(dostavka);
			$("#change_now").val("1");
			$("#total_summ").text(mytext);
			$("#post_summ").val(mytext);
			$(".dos_engels").show("slow");
			$("#insert_before").before("<tr height='30' id='append_this'><td></td><td>Доставка</td><td>"+dostavka+" руб.</td><td></td><td class='center_img'></td></tr>");
		}
		else
		{
			var mytext = parseInt($("#total_summ").text())-parseInt(dostavka);
			$("#change_now").val("0");
			$("#total_summ").text(mytext);
			$("#post_summ").val(mytext);
			$(".dos_engels").hide("slow");
			$("#append_this").remove();
		}

	});


	$('.banner_image').children().hide();
	$('.banner_image').children('img').eq(0).show();
	$('.banner_image').children('div').eq(0).show();
	$('#banners li').eq(0).addClass('action_active');

	var global_slide=2;
	setInterval(function(){
		$('#banners li').removeClass('action_active');
		$('#banners li[data-slide="'+global_slide+'"]').addClass('action_active');

		$('.banner_image').children().hide();
		$('.banner_image').children('[data-slide="'+global_slide+'"]').show();
		if (global_slide==4) global_slide=1;
		else global_slide+=1;
	},10000);

	$('#banners li').click(function(){
		var this_slide=$(this).data('slide');
		if (this_slide==4) global_slide=1;
		else global_slide=this_slide+1;
		$('#banners li').removeClass('action_active');
		$(this).addClass('action_active');

		$('.banner_image').children().hide();
		$('.banner_image').children('[data-slide="'+this_slide+'"]').show();
	});


	msg = new Array();
	var basket = '';
	var totalprice = 0;
	var totalCountGoods = 0;
	if (!$.cookie("basket")) {$.cookie("basket", '', {path: "/"});}
	basket = decodeURI($.cookie("basket"));
		basketArray = basket.split(",");// Находим все товары
		for(var i=0; i<basketArray.length-1;i++) {
			goodsId = basketArray[i].split(":"); // Находим id товара, цену и количество
			totalCountGoods+=parseInt(goodsId[1]);
			totalprice+=parseInt(goodsId[1])*parseInt(goodsId[2]);
		}
		if (!totalprice) {totalprice = 0;}
		$('#totalPrice').val(totalprice);
		$('#totalGoods').val(totalCountGoods);
		if (totalCountGoods!=0) $('#bucket div').text('В вашей корзине '+totalCountGoods+' тов.'); else $('#bucket div').html('Зайдите в каталог, затем добавьте <br>в корзину нужный вам товар');

		$(".buy_now").click(function(){
			$(".panel_buy").slideDown("slow");
		});

		$('.increase').click(function(){
			a=$('.quantity').val()*1;
			$('.quantity').val(a+1);

			a=$('.quantity').val();
			b=$('.button_buy').data('id').split('-');
			b=b[2];
			c=parseFloat(a*b);
			$('#current_price').text(c.toFixed(2));
		});

		$('.decrease').click(function(){
			a=$('.quantity').val()*1;
			if (a>1) $('.quantity').val(a-1);
			else $('.quantity').val(1);

			a=$('.quantity').val();
			b=$('.button_buy').data('id').split('-');
			b=b[2];
			c=parseFloat(a*b);
			$('#current_price').text(c.toFixed(2));
		});

		$('input[name="volume"], .quantity').change(function(){
			a=$('.quantity').val();
			b=$('.button_buy').data('id').split('-');
			b=b[2];
			c=parseFloat(a*b);
			$('#current_price').text(c.toFixed(2));
		});


		$('.button_buy').click(function() {
			data = $(this).data('id').split('-');
			data[3] = $('.quantity').val();
			addCart(data[1], data[2], data[3]);
			return false;
		});

		$('.to_bucket').click(function() {
			data = $(this).data('id').split('-');
			data[3] = $('.quantity').val();
			addCart(data[1], data[2], data[3]);
			return false;
		});



		function addCart(p1, p2, p3){
			if (!p3 || p3==0) {p3=1;}
		msg.id = p1; 		  // АйДи
		msg.price = parseInt(p2); // Цена
		msg.count = parseInt(p3); // Количество
		var check = false;
		var cnt = false;
		var totalCountGoods = 0;
		var totalprice = 0;
		var goodsId = 0;
		var basket = '';
		basket = decodeURI($.cookie("basket"));

		if (basket=='null') {basket = '';}

		basketArray = basket.split(",");
		for(var i=0; i<basketArray.length-1;i++) {
			goodsId = basketArray[i].split(":");
			if (goodsId[0]==msg.id)	// ищем, не покупали ли мы этот товар ранее
			{
				check = true;
				cnt   = goodsId[1];
				break;
			}
		}
		if(!check) {
			basket+= msg.id + ':' + msg.count + ':' + msg.price + ',';
		} else {
			jQuery.noticeAdd({
				text: 'Товар уже есть в корзине',
				stay: false
			});
		}
		if(!check) {
			jQuery.noticeAdd({
				text: 'Товар добавлен в корзину',
				stay: false
			});
			basketArray = basket.split(",");// Находим все товары
			for(var i=0; i<basketArray.length-1;i++) {
				goodsId = basketArray[i].split(":"); // Находим id товара, цену и количество
				totalCountGoods+=parseInt(goodsId[1]);
				totalprice+=parseInt(goodsId[1])*parseInt(goodsId[2]);
			}
			$('#totalPrice').val(totalprice);
			$('#totalGoods').val(totalCountGoods);
			$('#bucket div').text('В вашей корзине '+totalCountGoods+' тов.');
			$.cookie("totalPrice", totalprice, {path: "/"});
			$.cookie("basket", basket, {path: "/"});
		}

		$('#clearBasket').click(function() {
			$.cookie("totalPrice", '', {path: "/"});
			$.cookie("basket", '', {path: "/"});
			$('#totalPrice').val('0');
			$('#totalGoods').val('0');
			$('.in_bucket').text('0');
			jQuery.noticeAdd({
				text: 'Корзина очищена',
				stay: false
			});
			location.replace(location.href);

			return false;
		});
	}

	$(".tags a").not(".selected").click(function () {

		$(".products_list ul").html('<center><img src="/images/loader.gif" /></center>');

		var this_id = $(this).attr("data-id");
		var this_cat = $(this).attr("data-cat");
		var this_page = $("#this_page").attr("data-id");
		$("#tags_"+this_cat).find(".reset_this").remove();
		$("#tags_"+this_cat).append("<a class='reset_this' data-cat='"+this_cat+"'>x</a>");
		$('.reset_this').click(reset_filter);
		$("#tags_"+this_cat+" a").removeClass("selected");
		$(this).addClass("selected");

		var myArray = new Array();
		$(".tags a").each(function (i) {
			if ($(this).hasClass("selected")) {
				cat = $(this).attr("data-cat");
				myArray[cat] = $(this).attr("data-id");
			}
		});

		$.post( "/inc/actions.php", { 'action' : 'filter','myArray' : myArray, 'this_page' : this_page}, function( data ) {
			$(".products_list ul").html(data);
			$('.button_buy').click(function() {
				data = $(this).data('id').split('-');
				data[3] = $('.quantity').val();
				addCart(data[1], data[2], data[3]);
				return false;
			});
		});
	});


	function reset_filter(e) {

		e.preventDefault();
		$(".products_list ul").html('<center><img src="/images/loader.gif" /></center>');
		var this_cat = $(this).attr("data-cat");
		var this_page = $("#this_page").attr("data-id");
		$(this).remove();
		$("#tags_"+this_cat+" a").removeClass("selected");

		var myArray = new Array();
		$(".tags a").each(function (i) {
			if ($(this).hasClass("selected")) {
				cat = $(this).attr("data-cat");
				myArray[cat] = $(this).attr("data-id");
			}
		});

		$.post( "/inc/actions.php", { 'action' : 'filter','myArray' : myArray, 'this_page' : this_page}, function( data ) {
			$(".products_list ul").html(data);
			$('.button_buy').click(function() {
				data = $(this).data('id').split('-');
				data[3] = $('.quantity').val();
				addCart(data[1], data[2], data[3]);
				return false;
			});
		});
	}

});


$(document).ready(function(){

	$(window).scroll(function(){
		if ($(this).scrollTop() > 500) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	});

	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

});