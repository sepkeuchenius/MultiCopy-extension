<?php  get_header() ?>
<script>$(document).ready(function(){
	$('#header_image').hide()
	$('#header_image_index').show()

	if(screen.width < 900){
		$('.crop').each(function(){
			var src = $(this).attr('src');
			var i = src.indexOf('ar=') + 3;
			var ar = Number(src[i]);
			var newAr = Math.ceil(ar/3);
			src = src.replace('ar=' + ar, 'ar=' + newAr);
			$(this).attr('src', src)
		});
	}
})</script>
<div id='promo'>
<video id='hero' style="width: 100%; height: auto; z-index: -1;display:inline-block;filter:brightness(0.7) contrast(0.8);" autoplay="autoplay" loop="loop" muted width="300" height="150"> <source src="<?php echo get_template_directory_uri();?>/img/videos/desktop_kleiner.mp4" type="video/mp4" id='hero_src'/>
</video>
<img src='<?php echo get_template_directory_uri(); ?>/img/logo_big.svg' id='header_image_index' href='https//wxnder.nl' style="z-index:2;position:absolute;" alt="Prachtig borrelen in de natuur met heerlijke borrelboxen van WXNDER, romantische wandelingen met heerlijke producten waar je goed mee kan borrelen, gezellig eten en de omgeving ontdekken." />
</div>
<!-- <div class="content"> -->
<center><h1 class="green-header">Ontdek & Borrel</h1>
<p>Een originele date, een informele meeting of even bijkletsen met je moeder: doe het gezond, duurzaam én origineel met <span class="wxnder_text">WXNDER</span></p>

</center>
<!-- <div class="hr"></div> -->
<div id='products'>
<div id='box-product' class="product">
<img src='<?php echo get_template_directory_uri(); ?>/img/products/box_logo.svg' class='product-title'>
<img src='<?php echo get_template_directory_uri(); ?>/img/products/box.png' class="product-promo" alt="WXNDER Borrelbox, Borrelbox met route">
<div class="product-right">
	<ul class="product-items">
		<p style="" class="price">&euro;29,99</p>
		<li><b>Mooie route</b><br>getypt op hout</li>
		<li><b>Handgemaakte</b><br> stevige houten box</li>
		<li><b>Professioneel</b><br> samengestelde borrel</li>
	</ul>
<a href="/box" class="link product-link">Bestel de BOX</a>
</div>

</div>
<div id='ver-border'></div><div id='box-product' class="product">
<img src='<?php echo get_template_directory_uri(); ?>/img/products/go_logo.svg' class='product-title'>
<img src='<?php echo get_template_directory_uri(); ?>/img/products/go.png?auto=enhance&fm=webp&q=10' class="product-promo" alt="WXNDER Borreltas, Borreltas met route">
<div class="product-right">
	<ul class="product-items">
		<p style="" class="price">&euro;8,99</p>
		<li><b>Mooie route</b><br> getypt op hout</li>
		<li><b>Handig</b><br> hebruikbaar tasje</li>
		<li><b>Onbeperkte borrelhapjes</b><br> zelf samenstelbaar</li>
	</ul>
<a href="/go" class="link product-link">Bestel de GO</a>
</div>
</div>
</div>
<div class="promo-box"   onclick="window.open('/cadeau', '_self')">
<img src='<?php echo get_template_directory_uri(); ?>/img/promo/cadeau_1.jpg?auto=enhance&ar=3&fit=crop&crop=faces&fm=webp&q=10' id='promo-cadeau' class="crop" alt="Zoek jij nog een origineel cadeau voor moederdag of een andere gelegenheid? Met de WXNDER cadeaukaart geef je een unieke ervaring.">
<h2 class="title">Doe een <span class="wxnder_text">WXNDER</span> cadeau</h2><br>
</div>

<div class='content_section_hor'>
<div class='hor_section'>
	<div class='section_title'>
<h2>Magische plekjes</h2>
</div>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/star_night.svg' class='hor_section_img'id='starnight'style="height:150px" alt="Met WXNDER ontdek je nieuwe magische plekjes bij jou in de buurt"><br>
<!-- <p style="text-align: left;max-width:500px;">Het team van <span style="" class= 'wxnder_text' >WXNDER</span> is continu bezig met het zoeken naar de mooiste plekjes in de natuur voor onze routes.</p> -->
<p class="hor_section_text">Wij leveren een route die je leidt naar de mooiste plekjes in de streek, waar je kunt genieten van de lekkerste borrelhapjes en -drankjes die we kopen van lokale ondernemers uit de buurt.</p>
<br><a href="/routes" class="link">Bekijk routes</a>

</div>

<div class="hor_section">
	<div class='section_title'>
<h2>Originele date</h2>
</div>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/walking.svg'class='hor_section_img' style="height:150px" alt="WXNDER is leuk om op een date te doen, of een zakelijk gesprek"><br>
<p class="hor_section_text"><span class= 'wxnder_text' >WXNDER</span> is een prachtige manier om met iemand af te spreken of te daten, maar ook voor een zakelijk gesprek dat best wat informeler mag.</p>
<br>
<a href='/hoewerkthet' class="link">Hoe werkt het</a>

</div>
<div class='hor_section'>
	<div class='section_title'>
<h2>Lokale ondernemers</h2>
</div>
<img src='<?php echo get_template_directory_uri(); ?>/img/drawings/lokale ondernemers.svg'class='hor_section_img' style="height:150px" alt="Alle producten in de box komen van eerlijke lokale ondernemers"><br>
<p class="hor_section_text"> De box is gemaakt van hout, de inhoud wordt beschermd met papier en stro. De producten komen van lokale ondernemers, met een mooi verhaal én een eerlijk proces. De borrelhapjes en -drankjes zijn dus vers gemaakt en voor een eerlijke prijs ingekocht.</p>
<br><a href='/partners'class="link">Onze partners</a>

</div>


<!-- <div class='row_right'>
<div class="inner">
<h1>Het team van wxnder</h1><br>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/x_team.svg' class='side_pic'><br>
<p><span  class= 'wxnder_text' >WXNDER</span> bestaat uit een team van vier gedreven jonge ondernemers, ieder met eigen kwaliteiten maar een gezamenlijke droom, ondersteund door Alicia Henken, een enthousiaste dame met een expertise in content creatie.</p>
<br><a style="float: right;" href="/ons">Bekijk de verhalen</a>

 </div> -->
</div>
<div class="promo-box" onclick="window.open('/ons', '_self')">
<img src='<?php echo get_template_directory_uri(); ?>/img/promo/ons-desktop.jpg?enhance&fit=crop&ar=4&crop=faces&fm=webp&q=20' class="crop">
<h2 class="title">Ons <span class="wxnder_text">WXNDER</span> team</h2></div>
<!-- <div class='subtitle'><span>Lokaal</span> <span>Avontuurlijk</span> <span>COVID-Proof</span></div> -->
<!-- <img id='arrow_down' src='<?php echo get_template_directory_uri(); ?>/img/pictos/arrow_down.svg'></img> -->
<?php get_footer() ?>
