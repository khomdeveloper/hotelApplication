<div class="preloader" style="width:100%; position:relative; height:100%;">
    <img class="pa" src="<?php B::outURL(); ?>images/preloader.gif" alt style="left:50%; top:50%; margin-left:-64px; margin-top:-64px;"/>
    <div class="pa ac back_counter"><?php T::w([
		'please_wait' => [
			'en' => 'Please wait',
			'ru' => 'Пожалуйста подождите'
		]
	]); ?></div>
</div>