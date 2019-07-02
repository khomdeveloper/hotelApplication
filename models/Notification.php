<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notification
 *
 * @author valera261104
 */
class Notification extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();

		if ($com == 'check') {

			if (in_array($user->get('role'), ['admin',
						'staff'])) {

				return [
					'Orders' => [
						'checkUnread' => [
							'deleted'	 => self::getBy([
								'hotel_id'	 => $hotel->get('id'),
								'status'	 => 'unread',
								'type'		 => 'CancelOrder',
								'_return'	 => 'count'
							]),
							'count'		 => self::getBy([
								'hotel_id'	 => $hotel->get('id'),
								'status'	 => 'unread',
								'type'		 => 'Order',
								'_return'	 => 'count'
							])
						]
					],
					'Visits' => [
						'checkUnread' => [
							'count' => self::getBy([
								'hotel_id'	 => $hotel->get('id'),
								'status'	 => 'unread',
								'type'		 => 'Visit',
								'_return'	 => 'count'
							])
						]
					]
				];
			} else {

				return [
					'Orders' => [
						'checkUnread' => [
							'accepted'	 => self::getBy([
								'hotel_id'	 => $hotel->get('id'),
								'user_id'	 => $user->get('id'),
								'status'	 => 'unread',
								'type'		 => 'AcceptOrder',
								'_return'	 => 'count'
							]),
							'rejected'		 => self::getBy([
								'hotel_id'	 => $hotel->get('id'),
								'user_id'	 => $user->get('id'),
								'status'	 => 'unread',
								'type'		 => 'RejectOrder',
								'_return'	 => 'count'
							])
						]
					]
				];
			}
		}
	}

	public static function read($type) {
		$notifications = self::getBy([
					'type'		 => $type,
					'hotel_id'	 => Hotel::getCurrent()->get('id'),
					'status'	 => 'unread',
					'_return'	 => [0 => 'object']
		]);

		if (!empty($notifications)) {
			foreach ($notifications as $notification) {
				$notification->remove();
			}
		}
	}

	public static function f() {
		return [
			'title'		 => 'Notification about new orders',
			'datatype'	 => [
				'hotel_id'	 => [
					'Hotel' => [
						'id' => 'ON DELETE CASCADE'
					]
				],
				'user_id'	 => [
					'User' => [
						'id' => 'ON DELETE CASCADE'
					]
				]
			],
			'create'	 => [
				'hotel_id'	 => "bigint unsigned default null comment 'Hotel'",
				'user_id'	 => "bigint unsigned default null comment 'User'",
				'type'		 => "tinytext default null comment 'Object name'",
				'status'	 => "enum('read','unread') default 'unread' comment 'Notification status'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
