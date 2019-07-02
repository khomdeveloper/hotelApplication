<div class="check_confirmation_body">
	<div class="al" style="margin:10px 0px;">
		<div class="pr cp round_button_host" style="float:right; display:inline-block; margin:0px; margin-left:10px;" title="<?php
		T::w([
			'confirm_email' => [
				'en' => 'Confirm email',
				'ru' => 'Подтвердить email'
			]
		]);
		?>">
			<div class="pa cp confirm_email offer_id_{{offer_id}} round_button" style="background:mediumseagreen;">
				<i class="fa fa-check" style="margin-top:0px; font-size:1.2rem; color:rgb(0,100,128);"></i>
			</div>
		</div>
		<input type="text" class="confirmation_code confirmation_control" value="" placeholder="<?php 
			T::w([
				'enter_received_code_WER' => [
					'en' => 'Enter received code',
					'ru' => 'Введите полученный код'
				]
			])
		?>"/>
	</div>
	<div class="al" style="margin:10px 0px;">
		<div class="pr cp round_button_host" style="float:right; display:inline-block; margin:0px; margin-left:10px;" title="<?php
		T::w([
				'set_new_email' => [
					'en' => 'Set new email address',
					'ru' => 'Задайте новый адрес электронной почты'
				]
			]);
		?>">
			<div class="pa cp set_new_email offer_id_{{offer_id}} round_button" style="background:white;">
				<i class="fa fa-envelope-o" style="margin-top:0px; font-size:1.2rem; color:rgb(0,100,128);"></i>
			</div>
		</div>
		<input type="text" class="email_to_set confirmation_control" value="" placeholder="<?php 
			T::w([
				'enter_new_email_WER' => [
					'en' => 'Enter new email',
					'ru' => 'Введите новый email'
				]
			]);
		?>"/>
	</div>
	
	<div class="dialog_help">
		<div class="al" style="margin:10px; font-size:0.7rem;">
			<?php
			T::w([
				'without_notofication_address' => [
					'en' => 'Without email notifications it will be uncomfortable to follow the status of your transactions',
					'ru' => 'Без email для уведомлений вам будет неудобно следить за состоянием ваших сделок'
				]
			]);
			?>
		</div>
	</div>
	
</div>

