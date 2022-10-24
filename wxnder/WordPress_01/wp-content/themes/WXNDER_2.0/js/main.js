function burgerMenu(){
  $('#menu').fadeIn()
}
function burgerMenuOut(){
  $('#menu').fadeOut();
}
function sendToSlack(msg){
  var apiUrl = 'https://hooks.slack.com/services/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';


    var data = {
      "username": "Bestelproces",
      "text": msg
    }
    // $.ajax({
    //   url: apiUrl,
    //   type: 'POST',
    //   "data": data,
    //   "Content-type": 'application/json',
    //   succes : function(status){cosole.log(`status is ${status}`)}
    //   })
    $.post(apiUrl, JSON.stringify(data), function(x){console.log('done')}, "application/json")

}
$(document).ready(function(){
  $('#header_image').on('click', function(){window.location = 'https://wxnder.nl'})
  // $('#index-box').on('click', function(){window.location = '/box'})
  // $('#index-go').on('click', function(){window.location = '/go'})
  $('#index-partners').on('click', function(){window.location = '/partners'})
  $('#index-hoewerkthet').on('click', function(){window.location = '/hoewerkthet'})
  $('#index-routes').on('click', function(){window.location = '/routes'})
})

function sendData(user, page, event1, event2, event3, q){
  $.post( "https://wxnder.nl/wp-content/themes/WXNDER_2.0/resources/send.php", { User: user, Page: page, Event1: event1, Event2: event2, Event3: event3, Query: q } , function(r){
    console.log(r)
  });
}
