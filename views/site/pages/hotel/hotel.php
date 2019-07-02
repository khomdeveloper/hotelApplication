<?php
$user = User::logged();
$hotel = Hotel::getCurrent();
?>

<div class="page_content">
	<div class="page_block al hotel_page">
		<div style="padding:10px;">
			<div class="al">

				<h1 class="hotel_profile_h2"><?php
					T::w([
						'hotel_profile_h2' => [
							'en' => 'Hotel Profile',
							'ru' => 'Описание отеля'
						]
					]);
					?></h1>

				<form role="form" class="form_Hotel">
					<table>
						<tr>
							<td>
								<div class="upload_new"></div>
							</td>
							<td>
								<div class="Hotel_logo_host"></div>
							</td>
							<td>
								<button class="btn btn-primary btn-xs cut_image" type="button"><?php T::w([
									'cut_image' => [
										'en' => 'Cut Image',
										'ru' => 'Обрезать изображение'
									]
								]); ?></button>
							</td>
						</tr>
						<?php
						$path = Environment::addTheSlash(Environment::get('vh2015')) . 'templates/form/';

						Hotel::getForm([
							'title'		 => $path . 'json_input.php',
							'country'	 => $path . 'country.php',
							'visibility' => false,
							'logo_pos'	 => false,
						]);
						?>
						<tr>
							<td>

							</td>
							<td colspan="2">
								<button type="button" class="btn btn-primary save_changes_button save_changes_Hotel disabled"><?php
									T::w([
										'save_changes' => [
											'en' => 'Save changes',
											'ru' => 'Сохранить изменения'
										]
									]);
									?></button>
							</td>
						</tr>
					</table>
				</form>

			</div>
		</div>
	</div>
</div>	