var stages = 5

$(document).ready(function(){

  $('.mdl-layout__tab').on('click', function(){
    console.log('test')
    var idx = $(this).attr('href');
    console.log(idx)
    var id = idx.split('#')[1];
    $('.mdl-layout__tab-panel').removeClass('is-active')
    $(idx).addClass('is-active')

  })
  window.setTimeout(function(){

    $('#msg').slideUp()
  },1000)
  // $('.bestelling').draggable({ snap: ".pipeline-stage", stop:function(){
  //   var from = $(this).parent();
  //   var x = $(this).offset().left
  //   var y = $(this).css('top')
  //   for(var i=1; i<stages + 1; i++){
  //     var stagename = 'pipeline-stage-' + i;
  //     var stage = $("#" + stagename + '.inner');
  //
  //     var left = stage.offset().left;
  //     if(Math.abs(left - x) < 100){
  //       if(i == 1 && stage != from){markeerBesteld(stage, $(this))}
  //       if(i == 2 && stage != from){markeerBetaald(stage, $(this))}
  //       if(i == 3 && stage != from){markeerGepland(stage, $(this))}
  //       if(i == 4 && stage != from){markeerGemaakt(stage, $(this))}
  //       if(i == 5 && stage != from){markeerGeleverd(stage, $(this))}
  //     }
  //   }

    // $(this).css('left', 'unset')
    // $(this).css('top', 'unset')

  // }} )

  $('.bestelling').on('click', openBestelling)
  $('#bestellingBack').on('click', closeBestelling)
  $('.bestelling').each(function(){
    if($(this).find('.details .betaald').text().split(': ')[1].length > 0){
      $('#pipeline-stage-2 .innerstage').append($(this))
    }
    if($(this).find('.details .leverdatum').text().split(': ')[1].length > 0){
      $('#pipeline-stage-3 .innerstage').append($(this))
    }

    if($(this).find('.details .geproduceerd').text().split(': ')[1].length > 0){
      $('#pipeline-stage-4 .innerstage').append($(this))
    }
    if($(this).find('.details .geleverd').text().split(': ')[1].length > 0){
      $('#pipeline-stage-5 .innerstage').append($(this))
    }
  })
$('.box-actief').on('change', function(){
   var row = $(this).parent().parent();
   var id = row.find('.td_ID').text();
   $('#id').val(id);
   if($(this).is(':checked')){
     $('#action').val('add-box-actief')
   }
   else{
    $('#action').val('remove-box-actief')
    }
    $('#action-submit').click()
})
$('.go-actief').on('change', function(){
   var row = $(this).parent().parent();
   var id = row.find('.td_ID').text();
   $('#id').val(id);
   if($(this).is(':checked')){
     $('#action').val('add-go-actief')
   }
   else{
    $('#action').val('remove-go-actief')
    }
    $('#action-submit').click()
})


})
function markeerBesteld(stage , el){
  stage.append(el)
}
function markeerBetaald(stage , el){
  if(el.parent().attr('id') == 'pipeline-stage-4'){
     if(confirm("Markeren als niet meer gemaakt?")){
       var orderN = el.find('.ID').text().split(': ')[1];
       stage.append(el)
       $('option').each(function(){
         $(this).attr('value', $(this).attr('value').split("_")[0] + '_' + orderN)
       })
       $('#input-geproduceerddatum').val('verwijderen_' + orderN)
       $('#geproduceerd-submit').click()

     }

  }
}
function markeerGepland(stage , el){
  if(el.parent().parent().attr('id') == 'pipeline-stage-4'){
     if(confirm("Markeren als niet meer gemaakt?")){
       var orderN = el.find('.ID').text().split(': ')[1];
       stage.append(el)
       $('option').each(function(){
         $(this).attr('value', $(this).attr('value').split("_")[0] + '_' + orderN)
       })
       $('#input-geproduceerddatum').val('verwijderen_' + orderN)
       $('#geproduceerd-submit').click()

     }

  }
}
function markeerGemaakt(stage , el){
  if(stage.attr('id') == el.parent().attr('id')){return;}
  if(el.parent().parent().attr('id') == 'pipeline-stage-5'){
     if(confirm("Markeren als niet meer geleverd?")){
       var orderN = el.find('.ID').text().split(': ')[1];
       stage.append(el)
       $('option').each(function(){
         $(this).attr('value', $(this).attr('value').split("_")[0] + '_' + orderN)
       })
       $('#input-geleverddatum').val('verwijderen_' + orderN)
       $('#geleverd-submit').click()

     }

  }
  else{
    if(confirm("Markeren als nu gemaakt?")){
      var orderN = el.find('.ID').text().split(': ')[1];
      stage.append(el)
      $('option').each(function(){
        $(this).attr('value', $(this).attr('value').split("_")[0] + '_' + orderN)
      })
        $('#geproduceerd-submit').click()
      }
    }
}
function markeerGeleverd(stage , el){
  if(stage.attr('id') == el.parent().attr('id')){return;}

  if(confirm("Markeren als nu geleverd?")){
    var orderN = el.find('.ID').text().split(': ')[1];
    stage.append(el)
    $('option').each(function(){
      $(this).attr('value', $(this).attr('value').split("_")[0] + '_' + orderN)
    })
    $('#geleverd-submit').click()
  }
}
function openBestelling(){
  $('#bestelling').fadeIn()


  $('#bestellingen-pipeline').hide()
  var el = $(this).clone().addClass('full');
  $('#bestelling').find('#inner').html(el);
  var orderN = huidigeBestelling();
  $('option').each(function(){
    $(this).attr('value', $(this).attr('value').split("_")[0] + '_' + orderN)
  })
  $('#hiddenID').val(orderN)

}
function closeBestelling(){
  $('#bestelling').hide()
  $('#bestellingen-pipeline').fadeIn()
}
function gepland(el){
  var datum = prompt('Datum')

}
function geproduceerd(el){
  if(!el){el = huidigeBestelling()}
  confirm('Geproduceerd nu markeren?')
}
function geleverd(el){
  if(!el){el = huidigeBestelling()}
  confirm('Geleverd nu markeren?')
}
function veranderLeverdatum(el){
  if(!el){el = huidigeBestelling()}
  $('.bestelling-form').hide();
  $('#leverdatum-form').show();
}
function veranderProductiedatum(el){

  if(!el){el = huidigeBestelling()
  }
  $('.bestelling-form').hide();
  $('#productiedatum-form').show();

}
function huidigeBestelling(){
  var bestelling = $('#bestelling #inner .ID').text();
  console.log(bestelling)
  return bestelling;
}
function nieuweLeverdatum(){
  $('#leverdata').hide();
  $('#nieuwe-leverdatum').fadeIn();

}
function leverdatumTerug(){
  $('#leverdata').fadeIn();
  $('#nieuwe-leverdatum').hide();
}
function nieuweProductiedatum(){
  $('#productiedata').hide();
  $('#nieuwe-productiedatum').fadeIn();

}
function productiedatumTerug(){
  $('#productiedata').fadeIn();
  $('#nieuwe-productiedatum').hide();
}
function nieuwProduct(){
  $('#voorraad').hide();
  $('#nieuw-product').fadeIn();

}
function productTerug(){
  $('#voorraad').fadeIn();
  $('#nieuw-product').hide();
}
function deleteProductie(el){
  if(confirm('Weet je het zeker?')){
  var button = $(el);
  var row = button.parent().parent();
  var id = row.find('td').first().text();
  $('#id').val(id)
  $('#action').val('delete')
  $('#action-submit').click()
}
}
function deleteVoorraadgroep(el){
  if(confirm('Weet je het zeker?')){
  var button = $(el);
  var row = button.parent().parent();
  var id = row.find('td').first().text();
  var fromid = row.find('.td_FromID').first().text();
  var subid = row.find('.td_SubID').text();
  $('#id').val(id)
  $('#fromid').val(fromid)
  $('#subid').val(subid)
  $('#action').val('delete')
  $('#action-submit').click()
}
}
function edit(el){
  $('#voorraad').hide()
  $('#leverdata').hide()
  $('#edit-product').show();
  var row = $(el).parent().parent();
  var cells = row.find('td');
  var form = $('#editform')
  form.empty();
  cells.each(function(){
    var cell = $(this);
    if(!cell.attr('class')){return;}
    var col = cell.attr('class').split('td_')[1];
    if(col == 'ID'){
      var label = $('<span>').text(col)
      var input = $('<input>').attr('type', 'text').attr('name', col + '_old').val(cell.text())
      var div = $('<div>').attr('class', 'form-item');
      div.css('display', 'none')
      div.append(label)
      div.append(input)
      form.append(div)
    }
    var label = $('<span>').text(col)
    var input = $('<input>').attr('type', 'text').attr('name', col).val(cell.text())
    var div = $('<div>').attr('class', 'form-item')
    div.append(label)
    div.append(input)
    form.append(div)
    // form.append('<br>')
  })
  var br = $('<br>')
  var input = $('<input>').attr('type', 'submit').attr('name', 'edit-submit')
  var div = $('<div>').attr('class', 'form-item')
  div.append(br)
  div.append(input)
  form.append(div)
}
function editTerug(){
  $('#voorraad').show()
  $('#edit-product').hide();
}

function editProductactiviteit(el){
  $('#voorraad').hide()
  $('#leverdata').hide()
  $('#edit-product').show();
  var row = $(el).parent().parent();
  var cells = row.find('td');
  var form = $('#editform')
  $('#Ondergrens').val(row.find('.td_Ondergrens').text())
  $('#BOX-Beschrijving').val(row.find('.td_BOX-Beschrijving').text())
  $('#GO-Beschrijving').val(row.find('.td_GO-Beschrijving').text())
  $('#ID').val(row.find('.td_ID').text())
}
function editVoorraadgroep(el){
  $('#voorraad').hide()
  $('#leverdata').hide()
  $('#edit-product').show();
  var row = $(el).parent().parent();
  var cells = row.find('td');
  var form = $('#editform')
  $('#ID-old').val(row.find('.td_ID').text())
  $('#ID-edit').val(row.find('.td_FromID').text())
  $('#SubID-edit').val(row.find('.td_SubID').text())
  $('#Aantal-edit').val(row.find('.td_Aantal').text())

}
