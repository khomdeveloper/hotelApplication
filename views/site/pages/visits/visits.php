<div class="page_content">
	<div class="page_block al visits_page">
		<div style="padding:10px;">
			<div class="al">	
				<button type="button" class="btn btn-primary button_new_pin" style="float:right;"><?php
					T::w([
						'generate_new_pincode' => [
							'en' => 'Create PIN',
							'ru' => 'Создать PIN'
						]
					]);
					?></button>
				<h1><?php
						T::w([
							'visits_h2' => [
								'en' => 'Visits',
								'ru' => 'Визиты'
							]
						])
						?></h1>
				<?php echo H::getTemplate('pages/user/find_form',[
					'checkboxes' => H::getTemplate('pages/visits/checkboxes',[],true)
				],true); ?>
			</div>
		</div>
	</div>
</div>
