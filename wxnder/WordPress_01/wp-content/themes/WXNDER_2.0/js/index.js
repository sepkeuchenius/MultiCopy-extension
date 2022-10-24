$('.product_left').hover(function(){
  alert('test')
  $(this).find('.img').css('filter', 'blur(2px)')
}, function(){
  $(this).find('.img').css('filter', 'blur(2px)')
}
)
$(document).ready( function(){
  console.log('yes')
  if($(document).width() < 900){
    console.log('test');
    $('#hero_src').attr('src', $('#hero_src').attr('src').replace('desktop', 'mobiel'))
    document.getElementById('hero').load().play();

  }
})
