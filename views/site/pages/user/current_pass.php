<form role="form" class="current_pass_form">
	<table>
		<tr>
			<td><input type="pass" name="current_pass" class="form-control id_current_pass" value="" placeholder="<?php
									T::w([
										'your_current_pass8' => [
											'en' => 'Your current pass',
											'ru' => 'Ваш текущий пароль'
										]
									]);
									?>" /></td>
			<td class="ar">
				<button type="button" class="btn btn-primary continue_action disabled"><?php
										T::w([
											'continue_but_08' => [
												'en' => 'Continue',
												'ru' => 'Продолжить'
											]
										]);
										?></button>
			</td>
		</tr>
	</table>
</form>