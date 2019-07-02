<?php
$payment_method = UserPaymentMethod::getBy([
			'id'		 => $_GET['user_payment'],
			'_notfound'	 => true
		]);
?>
<div class="check_confirmation_body">
	<div class="ar" style="margin-bottom:10px;">
		<div class="al description_host" style="display:inline-block; width:50%; min-width:280px; min-height:53px; vertical-align:top; margin:3px; float:left;">
			<?php $payment_method->fout('description'); ?>
		</div>
		<div class="uploaded_files_host" style="display:inline-block; width:auto; vertical-align:middle;">
			{{uploaded_files}}
		</div>
	</div>

	<div class="time_remain_host">
		{{remain}}
	</div>

	<div class="ar" style="margin-top:10px; margin-bottom:20px;">

		<div class="pr cp round_button_host" style="float:right; display:inline-block; margin:0px; margin-top:-5px; margin-left:10px;" title="<?php
		T::w([
			'if_everything_is_ok' => [
				'en' => 'Release funds',
				'ru' => 'Заплатить'
			]
		]);
		?>">
			<div class="pa cp release_balance offer_id_{{offer_id}} round_button" style="background:mediumseagreen;">
				<i class="fa fa-usd" style="margin-top:0px; font-size:1.2rem; color:rgb(43,49,56);"></i>
			</div>
		</div>

		<div style="font-size:1rem;">
			<?php
			T::w([
				'if_everything_is_ok' => [
					'en' => 'Release funds',
					'ru' => 'Заплатить'
				]
			]);
			?>	
		</div>
	</div>	


	<div class="ar" style="margin-top:10px; margin-bottom:20px;">

		<div class="pr cp round_button_host" style="float:right; display:inline-block; margin:0px; margin-top:-5px; margin-left:10px;" title="<?php
		T::w([
			'ask_to_re-confirmm' => [
				'en' => 'Ask to re-confirm',
				'ru' => 'Запросить подтверждение'
			]
		]);
		?>">
			<div class="pa cp ask_confirmation offer_id_{{offer_id}} round_button" style="background:blueviolet;">
				<i class="fa fa-undo" style="margin-top:0px; font-size:1.2rem; color:rgb(43,49,56);"></i>
			</div>
		</div>

		<div style="font-size:1rem;">
			<?php
			T::w([
				'ask_to_re-confirmm' => [
					'en' => 'Ask to re-confirm',
					'ru' => 'Запросить подтверждение'
				]
			]);
			?>	
		</div>
	</div>

	<div class="ar" style="margin-top:10px; margin-bottom:10px;">

		<div class="pr cp round_button_host" style="float:right; display:inline-block; margin:0px; margin-top:-5px; margin-left:10px;" title="<?php
		T::w([
			'cahllenge_deal_176' => [
				'en' => 'Challenge deal',
				'ru' => 'Оспорить сделку'
			]
		]);
		?>">
			<div class="pa cp run_challenge offer_id_{{offer_id}} round_button" style="background:tomato;">
				<i class="fa fa-gavel" style="margin-top:0px; font-size:1.2rem; color:rgb(43,49,56);"></i>
			</div>
		</div>

		<div style="font-size:1rem;">
			<?php
			T::w([
				'cahllenge_deal_176' => [
					'en' => 'Challenge deal',
					'ru' => 'Оспорить сделку'
				]
			]);
			?>	
		</div>
	</div>

	<div class="dialog_help">
		<div class="al" style="margin:10px;">

			<ul style="margin-bottom:0px; margin-top:10px; font-size:0.7rem;">
				<li><?php
					T::w([
						'who_pay_what_part_of_commissison' => [
							'en' => 'Payment systems comissions paid by the buyer, service comission - by seller.',
							'ru' => 'Комиссии платежных систем оплачиваются за счет покупателя, комиссия сервиса за счет продавца.'
						]
					]);
					?></li>
				<li><?php
					T::w([
						'please_check_payment_17' => [
							'en'		 => 'Check that the required amount was {{b}}actually</span> credited to Your account',
							'ru'		 => 'Проверьте что запрашиваемая сумма {{b}}реально</span> поступили на Ваш счет',
							'_include'	 => [
								'b' => '<span class="red_bold">'
							]
						]
					]);
					?></li>
				<li style="color:tomato;">
					<?php
					T::w([
						'if_no_reaction' => [
							'en'		 => 'In case of no reaction from Your side before {{t}} deal will be automatically completed in buyers favor.',
							'ru'		 => 'В случае отсутствия реакции с Вашей стороны до {{t}} сделка будет автоматичсеки завершена в пользу покупателя.',
							'_include'	 => [
								't' => $_GET['time']
							]
						]
					])
					?>
				</li>
			</ul>

		</div>
	</div>
</div>
