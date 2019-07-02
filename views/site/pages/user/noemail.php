<div class="check_confirmation_body">
	<div class="al" style="margin:10px 0px;">
		<div class="pr cp round_button_host" style="float:right; display:inline-block; margin:0px; margin-left:10px;" title="<?php
		T::w([
				'set_one_email_address' => [
					'en' => 'Set email address',
					'ru' => 'Задайте адрес электронной почты'
				]
			]);
		?>">
			<div class="pa cp set_email offer_id_{{offer_id}} round_button" style="background:white;">
				<i class="fa fa-envelope-o" style="margin-top:0px; font-size:1.2rem; color:rgb(150,20,20);"></i>
			</div>
		</div>
		<input type="text" class="noemail_to_set confirmation_control" value="" placeholder="<?php 
			T::w([
				'enter_email_WER' => [
					'en' => 'Enter email address',
					'ru' => 'Введите email адрес'
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