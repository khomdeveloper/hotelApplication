<table style="width:100%;" class="default_login_menu">
	<tr><td class="ac sign_in_using"><?php T::w([
		'sign_in_using' => [
			'en' => 'Sign in using',
			'ru' => 'Войдите через'
		]
	]); ?></td></tr>
	<tr>
		<td class="p10 ac" style="padding-right:0px; padding-left:15px;">
			<div class="ulogin_host">
				<script src="//ulogin.ru/js/ulogin.js"></script>
				<div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name;optional=nickname,email,bdate,country,city,photo,photo_big,sex;providers=facebook,twitter,googleplus,instagram,vkontakte;hidden=other;redirect_uri=;callback=call_ulogin"></div>
				<script type="text/javascript">
					function call_ulogin(token) {//callback
						U.ulogin(token);
					}
				</script>
			</div>
		</td>
	</tr>
	<tr><td class="ac login_error_message"></td></tr>
    <tr>
		<td class="p10">
			<input type="text" name="login" class="input w100 login_input rc5 required" value="" placeholder="<?php
			T::w([
				'enter_email_placeholder' => [
					'ru' => 'Введите e-mail',
					'en' => 'Enter e-mail'
				]
			]);
			?>" style="font-size:1rem; border:1px solid silver; padding:3px;"/>
		</td>
    </tr>
    <tr>
		<td class="p10">
			<input type="text" name="pass" class="input w100 pass_input rc5 partially_required required_login_button" value="" placeholder="<?php
			T::w([
				'enter_password_placeholder' => [
					'ru' => 'Введите пароль',
					'en' => 'Enter password'
				]
			]);
			?>" style="font-size:1rem; border:1px solid silver; padding:3px;"/>
		</td>
    </tr>
	<tr>
		<td class="p10">
			<button type="button" class="btn btn-success signin_but"><?php T::w([
				'login_button_caption' => [
					'en' => 'Sign in',
					'ru' => 'Войти'
				]
			]); ?></button>
		</td>
	</tr>
	<tr>
		<td class="p10">
			<button type="button" class="btn btn-primary signup_but"><?php T::w([
				'signup_button_caption' => [
					'en' => 'Sign up',
					'ru' => 'Регистрация'
				]
			]); ?></button>
		</td>
	</tr>
	<tr>
		<td class="p10">
			<button type="button" class="btn btn-warning recall_but"><?php T::w([
				'recall_button_caption' => [
					'en' => 'Recall the pass',
					'ru' => 'Воспомнить пароль'
				]
			]); ?></button>
		</td>
	</tr>
</table>