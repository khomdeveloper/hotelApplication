<tr>
	<td style="width:20%;">{{label}}</td>
	<td colspan="2">
		<select name="{{name}}" class="form-control object_{{object}} id_{{name}}" default="{{value}}">
			<?php
				echo Permission::getRestrictedOperationsHTML([
					'object' => 'Service'
				]);
			?>
		</select>
	</td>
</tr>