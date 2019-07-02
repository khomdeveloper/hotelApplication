<?php if (!A::isAdmin()) { ?>
<div class="main_preloader_2" style="left:0px; 
	top:0px; 
	position:fixed; 
	width:100%; 
	height:100%;
	z-index:100500;
	background-image: url('<?php B::outURL();?>images/landing.jpg');
	background-size: cover;
	background-attachment:fixed;
	background-color:black;
	text-align:center;">
	<div><img src="<?php B::outURL();?>images/logo.png" style="margin:40px 0px; max-width:100%;"/></div>
	<img src="<?php B::outURL();?>images/preloader.gif" style="margin:auto;"/>
	<div style="color:silver; font-size:20px;"><?php T::w([
		'Please wait' => [
			'en' => 'Please wait',
			'ru' => 'Пожалуйста подождите'
		]
	]); ?></div>
</div>
<?php } ?>