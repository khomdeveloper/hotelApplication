<?php

/**
 * Description of Review
 *
 * @author valera261104
 */
class Review extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		Permission::check(get_called_class(), $com, $user, $hotel);

		if ($com === 'create') { //create new order by guest
			self::required([
				'rate' => true
					], $r);

			$visit = Order::getGuest($hotel, $user);

			if (self::getBy([
						'hotel_id'	 => $hotel->get('id'),
						'visit_id'	 => $visit->get('id'),
						'_return'	 => 'count'
					]) > 0) {
				throw new Exception(T::out([
					'you_have_already_sent_a_review (error)' => [
						'en' => 'You have already sent a review',
						'ru' => 'Вы уже отправили отзыв'
					]
				]));
			}

			self::getBy([
				'id'		 => '_new',
				'_notfound'	 => [
					'hotel_id'	 => $hotel->get('id'),
					'visit_id'	 => Order::getGuest($hotel, $user)->get('id'),
					'date'		 => (new DateTime())->format('Y-m-d H:i:s'),
					'message'	 => empty($r['message'])
							? ''
							: $r['message'],
					'rate'		 => $r['rate'] * 1
				]
			]);

			return [
				'Site' => [
					'showResponseMessage' => [
						'message' => T::out([
							'review_has_sent' => [
								'en' => 'Review has been sent',
								'ru' => 'Отзыв отправлен'
							]
						])
					]
				]
			];
		} elseif ($com === 'load') { //load order for edit
			//TODO:
		} elseif ($com === 'edit') { //edit order by guest
			//TODO:
		} elseif ($com === 'list') {//for admin
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

			$count = self::getBy([
								'hotel_id'	 => $hotel->get('id'),
								'_return'	 => 'count'
							]);
			
			$pages = floor($count / $screen);


			if (empty($count)) {
				return [
					'Review' => [
						'outList' => [
							'html' => ''
						]
					],
					'Site'	 => [
						'showResponseMessage' => [
							'message'	 => T::out([
								'no_feedback' => [
									'en' => 'No feedback',
									'ru' => 'Нет отзывов'
								]
							]),
							'error'		 => true
						]
					]
				];
			}

			$reviews = self::getBy([
						'hotel_id'	 => $hotel->get('id'),
						'_return'	 => [
							0 => 'object'
						],
						'_order'	 => '`date` DESC',
						'_limit'	 => [$page * $screen,
							$screen],
			]);

			$template = H::getTemplate('pages/review/staff_line', [], true);

			$h = [];

			foreach ($reviews as $record) {

				$visit = Guest::getBy([
							'id'		 => $record->get('visit_id'),
							'_notfound'	 => true
				]);

				$h[] = self::parse($template, [
							'id'		 => $record->get('id'),
							'name'		 => User::getBy([
								'id'		 => $visit->get('user_id'),
								'_notfound'	 => true
							])->getName(),
							'room'		 => $visit->get('room'),
							'date'		 => $record->get('date'),
							'rate'		 => $record->get('rate'),
							'message'	 => $record->get('message')
								], true);
			}

			return [
				'Review' => [
					'outList' => [
						'page'	 => $page,
						'pages'	 => $pages,
						'html'	 => H::getTemplate('pages/review/staff_list', [
							'list' => join('', $h)
								], true)
					]
				]
			];
		}
	}

	public static function restrictedOperations($operation = null) {

		$o = array_merge(parent::restrictedOperations(), [
			'create' => false,
			'list'	 => [
				'Review' => [
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

	public static function f() {
		return [
			'title'		 => 'Review',
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
				'visit_id'	 => "bigint unsigned default null comment 'Visit'",
				'hotel_id'	 => "bigint unsigned default null comment 'Hotel'",
				'date'		 => "datetime default null comment 'Date when need'",
				'message'	 => "text comment 'Guest comment'",
				'rate'		 => "tinyint default 5 comment 'Rate of review'",
				'status'	 => "enum('new','visible','deleted') default 'new' comment 'Review status'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
