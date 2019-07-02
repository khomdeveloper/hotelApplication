<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pin
 *
 * @author valera261104
 */
class Pin extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		Permission::check(get_called_class(), $com, $user, $hotel);

		if ($com == 'create') {
			
			self::required([
				'room'		 => true,
				'start'		 => true,
				'expired'	 => true
					], $r);

			if (strlen($r['room']) == 0){
				throw new Exception(T::out([
					'room_number_exepected2' => [
						'en' => 'Please enter room number',
						'ru' => 'Пожалуйста введите номер комнаты'
					]
				]));
			}
			
			//create code

			$l = 1;
			do {
				$code = substr(md5(microtime() . mt_rand(0, 10000)), 0, 8);
				$l = self::getBy([
							'code'		 => $code,
							'_return'	 => 'count'
				]);
			} while ($l == 1);

			self::getBy([
				'id'		 => '_new',
				'_notfound'	 => [
					'hotel_id'	 => $hotel->get('id'),
					'room'		 => $r['room'],
					'expired'	 => $r['expired'],
					'start'		 => $r['start'],
					'code'		 => $code
				]
			]);

			return [
				'Visits' => [
					'showPin' => [
						'code' => $code
					]
				]
			];
		} elseif ($com === 'check') {

			self::required([
				'code' => true
					], $r);

			$pin = self::getBy([
						'code'		 => $r['code'],
						'visit_id'	 => 'is null',
						'expired'	 => [
							'_between' => [
								(new DateTime())->format('Y-m-d H:i:s'),
								'3500-12-12 00:00:00'
							]
						],
						'_notfound'	 => T::out([
							'no_such_pin_or_pin_expired 2' => [
								'en' => 'No such pin or pin has expired or used!',
								'ru' => 'Пин просрочен, использован или не существует!'
							]
						])
			]);

			$visit = Guest::checkIn([
						'hotel_id'		 => $hotel->get('id'),
						'role'			 => 'guest',
						'user_id'		 => $user->get('id'),
						'begin'			 => $pin->get('start'),
						'end'			 => $pin->get('expired'),
						'room'			 => $pin->get('room'),
						'returnVisit'	 => true
			]);

			$pin->set([
				'visit_id' => $visit->get('id')
			]);

			return [
				'User' => [
					'reload' => [
						'data' => 'none'
					]
				]
			];
		}
	}

	public static function f() {
		return [
			'title'		 => 'Pin codes',
			'datatype'	 => [
				'visit_id'	 => [
					'UserHotel' => [
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
				'code'		 => "tinytext default null comment 'Pin code'",
				'start'		 => "datetime default null comment 'Check in'",
				'expired'	 => "datetime default null comment 'Expired date'",
				'room'		 => "tinytext default null comment 'Room'",
				'hotel_id'	 => "bigint unsigned default null comment 'Hotel'",
				'visit_id'	 => "bigint unsigned default null comment 'Visit, if null - not activated'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
