var codes = [
  3711,
  3971,
  3972,
  3941,
  3947,
  3951,
  3953,
  3956,
  3945,
  3984,
  3985,
  3958,
  3961,
  3962,
  3959,
  370,
  35,
  3981,
  3732,
  3931,
]

people = 2;
user = (Math.random() * 100000).toFixed(0);
$(document).ready(function(){
  $('.counter_button').on('click', counter )
  $('.counter_button-personen').on('click', counterPersonen )
  console.log('tesr')
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

  makeRoutes();
  makeLabels()
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
    $(".geslacht").css('background-color','mistyrose');
    $(this).css('background-color','orange');
  })
  $('.ingredient').hide()
  $('#ingredienten').hide()
  $('#eigen-glazen').hide()

  var ingredienten = {
    'bier':['bier', 'worst', 'kaas', 'crackers', 'sapjes'],
    'wijn':['wijn', 'worst', 'kaas', 'crackers', 'sapjes'],
    'alcoholvrij':['worst', 'kaas', 'crackers', 'sapjes'],
    'vega':['extra-drankje', 'nootjes', 'kaas', 'crackers', 'sapjes']
  }
  $('.box_option').on('click', function(){
    $('#extra_box_keuzes .option').css('background', 'mistyrose');
    $('#extra_box_keuzes .option').css('color', 'rgb(83,145,126)');
    selected['extra_box_keuze'] = '';

    var id= $(this).attr('id');
    if(!selected['box'] || selected['box'] !=id){
    selected['box'] =id;
    $('.box_option').css('background', 'mistyrose')
    $('.box_option .box_spec_title').css('color', 'orange')
    $(this).css('background', 'orange')
    $(this).find('.box_spec_title').css('color', 'mistyrose')
    var keuze = id.split('_')[2];

    $('.extra_box_keuze').hide();
    $('#'+keuze+'keuze').show();
    $('#eigen-glazen').hide()

    var in_box = ingredienten[keuze];
    $('.ingredient').hide()
    $('#ingredienten').show()
    for(var x in in_box){
      $('#ingredient--' + in_box[x]).show()
    }
    if(keuze == 'wijn'){
      $('#eigen-glazen').show()
    }

  }
  else{
    $('.box_option').css('background', 'mistyrose')
    $(this).find('.box_spec_title').css('color', 'orange')
    $('.extra_box_keuze').hide();
    selected['box'] = '';
    $('.ingredient').hide()
    $('#ingredienten').hide()
    $('#eigen-glazen').hide()
  }
  })
  $('#extra_box_keuzes .option').on('click', function(){
    var keuze = $(this).attr('id');
    if(!selected['extra_box_keuze'] || selected['extra_box_keuze'] !=keuze){
      selected['extra_box_keuze'] = keuze;
      $('#extra_box_keuzes .option').css('background', 'mistyrose');
      $('#extra_box_keuzes .option').css('color', 'rgb(83,145,126)');
      $(this).css('background', 'orange');
      $(this).css('color', 'mistyrose');
    }
    else{
      $('#extra_box_keuzes .option').css('background', 'mistyrose');
      $('#extra_box_keuzes .option').css('color', 'rgb(83,145,126)');
      selected['extra_box_keuze'] = '';
    }

  })
  $('#winkelmandje, #nextbutton, #backbutton, .counter_button, counter_button-personen, .route, #bestelknop,  #bestelknop--mobiel, .pic_left, #header_image, #burger_menu_button ').on('click', function(){
    sendData(user, 'pasen', $(this).attr('class'),$(this).attr('id'), $(this).text(), window.location.search);
  })
  sendData(user, 'pasen', "start", '', '', window.location.search);

  fillProducts()
})
var sentToSlack = false;

var pages = ['Box','Route', 'Gegevens', 'Levering','Betaling']
var currentPage = 0;
var selected = {}
function bestelNext(){
  if(!sentToSlack){
    sendToSlack('PASEN: Bestelproces begonnen', '#vragen')
    sentToSlack = true;
  }
  currentPage++;
  currentPage %= pages.length;
  if(pages[currentPage] == 'Levering'){
    // levering();
    $('#persoonlijkelevering').hide();
    $('#postnl').hide();
    var postcode = $('#bestelling_postcode').val().toLowerCase();
    var code = postcode.substring(0,4);
    var code_in_codes = codes.filter(function(c){return (code.indexOf(c.toString())=== 0)});
    if(code_in_codes.length>0){
      $('#persoonlijkelevering').show()
    }
    else{
      $('#postnl').show()
    }
    $('.totalprice').show()
  }
  if(pages[currentPage] == 'Gegevens'){
    gegevens();
  }
  if(pages[currentPage] == 'Betaling'){
    if(!betaling()){
      currentPage--;
      return;
    }
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

function makeRoutes(){
  for(var name in route_informatie){
    var info = route_informatie[name];
    invert = false;
    // name = name.replace
    if(name == "maisland" || name =='fields_of_the_past'){
      invert=true;
    }
    var imgs_url = "https://wxnder.imgix.net/routes/"+name+'?auto=enhance&fm=webp&q=10';
    var achtergrond = imgs_url + '_achtergrond' + info.achtergrond_type;
    var titel = imgs_url + '_titel.svg'
    var logo = imgs_url + '_logo.svg'

    var img_achtergrond = $('<img>').attr('src', achtergrond).addClass('route_foto')
    var img_titel = $('<p>').text(name.split('_').join(' ')).addClass('route_titel')
    var img_logo = $('<img>').attr('src', logo).addClass('route_logo')
    if(invert){
      img_logo.css('filter', 'invert(1)');
    }
    var lengte = info.lengte;
    var locatie = info.regio;

    var p_lengte = $("<p>").text(lengte).addClass('route_lengte')
    var p_locatie = $("<p>").text(locatie).addClass('route_locatie')

    var route_div = $("<div>").addClass('route');
    route_div.attr('id',name);
    route_div.append(img_achtergrond).append(img_titel).append(img_logo)
    route_div.append(p_lengte).append(p_locatie)

    $('#route').append(route_div)
    $('#routes').append(route_div)

    route_div.on('click', routeClicked)

  }
  $('.route').hover(function(){
    $(this).find('.route_foto').css('opacity', '0.7')
  }, function(){
    $(this).find('.route_foto').css('opacity', '0.5')

  })
}
function makeLabels(){
  var allLabels = []
  for(var route in route_informatie){
    var labels = route_informatie[route].labels;
    labels.forEach(function(label){
      if(allLabels.indexOf(label) == -1){
        allLabels.push(label)
      }
    })

  }

      allLabels.forEach(function(label){
         $('#labels').append($('<div>').addClass('label').text(label))
      })

}
function counter(){
    if($(this).text() == '-'){
      if(Number($(this).next().text())> 0 )  {
        $(this).next().text(Number($(this).next().text()) - 1)
      }
    }
    else{
      $(this).prev().text(Number($(this).prev().text()) + 1)
    }
    // calcPrice()
    var prices = {
      'wijn': [6,9,15,18,24,27],
    };
    var personen = Number($(this).parent().find('.number').text());
    if($(this).parent().attr('id') == 'counter-wijn-pasen'){
      var glas = 3;
      var fles = 3;
      var flessen = (personen + personen % 2) / 2
      var prijs = personen * glas + flessen * fles;
    }
    else if($(this).parent().attr('id') == 'counter-bier-pasen'){
      var fles = 3;
      var prijs = fles * personen;
    }
    $(this).parent().parent().find('.price span').text(prijs.toFixed(2).replace('.',','))

    fillProducts()
}
function counterPersonen(){
  if($(this).text() == '-'){
    if(Number($(this).next().text())> 2 )  {
      $(this).next().text(Number($(this).next().text()) - 1)
      people = Number($(this).next().text());
    }
  }
  else{
    if(Number($(this).prev().text())>7){
      alert('Als u voor meer dan 8 personen wilt kopen kunt u het best contact opnemen met info@wxnder.nl')
    }
    else{
    $(this).prev().text(Number($(this).prev().text()) + 1)
    }
    people = Number($(this).prev().text());
  }
  fillProducts()
  // calcPrice()
}
function fillProducts(){
    $("#productenlijst").empty()
    var box_prijzen = [0,0,20.99, 26.99, 31.99, 44.99, 49.99, 55.99, 59.99];
    var productPerPersoon = {
      'nootjes': 3,
      'kaas': 2,
      'kaascrackers': 2,
      'sapjes': 1,
      'choco': 1,
    }
    var aantallen = {}
    for(var product in productPerPersoon){
      var persoon = productPerPersoon[product];
      var aantal = Math.ceil(people / persoon);
      $('#sws-product-' + product).find('.sws-product-count').text(aantal)
    }
    var prijs = box_prijzen[people].toString().replace('.', ',')
    $("#productenlijst").append('<p><span id="people-counter">'+ people + '</span> WXNDER PASEN <span style="float:right;margin-right:10px;color:orange">&euro;'+prijs+'</span></p>')
    $('.extra_product .number').each(function(index){
      if(Number($(this).html()) >  0){
        // samenstelling += Number($(this).html()) + ' ' + $(this).parent().parent().find('.title').text() +' / ';
        var title = $(this).parent().parent().find('.title').text();
        var price = $(this).parent().parent().find('.price').text();
        var num = Number($(this).html())
        price = "&euro;"  + (Number(price.substring(1).replace(',','.'))).toFixed(2).toString()

        $("#productenlijst").append('<p>'+num + ' ' + title + '<span style="float:right;margin-right:10px;color:orange">'+price+'</span>'+ '</p>')
      }

    })
    $('#totalprice').html("&euro;" + calcPrice());
    $('#currentamount').html("&euro;" + calcPrice());

}
function routeClicked(){
  var id = $(this).attr('id');
  $('#marker-' + id).trigger('click');
}
var box_prijzen = [0,0,20.99, 26.99, 31.99, 44.99, 49.99, 55.99, 59.99];
function calcPrice(){

  var prijs = box_prijzen[people];
  var total = prijs
    $('.extra_product .price').each(function(index){
      console.log(total)
      total += Number($(this).text().substring(1).replace(',','.'));
    })
    total = total.toFixed(2)

  $('.totalprice').html('&euro;' + total.toString().replace('.', ','))
  return total;

}
function betaling(){
  console.log('test')
  if(!checkDate()){alert('Vul een correcte geboortedatum in!');return false;}
  if(!checkData()){alert('Vul je gegevens in'); return false;}
  $('.totalprice').hide();

  // sendToSlack('Bestelproces Valentijn: Betaalpagina')

  // var total = calcPrice();
  var samenstelling ='';
  var samenstellingID ='';

  var naamNaarID = {
    'nootjes': 61,
    'sapjes': 92,
    'kaas': 11,
    'kaascrackers':31,
    'choco':171,
    'wijn1': 1074959501,
    'wijn2': 415207724,
    'wijn3': 1503983960,
    'wijn4': 803763447,
    'wijn5': 1269791125 ,
    'wijn6': 641013103,
    'bier': 72,
    'box2':1790389534,
    'box3':382036040,
    'box4':989455560,
    'box5':295603244,
    'box6':1224324134,
    'box7':498659775,
    'box8':593908856,

  }
  var naamNaarGoedenaam = {
    'nootjes': "Nootjes",
    'sapjes': "Sapjes",
    'kaas': "Kaas",
    'kaascrackers':"Kaascrackers",
    'choco':"Tony's Chocolonely",
    'wijn1': "Wijn",
    'wijn2': "Wijn",
    'wijn3': "Wijn",
    'wijn4': "Wijn",
    'wijn5': "Wijn" ,
    'wijn6': "Wijn",
    'bier': "Bier",
    'box2':"Paasbox",
    'box3':"Paasbox",
    'box4':"Paasbox",
    'box5':"Paasbox",
    'box6':"Paasbox",
    'box7':"Paasbox",
    'box8':"Paasbox",

  }

  // $('.sws-product').each(function(){
  //   var naam = $(this).attr('id').split('sws-product-')[1];
  //   var prID = naamNaarID[naam];
  //   if(naam == 'route'){return;}
  //   var goedenaam = naamNaarGoedenaam[naam];
  //   var aantal = $(this).find('.sws-product-count').text();
  //   samenstelling += aantal + ' ' +goedenaam + ' && '
  //   samenstellingID += aantal + ' ' + prID + ' && '
  // })
    $('.extra_product').each(function(){
      var naam = $(this).attr('id').split('extra-product-')[1];
      var aantal = $(this).find('.counter').find('.number').text();
      if(aantal == 0){return}
      if(naam == 'wijn'){naam =naam+aantal}
      var goedenaam = naamNaarGoedenaam[naam];
      var id = prID = naamNaarID[naam];
      samenstelling += aantal + ' ' + goedenaam + ' && '
      samenstellingID += aantal + ' ' + prID + ' && '
    })

    var boxNaam = 'WXNDER BOX PASEN '+people+' P.';
    var id = naamNaarID['box' + people];

    samenstelling += '1 '+ boxNaam;
    samenstellingID += "1 " + id;

    var verzend = 3.99;
    levering = $('#levering').val()
    if($('#bestelling_stad').val().toLowerCase() != 'utrecht'){
      verzend = 5.99
      $('#verzendkosten').html('&euro;5,99')
      levering='POSTNL'
    }
    var prijs = calcPrice();
    total = Number(prijs) + Number(verzend);

    total = total.toFixed(2)
    //
    // $('#box-prijs').html('WXNDER ' + box_keuze.toUpperCase())
    // $('#box-prijs-euro').html('&euro;'+prijs.toFixed(2))

  if($('#bestelling_factuur_stad').val().length ==0){
    $('#bestelling_factuur_stad').val($('#bestelling_stad').val())
  }
  if($('#bestelling_factuur_straatnaam').val().length ==0){
    $('#bestelling_factuur_straatnaam').val($('#bestelling_straatnaam').val())
  }

  if($('#bestelling_factuur_postcode').val().length ==0){
    $('#bestelling_factuur_postcode').val($('#bestelling_postcode').val())
  }
  var route = 'Geen keuze';
  if(selected['route']){route = selected['route']}
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
      'Samenstelling': samenstelling,
      'Route': route,
      'Opmerkingen': $('#extra_opmerkingen').val(),
    }),
    'SamenstellingID': samenstellingID,
    'Samenstelling': samenstelling,
    'Totaalprijs': total,
    'Leverdatum':levering,
  }


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
  $('#box-prijs').text(boxNaam)
  $('#box-prijs-euro').text(box_prijzen[people]);

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
}
function bestelBox(){
  document.getElementById('bestelproces').scrollIntoView({'behavior': 'smooth'});
  if(!sentToSlack){
    sendToSlack('PASEN: Bestelproces begonnen', '#vragen')
    sentToSlack = true;
  }
}
// function labelClicked(){
//   var id = $(this).attr('id');
//   var route = id.split('-')[1];
//   console.log(route)
//   $('#' + route).trigger('click')
// }
// function routeClicked(route){
//   $('.route').css('background', 'none')
//   $('#' + route).css('background', 'orange');
//   selected['route'] = $('#' + route).attr('id');
//   var url = $('#maker-' + route).css('background-image');
//   $('.marker').css('background-image', url)
// }
