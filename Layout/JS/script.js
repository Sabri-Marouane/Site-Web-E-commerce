
// NavBar
$('#check').click(function(){
    $('#menu').toggleClass('lok');
})

// Login
$('.btninsr').click(function(){
    $('#btn').addClass('lokbtn');
    $('#login').hide();
    $('#insription').show();
});
$('.btnlogin').click(function(){
    $('#btn').removeClass('lokbtn');
    $('#login').show();
    $('#insription').hide();
});
$('#pass2').keyup(function(){
    if ($('#pass1').val()!=='') {
        if ($('#pass2').val() !== $('#pass1').val()){
            $('#erreur').text('Enter le même mot de passe');
        }
        else{
            $('#erreur').text('');
        }
    }
    else{
        $('#erreur').text('');
    }
});
$('#pass1').keyup(function(){
    if ($('#pass2').val()!=='') {
        if ($('#pass2').val() !== $('#pass1').val()){
            $('#erreur').text('Enter le même mot de passe');
        }
        else{
            $('#erreur').text('');
        }
    }
    else{
        $('#erreur').text('');
    }
});
$('#insription .inputBox input').keyup(function(){
    if ($('#nom').val()!=='' && $('#prenom').val()!=='' && $('#email').val()!=='' && $('#adress').val()!=='' && $('#pass1').val()!=='' && $('#pass2').val()!==''){
        if ($('#pass1').val() == $('#pass2').val()) {
            $('#inscr').removeAttr('disabled');
            $('#inscr').removeClass('liko');
        }
        else{
            $('#inscr').attr("disabled","disabled");
            $('#inscr').addClass('liko');
        }
    }
    else{
        $('#inscr').attr("disabled","disabled");
        $('#inscr').addClass('liko');
    }
});
$('.inputBox input').keyup(function(){
    if ($(this).val() !== ''){
        $(this).siblings('.text').addClass('boom');
    }
    else{
        $(this).siblings('.text').removeClass('boom');
    }
});
var pass1 = document.getElementById('pass1');
var pass2 = document.getElementById('pass2');
$('.eyeclose').click(function(){
    pass1.type ='text';
    pass2.type ='text';
    $('.eyeopen').show();
    $('.eyeclose').hide();
})
$('.eyeopen').click(function(){
    pass1.type ='password';
    pass2.type ='password';
    $('.eyeclose').show();
    $('.eyeopen').hide();
})
$('#login .inputBox input').keyup(function(){
    if ($('#emaill').val()!=='' && $('#password').val()!==''){
        $('#log').removeAttr('disabled');
        $('#log').removeClass('liko');
    }
    else{
        $('#log').attr("disabled","disabled");
        $('#log').addClass('liko');
    }
});

// Produits
$('.likein').click(function(){
    $(this).addClass('likeout');
    $(this).siblings('.liked').removeClass('likeout');
    $('.like-val').val(1);
})
$('.liked').click(function(){
    $(this).siblings('.likein').removeClass('likeout');
    $(this).addClass('likeout');
    $('.like-val').val(0);
})
// 
$('.icon').click(function(){
    $(this).parent().parent().parent('.content-wrapper').addClass('content-wrapper-plus');
    $(this).parent().parent().parent('.content-wrapper').siblings('.img-wrapper').children('.img').addClass('img-plus');
    $(this).parent().parent().parent('.content-wrapper').children('.title').addClass('title-plus');
    $(this).parent().parent().parent('.content-wrapper').children('.price').hide();
    $(this).parent().hide();
    $(this).parent().parent().siblings('.like').hide();
})
$('.annule').click(function(){
    $('.img').removeClass('img-plus');
    $('.content-wrapper').removeClass('content-wrapper-plus');
    $('.title').removeClass('title-plus');
    $('.price').show();
    $('.icons').show();
    $('.like').fadeIn(4000);
});

// Items
$('.les-li').click(function(){
    $(this).addClass('active-li');
    $(this).siblings().removeClass('active-li');
});
// 
$('.img-1').click(function(){
    $('.item-1').addClass('active-img');
    $('.item-1').siblings().removeClass('active-img');
});
$('.img-2').click(function(){
    $('.item-2').addClass('active-img');
    $('.item-2').siblings().removeClass('active-img');
});
$('.img-3').click(function(){
    $('.item-3').addClass('active-img');
    $('.item-3').siblings().removeClass('active-img');
});
$('.img-4').click(function(){
    $('.item-4').addClass('active-img');
    $('.item-4').siblings().removeClass('active-img');
});
$('.img-5').click(function(){
    $('.item-5').addClass('active-img');
    $('.item-5').siblings().removeClass('active-img');
});
// 
$('.size').click(function(){
    $(this).addClass('active-size');
    $(this).siblings().removeClass('active-size');
    $('.sizes-value').val($(this).children().children('.size-1').val());
});
$('.color').click(function(){
    $(this).addClass('active-color');
    $(this).siblings().removeClass('active-color');
    $('.colors-value').val($(this).children().children('.color-1').val());
});
// 
var prix = (($('.prixmaxprice').val() - $('.prixprice').val()) / $('.prixmaxprice').val() * 100 );
$('.rapport').text('-' + Math.round(prix) + '%');
// 
var quantity = 1;
$('.plus').click(function(){
    if (quantity<100) {
        quantity+=1;
        $('.input-quantity').val(quantity);
        $('.number-quantity').text($('.input-quantity').val());
    }
})
$('.moin').click(function(){
    if (quantity>1) {
        quantity-=1;
        $('.input-quantity').val(quantity);
        $('.number-quantity').text($('.input-quantity').val());
    }
})
// 
$('.no-like').click(function(){
    $(this).addClass('lok-like');
    $('.yes-like').removeClass('lok-like');
    $('.like-item').addClass('liked-item');
    $('.like-value').val(1);
})
$('.yes-like').click(function(){
    $(this).addClass('lok-like');
    $('.no-like').removeClass('lok-like');
    $('.like-item').removeClass('liked-item');
    $('.like-value').val(0);
})
// 
$('.size').click(function(){
    if ($('.sizes-value').val() !== '' && $('.colors-value').val() !== '') {
        $('.smt-item').removeAttr('disabled');
        $('.smt-item').addClass('smt-item-plus');
    }
});
$('.color').click(function(){
    if ($('.sizes-value').val() !== '' && $('.colors-value').val() !== '') {
        $('.smt-item').removeAttr('disabled');
        $('.smt-item').addClass('smt-item-plus');
    }
});

// Erreur
var container = document.getElementById('pageerreur');
container.onmousemove = function(e){
    var x = - e.clientX,
        y = - e.clientY;
    container.style.backgroundPositionX = x + 'px';
    container.style.backgroundPositionY = y + 'px';
}

