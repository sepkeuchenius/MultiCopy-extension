<!doctype html>
<html class="no-js" style='margin:0 !important;'>
	<head>

		<meta name="theme-color" content="#000000" />
		<title>WXNDER</title>
		<script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <!--<script  src="//code.jquery.com/jquery-1.12.4.js"></script>-->
  	<script async src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo get_template_directory_uri(); ?>/img/favico.png" rel="shortcut icon">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Muli:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

				<link href="<?php echo get_template_directory_uri(); ?>/css/main.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/header.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/footer.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/index.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/main_mobile.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/box.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/main_desktop.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/ons.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/contact.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/partners.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/routes.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/pasen.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/dev.css" rel="stylesheet">

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<!-- Facebook Pixel Code -->
      <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '370154204181109');
        fbq('track', 'PageView');
      </script>
      <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=370154204181109&ev=PageView&noscript=1" /></noscript>
      <!-- End Facebook Pixel Code -->
			<!-- Global site tag (gtag.js) - Google Analytics -->
				<script async src="https://www.googletagmanager.com/gtag/js?id=G-E8DV6MS71V"></script>
				<script>
				  window.dataLayer = window.dataLayer || [];
				  function gtag(){dataLayer.push(arguments);}
				  gtag('js', new Date());

				  gtag('config', 'G-E8DV6MS71V');
				</script>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167741883-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'UA-167741883-1');
		</script>
		<?php wp_head(); ?>
		<script>
        // conditionizr.com
        // configure environment tests
        conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
        </script>


				<script src='<?php echo get_template_directory_uri(); ?>/js/main.js'></script>
				<script src='<?php echo get_template_directory_uri(); ?>/js/index.js'></script>
				<script src='<?php echo get_template_directory_uri(); ?>/js/routes.js'></script>


<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
<script>$(document).ready(function(){
	$('#header_image').hide()
	$('#header_image_index').show()
})</script>
	</head>
	<body <?php body_class(); ?>>

		<!-- wrapper -->
		<div class="wrapper" style="display:none;">

			<!-- header -->
			<header class="header clear" role="banner">

					<!-- logo -->
					<div class="logo" style="display:none;">
						<a href="<?php echo home_url(); ?>">
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
<!-- 							<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Logo" class="logo-img"> -->
						</a>
					</div>
					<!-- /logo -->



			</header>



		</div>
		<!-- <div id='action-row'>Tijdelijk: <a class="link" href='/pasen?src=header' style="font-size:1em;padding-bottom:0;">WXNDER PASEN</a>! Bestel nu en ontvang voor pasen.</div> -->
		<div id='active_header'>
		<img src='<?php echo get_template_directory_uri(); ?>/img/logo.svg' id='header_image' href='https//wxnder.nl' style="z-index:2;" />

		<img src='<?php echo get_template_directory_uri(); ?>/img/menu.svg' id='burger_menu_button' onclick="burgerMenu()">
		<header id= 'header'>
			<!--<a class='header_button'  href='/updates'>Updates</a>-->
			<!--<a class='header_button'  href='/info'>Info</a>-->
			<a class='header_button'  href='/pasen' >Pasen</a>
			<a class='header_button'  href='/routes'>Routes</a>
			<a class='header_button' href='/'>Producten</a>
			<a class='header_button'  href='/ons' >Over ons</a>
			<a class='header_button'  href='/ons' >Contact</a>
		</header>

		<div id='menu' onclick="burgerMenuOut()">
		  <!--<div class='menu_link' href='/updates'>Updates</div>-->
		  	<!--<a class='header_button'  ><a href='/updates' class='menu_item_text'>Updates</a></a>-->
			<!--<a class='header_button' ><a  href='/info' class='menu_item_text'>Info</a></a>-->
			<a  href='/routes' class='menu_item_text'>Routes</a>
			<a  href='/pasen' class='menu_item_text'>Pasen&#x1F430;</a>
		<a  href='/box' class='menu_item_text' id='menu-box'>BOX</a>
		<a  href='/go' class='menu_item_text' id='menu-go'>GO</a>
		<a href='/ons'   class='menu_item_text'>Over ons</a>
			<a href='/ons'   class='menu_item_text'>Contact</a>

		</div>

		</div>
			<!-- /header -->

<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/background.jpg' id='background'> -->
<div id='promo'>
<video id='hero' style="width: 100%; height: auto; z-index: -1;display:inline-block" autoplay="autoplay" loop="loop" muted width="300" height="150" playsinline> <source src="<?php echo get_template_directory_uri();?>/img/videos/desktop_kleiner.mp4" type="video/mp4" id='hero_src'/>
</video>
<img src='<?php echo get_template_directory_uri(); ?>/img/logo_big.svg' id='header_image_index' href='https//wxnder.nl' style="z-index:2;position:absolute;" />
</div>
<!-- <div class="content"> -->
<br>
<center><h1 class="green-header">Ontdek & Borrel</h1>
<p>Een originele date, een informele meeting of even bijkletsen met je moeder: doe het gezond, duurzaam én origineel met <span class="wxnder_text">WXNDER</span></p>

</center>
<!-- <div class="hr"></div> -->
<div id='products'>
<div id='box-product' class="product">
<img src='<?php echo get_template_directory_uri(); ?>/img/products/box_logo.svg' class='product_title'>
<img src='<?php echo get_template_directory_uri(); ?>/img/products/box.png' class="product-promo">
<p style="" class="price">&euro;29,99</p>
<div class="product-right">
	<ul class="product-items">
		<li><b>Mooie route</b><br>getypt op hout</li>
		<li><b>Handgemaakte</b><br> stevige houten box</li>
		<li><b>Professioneel</b><br> samengestelde borrel</li>
	</ul>
<a href="/go" class="link product-link">Bestel de BOX</a>
</div>

</div>
<div id='ver-border'></div><div id='box-product' class="product">
<img src='<?php echo get_template_directory_uri(); ?>/img/products/go_logo.svg' class='product_title'>
<img src='<?php echo get_template_directory_uri(); ?>/img/products/go.png' class="product-promo">
<p style="" class="price">&euro;9,99</p>
<div class="product-right">
	<ul class="product-items">
		<li><b>Mooie route</b><br> getypt op hout</li>
		<li><b>Handig</b><br> hebruikbaar tasje</li>
		<li><b>Onbeperkte borrelhapjes</b><br> zelf samenstelbaar</li>
	</ul>
<a href="/go" class="link product-link">Bestel de GO</a>
</div>
</div>
</div>
<div class="promo-box" onclick="window.open('/cadeau', '_self')">
	<img src='<?php echo get_template_directory_uri(); ?>/img/promo/cadeau_1.jpg' id='mobile-promo-cadeau'>
<img src='<?php echo get_template_directory_uri(); ?>/img/promo/cadeau.jpg' id='desktop-promo-cadeau'>
<h2 class="title">Doe een <span class="wxnder_text">WXNDER</span> cadeau</h2>
</div>

<div class='content_section_hor'>
<div class='hor_section'>
	<div class='section_title'>
<h2>Magische plekjes</h2>
</div>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/star_night.svg' class='hor_section_img'id='starnight'style="height:150px"><br>
<!-- <p style="text-align: left;max-width:500px;">Het team van <span style="" class= 'wxnder_text' >WXNDER</span> is continu bezig met het zoeken naar de mooiste plekjes in de natuur voor onze routes.</p> -->
<p class="hor_section_text">Wij leveren een route die je leidt naar de mooiste plekjes in de streek, waar je kunt genieten van de lekkerste borrelhapjes en -drankjes die we kopen van lokale ondernemers uit de buurt.</p>
<br><a href="/routes" class="link">Bekijk routes</a>

</div>

<div class="hor_section">
	<div class='section_title'>
<h2>Originele date</h2>
</div>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/walking.svg'class='hor_section_img' style="height:150px"><br>
<p class="hor_section_text"><span class= 'wxnder_text' >WXNDER</span> is een prachtige manier om met iemand af te spreken of te daten, maar ook voor een zakelijk gesprek dat best wat informeler mag.</p>
<br>
<a href='/hoewerkthet' class="link">Hoe werkt het</a>

</div>
<div class='hor_section'>
	<div class='section_title'>
<h2>Lokale ondernemers</h2>
</div>
<img src='<?php echo get_template_directory_uri(); ?>/img/drawings/lokale ondernemers.svg'class='hor_section_img' style="height:150px"><br>
<p class="hor_section_text"> De box is van hout, de inhoud wordt beschermd met papier en stro. De producten komen van lokale ondernemers, met een mooi verhaal én een eerlijk proces. De borrelhapjes en -drankjes zijn dus vers gemaakt en voor een eerlijke prijs ingekocht.</p>
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
<div class="promo-box" onclick="window.open('/ons', '_self')"><img src='<?php echo get_template_directory_uri(); ?>/img/promo/ons.jpg' class='mobile-promo'>
<img src='<?php echo get_template_directory_uri(); ?>/img/promo/ons-desktop.jpg' class='desktop-promo'>
<h2 class="title">Ons <span class="wxnder_text">WXNDER</span> team</h2></div>
<!-- <div class='subtitle'><span>Lokaal</span> <span>Avontuurlijk</span> <span>COVID-Proof</span></div> -->
<!-- <img id='arrow_down' src='<?php echo get_template_directory_uri(); ?>/img/pictos/arrow_down.svg'></img> -->
<?php get_footer() ?>
