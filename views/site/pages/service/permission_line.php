<tr>
	<?php // <td>{{group}}</td> ?>
	<td>{{user}}</td>
	<td>{{operation}}</td>
	<td>
		<button type="button" class="btn btn-danger btn-xs remove_Permission remove_button id_{{permission_id}}" style="float:right;"><?php
			T::w([
				'remove' => [
					'en' => 'Remove',
					'ru' => 'Удалить'
				]
			]);
			?></button>
	</td>
</tr>