<?php

/**
 * Description of Translation
 *
 * @author valera261104
 */
class Translation extends T{

	public static function action($r, $dontdie = false) {
		$com = $r[get_called_class()];
	
		$user = User::logged();
		$hotel = Hotel::getCurrent();

		Permission::check('Translation', $com, $user, $hotel);
		
		return parent::action($r, $dontdie);
	}	
	
}
