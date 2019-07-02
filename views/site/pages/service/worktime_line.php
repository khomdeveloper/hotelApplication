<tr class="workTimeLine">
	<?php // <td>{{group}}</td> ?>
	<td class="edit_Worktime id_{{id}}">{{day}}</td>
	<td class="edit_Worktime id_{{id}}">{{type}}</td>
	<td class="edit_Worktime id_{{id}}">{{begin}}</td>
	<td class="edit_Worktime id_{{id}}">{{end}}</td>
	<td>
		<button type="button" class="btn btn-danger btn-xs remove_Worktime remove_button id_{{id}}" style="float:right;"><?php
			T::w([
				'remove' => [
					'en' => 'Remove',
					'ru' => 'Удалить'
				]
			]);
			?></button>
	</td>
</tr>