<div class="page_content">
	<div class="page_block al pin_page">
		<div style="padding:10px;">
			<div class="pin_data">
				<h2><?php
					T::w([
						'insert_pin_code' => [
							'en' => 'Enter PIN code',
							'ru' => 'введите PIN'
						]
					])
					?></h2>
				<form role="form">
					<input type="text" class="form-control id_pin" value=""/>
					<div class="ar" style="margin-top:10px;">
					<button type="button" class="btn btn-primary enter_pin"><?php
						T::w([
							'submit' => [
								'en' => 'Submit',
								'ru' => 'Отправить'
							]
						]);
						?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>