<?php

/**
 * Description of Guest
 *
 * @author valera261104
 */
class Guest extends UserHotel {

	public static function getTable() {
		return 'hotel_userhotel';
	}

	function tableName() {
		return 'hotel_userhotel';
	}

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();

		Permission::check('Guest', $com, $user, $hotel);

		if ($com == 'check_in') { //restricted
			self::required([
				'user_id' => true
					], $r);

			$r['hotel_id'] = $hotel->get('id');

			return self::checkIn($r);
		} elseif ($com == 'pin') { //deprecated
			throw new Exception('deprecated');

			self::required([
				'room'	 => true,
				'pin'	 => true
					], $r);

			$guest = Guest::getBy([
						'user_id'	 => $user->get('id'),
						'hotel_id'	 => $hotel->get('id'),
						'role'		 => 'guest',
						'end'		 => [
							'_between' => [
								(new DateTime())->format('Y-m-d H:i:s'),
								'2050-12-12 00:00:00'
							]
						],
						'_notfound'	 => T::out([
							'not_registered_as_gust_error' => [
								'en'		 => 'You are not registered in the hotel {{hotel}} as a guest at this moment.',
								'ru'		 => 'Вы не зарегистрированы в настоящий момент в отеле {{hotel}} в качестве гостя',
								'_include'	 => [
									'hotel' => $hotel->get('title')
								]
							]
						])
					])->checkPIN($r);

			return [
				'Site' => [
					'showResponseMessage' => [
						'message' => T::out([
							'pin_success' => [
								'en' => 'PIN is ok. Now you can use hotel services',
								'ru' => 'ПИН впорядке. Теперь вы можете использовать сервисы отеля'
							]
						])
					]
				]
			];
		} elseif ($com == 'checkout') { //restricted
			self::required([
				'visit_id' => true
					], $r);

			$r['hotel_id'] = $hotel->get('id');

			return self::checkOut($r);
		} elseif ($com == 'extend') { //restricted
			self::required([
				'visit_id'	 => true,
				'till'		 => true
					], $r);

			$r['hotel_id'] = $hotel->get('id');

			return self::extendTill($r);
		}

		return parent::action($r, $dontdie);
	}

	public function checkPIN($r = null) {

		return $this;

		/*
		  if (empty($r)) {

		  if (empty($_SESSION['pin']) || $this->get('pin') != $_SESSION['pin']) {

		  unset($_SESSION['pin']);

		  M::ok([
		  'Order' => [
		  'showPINForm' => [
		  'html' => H::getTemplate('pages/order/pin_form', [], true)
		  ]
		  ]
		  ]);
		  }
		  } else {

		  if ($this->get('pin') == $r['pin'] && $this->get('room') == $r['room']) {
		  $_SESSION['pin'] = $this->get('pin');
		  } else {

		  unset($_SESSION['pin']);

		  M::ok([
		  'Order' => [
		  'showPINForm' => [
		  'css'	 => 'type-danger',
		  'html'	 => H::getTemplate('pages/order/pin_form', [], true)
		  ]
		  ]
		  ]);
		  }
		  }

		  return $this; */
	}

	public static function restrictedOperations($operation = null) {
		$o = array_merge(parent::restrictedOperations(), [
			'checkin'	 => true,
			'checkout'	 => true,
			'extend'	 => true,
			'find'		 => [
				'Visits' => [
					'outputUsersList' => [
						'html' => ''
					]
				]
			]
		]);

		return empty($operation)
				? $o
				: (empty($o[$operation])
						? false
						: $o[$operation]);
	}

	/**
	 * 
	 * @param type $input
	 */
	public static function checkIn($r) {

		//TODO: check if user not already checked now

		if (self::getBy([
					'user_id'	 => $r['user_id'],
					'hotel_id'	 => $r['hotel_id'],
					'end'		 => [
						'_between' => [
							(new DateTime())->format('Y-m-d H:i:s'),
							'2050-12-12 00:00:00'
						]
					],
					'_return'	 => 'count'
				]) > 0) {

			throw new Exception(T::out([
				'user_already_checkedin (error)' => [
					'en' => 'User already checked in the hotel',
					'ru' => 'Пользователь уже зарегистрировался в отеле'
				]
			]));
		}

		/*
		  $l = false;
		  do {
		  $code = substr(md5(microtime()), 0, 4);

		  $l = self::getBy([
		  'pin'		 => $code,
		  '_return'	 => 'count'
		  ]) > 0;
		  } while ($l == true);
		 */

		$visit = self::getBy([
					'id'		 => '_new',
					'_notfound'	 => [
						'user_id'	 => User::getBy([
							'id'		 => $r['user_id'],
							'_notfound'	 => true
						])->get('id'),
						'hotel_id'	 => $r['hotel_id'],
						'role'		 => 'guest',
						'begin'		 => $r['begin'],
						'end'		 => $r['end'],
						'room'		 => $r['room']
					]
		]);

		if (isset($r['returnVisit'])) {

			Notification::create([
				'hotel_id'	 => Hotel::getCurrent()->get('id'),
				'user_id'	 => $r['user_id'],
				'type'		 => 'Visit'
			]);

			return $visit;
		} else {
			return self::findUser([
						'who'	 => '@',
						'filter' => 'guest'
			]);
		}
	}

	public static function checkOut($r) {

		self::getBy([
			'id'		 => $r['visit_id'],
			'role'		 => 'guest',
			'_notfound'	 => true
		])->set([
			'end'	 => (new DateTime())->format('Y-m-d H:i:s'),
			'role'	 => 'past'
		]);

		return self::findUser([
					'who'	 => '@',
					'filter' => 'guest'
		]);
	}

	public static function extendTill($r) {

		//check if this user already registered

		$visit = self::getBy([
					'id'		 => $r['visit_id'],
					'role'		 => ['guest',
						'past'],
					'_notfound'	 => true
				])->set([
			'end'	 => $r['till'],
			'role'	 => 'guest'
		]);

		if (self::getBy([
					'user_id'	 => $visit->get('user_id'),
					'role'		 => 'guest',
					'hotel_id'	 => $r['hotel_id'],
					'_return'	 => 'count'
				]) > 1) {
			throw new Exception(T::out([
				'user_has_already_registered' => [
					'en' => 'Guest has already registered in the hotel',
					'ru' => 'Посетитель уже зарегистрирован в отеле'
				]
			]));
		}

		return self::findUser([
					'who'	 => '@',
					'filter' => 'guest'
		]);
	}

	public function add($what) {
		if (empty($what['role']) || $what['role'] != 'guest') {
			$what['role'] = 'guest';
		}
		return parent::add($what);
	}

	public static function findUser($r) {

		Notification::read([
			'Visit'
		]);

		$hotel_id = empty($r['hotel_id'])
				? Hotel::getCurrent()->get('id')
				: $r['hotel_id'];

		$page = empty($r['page'])
				? 0
				: $r['page'];

		$filter = [
			$r['filter']
		];

		$users = [];

		if (strpos($r['who'], '@') !== false) {
			$findBy = "
				AND (
					`login` like :who 
					OR
					`email` like :who
				)
				";
		} else { //by name
			$fl_name = explode(' ', $r['who']);

			$findBy = "
				AND concat(`first_name`,' ',`last_name`) like :who 
				";
		}

		//TODO: добавить что пользователь сейчас актуален

		$addOut = ',' . join(',', [
					"`hotel_userhotel`.`begin` as `checkin`",
					"`hotel_userhotel`.`end` as `checkout`",
					"`hotel_userhotel`.`room` as `room`",
					"`hotel_userhotel`.`pin` as `pin`",
					"`hotel_userhotel`.`id` as `visit_id`"
		]);

		$screen = S::getBy([
					'key'		 => 'pagination_page_size',
					'_notfound'	 => [
						'key'		 => 'pagination_page_size',
						'val'		 => 3,
						'comment'	 => 'Size of page when pagination'
					]
				])->d('val');



		if (in_array('visitor', $filter)) {

			throw new Exception('Forbidden to find visitors');

			$query = "
				SELECT `hotel_user`.`id` as `user_id`, 'visitor' as `status` " . $addOut . "
				FROM `hotel_user`
				LEFT JOIN `hotel_userhotel` ON `hotel_userhotel`.`user_id` = `hotel_user`.`id`
				WHERE `hotel_user`.`id` != :self_id
				AND `hotel_userhotel`.`user_id` is null
				" . $findBy . "
				LIMIT " . ($page * $screen) . "," . $screen . "
				";

			$query_count = "
				SELECT count(`hotel_user`.`id`) as `count`
				FROM `hotel_user`
				LEFT JOIN `hotel_userhotel` ON `hotel_userhotel`.`user_id` = `hotel_user`.`id`
				WHERE `hotel_user`.`id` != :self_id
				AND `hotel_userhotel`.`user_id` is null
				" . $findBy;
		} else {
			$query = "
				SELECT	`user_id`, `hotel_userhotel`.`role` as `status`" . $addOut . "
				FROM `hotel_userhotel`
				LEFT JOIN `hotel_user` ON `hotel_userhotel`.`user_id` = `hotel_user`.`id`
				WHERE `hotel_id` = :hotel_id
				AND `user_id` != :self_id
				AND `hotel_userhotel`.`role` in ('" . join("','", $filter) . "') 
				" . $findBy . "
				LIMIT " . ($page * $screen) . "," . $screen . "
				";

			$query_count = "
				SELECT	count(`user_id`) as `count`
				FROM `hotel_userhotel`
				LEFT JOIN `hotel_user` ON `hotel_userhotel`.`user_id` = `hotel_user`.`id`
				WHERE `hotel_id` = :hotel_id
				AND `user_id` != :self_id
				AND `hotel_userhotel`.`role` in ('" . join("','", $filter) . "') 
				" . $findBy;
		}

		$db = Yii::app()->db->createCommand($query_count)->query(in_array('visitor', $filter)
						? ['self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%']
						: [
					'hotel_id'	 => $hotel_id,
					'self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%'
		]);

		while (($row = $db->read()) != false) {
			$count = $row['count'];
		}

		$pages = floor($count / $screen);

		$db = Yii::app()->db->createCommand($query)->query(in_array('visitor', $filter)
						? ['self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%']
						: [
					'hotel_id'	 => $hotel_id,
					'self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%'
		]);


		if (!empty($db)) {
			while (($row = $db->read()) != false) {
				$users[] = $row;
			}
		}

		if (empty($users)) {

			return [
				'Visits' => [
					'outputUsersList' => [
						'html' => ''
					]
				],
				'Site'	 => [
					'showResponseMessage' => [
						'message'	 => T::out([
							'no_results' => [
								'en' => 'No user founded',
								'ru' => 'Пользователей не найдено'
							]
						]),
						'error'		 => true
					]
				]
			];
		}

		$line_template = H::getTemplate('pages/visits/inline.php', [], true);

		$h = [];

		foreach ($users as $user) {

			//check if user has ended but not checked out

			$end = (new DateTime($user['checkout']))->getTimestamp();
			$now = (new DateTime())->getTimestamp();

			$User = User::getBy([
						'id' => $user['user_id']
			]);

			$pin = Pin::getBy([
						'visit_id' => $user['visit_id']
			]);

			$h[] = H::parse($line_template, [
						'name'		 => $User->get('first_name') . ' ' . $User->get('last_name'),
						'email'		 => $User->get('email'),
						'user_id'	 => $User->get('id'),
						'checkin'	 => $user['checkin'],
						'checkout'	 => $user['checkout'],
						'room'		 => $user['room'],
						'pin'		 => empty($pin)
								? ''
								: $pin->get('code'),
						'visit_id'	 => $user['visit_id'],
						'orders'	 => Order::getAcceptedFor($user['visit_id']),
						'role'		 => $user['status'],
						'buttons'	 => $user['status'] == 'visitor'
								? '<button class="btn btn-success btn-xs button_checkin user_id_' . $User->get('id') . '" style="margin:3px;">' . T::out([
									'button_checkin' => [
										'en' => 'Checkin',
										'ru' => 'Зарегистрировать'
									]
								]) . '</button>'
								: ($r['filter'] == 'guest'
										? '<button class="btn btn-info btn-xs button_checkout visit_id_' . $user['visit_id'] . '" style="margin:3px;">' . T::out([
											'button_checkout' => [
												'en' => 'Checkout',
												'ru' => 'Выписать'
											]
										]) . '</button>
		<button class="btn btn-default btn-xs button_extend visit_id_' . $user['visit_id'] . '" style="margin:3px;">' . T::out([
											'button_extend' => [
												'en' => 'Extend',
												'ru' => 'Продлить'
											]
										]) . '</button>'
										: ('<button class="btn btn-default btn-xs button_extend visit_id_' . $user['visit_id'] . '" style="margin:3px;">' . T::out([
											'button_extend' => [
												'en' => 'Extend',
												'ru' => 'Продлить'
											]
										]) . '</button>' . '<button class="btn btn-success btn-xs button_checkin user_id_' . $User->get('id') . '" style="margin:3px;">' . T::out([
											'button_checkin' => [
												'en' => 'Checkin',
												'ru' => 'Зарегистрировать'
											]
										]) . '</button>')),
						'status'	 => $user['status'] == 'visitor'
								? '<div class="panel panel-warning" style="margin:0px; padding:0px; text-shadow:none;"><div class="panel-heading" style="padding:2px; font-size:0.7rem; text-align:center;">' . T::out([
									'not_checked' => [
										'en' => 'Not checked',
										'ru' => 'Не зарегистрирован'
									]
								]) . '</div></div>'
								: ($user['status'] == 'guest' && ($now < $end)
										? '<div class="panel panel-success" style="margin:0px; padding:0px; text-shadow:none;"><div class="panel-heading" style="padding:2px; font-size:0.7rem; text-align:center;">' . T::out([
											'signed_in_panel' => [
												'en' => 'Active',
												'ru' => 'Активен'
											]
										]) . '</div></div>'
										: ($user['status'] == 'past'
												? '<div class="panel panel-warning" style="margin:0px; padding:0px; text-shadow:none; border:none;"><div class="panel-heading" style="padding:3px; font-size:0.7rem; text-align:center; border:none; border-radius:3px;">' . T::out([
													'ended-panel' => [
														'en' => 'Left',
														'ru' => 'Выехал'
													]
												]) . '</div></div>'
												: '<div class="panel panel-danger" style="margin:0px; padding:0px; text-shadow:none; border:none;"><div class="panel-heading" style="padding:3px; font-size:0.7rem; text-align:center; border:none; border-radius:3px;">' . T::out([
													'ended-panel' => [
														'en' => 'Ended',
														'ru' => 'Закончился'
													]
												]) . '</div></div>'))
							], true);
		}


		return [
			'Visits' => [
				'outputUsersList' => [
					'pages'	 => $pages,
					'page'	 => $page,
					'screen' => $screen,
					'html'	 => H::getTemplate('pages/visits/list.php', [
						'list' => join('', $h)
							], true),
					'roles'	 => false,
					'filter' => $r['filter']
				]
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
