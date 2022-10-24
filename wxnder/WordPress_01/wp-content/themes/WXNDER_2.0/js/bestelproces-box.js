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

  user = (Math.random() * 100000).toFixed(0);
$(document).ready(function(){
  $('.counter_button').on('click', counter )
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
    $(".geslacht").css('background-color','rgb(229 229 229)');
    $(this).css('background-color','#4f917f');
  })
  $('.ingredient').hide()
  $('#ingredienten').hide()
  $('#eigen-glazen').hide()

  var ingredienten = {
    'bier':['bier', 'worst', 'kaas', 'crackers', 'sapjes'],
    'wijn':['wijn', 'worst', 'kaas', 'crackers', 'sapjes'],
    'alcoholvrij':['worst', 'kaas', 'crackers', 'sapjes-4'],
    'vega':['extra-drankje', 'nootjes', 'kaas', 'crackers', 'sapjes']
  }
  $('.box_option').on('click', function(){
    $('#extra_box_keuzes .option').css('background', 'rgb(229 229 229)');
    $('#extra_box_keuzes .option').css('color', 'rgb(83,145,126)');
    selected['extra_box_keuze'] = '';

    var id= $(this).attr('id');
    if(!selected['box'] || selected['box'] !=id){
    selected['box'] =id;
    $('.box_option').css('background', 'rgb(229 229 229)')
    $('.box_option .box_spec_title').css('color', '#4f917f')
    $(this).css('background', '#4f917f')
    $(this).find('.box_spec_title').css('color', 'rgb(229 229 229)')
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
    $('.box_option').css('background', 'rgb(229 229 229)')
    $(this).find('.box_spec_title').css('color', '#4f917f')
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
      $('#extra_box_keuzes .option').css('background', 'rgb(229 229 229)');
      $('#extra_box_keuzes .option').css('color', 'rgb(83,145,126)');
      $(this).css('background', '#4f917f');
      $(this).css('color', 'rgb(229 229 229)');
    }
    else{
      $('#extra_box_keuzes .option').css('background', 'rgb(229 229 229)');
      $('#extra_box_keuzes .option').css('color', 'rgb(83,145,126)');
      selected['extra_box_keuze'] = '';
    }

  })
  $('#winkelmandje, #nextbutton, #backbutton, .counter_button, .route, #bestelknop,  #bestelknop--mobiel, .pic_left, #header_image, #burger_menu_button, .box_option , .option ').on('click', function(){
    sendData(user, 'box', $(this).attr('class'),$(this).attr('id'), $(this).text(), window.location.search);
  })
  sendData(user, 'box', "start", '', '', window.location.search);

})
var sentToSlack = false;

var pages = ['Box','Route', 'Gegevens', 'Levering','Betaling']
var currentPage = 0;
var selected = {}
function bestelNext(){
  if(!sentToSlack){
    sendToSlack('BOX: Bestelproces begonnen', '#vragen')
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
    var imgs_url = "https://wxnder.nl/wp-content/themes/WXNDER_2.0/img/routes/"+name
    var achtergrond = imgs_url + '_achtergrond' + info.achtergrond_type+'?auto=enhance&fm=webp&q=1';
    var titel = imgs_url + '_titel.svg' +'?auto=enhance&fm=webp&q=1';
    var logo = imgs_url + '_logo.svg'+'?auto=enhance&fm=webp&q=1';

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
    $(this).find('.route_foto').css('opacity', '0.8')
  }, function(){
    $(this).find('.route_foto').css('opacity', '0.7')

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
    fillProducts()
}
function fillProducts(){
    $("#productenlijst").empty()
    var keuze = selected['box'];
    if(!keuze){keuze = "alcoholvrij"}
    keuze = keuze.toUpperCase()
    $("#productenlijst").append('<p>1 WXNDER '+keuze+'<span style="float:right;margin-right:10px;color:#4f917f">&euro;8,50</span></p>')
    $('.extra_product .number').each(function(index){
      if(Number($(this).html()) >  0){
        // samenstelling += Number($(this).html()) + ' ' + $(this).parent().parent().find('.title').text() +' / ';
        var title = $(this).parent().parent().find('.title').text();
        var price = $(this).parent().parent().find('.price').text();
        var num = Number($(this).html())
        price = "&euro;"  + (Number(price.substring(1).replace(',','.')) * num).toFixed(2).toString()

        $("#productenlijst").append('<p>'+num + ' ' + title + '<span style="float:right;margin-right:10px;color:#4f917f">'+price+'</span>'+ '</p>')
      }

    })
    $('#totalprice').html("&euro;" + calcPrice());
    $('#currentamount').html("&euro;" + calcPrice());

}
function routeClicked(){
  var id = $(this).attr('id');
  $('#marker-' + id).trigger('click');
}
function calcPrice(){
  var total = 8.5;
    $('.extra_product .price').each(function(index){
      total += Number($(this).text().substring(1).replace(',','.')) * Number($(this).parent().find('.number').text());
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



  var producten = {
    'Bier IPA':  72,
    'Bier Triple':  74,
    'Wijn wit':  81,
    'Wijn rood':  83,
    'Sapje appel-vlierbes':  91,
    'Sapje appel-peer':  93,
    'Sapje appel':  92,
    'Kaas knoflook':  11,
    'Kaas naturel':  13,
    'Kaas brandnetel':  12,
    'Kaas oud':  14,
    'Kaascrackers':  31,
    'Nootjes':  61,
    'Worst':  21
  }
  var samenstellingen = {
    'wijn':{
      'rood':['Wijn rood', 'Kaas oud', 'Kaascrackers', 'Worst', 'Sapje appel', 'Sapje appel-peer', 'Nootjes'],
      'wit':['Wijn rood', 'Kaas knoflook', 'Kaascrackers', 'Worst', 'Sapje appel', 'Sapje appel-peer', "Nootjes"],
    },
    'vega':{
      'rood':             ['Wijn rood', 'Kaas oud', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],
      'wit':            ['Wijn wit', 'Kaas knoflook', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],
      'ipa':            ['Bier IPA', 'Kaas brandnetel', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],
      'tripel':         ['Bier Tripel', 'Kaas brandnetel', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],
      'appel':          ['Sapje appel', 'Sapje appel', 'Kaas brandnetel', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],
      'appel-peer':     ['Sapje appel-peer', 'Sapje appel-peer', 'Kaas brandnetel', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],
      'appel-vlierbes': ['Sapje appel-vlierbes', 'Sapje appel-vlierbes', 'Kaas brandnetel', 'Kaascrackers', 'Nootjes', 'Sapje appel', 'Sapje appel-peer'],

    },
    'bier':{
      'ipa':            ['Bier IPA', 'Bier IPA','Kaas brandnetel', 'Kaascrackers', 'Worst', 'Sapje appel', 'Sapje appel-peer', 'Nootjes'],
      'tripel':         ['Bier Tripel', 'Bier Tripel','Kaas brandnetel', 'Kaascrackers', 'Worst', 'Sapje appel', 'Sapje appel-peer', 'Nootjes'],
    },
    'alcoholvrij': ['Sapje appel-vlierbes', 'Sapje appel', 'Kaas brandnetel', 'Kaascrackers', 'Worst', 'Sapje appel', 'Sapje appel-peer', 'Nootjes'],
  }
  var prijzen = {
    'wijn': 34.99,
    'bier': 29.99,
    'vega':34.99,
    'alcoholvrij':29.99
  }
  var box_keuze = selected['box'];
  var extra_keuze = selected['extra_box_keuze'];
  var prijs = 0;
  if(box_keuze && box_keuze.length > 0){
      box_keuze = box_keuze.split('box_option_')[1]
      prijs = prijzen[box_keuze]
      var samenstelling = samenstellingen[box_keuze]
      if(extra_keuze && extra_keuze.length > 0){
        console.log(extra_keuze)
        extra_keuze = extra_keuze.split(box_keuze + '-')[1]
        console.log(extra_keuze)
        samenstelling = samenstelling[extra_keuze]
      }
      else{
        if(box_keuze != 'alcoholvrij'){
          samenstelling = samenstelling[Object.keys(samenstelling)[0]]
        }
      }
  }
  else{
    prijs = prijzen['alcoholvrij']
    samenstelling = samenstellingen['alcoholvrij'];
    box_keuze = 'alcoholvrij'
  }
  var box_ids = {
    'wijn': '686740224',
    'bier': '177200114',
    'vega': '201884952',
    'alcoholvrij': '820944228'
  }
  var box_id = box_ids[box_keuze];
  var samenstellinglijst = samenstelling;
  samenstelling = '';
  var samenstellingID = '';
  for(var x in samenstellinglijst){
    var onderdeel = samenstellinglijst[x];
    var id = producten[onderdeel];
    samenstelling += 1 + ' ' + onderdeel + ' && ';
    samenstellingID+= 1 + ' ' + id + ' && ';
  }

  glazen = 'nvt';
  if(box_keuze == 'wijn'){
    if($('#glazen').is(':checked')){
      glazen = 'eigen'
    }

    else{
      glazen = 'onze'
    }
  }

    var verzend = 3.99;
    levering = $('#levering').val()

    var postcode = $('#bestelling_postcode').val().toLowerCase();
    var code = postcode.substring(0,4);
    var code_in_codes = codes.filter(function(c){return (code.indexOf(c.toString())=== 0)});


    if(code_in_codes.length>0){
      verzend = 3.99;
      levering = $('#levering').val();
    }
    else{
      verzend = 5.99
      $('#verzendkosten').html('&euro;5,99')
      levering='POSTNL'
    }



    total = Number(prijs) + Number(verzend);

    total = total.toFixed(2)

    $('#box-prijs').html('WXNDER ' + box_keuze.toUpperCase())
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
      'Meeloper': $('#bestelling_naam_2').val(),
      'Opmerkingen': $('#extra_opmerkingen').val(),
      'Glazen': glazen
    }),
    'SamenstellingID': samenstellingID,
    'Samenstelling': samenstelling,
    'BoxID': box_id,
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
    sendToSlack('BOX: Bestelproces begonnen', '#vragen')
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
//   $('#' + route).css('background', '#4f917f');
//   selected['route'] = $('#' + route).attr('id');
//   var url = $('#maker-' + route).css('background-image');
//   $('.marker').css('background-image', url)
// }
