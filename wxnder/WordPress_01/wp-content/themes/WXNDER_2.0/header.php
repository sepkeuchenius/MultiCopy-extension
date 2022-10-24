<!doctype html>
<html class="no-js" style='margin:0 !important;'>
	<head>

		<!-- <meta name="theme-color" content="#000000" /> -->
		<title><?php wp_title() ?></title>
		<script type="text/javascript" charset="UTF-8" src="//cdn.cookie-script.com/s/dc85aa3e6a092f8cacd5dd525198a894.js"></script>

		<script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <!--<script  src="//code.jquery.com/jquery-1.12.4.js"></script>-->
  	<!-- <script async src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
		<link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo get_template_directory_uri(); ?>/img/favico.png" rel="shortcut icon">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Muli:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

				<link href="<?php echo get_template_directory_uri(); ?>/css/main.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/header.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/footer.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/index.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/main_mobile.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/box.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/main_desktop.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/ons.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/contact.css" rel="stylesheet">
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/partners.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/routes.css" rel="stylesheet" >
				<link href="<?php echo get_template_directory_uri(); ?>/css/pages/pasen.css" rel="stylesheet" >

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" defer>

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<!-- Facebook Pixel Code -->
      <script type='text/plain' data-cookiescript='accepted' data-cookiecategory = 'targeting'>
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
				<script async src="https://www.googletagmanager.com/gtag/js?id=G-E8DV6MS71V" type='text/plain' data-cookiescript='accepted' data-cookiecategory = 'targeting'></script>
				<script type='text/plain' data-cookiescript='accepted' data-cookiecategory = 'targeting'>
				  window.dataLayer = window.dataLayer || [];
				  function gtag(){dataLayer.push(arguments);}
				  gtag('js', new Date());

				  gtag('config', 'G-E8DV6MS71V');
				</script>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167741883-1" defer type='text/plain' data-cookiescript='accepted' data-cookiecategory = 'targeting'></script>
		<script defer type='text/plain' data-cookiescript='accepted' data-cookiecategory = 'targeting'>
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
		<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/logo_big.svg' id='header_image_index' href='https//wxnder.nl' style="z-index:2;position:absolute;" /> -->

		<img src='<?php echo get_template_directory_uri(); ?>/img/menu.svg' id='burger_menu_button' onclick="burgerMenu()">
		<header id= 'header'>
			<!--<a class='header_button'  href='/updates'>Updates</a>-->
			<!--<a class='header_button'  href='/info'>Info</a>-->
			<a class='header_button'  href='/routes'>Routes</a>
			<a class='header_button' href='/'>Producten</a>
			<a class='header_button'  href='/ons' >Over ons</a>
			<a class='header_button'  href='/contact' >Contact</a>
		</header>

		<div id='menu' onclick="burgerMenuOut()">
		  <!--<div class='menu_link' href='/updates'>Updates</div>-->
		  	<!--<a class='header_button'  ><a href='/updates' class='menu_item_text'>Updates</a></a>-->
			<!--<a class='header_button' ><a  href='/info' class='menu_item_text'>Info</a></a>-->
			<a  href='/routes' class='menu_item_text'>Routes</a>
		<a  href='/box' class='menu_item_text' id='menu-box'>BOX</a>
		<a  href='/go' class='menu_item_text' id='menu-go'>GO</a>
		<a href='/cadeau'   class='menu_item_text'>Cadeau</a>
		<a href='/ons'   class='menu_item_text'>Over ons</a>
		<a href='/contact'   class='menu_item_text'>Contact</a>

		</div>

		</div>
			<!-- /header -->
