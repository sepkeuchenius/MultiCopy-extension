<?php
  $msg = false;
  if(isset($_POST['submit'])){
    if(isset($_POST['code'])){
      if($_POST['code'] == '7932'){
        $msg = "Leuk geprobeerd!";
      }
    }
  }


 ?>
<?php get_header() ?>


<div style='height:calc(100vh - 170px); width:100vw; background: rgb(229 229 229);box-sizing:border-box; padding:20vh; 0'>

<center>
  <form method="post">
  <h1 class="header_title">Giftcard</h1>
  <input type='text' name='code' placeholder="Code">
  &nbsp;&nbsp;
  <input type='submit' value='Verzilver' name="submit">
</form>
<br>
<br>
<br>
<?php if($msg){echo "<p>$msg</p>";} ?>

</div>

<?php get_footer() ?>
