user = (Math.random() * 100000).toFixed(0);
$(document).ready(function(){
  if($(window).width() <= 700){
    $('#route').insertBefore($('#map_container'))
  }
  $("#nextbutton").text( pages[1]+' >' )
  $('.bestelpagina').hide();
  $('#bestelpagina_0').show()

  $('.pic_left').on('click', function(){
    var pic = $(this).css('background-image');
    // console.log(pic)
    $('#pic_right').css('background', pic + ' center/cover white')
  })

  if($(document).width()>00){
    // console.log('test')
    $('#pictures').before(($('#content_section_hor')))
  }

  $('#factuuradres_check').on('change', function(){
      if($(this).prop('checked')){
        $('#factuuradres').show()
      }
      else{
        $('#factuuradres').hide()
      }

  })
  $('.geslacht').on('click', function(){
    selected['geslacht'] = $(this).attr('id').split('_')[1];
    $(".geslacht").css('background-color','rgb(229 229 229)');
    $(this).css('background-color','#4f917f');
  })
  $('.cadeau_option').on('click', function(){
    $('.cadeau_option').css('background', 'rgb(229 229 229)');
    $('.cadeau_option').find('.box_spec_title').css('color', '#4f917f')
    $(this).css('background', '#4f917f')
    $(this).find('.box_spec_title').css('color', 'white')
    selected['cadeau'] = $(this).attr('id').split("_")[1]


  })

  $('#winkelmandje, #nextbutton, #backbutton, .counter_button, .route, #bestelknop,  #bestelknop--mobiel, .pic_left, #header_image, #burger_menu_button, .box_option , .option ').on('click', function(){
    sendData(user, 'cadeau', $(this).attr('class'),$(this).attr('id'), $(this).text(), window.location.search);
  })
  sendData(user, 'cadeau', "start", '', '', window.location.search);

})
var sentToSlack = false;
var pages = ['Bon','Personalisatie', 'Gegevens', 'Levering','Betaling']
var currentPage = 0;
var selected = {}
function bestelNext(){
  if(!sentToSlack){
    sendToSlack('CADEAU: Bestelproces begonnen', '#vragen')
    sentToSlack = true;
  }
  currentPage++;
  currentPage %= pages.length;
  if(pages[currentPage] == 'Levering'){
    // levering();
    $('#persoonlijkelevering').hide();
    $('.totalprice').show()
    if(!gegevens()){
      currentPage--;
      return;
    }

  }
  if(pages[currentPage] == 'Gegevens'){

  }
  if(pages[currentPage] == 'Betaling'){
    // if(!betaling()){
    //   currentPage--;
    //   return;
    // }
    betaling();
  }
  $('.bestelpagina').hide();
  $("#bestelpagina_" + currentPage).fadeIn()
  if(currentPage == 0){
    $('#backbutton').hide();
  }
  else{
    $('#backbutton').text('< ' + pages[currentPage - 1]).show();
  }
  if(currentPage == pages.length - 1){
    $('#nextbutton').hide();
  }
  else{
    $('#nextbutton').text( pages[Number(currentPage) + 1]+' >' ).show();
  }
}
function bestelBack(){
  currentPage = (((currentPage - 1) % pages.length) - 1 ) % pages.length
  bestelNext();
}

function betaling(){

  $('.totalprice').hide();
     var prijs = 0;
     var cadeau = '';
     var samenstelling = '';
     var samenstellingID = '';
    if(selected[cadeau] && selected[cadeau] == 'box'){
      prijs = 34.99;
      cadeau = 'box';
      samenstelling = "1 Cadeaubon BOX & &";
      samenstellingID = "1 1002 && ";
    }
    else{
      prijs = 19.99;
      cadeau ='go';
      samenstelling = "1 Cadeaubon GO && ";
      samenstellingID = "1 1001 && ";
    }

    // var verzend = 3.99;
    // var levering = $('#levering').val()
    // if($('#bestelling_stad').val().toLowerCase() != 'utrecht'){
    verzend = 5.99
    $('#verzendkosten').html('&euro;5,99')
    levering='POSTNL'
    samenstellingID += "1 9002 && "
    samenstelling += "1 POSTNL && "
    // }
    // else{
    //   samenstellingID += "1 9001 && "
    //   samenstelling += "1 Persoonlijke verzending && "
    // }

    total = Number(prijs) + Number(verzend);

    total = total.toFixed(2)

    $('#box-prijs').html('WXNDER Cadeaubon ' + cadeau.toUpperCase())
    $('#box-prijs-euro').html('&euro;'+prijs.toFixed(2))

  if($('#bestelling_factuur_stad').val().length ==0){
    $('#bestelling_factuur_stad').val($('#bestelling_stad').val())
  }
  if($('#bestelling_factuur_straatnaam').val().length ==0){
    $('#bestelling_factuur_straatnaam').val($('#bestelling_straatnaam').val())
  }

  if($('#bestelling_factuur_postcode').val().length ==0){
    $('#bestelling_factuur_postcode').val($('#bestelling_postcode').val())
  }
  var geslacht = 'onbekend';
  if(selected['geslacht']){geslacht = selected['geslacht']}
  var ob = {
    'Naam': $('#bestelling_naam').val(),
    'Achternaam': $('#bestelling_achternaam').val(),
    'Geboortedatum': checkDate(),
    'Email': $('#bestelling_email').val(),
    'Straatnaam': $('#bestelling_straatnaam').val(),
    'Stad': $('#bestelling_stad').val(),
    'Telefoonnummer': $('#bestelling_tel').val(),
    'Postcode': $('#bestelling_postcode').val(),
    'Geslacht': geslacht,
    'Ordernumber': new Date().getTime(),
    'Factuur_stad': $('#bestelling_factuur_stad').val(),
    'Factuur_straatnaam': $('#bestelling_factuur_straatnaam').val(),
    'Factuur_postcode': $('#bestelling_factuur_postcode').val(),
    "Specificaties": JSON.stringify({
      "Bericht": $('#cadeau-bericht').val(),
      "Ontvanger": $('#cadeau-ontvanger').val(),
      "Namens": $('#cadeau-namens').val(),
      "Loopt-mee": $('#cadeau-loop-zelf-mee').val(),
      "Foto-achterkant": $('#cadeau-foto-achterkant').val(),
      'Opmerkingen': $('#extra_opmerkingen').val(),
    }),
    'SamenstellingID': samenstellingID,
    'Samenstelling': samenstelling,
    'Totaalprijs': total,
    'Leverdatum':levering,
  }

console.log(ob);
  //make form for php
  for(var key in ob){
    var input = $('<input>').attr('type', 'text').attr('name',key).attr('class', 'result_field');
    input.val(ob[key].toString())
    $('#resultsx').prepend(input)
  }
  $('.checkoutproduct').remove()
  $('.extra_product .number').each(function(index){
    if(Number($(this).html()) >  0){
      // samenstelling += Number($(this).html()) + ' ' + $(this).parent().parent().find('.title').text() +' / ';
      var title = $(this).parent().parent().find('.title').text();
      var num = Number($(this).html())

      var price = Number($(this).parent().parent().find('.price').text().substring(1).replace(',', '.')) * num
      $('<tr  class="checkoutproduct"><td>' + num + ' ' + title + '</td><td>' + price.toFixed(2).toString() + '</td> </tr>' ).insertAfter('#firstrowgo')
    }

  })
  $('#total_amount').html('&euro;'+ob.Totaalprijs)


  return true;
}
function checkDate(){
  if($('#bestelling_geboortedatum').val() && $('#bestelling_geboortedatum').val() != null){
    return $('#bestelling_geboortedatum').val();
  }
  else{
    return false;
  }
}
function checkData(){
  return  $('#bestelling_naam').val().length > 0 &&      $('#bestelling_achternaam').val().length > 0 &&    $('#bestelling_tel').val().length > 0 &&      $('#bestelling_email').val().length > 0 &&      $('#bestelling_straatnaam').val().length > 0 &&      $('#bestelling_stad').val().length > 0 &&    $('#bestelling_postcode').val().length > 0;

}

function gegevens(){
    if(!checkDate()){alert('Vul een correcte geboortedatum in!');return false;}
    if(!checkData()){alert('Vul je gegevens in'); return false;}
    return true;
}
function bestelBox(){
  document.getElementById('bestelproces').scrollIntoView({'behavior': 'smooth'});
  if(!sentToSlack){
    sendToSlack('CADEAU: Bestelproces begonnen', '#vragen')
    sentToSlack = true;
  }
}
