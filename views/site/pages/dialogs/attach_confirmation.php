<?php
$payment_method = UserPaymentMethod::getBy([
			'id'		 => $_GET['user_payment'],
			'_notfound'	 => true
		]);
?>
<div class="attach_confirmation_body">
	<?php /* ?>
	  <div style="width:90%; min-width:280px; padding:10px; padding-top:0px; margin-left:-20px; display:inline-block;" class="al">
	  <?php
	  if (!empty($_GET['reconfirm'])) {
	  T::w([
	  'transfer_58' => [
	  'en'		 => '<div style="float:right; margin-right:-20px;">(amount {{amount}}$)</div>',
	  'ru'		 => '<div style="float:right; margin-right:-20px;">(сумма {{amount}}$)</div>',
	  '_include'	 => [
	  'amount' => $_GET['amount']
	  ]
	  ]
	  ]);
	  }
	  ?>
	  <b><?php
	  T::w([
	  'payment_details' => [
	  'en' => 'Payment details',
	  'ru' => 'Платежные реквизиты'
	  ]
	  ]);
	  ?>:</b></div>

	  <div class="al" style="width:90%; min-width:280px; padding:10px; display:inline-block; background:white; color:black; border-radius:5px;">
	  <?php $payment_method->fout('description'); ?>
	  </div>

	  <?php */ ?>


	<div class="ar" style="margin-bottom:10px;">
		<div class="al description_host" style="display:inline-block; width:50%; min-width:280px; min-height:53px; vertical-align:top; margin:3px; float:left;">
			<?php $payment_method->fout('description'); ?>
		</div>
		<div class="uploaded_files_host" style="display:inline-block; width:auto; vertical-align:middle;">
			<div class="upload_new ac">
				<label class="cp">
					<div class="cp pa Offer_uploader_host_{{offer_id}}_new" style="width:auto; height:auto; opacity:0; left:-10000px;"></div>
					<i class="fa fa-upload"></i>
				</label>
			</div>
			{{uploaded_files}}
		</div>
	</div>	

	<div class="time_remain_host">
		{{remain}}
	</div>

	<?php /* ?>
	  <?php if (empty($_GET['reconfirm'])) { ?>
	  <div class="rc ac red_message" style="border-radius:5px; margin-top:0px; margin-top:10px;"><?php
	  T::w([
	  'without_confirmation_the_order_will_be_cancelled_3' => [
	  'en'		 => 'Without confirmation the order will be canceled {{time}} even you have already pay!',
	  'ru'		 => 'Неподтвержденный заказ будет отменен {{time}} даже если Вы уже заплатили!',
	  '_include'	 => [
	  'time' => $_GET['time']
	  ]
	  ]
	  ]);
	  ?></div>
	  <?php } else { ?>
	  <div class="rc ac yellow_message" style="border-radius:5px; margin-top:0px; margin-top:10px;"><?php
	  echo T::out([
	  'message_from_the_seller' => [
	  'en' => 'Message from the seller:',
	  'ru' => 'Сообщение от продавца:'
	  ]
	  ]) . '<br/><br/>' . $_GET['reconfirm'] . '<br/><br/>' . T::out([
	  'you_need_to_send_confirmation_before_7' => [
	  'en' => 'Please do it before {{time}} or the order will be canceled even you have already pay!',
	  'ru' => 'Пожалуйста сделайте это до {{time}} иначе заказ будет отменен даже если Вы уже заплатили!'
	  ]
	  ]);
	  ?></div>
	  <?php } ?>
	  <?php */ ?>


	<div style="display:inline-block; float:right; margin-top:5px; margin-bottom:15px; margin-left:10px;">
		<div style="font-size:1rem; display:inline-block;">
			<?php
			T::w([
				'send_confirmation_abg' => [
					'en' => 'Send confirmation',
					'ru' => 'Подтвердить оплату'
				]
			]);
			?>	

			<div class="pr cp round_button_host" style="display:inline-block; margin:0px; margin-top:-5px; margin-left:10px;" title="<?php
			T::w([
				'send_confirmation_abg' => [
					'en' => 'Send confirmation',
					'ru' => 'Подтвердить оплату'
				]
			]);
			?>">
				<div class="pa cp button_confirm offer_id_{{offer_id}} round_button" style="background:mediumseagreen;">
					<i class="fa fa-envelope-o" style="margin-top:0px; font-size:1.2rem; color:rgb(43,49,56);"></i>
				</div>
			</div>
		</div>
	</div>	


	<?php if (!empty($_GET['reconfirm'])) { ?>

		<div style="display:inline-block; float:right; margin-top:5px; margin-bottom:15px;">
			<div style="font-size:1rem; display:inline-block;">
				<?php
				T::w([
					'cahllenge_deal_176' => [
						'en' => 'Challenge deal',
						'ru' => 'Оспорить сделку'
					]
				]);
				?>	
			</div>
			<div class="pr cp round_button_host" style="display:inline-block; margin:0px; margin-top:-5px; margin-left:10px;" title="<?php
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
		</div>
	<?php } ?>

	<div class="dialog_help" style="clear:both;">
		<div class="al" style="margin:10px;">
			<ul style="margin-bottom:0px; margin-top:10px; font-size:0.7rem;">
				<li><?php
					T::w([
						'we_cannot_control_payment' => [
							'en' => 'You make direct payment to the seller, the service does not control the transaction and we are not able to get your money back or cancel payment!',
							'ru' => 'Вы осуществляете прямой платеж на счет продавца, сервис не контролирует проводку и не в состоянии вернуть Вам средства или отменить платеж!'
						]
					]);
					?></li>
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
						'please_attach_payment_confirmation' => [
							'en' => 'Highly recommend to attach a screenshot confirming the payment.',
							'ru' => 'Настоятельно рекомендуем приложить скриншот, подтверждающий платеж.'
						]
					]);
					?></li>
			</ul>
		</div>
	</div>
</div>
