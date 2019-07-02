<tr class="staff_order_line id_{{id}}">
	<td class="object_Order id_{{id}}">{{name}}<br/><?php T::w([
		'room' => [
			'en' => 'room',
			'ru' => 'номер'
		]
	]); ?>: {{room}}</td>
	<td class="object_Order id_{{id}}">{{service}}<br/>{{price}} EUR x {{quantity}}</td>
	<td class="object_Order id_{{id}}">{{date}}<br/><span class="guest_order_note">{{note}}</span></td>
	<td>{{status}}{{buttons}}</td>
</tr>