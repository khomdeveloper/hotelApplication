<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserHotel
 *
 * @author valera261104
 */
class UserHotel extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		//TODO: move all not common functions to Staff
		
		if ($com == 'find') {

			self::required([
				'who' => true
					], $r);
			
			return static::findUser($r);
				
		} elseif ($com == 'selection') {

			$staff = self::getBy([
						'hotel_id'	 => Hotel::getCurrent()->get('id'),
						'role'		 => 'staff',
						'_return'	 => [0 => 'object']
			]);

			if (empty($staff)) {
				return [
					'html' => 'Please add some staff'
				];
			} else {

				$h = ['<ul>'];
				foreach ($staff as $record) {

					$user = User::getBy([
								'id' => $record->get('user_id')
					]);

					if (!empty($user)) {
						$h[] = '<li class="select_Staff id_' . $record->get('id') . '">' . $user->getName() . '</li>';
					}
				}
				$h[] = '</ul>';

				return [
					'html' => join('', $h)
				];
			}
	
		}
	}

	public static function getHotelId($r) {
		return empty($r['hotel_id'])
				? User::logged()->get('hotel')
				: $r['hotel_id'];
	}

	public static function searchFilter($r) {

		$user = User::logged();

		if ($user->get('role') == 'admin') {
			$role = $r['filter'] == 'staff'
					? [
				'staff',
				'admin'
					]
					: [
				$r['filter']
			];
			
		} elseif ($user->get('role') == 'staff') {
			$role = [
				$r['filter']
			];
		} else {
			throw new Exception(T::out([
				'no_information_for_guests' => [
					'en' => 'Not enough permissions to get the information',
					'ru' => 'Недостаточно прав для получения информации'
				]
			]));
		}

		return $role;
	}

	public static function f() {
		return [
			'title'		 => 'Staff, Guest, Admin (parent)',
			'datatype'	 => [
				'user_id'	 => [
					'User' => [
						'id' => 'ON DELETE CASCADE'
					]
				],
				'hotel_id'	 => [
					'Hotel' => [
						'id' => 'ON DELETE CASCADE'
					]
				]
			],
			'create'	 => [
				'hotel_id'	 => "bigint unsigned comment 'Link to User'",
				'user_id'	 => "bigint unsigned comment 'Link to Hotel'",
				'role'		 => "enum('admin','staff','guest','visitor', 'past') default 'guest' comment 'User role in system'",
				'begin'		 => "datetime default null comment 'Start active period'",
				'end'		 => "datetime default null comment 'Add active period'",
				'room'		 => "tinytext default null comment 'Guest room'",
				'pin'		 => "tinytext default null comment 'access PIN for guest'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
