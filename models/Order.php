<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Order
 *
 * @author valera261104
 */
class Order extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		Permission::check(get_called_class(), $com, $user, $hotel);

		if ($com === 'services') {

			$level = empty($r['level'])
					? 0
					: $r['level'];

			return [
				'Order' => [
					'outputServices' => [
						'html'	 => self::getServices($level, $hotel),
						'title'	 => T::out([
							'guest_service_h2' => [
								'en' => 'Hotel services',
								'ru' => 'Сервисы отеля'
							]
						])
					]
				]
			];
		} elseif ($com === 'load') {

			self::required([
				'id' => true
					], $r);

			$order = self::getBy([
						'id'		 => $r['id'],
						'visit_id'	 => self::getGuest($hotel, $user)->get('id'),
						'_notfound'	 => true
			]);

			$service = Service::getBy([
						'id'		 => $order->get('service_id'),
						'_notfound'	 => true
			]);

			return [
				'Order' => [
					'loadOrder' => [
						'title'	 => $service->get('title'),
						'price'	 => $service->get('price'),
						'html'	 => H::getTemplate('pages/order/new_order_form', [], true),
						'data'	 => $order->toArray(['id' => true])
					]
				]
			];
		} elseif ($com === 'accept') { //restricted
			self::required([
				'id' => true
					], $r);

			$order = self::getBy([
						'id'		 => $r['id'],
						'status'	 => [
							'new',
							'deleted'
						],
						'_notfound'	 => true
			]);

			$visit = Guest::getBy([
						'id'		 => $order->get('visit_id'),
						'role'		 => 'guest',
						'_notfound'	 => T::out([
							'unable_accept_because_sign out' => [
								'en' => 'Unable to accept because guest signed out from the hotel',
								'ru' => 'Невозможно принять потому что гость выписался из отеля'
							]
						])
			]);

			Service::getBy([
				'id'		 => $order->get('service_id'),
				'visibility' => 'visible',
				'_notfound'	 => T::out([
					'service_no_visible_error' => [
						'en' => 'Service unavailable',
						'ru' => 'Сервис недоступен'
					]
				])
			])->isAvailable(new DateTime($order->get('date')));

			$order->set([
				'status'	 => 'accepted',
				'operator'	 => Staff::getBy([
					'user_id'	 => $user->get('id'),
					'hotel_id'	 => $hotel->get('id'),
					'role'		 => ['admin',
						'staff'],
					'_notfound'	 => true
				])->get('id')
			]);

			Notification::create([
				'hotel_id'	 => Hotel::getCurrent()->get('id'),
				'user_id'	 => UserHotel::getBy([
					'id'		 => $order->get('visit_id'),
					'_notfound'	 => true
				])->get('user_id'),
				'type'		 => 'AcceptOrder'
			]);

			return self::getOrders($r, $hotel, $user);
		} elseif ($com == 'create') {

			self::required([
				'quantity'	 => true,
				'date'		 => true,
				'note'		 => true,
				'service_id' => true
					], $r);

			//check date

			self::getBy([
				'id'		 => '_new',
				'_notfound'	 => [
					'visit_id'	 => self::getGuest($hotel, $user)->get('id'),
					'service_id' => Service::getBy([
						'id'		 => $r['service_id'],
						'visibility' => 'visible',
						'_notfound'	 => T::out([
							'service_no_visible_error' => [
								'en' => 'Service unavailable',
								'ru' => 'Сервис недоступен'
							]
						])
					])->isAvailable(new DateTime($r['date']))->get('id'),
					'quantity'	 => $r['quantity'],
					'date'		 => $r['date'],
					'note'		 => $r['note'],
					'hotel_id'	 => $hotel->get('id')
				]
			]);

			Notification::getBy([
				'id'		 => '_new',
				'_notfound'	 => [
					'hotel_id'	 => Hotel::getCurrent()->get('id'),
					'type'		 => 'Order'
				]
			]);

			return [
				'Site' => [
					'showResponseMessage'	 => [
						'message' => T::out([
							'order_created' => [
								'en' => 'Order created',
								'ru' => 'Заказ создан'
							]
						])
					],
					'switchTo'				 => [
						'page' => 'orders'
					]
				]
			];
		} elseif ($com == 'cancel') {

			self::required([
				'id' => true
					], $r);

			if (in_array($user->get('role'), [
						'admin',
						'staff'
					])) {

				Permission::check('Order', 'staff_' . $com, $user, $hotel);

				$order = self::getBy([
							'id'		 => $r['id'],
							'status'	 => [
								'new',
								'accepted'
							],
							'_notfound'	 => true
						])->set([
					'status'	 => 'deleted',
					'operator'	 => Staff::getBy([
						'user_id'	 => $user->get('id'),
						'hotel_id'	 => $hotel->get('id'),
						'role'		 => ['admin',
							'staff'],
						'_notfound'	 => true
					])->get('id')
				]);

				Notification::create([
					'hotel_id'	 => Hotel::getCurrent()->get('id'),
					'user_id'	 => UserHotel::getBy([
						'id'		 => $order->get('visit_id'),
						'_notfound'	 => true
					])->get('user_id'),
					'type'		 => 'RejectOrder'
				]);

				return self::getOrders($r, $hotel, $user);
			} else { //cancel by guest
				self::getGuest($hotel, $user);

				Notification::getBy([
					'id'		 => '_new',
					'_notfound'	 => [
						'hotel_id'	 => Hotel::getCurrent()->get('id'),
						'type'		 => 'CancelOrder'
					]
				]);

				self::getBy([
					'id'		 => $r['id'],
					'operator'	 => 'is null',
					'status'	 => ['new',
						'timechange'],
					'_notfound'	 => T::out([
						'service_already_accepted' => [
							'en' => 'Unable to change because service has already accepted',
							'ru' => 'Невозможно внести изменения, потому что услуга уже принята'
						]
					])
				])->remove();
			}

			return self::getList($r, $hotel, $user);
		} elseif ($com == 'set') {

			self::required([
				'id' => true
					], $r);

			if (in_array($user->get('role'), ['admin',
						'staff'])) {

				//TODO: what we can change here?
			} else { //guest_restriction
				self::getGuest($hotel, $user);

				$set = [];

				foreach ($r as $key => $val) {
					if (!isset(self::getRestrictedForGuests()[$key])) {
						$set[$key] = $val;
					}
				}

				if (!empty($set)) {

					$service_id = self::getBy([
								'id'		 => $r['id'],
								'status'	 => ['new',
									'timechange'],
								'_notfound'	 => T::out([
									'service_already_accepted' => [
										'en' => 'Unable to change because service has already accepted',
										'ru' => 'Невозможно внести изменения, потому что услуга уже принята'
									]
								])
							])->set($set)->get('service_id');

					if (isset($set['date'])) {//check possible dates
						Service::getBy([
							'id'		 => $service_id,
							'visibility' => 'visible',
							'_notfound'	 => T::out([
								'service_no_visible_error' => [
									'en' => 'Service unavailable',
									'ru' => 'Сервис недоступен'
								]
							])
						])->isAvailable(new DateTime($set['date']));
					}
				}

				return self::getList($r, $hotel, $user);
			}
		} elseif ($com == 'list') {

			if ($user->get('role') == 'guest') {

				Notification::read([
					'AcceptOrder',
					'RejectOrder'
				]);

				return self::getList($r, $hotel, $user);
			} elseif (in_array($user->get('role'), ['admin',
						'staff'])) {

				Permission::check('Order', 'staff_list', $user, $hotel);
				Notification::read(['Order',
					'CancelOrder']);
				return self::getOrders($r, $hotel, $user);
			} else {
				throw new Exception('Not availbale for current user role');
			}
		} elseif ($com == 'history') {
			return self::getHistory($r, $hotel, $user);
		} elseif ($com == 'deleted') {
			return self::getOrders($r, $hotel, $user, ['deleted']);
		} elseif ($com == 'open') {

			self::required([
				'id' => true
					], $r);

			$service = Service::getBy([
						'id'		 => $r['id'],
						'_notfound'	 => true
			]);

			if ($service->get('type') === 'service') {
				$guest = self::getGuest($hotel, $user);

				//try to find present orders

				/* if (Order::getBy([
				  'visit_id'	 => $guest->get('id'),
				  'service_id' => $service->get('id'),
				  '_return'	 => 'count'
				  ]) > 0) {

				  return [
				  'Orders' => [
				  'showOrdersForService' => [
				  'service_id' => $service->get('id')
				  ]
				  ]
				  ];
				  } else { */
				return [
					'Order' => [
						'showCreateOrderForm' => [
							'title'		 => $service->get('title'),
							'price'		 => $service->d('price'),
							'service_id' => $service->get('id'),
							'html'		 => H::getTemplate('pages/order/new_order_form', [], true)
						]
					]
				];
				/* } */
			} else {//open sub services
				return [
					'Order' => [
						'outputServices' => [
							'html'	 => self::getServices($service->get('id'), $hotel),
							'title'	 => $service->getCrumbs($service->get('id')),
							'crumb'	 => 1
						]
					]
				];
			}
		}
	}

	public static function restrictedOperations($operation = null) {

		$o = array_merge(parent::restrictedOperations(), [
			'staff_cancel'	 => true,
			'accept'		 => true,
			'remove'		 => false,
			'create'		 => false,
			'set'			 => false,
			'history'		 => [
				'Order' => [
					'outList' => [
						'html' => ''
					]
				]
			],
			'deleted'		 => [
				'Order' => [
					'outList' => [
						'html' => ''
					]
				]
			],
			'staff_list'	 => [
				'Order' => [
					'outList' => [
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

	public static function getGuest($hotel, $user) {

		return Guest::getBy([
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
				])->checkPIN();
	}

	/**
	 * return orders for selected guest
	 * 
	 * @param type $r (can set service)
	 * @param type $hotel
	 * @param type $user
	 * @return type
	 */
	public static function getList($r, $hotel, $user) {

		$page = empty($r['page'])
				? 0
				: $r['page'];

		$screen = S::getBy([
					'key'		 => 'pagination_page_size',
					'_notfound'	 => [
						'key'		 => 'pagination_page_size',
						'val'		 => 3,
						'comment'	 => 'Size of page when pagination'
					]
				])->d('val');

		$visit = self::getGuest($hotel, $user);

		$orders = Order::getBy([
					'visit_id'	 => $visit->get('id'),
					'service_id' => empty($r['service_id'])
							? '>>0'
							: $r['service_id'],
					'_limit'	 => [$page * $screen,
						$screen],
					'_return'	 => [
						0 => 'object'
					]
		]);

		$pages = floor(Order::getBy([
					'visit_id'	 => $visit->get('id'),
					'service_id' => empty($r['service_id'])
							? '>>0'
							: $r['service_id'],
					'_return'	 => 'count'
				]) / $screen);

		if (empty($orders)) {
			return [
				'Order'	 => [
					'outList' => [
						'html' => ''
					]
				],
				'Site'	 => [
					'showResponseMessage' => [
						'message'	 => T::out([
							'no_orders_founded' => [
								'en' => 'No orders founded',
								'ru' => 'Заказов не найдено'
							]
						]),
						'error'		 => true
					]
				]
			];
		}

		$template = H::getTemplate('pages/orders/guest_line', [], true);

		$h = [];

		foreach ($orders as $order) {

			$service = Service::getBy([
						'id'		 => $order->get('service_id'),
						'_notfound'	 => true
			]);

			$h[] = self::parse($template, [
						'id'		 => $order->get('id'),
						'name'		 => $user->get('name'),
						'room'		 => $visit->get('room'),
						'service'	 => $service->get('title'),
						'price'		 => $service->get('price'),
						'quantity'	 => $order->get('quantity'),
						'date'		 => $order->get('date'),
						'note'		 => $order->get('note'),
						'status'	 => $order->outStatus(),
						'can_edit'	 => in_array($order->get('status'), ['new',
							'timechange'])
								? 'can_edit'
								: '',
						'buttons'	 => in_array($order->get('status'), ['new',
							'timechange'])
								? '<button class="btn btn-xs btn-danger cancel_order_button id_' . $order->get('id') . '">' . T::out([
									'cancel_order_button' => [
										'en' => 'Cancel',
										'ru' => 'Отменить'
									]
								]) . '</button>'
								: ''
							], true);
		}

		return [
			'Order' => [
				'outList' => [
					'page'	 => $page,
					'pages'	 => $pages,
					'html'	 => H::getTemplate('pages/orders/guest_list', [
						'list' => join('', $h)
							], true)
				]
			]
		];
	}

	public static function getAcceptedFor($visit_id) {

		if (empty($visit_id)) {
			return false;
		}

		$orders = self::getBy([
					'visit_id'	 => $visit_id,
					'hotel_id'	 => Hotel::getCurrent()->get('id'),
					'status'	 => 'accepted',
					'_return'	 => [0 => 'object']
		]);

		if (empty($orders)) {
			return '';
		}

		$amount = 0;
		$h = ['<ul style="margin-bottom:0px;">'];
		foreach ($orders as $order) {
			$service = Service::getBy([
						'id' => $order->get('service_id')
			]);
			$h[] = '<li>' . $service->get('title') . ' (' . $order->get('quantity') . ')' . '</li>';
			$amount += ($service->d('price') * $order->d('quantity'));
		}
		$h[] = '</ul><div style="border-bottom:1px solid silver;"></div>';
		$h[] = '<div class="ar">' . T::out([
					'total_visit_amount' => [
						'ru'		 => 'Всего: {{total}} EUR',
						'en'		 => 'Total: {{total}} EUR',
						'_include'	 => [
							'total' => $amount
						]
					]
				]) . '</div>';

		return join('', $h);
	}

	public function outStatus() {

		//TODO: and here check service problems

		if ($this->get('status') === 'new') {
			return T::out([
						'wait for acceptance' => [
							'en'		 => '{{d}}wait for acceptance{{/d}}',
							'ru'		 => '{{d}}ждет принятия{{/d}}',
							'_include'	 => [
								'd'	 => '<div class="panel panel-info" style="margin:0px; padding:0px; text-shadow:none;"><div class="panel-heading" style="padding:2px; font-size:0.6rem; text-align:center;">',
								'/d' => '</div></div>'
							]
						]
			]);
		} elseif ($this->get('status') === 'timechange') {
			return T::out([
						'service_problem' => [
							'en'		 => '{{d}}holded because of changing service work time{{/d}}',
							'ru'		 => '{{d}}отложен из-за изменения времени работы сервиса{{/d}}',
							'_include'	 => [
								'd'	 => '<div class="panel panel-warning" style="margin:0px; padding:0px; text-shadow:none;"><div class="panel-heading" style="padding:2px; font-size:0.6rem; text-align:center;">',
								'/d' => '</div></div>'
							]
						]
			]);
		} elseif ($this->get('status') === 'accepted') {

			return T::out([
						'order_is_accepted_by_2' => [
							'en'		 => '{{d}}accepted by {{name}}{{/d}}',
							'ru'		 => '{{d}}принят {{name}}{{/d}}',
							'_include'	 => [
								'd'		 => '<div class="panel panel-success" style="margin:0px; padding:0px; text-shadow:none;"><div class="panel-heading" style="padding:2px; font-size:0.6rem; text-align:center;">',
								'/d'	 => '</div></div>',
								'name'	 => User::getBy([
									'id'		 => Staff::getBy([
										'id'		 => $this->get('operator'),
										'_notfound'	 => true
									])->get('user_id'),
									'_notfound'	 => true
								])->getName()
							]
						]
			]);
		} elseif ($this->get('status') === 'deleted') {
			return T::out([
						'order_is_cancled_by_2' => [
							'en'		 => '{{d}}rejected by {{name}}{{/d}}',
							'ru'		 => '{{d}}отказ {{name}}{{/d}}',
							'_include'	 => [
								'd'		 => '<div class="panel panel-danger" style="margin:0px; padding:0px;  text-shadow:none; border:none;"><div class="panel-heading" style="padding:2px; font-size:0.6rem; text-align:center; border:none; border-radius:3px;">',
								'/d'	 => '</div></div>',
								'name'	 => User::getBy([
									'id'		 => Staff::getBy([
										'id'		 => $this->get('operator'),
										'_notfound'	 => true
									])->get('user_id'),
									'_notfound'	 => true
								])->getName()
							]
						]
			]);
		}

		return 'unexpectd status ' . $this->get('status');
	}

	/**
	 * 
	 * @param type $r - return orders of all guests for hotel
	 * @param type $hotel
	 * @param type $user
	 * @return type
	 */
	public static function getOrders($r, $hotel, $user, $filter = [
		'new',
		'accepted'
	]) {

		$page = empty($r['page'])
				? 0
				: $r['page'];

		$screen = S::getBy([
					'key'		 => 'pagination_page_size',
					'_notfound'	 => [
						'key'		 => 'pagination_page_size',
						'val'		 => 3,
						'comment'	 => 'Size of page when pagination'
					]
				])->d('val');


		$orders = Order::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'service_id' => empty($r['service_id'])
							? '>>0'
							: $r['service_id'],
					'date'		 => [
						'_between' => [
							(new DateTime())->format('Y-m-d H:i:s'),
							'2050-12-10 00:00:00'
						]
					],
					'status'	 => $filter,
					'_limit'	 => [$page * $screen,
						$screen],
					'_return'	 => [
						0 => 'object'
					]
		]);

		$pages = floor(Order::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'service_id' => empty($r['service_id'])
							? '>>0'
							: $r['service_id'],
					'status'	 => $filter,
					'date'		 => [
						'_between' => [
							(new DateTime())->format('Y-m-d H:i:s'),
							'2050-12-10 00:00:00'
						]
					],
					'_return'	 => 'count'
				]) / $screen);

		if (empty($orders)) {
			return [
				'Order'	 => [
					'outList' => [
						'html' => ''
					]
				],
				'Site'	 => [
					'showResponseMessage' => [
						'message'	 => T::out([
							'no_orders_founded' => [
								'en' => 'No orders founded',
								'ru' => 'Заказов не найдено'
							]
						]),
						'error'		 => true
					]
				]
			];
		}

		$template = H::getTemplate('pages/orders/order_line', [], true);

		$h = [];

		foreach ($orders as $order) {

			$service = Service::getBy([
						'id'		 => $order->get('service_id'),
						'_notfound'	 => true
			]);

			$visit = Guest::getBy([
						'id'		 => $order->get('visit_id'),
						'_notfound'	 => true
			]);

			$guest = User::getBy([
						'id'		 => $visit->get('user_id'),
						'_ntofound'	 => true
			]);

			$h[] = self::parse($template, [
						'id'		 => $order->get('id'),
						'name'		 => $guest->getName(),
						'room'		 => $visit->get('room'),
						'service'	 => $service->get('title'),
						'price'		 => $service->get('price'),
						'quantity'	 => $order->get('quantity'),
						'date'		 => $order->get('date'),
						'note'		 => $order->get('note'),
						'status'	 => $order->outStatus(),
						'buttons'	 => (in_array($order->get('status'), ['new',
							'deleted'])
								? '<button class="btn btn-xs btn-success accept_order_button id_' . $order->get('id') . '">' . T::out([
									'accept_order_button' => [
										'en' => 'Accept',
										'ru' => 'Принять'
									]
								]) . '</button>'
								: '') . (in_array($order->get('status'), ['new',
							'accepted'])
								? '<button class="btn btn-xs btn-danger cancel_order_button id_' . $order->get('id') . '">' . T::out([
									'cancel_order_button' => [
										'en' => 'Cancel',
										'ru' => 'Отменить'
									]
								]) . '</button>'
								: '')
							], true);
		}

		return [
			'Order' => [
				'outList' => [
					'filter' => $filter[0] == 'deleted'
							? 'deleted'
							: 'list',
					'page'	 => $page,
					'pages'	 => $pages,
					'html'	 => H::getTemplate('pages/orders/order_list', [
						'list'	 => join('', $h),
						'button' => '<button class="btn btn-xs btn-default order_history_button">' . T::out([
							'order_history_button' => [
								'en' => 'History',
								'ru' => 'Архив'
							]
						]) . '</button>'
							], true)
				]
			]
		];
	}

	/**
	 * 
	 * @param type $r - return history
	 * @param type $hotel
	 * @param type $user
	 * @return type
	 */
	public static function getHistory($r, $hotel, $user, $filter = [
		'accepted',
		'deleted'
	]) {

		$page = empty($r['page'])
				? 0
				: $r['page'];

		$screen = S::getBy([
					'key'		 => 'pagination_page_size',
					'_notfound'	 => [
						'key'		 => 'pagination_page_size',
						'val'		 => 3,
						'comment'	 => 'Size of page when pagination'
					]
				])->d('val');


		$orders = Order::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'service_id' => empty($r['service_id'])
							? '>>0'
							: $r['service_id'],
					'date'		 => [
						'_between' => [
							'2000-12-10 00:00:00',
							(new DateTime())->format('Y-m-d H:i:s')
						]
					],
					'status'	 => $filter,
					'_limit'	 => [
						$page * $screen,
						$screen
					],
					'_return'	 => [
						0 => 'object'
					]
		]);

		$pages = floor(Order::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'service_id' => empty($r['service_id'])
							? '>>0'
							: $r['service_id'],
					'status'	 => $filter,
					'date'		 => [
						'_between' => [
							'2000-12-10 00:00:00',
							(new DateTime())->format('Y-m-d H:i:s')
						]
					],
					'_return'	 => 'count'
				]) / $screen);

		if (empty($orders)) {
			return [
				'Order'	 => [
					'outList' => [
						'html' => ''
					]
				],
				'Site'	 => [
					'showResponseMessage' => [
						'message'	 => T::out([
							'no_orders_founded' => [
								'en' => 'No orders founded',
								'ru' => 'Заказов не найдено'
							]
						]),
						'error'		 => true
					]
				]
			];
		}

		$template = H::getTemplate('pages/orders/order_line', [], true);

		$h = [];

		foreach ($orders as $order) {

			$service = Service::getBy([
						'id'		 => $order->get('service_id'),
						'_notfound'	 => true
			]);

			$visit = Guest::getBy([
						'id'		 => $order->get('visit_id'),
						'_notfound'	 => true
			]);

			$guest = User::getBy([
						'id'		 => $visit->get('user_id'),
						'_ntofound'	 => true
			]);

			$h[] = self::parse($template, [
						'id'		 => $order->get('id'),
						'name'		 => $guest->getName(),
						'room'		 => $visit->get('room'),
						'service'	 => $service->get('title'),
						'price'		 => $service->get('price'),
						'quantity'	 => $order->get('quantity'),
						'date'		 => $order->get('date'),
						'note'		 => $order->get('note'),
						'status'	 => $order->outStatus(),
						'buttons'	 => ''
							], true);
		}

		return [
			'Order' => [
				'outList' => [
					'filter' => 'history',
					'page'	 => $page,
					'pages'	 => $pages,
					'html'	 => H::getTemplate('pages/orders/order_list', [
						'list'	 => join('', $h),
						'button' => '<button class="btn btn-xs btn-default order_activelist_button">' . T::out([
							'order_activelist_button' => [
								'en' => 'Actual',
								'ru' => 'Актуальные'
							]
						]) . '</button>'
							], true)
				]
			]
		];
	}

	public static function getServices($level, $hotel) {

		$services = Service::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'parent_id'	 => empty($level)
							? 'is null'
							: $level,
					'visibility' => 'visible',
					'_return'	 => [0 => 'object']
		]);

		$template = H::getTemplate('pages/order/service.php', [], true);

		$h = [];

		if (!empty($services)) {

			if (!empty($level)) {
				$parent = Service::getBy([
							'id'		 => $level,
							'_notfound'	 => true
				]);
				if ($parent->get('type') == 'list') {
					$h[] = '<select value="null" class="service_select form-control" style="width:50%;"><option disabled="disabled" value="null" selected="selected">' . T::out([
								'select_service' => [
									'en' => 'Select service',
									'ru' => 'Выбрать сервис'
								]
							]) . '</option>';
				}
			}


			if (!empty($parent) && $parent->get('type') == 'list') {
				$h[] = $parent->outputChildrenServices($hotel, 0);
				//$h[] = '';
				$h[] = '</select>';
			} else {
				foreach ($services as $service) {

					$h[] = self::parse($template, [
								'id'			 => $service->get('id'),
								'background'	 => $service->get(['image' => 0])
										? 'background-image:url(' . $service->get(['image' => 0]) . ');'
										: 'background:rgba(0,0,0,0.5);',
								'title'			 => $service->get('title'),
								'description'	 => $service->get('description'),
								'worktime'		 => $service->outWorktime(),
								'price'			 => $service->get('price'),
								'dontdisplay'	 => $service->d('price') == 0 || $service->d('parent_id') == 0
										? 'display:none;'
										: ''
									], true);
				}
			}
		}


		return join('', $h);
	}

	public static function getRestrictedForGuests() {
		return [
			'id'		 => true,
			'visit_id'	 => true,
			'service_id' => true,
			'operator'	 => true,
			'hotel_id'	 => true,
			'status'	 => true
		];
	}

	public static function f() {
		return [
			'title'		 => 'Order',
			'datatype'	 => [
				'visit_id'	 => [
					'UserHotel' => [
						'id' => 'ON DELETE CASCADE'
					]
				],
				'service_id' => [
					'Service' => [
						'id' => 'ON DELETE CASCADE'
					]
				],
				'operator'	 => [
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
				'visit_id'	 => "bigint unsigned default null comment 'Visit'",
				'service_id' => "bigint unsigned default null comment 'Service'",
				'quantity'	 => "float unsigned default 0 comment 'Quantity'",
				'date'		 => "datetime default null comment 'Date when need'",
				'note'		 => "text comment 'Guest comment'",
				'operator'	 => "bigint unsigned default null comment 'Staff or null if not accepted'",
				'hotel_id'	 => "bigint unsigned default null comment 'Visit'",
				'status'	 => "enum('new','accepted','deleted','timechange') default 'new' comment 'Order acceptance status'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
