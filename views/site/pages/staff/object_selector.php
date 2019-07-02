<tr>
	<td style="width:20%;">{{label}}</td>
	<td colspan="2">
		<select name="{{name}}" class="form-control object_{{object}} id_{{name}}" default="{{value}}">
			<?php
			foreach (Permission::permittedObjects() as $object => $val) {
				?>
				<option value="<?php echo $object; ?>"><?php echo empty($val) || $val === true
					? $object
					: $val; ?></option>
				<?php
			}
			?>
		</select>
	</td>
</tr>