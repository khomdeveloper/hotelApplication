<tr class="guest_line {{role}} user_id_{{user_id}} visit_id_{{visit_id}}">
	<td>{{name}}<br/>{{email}}<br/>
	<?php
		T::w([
			'room' => [
				'en' => 'room',
				'ru' => 'номер'
			]
		]);
	?>: {{room}}<br/>
	<?php
		T::w([
			'pin' => [
				'en' => 'pin',
				'ru' => 'пин'
			]
		])
	?>: {{pin}}
	</td>
	<td style="font-size:0.7rem;">{{checkin}}<br/>{{checkout}}{{status}}</td>
	<td style="font-size:0.7rem;">{{orders}}</td>
	<td>{{buttons}}</td> 	
</tr>