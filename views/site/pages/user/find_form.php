<form role="form" onsubmit="return false;">
	<table>
		<tr>
			<td style="width:20%;"><?php
				T::w([
					'Find user' => [
						'en' => 'Find user',
						'ru' => 'Найти пользователя'
					]
				]);
				?></td>
			<td>
				<input type="text" name="find_user" class="form-control id_find_user" value="" placeholder="<?php
				T::w([
					'enter name or email' => [
						'en' => 'Enter name or email',
						'ru' => 'Введите имя или email'
					]
				]);
				?>"/></td>
			<td>
				<button type="button" class="btn btn-primary find_user_button disabled"><?php
					T::w([
						'find_but_caption_2' => [
							'en' => 'Find',
							'ru' => 'Искать'
						]
					]);
					?></button>
			</td>
		</tr>		
		{{checkboxes}}
	</table>
</form>
<div class="output_finded_list">

</div>