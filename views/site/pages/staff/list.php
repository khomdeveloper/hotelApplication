<table class="table">
	<thead>
		<tr>
			<th><?php
				T::w([
					'user_name' => [
						'en' => 'Name',
						'ru' => 'Имя'
					]
				]);
				?></th>
			<th><?php
				T::w([
					'email' => [
						'en' => 'email',
						'ru' => 'email'
					]
				]);
				?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{{list}}
	</tbody>
</table>
<div class="ac pagination_host"></div>