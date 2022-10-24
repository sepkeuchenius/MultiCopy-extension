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
    var keuze = $(this).text();
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

})

var pages = ['Box','Route', 'Gegevens', 'Levering','Betaling']
var currentPage = 0;
var selected = {}
function bestelNext(){
  currentPage++;
  currentPage %= pages.length;
  if(pages[currentPage] == 'Levering'){
    // levering();
    $('#persoonlijkelevering').hide();
    $('#postnl').hide();
    if($('#bestelling_stad').val().toLowerCase() == 'utrecht'){
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
    var titel = imgs_url + '_titel.svg'+'?auto=enhance&fm=webp&q=1';
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
    $("#productenlijst").append('<p>1 WXNDER BOX<span style="float:right;margin-right:10px;color:orange">&euro;8,50</span></p>')
    $('.extra_product .number').each(function(index){
      if(Number($(this).html()) >  0){
        // samenstelling += Number($(this).html()) + ' ' + $(this).parent().parent().find('.title').text() +' / ';
        var title = $(this).parent().parent().find('.title').text();
        var price = $(this).parent().parent().find('.price').text();
        var num = Number($(this).html())
        price = "&euro;"  + (Number(price.substring(1).replace(',','.')) * num).toFixed(2).toString()

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

  var total = calcPrice();

  var verzend = 3.99;
  levering = $('#levering').val()
  if($('#bestelling_stad').val().toLowerCase() != 'utrecht'){
    verzend = 5.99
    $('#verzendkosten').text('5,99')
    levering='POSTNL'
  }

  total = Number(total) + Number(verzend);

  total = total.toFixed(2)

  var samenstelling=''
  var samenstellingID=''

  $('.extra_product .number').each(function(index){
    if(Number($(this).html()) >  0){
      var amount = Number($(this).html());
      var productTitle =  $(this).parent().parent().find('.title').text()
      var id =  $(this).parent().parent().attr('id');
      samenstelling +=  amount+ ' ' + productTitle + ' && ';
      samenstellingID += amount + ' ' + id + ' && ';
    }

  })
  console.log('test')
  console.log(samenstelling)

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
  console.log(samenstellingID)
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
  $('#total_amount').text(ob.Totaalprijs)


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
