<?php
  try{
    if ( isset( $_POST['submit'] ) ) { // retrieve the form data by using the element's name attributes value as key $firstname = $_POST['firstname']; $lastname = $_POST['lastname']; // display the results
// $channel  = '#Verkoop';
$bot_name = 'ContactFormulier';
$icon     = ':alien:';
$message  = 'Nieuwe vraag van '. $_POST['contactinfo'] . ' : "'. $_POST['question']. ' "';



$data = array(
  'username'    => $bot_name,
  'text'        => $message,
  'icon_emoji'  => $icon,
);

$data_string = json_encode($data);

$ch = curl_init('https://hooks.slack.com/services/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');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string))
  );

//Execute CURL
$result = curl_exec($ch);
$sent = TRUE;
    }
else{
$sent = FALSE;
}
  }
  catch(Exception $e){
    echo 'Er is iets misgegaan!';

  }
?>
<?php get_header() ?>

<div class='page_content'>
  <center>
  <h1 class='header_title'>Stel een vraag</h1>
  <?php if($sent){ echo "<p style='color: black !important; font-weight:bold !important;'>Gelukt!</p>" ; } ?>
  <p class="">We staan klaar om jouw vraag te beantwoorden binnen no-time!</p>
  <br>
  <form method="post">
  <input type='text' name='contactinfo' placeholder="Email / Tel">
  <br>
  <textarea cols=60 rows=5 placeholder="Typ hier jouw vraag" name='question'></textarea>
  <br><input type="submit" value="Stuur" name='submit'/>
  <br>
</form>
<br>
  <p class="">Of anders:</p>
  <img src='<?php echo get_template_directory_uri(); ?>/img/icons/call.svg'class='contact-bubble' id='contact-call'>
  <img src='<?php echo get_template_directory_uri(); ?>/img/icons/facebook.svg'class='contact-bubble'id='contact-fb'>
  <img src='<?php echo get_template_directory_uri(); ?>/img/icons/insta.svg'class='contact-bubble'id='contact-insta'>
  <img src='<?php echo get_template_directory_uri(); ?>/img/icons/WhatsApp.svg'class='contact-bubble'id='contact-whatsapp'>
  <img src='<?php echo get_template_directory_uri(); ?>/img/icons/email.svg'class='contact-bubble'id='contact-email'>


</center>
<div id='contact-action--bel-berend' class="contact-action" style="display:block;">
  <img src='<?php echo get_template_directory_uri(); ?>/img/ons/berend.jpg' class='contact-img' id='contact-berend'>
  <a class='contact-action-text link' href='tel:+31652737753' target='_blank'>Bel Berend</a>
</div>
<div id='contact-action--whatsapp-thijs'  class="contact-action">
  <img src='<?php echo get_template_directory_uri(); ?>/img/ons/thijs1.PNG' class='contact-img' id='contact-thijs--whatsapp'>
  <a class='contact-action-text link' href='https://wa.me/31614900526' target='_blank'>WhatsApp Thijs</a>
</div>
<div id='contact-action--email-thijs'  class="contact-action">
  <img src='<?php echo get_template_directory_uri(); ?>/img/ons/thijs1.PNG' class='contact-img' id='contact-thijs--email'>
  <a class='contact-action-text link' href='mailto:info@wxnder.nl' target='_blank'>Email Thijs</a>
</div>

</div>


</div>

<script>
  $(document).ready(function(){

    var direct_links = {
      'fb': 'https://fb.com/',
      'insta': 'https://instagram.com/wxndernl',
    }
    var action_content={
      'call':'contact-action--bel-berend',
      'whatsapp':'contact-action--whatsapp-thijs',
      'email':'contact-action--email-thijs',
    }
    $('.contact-bubble').on('click', function(){
      $('.contact-action').hide();


      var id = $(this).attr('id').split('-')[1]
      if(direct_links[id]){
        window.open(direct_links[id])
      }
      else{
        content = action_content[id];
        $('#' + content).show();

      }
    })
})

</script>
<?php get_footer() ?>
