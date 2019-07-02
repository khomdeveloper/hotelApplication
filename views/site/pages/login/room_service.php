<table style="width:100%;" class="default_login_menu">
    <tr>
		<td class="p10">
			<input type="text" name="login" class="input w100 login_input rc5 required" value="" placeholder="<?php
			T::w([
				'enter_room_number_placeholder' => [
					'ru' => 'Введите номер комнаты',
					'en' => 'Enter room number'
				]
			]);
			?>" style="font-size:1rem; border:1px solid silver; padding:3px;"/>
		</td>
		<td class="p10">
			
		</td>
    </tr>
    <tr>
		<td class="p10">
			<input type="text" name="pass" class="input w100 pass_input rc5 partially_required required_login_button" value="" placeholder="<?php
			T::w([
				'enter_pin_placeholder2' => [
					'ru' => 'Пинкод от администратора',
					'en' => 'Pin from receptionist'
				]
			]);
			?>" style="font-size:1rem; border:1px solid silver; padding:3px;"/>
		</td>
		<td class="p10">
			<div class="pr" style="width:32px; height:32px; float:right; left:-10px;">
				<div class="pa login_button">
					<i class="fa fa-key" style="font-size:32px;"></i>
				</div>
			</div>
		</td>
    </tr>
</table>