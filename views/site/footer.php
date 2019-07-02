<div class="footer shadow silver">
    <div class="footer_partners_host al">
	<?php include "keyPartners.php"; ?>
    </div>
    <div class="footer_contact_host al">
	<?php include "contacts.php" ?>
    </div>
    <div class="footer_subscription_host">
	<?php include "subscription.php"; ?>
    </div>
    <div class="footer_social_host">
	<table style="width:100%; padding-left:10px;">
	    <tr>
		<td colspan="5" class="ptb_10" style="padding-bottom:14px;"><?php
		    T::w([
			'social_media' => [
			    'en'	 => 'Social media',
			    'ru'	 => 'Социальные сети'
			]
		    ]);
		    ?></td>
	    </tr>
	    <tr>
		<td>
		    <?php include "social_media.php"; ?>
		</td>
		<td style="width:100%; padding-left:10px;" class="goto_faq_host">

		</td>
	    </tr>
	</table>
    </div>
</div>    
<script type="text/javascript">
    A.w(['$', 'Base', 'T', 'Site', 'APPLICATIONSETTINGSREADY'], function() {
	T.add(<?php
		    echo json_encode(T::all([
				'whats you save title'		 => [
				    'en'	 => 'What you save',
				    'ru'	 => 'Что вы экономите'
				],
				'whats you save description'	 => [
				    'en'	 => 'You can count on significant discounts from our partners.',
				    'ru'	 => 'Вы можете расчитывать на значительные скидки у наших партнеров.'
				],
				'WHAT`S INCLUDED'		 => [
				    'en'	 => 'WHAT`S INCLUDED',
				    'ru'	 => 'ЧТО ВКЛЮЧЕНО'
				],
				'WHAT YOU SAVE'			 => [
				    'en'	 => 'WHAT YOU SAVE',
				    'ru'	 => 'ЧТО ВЫ ЭКОНОМИТЕ'
				],
				'see_how'			 => [
				    'en'	 => 'See how',
				    'ru'	 => 'Смотрите как'
				],
				'ERROR'				 => [
				    'en'	 => 'ERROR',
				    'ru'	 => 'ОШИБКА'
				],
				'enter_email_address'		 => [
				    'en'	 => 'Please fill email address',
				    'ru'	 => 'Пожалуйста введите адрес электронной почты.'
				],
				'show_my_orders'		 => [
				    'en'	 => 'All my orders',
				    'ru'	 => 'Все мои заказы'
				],
				'goto_payment'			 => [
				    'en'	 => 'Go to payment',
				    'ru'	 => 'Перейти к оплате'
				],
				'back_to_shop'			 => [
				    'en'	 => 'Add more products',
				    'ru'	 => 'Добавить еще продукты'
				],
				'add_to_basket'			 => [
				    'en'	 => 'Add to order',
				    'ru'	 => 'Добавить в заказ'
				],
				'download_guide'		 => [
				    'en'	 => 'Download Guide',
				    'ru'	 => 'Загрузить Гид'
				],
				'customers_have_reviews'	 => [
				    'en'	 => 'customers reviews',
				    'ru'	 => ' отзывов'
				],
				'terribly'			 => [
				    'en'	 => 'Terribly',
				    'ru'	 => 'Ужасно'
				],
				'bad'				 => [
				    'en'	 => 'Bad',
				    'ru'	 => 'Плохо'
				],
				'normal'			 => [
				    'en'	 => 'Normal',
				    'ru'	 => 'Нормально'
				],
				'good'				 => [
				    'en'	 => 'Good',
				    'ru'	 => 'Хорошо'
				],
				'great'				 => [
				    'en'	 => 'Great',
				    'ru'	 => 'Великолепно'
				],
				'visitor'			 => [
				    'en'	 => 'Visitor',
				    'ru'	 => 'Посетитель'
				],
				'buer'				 => [
				    'en'	 => 'Buyer',
				    'ru'	 => 'Покупатель'
				],
				'customers_have_written'	 => [
				    'ru'	 => ' покупателей написали отзыв.',
				    'en'	 => ' customers have written a review.'
				],
				'no_reviews'			 => [
				    'en'	 => 'There are no any reviews. You can be the first!',
				    'ru'	 => 'Отзывов пока нет. Вы можете оставить первый!'
				],
				'write_some_words_if_you_want'	 => [
				    'en'	 => 'Add some words if you want...',
				    'ru'	 => 'Добавьте пару слов, если хотите...'
				],
				'send_review'			 => [
				    'en'	 => 'Send',
				    'ru'	 => 'Отправить'
				],
				'log_out'			 => [
				    'en'	 => 'Log out',
				    'ru'	 => 'Выйти'
				],
				'goto_moscow'			 => [
				    'en'	 => 'Visit Moscow',
				    'ru'	 => 'Посети Москву'
				],
				'language_selector'		 => [
				    'en'	 => 'Language',
				    'ru'	 => 'Язык'
				],
				'ABOUT'				 => [
				    'en'	 => 'ABOUT',
				    'ru'	 => 'О ПРОЕКТЕ'
				],
				'INCLUDED'			 => [
				    'en'	 => 'INCLUDED',
				    'ru'	 => 'ЧТО ВКЛЮЧЕНО'
				],
				'BUY'				 => [
				    'en'	 => 'BUY',
				    'ru'	 => 'КУПИТЬ'
				],
				'PARTNERS'			 => [
				    'en'	 => 'PARTNERS',
				    'ru'	 => 'ПАРТНЕРЫ'
				],
				'goto_faq'			 => [
				    'en'	 => 'FAQ',
				    'ru'	 => 'FAQ'
				],
				'subscribe'			 => [
				    'en'	 => 'Subscribe',
				    'ru'	 => 'Подписаться'
				]
		    ]));
		    ?>);

	Site.init('<?php
		    echo empty($_REQUEST['page'])
			    ? 'Langing'
			    : $_REQUEST['page'];
		    ?>');
    });
</script>