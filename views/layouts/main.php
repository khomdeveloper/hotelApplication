<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta property = "og:title" content="<?php
			T::w([
				'mobile_receptionist' => [
					'en' => 'Mobile reseptionist',
					'ru' => 'Мобильный администратор'
				]
			]);
			?>"/>
		<meta property = "og:description" content=""/>
		<meta property = "og:image" content="<?php echo B::setProtocol('https:',B::baseUrl() . 'images/search_cursor.png'); ?>"/>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- blueprint CSS framework -->
		<?php /* ?>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
		
		
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <!--
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
-->
		<link rel="stylesheet" type="text/css" href="<?php echo B::getSelfURL(); ?>css/usial.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/local.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/controls.css" />

<?php */ ?>
		<meta name=viewport content="width=device-width"/>  

        <title><?php
			T::w([
				'mobile_receptionist' => [
					'en' => 'Mobile reseptionist',
					'ru' => 'Мобильный администратор'
				]
			]);
			?></title>
    </head>

    <body style="font-family: Verdana, Geneva, sans-serif; height:100%; min-height:auto; ">
		<?php include Environment::addTheSlash(Environment::get('site_root')) .  'protected/views/site/main_preloader.php'; ?>
		<?php echo $content; ?>
    </body>
</html>
