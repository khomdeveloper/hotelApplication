<div class="al withdrawal_host">
	<div>
		<div style="display:inline-block;">
			<input class="manual_payment auto_manual payment_control" type="radio" name="auto_manual" checked="checked"> <?php
			T::w([
				'manual_payment' => [
					'en' => 'Manual payment',
					'ru' => 'Платеж вручную'
				]
			]);
			?>
		</div>
		<div style="display:inline-block; margin-bottom:10px;">
			<input class="automatic_payment auto_manual payment_control" type="radio" name="auto_manual"> <?php
			T::w([
				'automatic_payment' => [
					'en' => 'Automatic payment',
					'ru' => 'Автоматический платеж'
				]
			]);
			?> <span class="goto_help id_<?php
			$what_is_payment_page = Faq::getBy([
						'key'		 => 'what_is_payment_page',
						'_notfound'	 => [
							'key'			 => 'what_is_payment_page',
							'title'			 => T::getBy([
								'key'		 => 'what_is_payment_page',
								'_notfound'	 => [
									'key'	 => 'what_is_payment_page',
									'en'	 => 'What is payment page in automatic withdraw mode?',
									'ru'	 => 'Что такое страница платежей в автоматическом режиме оплаты?'
								]
							])->get('id'),
							'description'	 => T::getBy([
								'key'		 => 'payment_page_is',
								'_notfound'	 => [
									'key'	 => 'payment_page_is',
									'en'	 => 'In the {{s1}}automatic mode of payment{{/s}}, it is assumed that You have your website where the buyer can pay the purchase warranty.<br/><br/>To do this, it goes to the payment page (the link to which You point here) and pays.<br/><br/>In this link, our server automatically adds a key to identify the transaction after successful payment You should save this key on your server to identify the success of payment on the {{s}}checkout page{{/s}} of the payment.',
									'ru'	 => 'В {{s1}}автоматическом режиме оплаты{{/s}} предполагается, что у Вас есть свой сайт на котором покупатель может оплатить покупку гарантийных обязательств.<br/><br/>Для этого он переходит на страницу оплаты (ссылку на которую Вы и указываете здесь) и оплачивает.<br/><br/>В эту ссылку наш сервер автоматически добавляет ключ чтобы идентифицировать транзакцию, после успешной оплаты Вам следует сохранить этот ключ на своем сервере, чтобы идентифицировать успешность платежа на {{s}}странице проверки платежа{{/s}}.',
								]
							])->get('id')
						]
			]);


			$what_is_confirmation_page = Faq::getBy([
						'key'		 => 'what_is_confirmation_page',
						'_notfound'	 => [
							'key'			 => 'what_is_confirmation_page',
							'title'			 => T::getBy([
								'key'		 => 'what_is_confirmation_page_question',
								'_notfound'	 => [
									'key'	 => 'what_is_confirmation_page_question',
									'en'	 => 'What is confirmation page in withdrawal menu?',
									'ru'	 => 'Что такое страница подтверждения в меню вывода средств?'
								]
							])->get('id'),
							'description'	 => T::getBy([
								'key'		 => 'what_is_confirmation_page_answer',
								'_notfound'	 => [
									'key'	 => 'what_is_confirmation_page_answer',
									'en'	 => 'In {{s}}an automatic payment{{/s}} our server periodically polls this page on your server, passing the same key as in the formation of links on {{s2}}payment page{{/s}}. If payment on this page was successful Your server should return a 1 if not then 0. In the case of a positive response, our system will pay the buyer the frozen warranty.',
									'ru'	 => 'В {{s}}режиме автоматической оплаты{{/s}} наш сервер периодически опрашивает эту страницу на вашем сервере, передавая тот же ключ что и при формировании ссылки на {{s2}}страницу оплаты{{/s}}. Если оплата на этой странице произошла успешно Ваш сервер должен вернуть 1, если нет то 0. В случае положительного ответа наша система выплатит покупателю замороженные гарантийные обязательства.',
								]
							])->get('id')
						]
			]);

			$automatic_payment = Faq::getBy([
						'key'		 => 'automatic_payment',
						'_notfound'	 => [
							'key'			 => 'automatic_payment',
							'title'			 => T::getBy([
								'key'		 => 'what_is_automatic_payment_question',
								'_notfound'	 => [
									'key'	 => 'what_is_automatic_payment_question',
									'en'	 => 'What is automatic payment?',
									'ru'	 => 'Что такое автоматический платеж?'
								]
							])->get('id'),
							'description'	 => T::getBy([
								'key'		 => 'what_is_automatic_payment_answer',
								'_notfound'	 => [
									'key'	 => 'what_is_automatic_payment_answer',
									'en'	 => 'Automatic payment assumes that You have your own website where You can accept payment. The buyer in this case there is no need to confirm the payment, it is enough to make the payment on {{s1}}payment page{{/s}}, then our server will automatically request {{s}}the confirmation page(address){{/s}} and give frozen warranty obligations.',
									'ru'	 => 'Автоматический платеж предполагает что у Вас есть свой сайт на котором Вы можете принимать оплату. Покупателю в этом случае не нужно дополнительно подтверждать платеж, ему достаточно совершить платеж на {{s1}}странице оплаты{{/s}}, затем наш сервер автоматически запросит {{s}}страницу(адрес) подтверждения{{/s}} и выдаст замороженные гарантийные обязательства.'
								]
							])->get('id')
						]
			]);

			echo $automatic_payment->set([
				'data' => json_encode([
					'/s' => '</span>',
					's1' => '<span class="goto_help id_' . $what_is_payment_page->get('id') . '">',
					's'	 => '<span class="goto_help id_' . $what_is_confirmation_page->get('id') . '">'
				])
			])->get('id');
			?>"><?php
					  T::w([
						  'what_is_it (in withdrawal dialog)' => [
							  'en' => 'What is it?',
							  'ru' => 'Что это такое?'
						  ]
					  ]);
					  ?></span>
		</div>
	</div>
	<textarea class="payment_type_details rc payment_control manual_payment_host" style="display:inline-block; height:115px; margin-bottom:10px;" placeholder="<?php
	T::w([
		'enter_account_details_d' => [
			'en' => 'Please enter payment details oy account in choosen payment system',
			'ru' => 'Пожалуйста введите реквизиты счета в выбранной платежной системе'
		]
	]);
	?>"></textarea>
	<div style="display:none;" class="automatic_payment_host automatic_payment_links_host">
		<div style="margin-bottom:5px;"><?php
			T::w([
				'payment_page_url_s' => [
					'en'		 => '{{s}}Payment page url{{/s}} on your website:',
					'ru'		 => '{{s}}Адрес страницы оплаты{{/s}} на вашем сайте:',
					'_include'	 => [
						's'	 => '<span class="goto_help id_' . $what_is_payment_page->set([
							'data' => json_encode([
								'/s' => '</span>',
								's'	 => '<span class="goto_help id_' . $what_is_confirmation_page->get('id') . '">',
								's1' => '<span class="goto_help id_' . $automatic_payment->get('id') . '">'
							])
						])->get('id') . '">',
						'/s' => '</span>'
					]
				]
			]);
			?></div>
		<input type="url" class="payment_page_url payment_control" value=""/>
		<div style="margin-bottom:5px; margin-top:15px;"><?php
			T::w([
				'confirmation_page_s2' => [
					'en'		 => '{{s}}Confirmation page url{{/s}}',
					'ru'		 => '{{s}}Адрес страницы подтверждения{{/s}}',
					'_include'	 => [
						'/s' => '</span>',
						's'	 => '<span class="goto_help id_' . $what_is_confirmation_page->set([
							'data' => json_encode([
								's'	 => '<span class="goto_help id_' . $automatic_payment->get('id') . '">',
								's2' => '<span class="goto_help id_' . $what_is_payment_page->get('id') . '">',
								'/s' => '</span>'
							])
						])->get('id') . '">'
					]
				]
			]);
			?>:</div>
		<input type="url" class="confirmation_page_url payment_control" value=""/>
	</div>


	<div style="" class="amount_price_host">
		<table>
			<tr>
				<td class="ar"><?php
					T::w([
						'Withdraw_s' => [
							'en' => 'Withdraw',
							'ru' => 'Вывести'
						]
					])
					?></td>
				<td class="ar" style="padding:10px; padding-top:0px; padding-right:0px;">
					<input type="number" class="withdraw_amount_correction payment_control" placeholder="Amount"/>		
				</td>
				<td style="padding-left:10px;">$</td>
			</tr>
			<tr>
				<td class="ar"><?php
					T::w([
						'Want to get' => [
							'en' => 'Want to get',
							'ru' => 'Хочу получить'
						]
					]);
					?></td>
				<td class="ar" style="padding:10px; padding-right:0px;">
					<input type="number" class="withdraw_price payment_control" value="1"/>
				</td>
				<td style="padding-left:10px;">
					<input type="text" class="withdraw_nominal payment_control" value="" placeholder="currency" title="Name of currency"/>
				</td>
			</tr>
			<tr class="manual_payment_host">
				<td class="ar"><?php
					T::w([
						'Ready to wait' => [
							'en' => 'Ready to wait',
							'ru' => 'Готов ждать'
						]
					]);
					?></td>
				<td class="ar" style="padding:10px; padding-right:0px;">
					<input type="number" class="withdraw_wait payment_control" value="<?php
					S::getBy([
						'key'		 => 'time_should_pay',
						'_notfound'	 => [
							'key'	 => 'time_should_pay',
							'val'	 => 8
						]
					])->fout('val')
					?>" min="<?php
						   S::getBy([
							   'key'		 => 'time_should_pay',
							   '_notfound'	 => [
								   'key'	 => 'time_should_pay',
								   'val'	 => 8
							   ]
						   ])->fout('val')
						   ?>" max="<?php
						   S::getBy([
							   'key'		 => 'time_should_pay_max',
							   '_notfound'	 => [
								   'key'	 => 'time_should_pay_max',
								   'val'	 => 56
							   ]
						   ])->fout('val');
						   ?>"/>
				</td>
				<td style="padding-left:10px;"><?php
					T::w([
						'hours' => [
							'en' => 'hours',
							'ru' => 'часов'
						]
					]);
					?></td>
			</tr>
			<?php /* ?>
			  <tr class="automatic_payment_host" style="display:none;">
			  <td colspan="3" style="padding-top:15px; text-align:right;">
			  <input type="checkbox" class="show_in_iframe payment_control" style="width:20px;"/> <?php
			  T::w([
			  'show_in_iframe_withdraw_2' => [
			  'en' => 'Show payment page in iframe',
			  'ru' => 'Показывать страницу оплаты в iframe'
			  ]
			  ])
			  ?></td>
			  </tr>
			  <?php */ ?>
		</table>	
	</div>
</div>
<div class="al need_currency_name_reminder" style="color:lightyellow; display:none;"><?php
	T::w([
		'need_currency_name' => [
			'en' => 'Please enter the currency name in which you would like to get money',
			'ru' => 'Пожалуйста введите название валюты в которой вы хотите получить средства'
		]
	]);
	?></div>
<div class="red_message rc manual_payment_host" style="width:98%; padding:1%; border-radius:5px; font-size:0.7rem;">
	<div class="al">
		<?php
		T::w([
			'warning_if_throw_your_offers' => [
				'en'		 => '{{input}} I understand that in manual mode the fact of money income should be checked by myself and in case of no reaction from my side in {{time}} hours from the acception moment, the deal will be completed in Buyer favour.',
				'ru'		 => '{{input}} Я понимаю что в ручном режиме факт поступления платежа должен проверяться мною самостоятельно и в случае отсутствия реакции с моей стороны в течение {{time}} часов с момента принятия, сделка будет завершена в пользу покупателя.',
				'_include'	 => [
					'input'	 => '<input style="margin-left:0px; width:20px;" type="checkbox" class="read_and_understand payment_control"/>',
					'time'	 => '<span class="wt_76490">' . S::getBy([
						'key'		 => 'time_should_pay',
						'_notfound'	 => [
							'key'	 => 'time_should_pay',
							'val'	 => 8
						]
					])->get('val') . '</span>'
				]
			]
		])


		/*
		  T::w([
		  'warning_what_throw_your_offers_01' => [
		  'en'		 => 'Once the buyer has attached a confirmation screenshot, system starts the countdown and if there is no response from Your side after {{time}} hours the transaction will be completed in buyers favor.<br/><br/>You should closely monitor the status of deals and notifications by e-mail.<br/><br/>The contents of the screenshots and the actual fact of payment is not checked automatically, You must do it manually!',
		  'ru'		 => 'Как только покупатель приложил скриншот — запускается обратный отсчет времени и при отсутствии реакции с Вашей стороны через {{time}} часов сделка будет завершена в пользу покупателя.<br/><br/>Внимательно следите за состоянием сделок и уведомлениями на электронную почту.<br/><br/>Содержимое скриншотов и факт реального платежа автоматически не проверяется, Вы должны это сделать вручную!',
		  '_include'	 => [
		  'time' => '<span class="wt_76490">' . S::getBy([
		  'key'		 => 'time_should_pay',
		  '_notfound'	 => [
		  'key'	 => 'time_should_pay',
		  'val'	 => 8
		  ]
		  ])->get('val') . '</span>'
		  ]
		  ]
		  ]);
		  ?></div>
		  <div style="margin-top:10px;"><input style="width:20px;" type="checkbox" class="read_and_understand payment_control"/> <?php
		  T::w([
		  'i_have_read_and_understand' => [
		  'en' => 'I have read and understand',
		  'ru' => 'Я прочитал и понял'
		  ]
		  ]);
		  ?> </div> <?php */
		?>
	</div>
	<input type="hidden" class="payment_type_default" value=""/>
	<input type="hidden" class="payment_method_id" value=""/>
