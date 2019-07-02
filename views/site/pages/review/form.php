<table class="review_form">
	<tr>
		<td><?php
					T::w([
						'Rate' => [
							'en' => 'Rate',
							'ru' => 'Рейтинг'
						]
					]);
					?></td>
		<td>
			<div class="ratestar_host">
				<?php
					for ($i = 0; $i<5; $i++){
						?>
				<i class="ratestar id_<?php echo $i; ?> fa fa-star-o" aria-hidden="true"></i>
				<?php
					}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td><?php
					T::w([
						'Message' => [
							'en' => 'Message',
							'ru' => 'Сообщение'
						]
					]);
					?></td>
		<td>
			<textarea class="form-control object_Review id_message default" placeholder="<?php 
				T::w([
					'please_write_some_info1' => [
						'en' => 'Please write a several works about the hotel',
						'ru' => 'Пожалуйста напишите несколько слов об отеле'
					]
				]);
			?>" default=""></textarea>
		</td>
	</tr>
	<tr>
			<td></td>
			<td>
				<button type="button" class="btn btn-primary send_review_button disabled"><?php
					T::w([
						'Send' => [
							'en' => 'Send',
							'ru' => 'Отправить'
						]
					]);
					?></button>
			</td>
		</tr>
</table>