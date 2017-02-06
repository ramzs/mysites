/**
 * Created with JetBrains PhpStorm.
 * User: SkyMan
 * Date: 17.03.16
 * Time: 6:58
 * To change this template use File | Settings | File Templates.
 */
var site_url = 'http://www.emae.ru/shop/';
var shk_timer;
$(document).ready(function() {
    var body = $('body');

    body.on('click', 'video', function() {
        var video_obj = $("#" + $(this).attr('id'));
        var video = $("#" + $(this).attr('id')).get(0);
        if(video.paused) {
            video.play();
            if(video_obj.parent().find('.play_video').length > 0) {
                video_obj.parent().find('.play_video').hide();
            }
        } else {
            video.pause();
            if(video_obj.parent().find('.play_video').length > 0) {
                video_obj.parent().find('.play_video').show();
            }
        }
    });

    body.on('click', '.play_video', function() {
        var video = $("#" + $(this).parent().find('video').attr('id')).get(0);
        video.play();
        $(this).hide();
    });


    body.on('click', '.fr_deliv_city_link_popup', function(e) {
        $('#regselect').arcticmodal();
        e.preventDefault();
    });

    $("#geo_city").keyup(function (event) {
        if(38 != event.keyCode && 40 != event.keyCode && 13 != event.keyCode) {
            var vl = $(this).val();
            var cnt = vl.length;

            if (cnt > 0) {
                var data = { vl: "" + vl + ""};
                $.ajax({
                    type: "GET",
                    url: site_url + "assets/modules/priceregion/ajax.php",
                    data: data
                }).done(function (html) {
                    if (html != "") {
                        $("#select-city-box").slideDown();
                        $("#select-city").html(html);
                        $('#geo_city').focus();
                        selectKeys();
                    }
                });
            }
        }
    });

    $("#geo_city").blur(function () {
        $("#select-city-box").slideUp();
    });

    $('.fancy-image').fancybox({'type' : 'image'});
    $('.fast_view').on('click', function(e) {
        var prod_id = $(this).attr('id').substr(20);
        $('#hid_prod_id_fast_view').val(prod_id);
        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            cache: false,
            dataType: 'html',
            success: function(data) {
                var fast_vew_text = $('#fast_view .fast_view_text');
                fast_vew_text.html(data);
                fast_vew_text.find('img').each(function(idx, elm) {
                    $(elm).attr('src' , site_url + $(elm).attr('src'));
                    $(elm).attr('data-large', site_url + $(elm).data('large'));
                });

                fast_vew_text.find('a.fancy-image').each(function(idx, elm) {
                    $(elm).attr('href' , site_url + $(elm).attr('href'));
                });

                fast_vew_text.find('.link_to_cart').attr('href', '/shop/' + fast_vew_text.find('.link_to_cart').attr('href'));
                fast_vew_text.find('.butn_add').removeAttr('onclick');
                fast_vew_text.find('.fast_view_icons a').each(function(idx, elm) {
                    $(elm).attr('href', '/shop/' + $(elm).attr('href'));
                });

                $('#fast_view').arcticmodal({
                    afterOpen: function () {
                        more_text();
                        zoom_image();
                        $('.navigation .carousel ul li').on('click', function() {
                            var index_li = $('.navigation .carousel ul li').index($(this));
                            $('#tovar_images_carousel li').hide();
                            $('#tovar_images_carousel li:eq('+index_li+')').show();
                        });
                        waiting_actions();
                    },
                    overlay: {
                        tpl: '<div class="arcticmodal-overlay"></div>',
                        css: {backgroundColor: "#fff", opacity: 0.6}
                    },
                    afterClose: function() {
                        body.css('overflow', 'auto');
                        $('.magnifier, .tracker, .cursorshade, .statusdiv, .zoom-image').hide();
                    }
                });

                showDeliveryText();
            }
        });
        e.preventDefault();
    });

    var fast_view = $('#fast_view');
    fast_view.on('click', '#fast_view_colors a', function(e) {
        var index_a = $('#fast_view_colors a').index($(this));
        var colors_html = $('.fast_view_other_colors').html();
        $.ajax({
            type: 'GET',
            url: site_url + $(this).attr('href'),
            cache: false,
            dataType: 'html',
            success: function(data) {
                var prepare_tovar = $('#prepare_tovar');
                prepare_tovar.html(data);
                prepare_tovar.find('img').each(function(idx, elm) {
                    $(elm).attr('src' , site_url + $(elm).attr('src'));
                    $(elm).attr('data-large', site_url + $(elm).data('large'));
                });

                prepare_tovar.find('a.fancy-image').each(function(idx, elm) {
                    $(elm).attr('href' , site_url + $(elm).attr('href'));
                });

                prepare_tovar.find('.butn_add').removeAttr('onclick');
                prepare_tovar.find('.link_to_cart').attr('href', '/shop/' + prepare_tovar.find('.link_to_cart').attr('href'));
                $('#prepare_tovar .fast_view_other_colors').html(colors_html);
                $('#fast_view .fast_view_text').html(prepare_tovar.html());
                prepare_tovar.html('');
                $('#fast_view #fast_view_colors img').removeClass('active_image');
                $('#fast_view #fast_view_colors a:eq('+index_a+')').find('img').addClass('active_image');
                more_text();
                zoom_image();
                $('.navigation .carousel ul li').on('click', function() {
                    var index_li = $('.navigation .carousel ul li').index($(this));
                    $('#tovar_images_carousel li').hide();
                    $('#tovar_images_carousel li:eq('+index_li+')').show();
                });
                showDeliveryText();
                waiting_actions();
            }
        });
        e.preventDefault();
    });

    fast_view.on('click', 'button.butn_add', function() {
        $(this).addClass('hide_but_to_cart');
        $(this).parent().find('.link_to_cart').css('display', 'block');
    });

    fast_view.on('click', '.size_list a', function(e) {
        var size = $(this);
        var fr_delivery = $('#delivery_fr').data('delivery');
        if(!$(this).hasClass('active')) {
            fast_view.find('.link_to_cart').hide();
            fast_view.find('button.shk-but').removeClass('hide_but_to_cart');
        }
        $('#fast_view .size_list a').removeClass('active');
        $('.size_list > ul li span').removeClass('active');
        var price = size.data('price');
        var saleprice = size.data('saleprice');
        var tovar_id = size.data('id');

        if(saleprice > 0) {
            $('.fast_view_price_block #salePrice').html(price + ' руб.');
            $('.fast_view_price_block #price span').html(saleprice + ' руб.');
            $('.price_block input[name="shk-id"]').val(tovar_id);

        } else {
            $('#salePrice').html('');
            $('#price span').html(price + ' руб.');
            $('.to_cart_but_fast_view input[name="shk-id"]').val(tovar_id);
        }

        $('.to_cart_but_fast_view #select__size__add').val(size.text());

        size.addClass('active');
        showDeliveryTextHtml(size, fr_delivery);
        $('.link_to_waiting_list').hide();
        $('.butn_add').show();
        e.preventDefault();
    });

    fast_view.on('submit', '.orderForms', function(e) {
        $.ajax({
            type: "POST",
            cache: false,
            url: site_url+'assets/snippets/shopkeeper/ajax-action.php',
            data: $(this).serialize()+'&lang=russian-UTF8&action=fill_cart&addit_data_tpl=&cart_row_tpl=@FILE:chunk_shopCartRow.tpl' +
                '&cart_tpl=mini-cartTpl&cart_type=small&change_price=false&currency=руб.&link_allow=true&nocounter=false' +
                '&order_page=forma-zakaza.html&price_tv=price_tv&site_url=http://www.emae.ru/shop/',
            success: function(data){
                var hide_cart = $('#hide_cart');
                hide_cart.html(data);
                var count_in_cart = hide_cart.find('#shopCart').data('count');
                $('.count_tovars_to_cart').html(count_in_cart);
                $.cookie('count_cart_tovars', count_in_cart, { expires: 7, path: '/'});
                clearTimeout(shk_timer);
                $('#stuffHelper_new').fadeIn(500);
                shk_timer = setTimeout(function () {
                    $('#stuffHelper_new').fadeOut();
                }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert(textStatus+' '+errorThrown);
            }
        });
        e.preventDefault();
    });

    body.on('click', '.link_to_waiting_list', function(e) {
        var elem = $(this);
        var login = $(this).data('login');
        var size = $(this).attr('data-size');
        if(!login) {
            $('#mess_login').arcticmodal();
        } else {
            $.ajax({
                type: 'POST',
                url: site_url + 'waiting_list.php',
                cache: false,
                dataType: 'html',
                data: {
                    'action': 'add_to_waitinglist',
                    'tovar_id': $('input[name="shk-id"]').val(),
                    'size': size
                },
                success: function(data) {
                    if($(elem).hasClass('fast_waiting_link')) {
                        $('#stuffHelper_waiting').show();
                        shk_timer = setTimeout(function () {
                            $('#stuffHelper_waiting').fadeOut(500);
                        }, 1000);
                    }
                }
            });
        }
        e.preventDefault();
    });

    jQuery('#select-city-box').on('click', 'input', function(){
        jQuery('#geo_city').val(jQuery(this).val());
        jQuery('#hid_savecity').val(jQuery(this).data('name'));
        jQuery('#hid_citycode').val(jQuery(this).data('citycode'));
        jQuery('#form_select_city').submit();
    });

    $('#form_select_city').on('submit', function(e) {
        if($('#select-city input.active').length > 0) {
            $('#geo_city').val($('#select-city input.active').val());
        }

        var active_tovar_id = $('#hid_prod_id_fast_view').val();
        var new_city = $('#hid_savecity').val();
        $('.city-link').html(new_city);
        if(active_tovar_id != '') {
            var tovar_fast_view_link = $('#link_to_tovar_extra_' + active_tovar_id).attr('href');
            $.ajax({
                type: 'POST',
                url: site_url,
                cache: false,
                dataType: 'html',
                data: $(this).serialize(),
                success: function() {
                    $.ajax({
                        type: 'GET',
                        url: tovar_fast_view_link,
                        cache: false,
                        dataType: 'html',
                        success: function(data) {
                            $('#fast_view .fast_view_deliv_wrap').html($(data).find('.fast_view_deliv_wrap').html());
                            showDeliveryText();
                            $('#regselect .arcticmodal-close a').trigger('click');
                            $('#geo_city').val('ВВЕДИТЕ НАЗВАНИЯ ГОРОДА');
                        }
                    });
                }
            });
            e.preventDefault();
        }
    });
    
    
});


function more_text() {
    var showChar = 100;
    var ellipsestext = "...";
    var moretext = "Подробнее";
    var lesstext = "Свернуть";
    $('.fast_desc_value').each(function() {
        var content = $(this).html();
        if(content.length > 0) {
            var words = content.split(' ');
            var i = 0, count_words = words.length;
            var c = '';
            var h = '';
            for(i;i < count_words;i++) {
                if(i < 20) {
                    c += words[i] + ' ';
                } else {
                    h += words[i] + ' ';
                }
            }
            var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
            $(this).html(html);
        }
    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
}

function zoom_image() {
    $(".zoom-image").imagezoomsl({
        zoomrange: [1, 10],
        magnifiersize: [450, 505],
        scrollspeedanimate: 10,
        loopspeedanimate: 5,
        showstatustime:200000,
        cursorshadeborder: "5px solid #b9b9b9",
        magnifiereffectanimate: "fadeIn",
        magnifierborder: "1px solid #b9b9b9",
        /*leftoffset:25,*/
        descarea:".cont_magnifier",
        catalog: 1
    });
}

function waiting_actions() {
    $('.not_active_size').on('click', function() {
        $('.size_list a, .size_list span').removeClass('active');
        $(this).addClass('active');
        $('button.butn_add, .link_to_cart').hide();
        $('.link_to_waiting_list').show();
        $('.link_to_waiting_list').attr('data-size', $(this).html());
    });

    $('.not_active_size_li').hover(function() {
        $(this).find('.waiting_toltip').fadeIn();
    }, function() {
        $(this).find('.waiting_toltip').fadeOut();
    });
}

function showDeliveryText() {
    if($('#fast_view .fast_view_text').find('.size_list a.active')) {
        var size_active = $('#fast_view .fast_view_text').find('.size_list a.active');
        var fr_delivery = $('#delivery_fr').data('delivery');
        showDeliveryTextHtml(size_active, fr_delivery);
    }
}


function showDeliveryTextHtml(size_active, fr_delivery) {
    selected_city = $('#delivery_fr').data('city');
    if(size_active.data('count') == '0') {
        if(fr_delivery > 0) {
            if($('#page_tovar').length > 0) {
                $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в течении</span><span class="fr_deliv_green_txt"> '+fr_delivery+' дней</span><br/><span class="fr_deliv_black_txt">в</span> <a href="#" class="fr_deliv_city_link_popup">'+selected_city+'</a>').show();
            } else {
                $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в</span> <a href="#" class="fr_deliv_city_link_popup"> '+selected_city+'</a> <span class="fr_deliv_black_txt">в течении </span><span class="fr_deliv_green_txt">'+fr_delivery+' дней</span>').show();
            }
        }
    } else {
        if($('#delivery_fr').data('franshiza') == 1) {
            if($('#page_tovar').length > 0) {
                $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в течении </span><span class="fr_deliv_red_txt">1 дня</span><br/><span class="fr_deliv_black_txt">в</span> <a href="#" class="fr_deliv_city_link_popup">'+selected_city+'</a>').show();
            } else {
                $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в</span> <a href="#" class="fr_deliv_city_link_popup">'+selected_city+'</a> <span class="fr_deliv_black_txt">в течении</span> <span class="fr_deliv_red_txt">1 дня</span>').show();
            }
        } else {
            if($('#page_tovar').length > 0) {
                if(selected_city == 'Москва') {
                    $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в течении</span><span class="fr_deliv_red_txt"> 1 дня</span><br/><span class="fr_deliv_black_txt">в</span> <a href="#" class="fr_deliv_city_link_popup">'+selected_city+'</a>').show();
                } else {
                    $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка </span><span class="fr_deliv_green_txt">от 1 до 5 дней</span><br/><span class="fr_deliv_black_txt"> в</span> <a href="#" class="fr_deliv_city_link_popup">'+selected_city+'</a>').show();
                }
            } else {
                if(selected_city == 'Москва') {
                    $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в</span> <a href="#" class="fr_deliv_city_link_popup"> '+selected_city+'</a><span class="fr_deliv_black_txt"> в течении </span><span class="fr_deliv_red_txt">1 дня</span>').show();
                } else {
                    $('#delivery_fr').html('<span class="fr_deliv_black_txt">Доставка в</span> <a href="#" class="fr_deliv_city_link_popup"> '+selected_city+'</a> <span class="fr_deliv_green_txt">от 1 до 5 дней </span>').show();
                }
            }
        }
    }
}

function selectKeys() {
    var arrayInp=[];
    $('#select-city input').each(function() {
        arrayInp.push($(this));
    });
    var i=0, number='', count='undefined' !== typeof arrayInp ? (arrayInp.length-1) : 0;
    $('#geo_city').unbind('keydown');
    $('#geo_city').bind('keydown', function(event) {
        if(count!=0) {
            if(38==event.keyCode) {
                if(0<number) {
                    arrayInp[number].removeClass('active');
                    --number;
                    --i;
                    arrayInp[number].addClass('active');
                    scrollFunction('up', arrayInp[number]);
                    $('#hid_savecity').val(arrayInp[number].val());
                }
            } else if(40==event.keyCode) {
                if(i!=count) {
                    if(arrayInp[i].hasClass('active') && i<count) {
                        ++i;
                    }
                    for(var l in arrayInp) {
                        if(number==l) {
                            arrayInp[number].removeClass('active');
                        }
                        if(l==i) {
                            number=i;
                            arrayInp[i].addClass('active');
                            scrollFunction('down', arrayInp[number]);
                            $('#hid_savecity').val(arrayInp[number].val());
                        }
                    }
                }
            }
        } else {
            return;
        }
    });
}

function scrollFunction(key, elm) {
    var scroll_val = $('#select-city-box').scrollTop();
    var pos_elm = $(elm).position().top + 2;
    var parent_height = $('#select-city-box').height();
    var inp_height = $('#select-city input').innerHeight();

    if(pos_elm > parent_height) {
        if(key == 'down') {
            $('#select-city-box').scrollTop(scroll_val + inp_height);
        }
    }

    if(pos_elm < 0) {
        $('#select-city-box').scrollTop(scroll_val - inp_height);
    }
}