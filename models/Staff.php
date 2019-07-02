<?php

/**
 * Description of Staff
 *
 * @author valera261104
 */
class Staff extends UserHotel {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();

		Permission::check('Staff', $com, $user, $hotel);

		if ($com == 'get_permissions') {

			self::required([
				'user_id' => true
					], $r);

			$User = User::getBy([
						'id'		 => $r['user_id'],
						'_notfound'	 => true
			]);

			return [
				'Staff' => [
					'showUserPermissions' => [
						'staff_id'		 => Staff::getBy([
							'user_id'	 => $r['user_id'],
							'hotel_id'	 => $hotel->get('id'),
							'role'		 => [
								'staff',
								'admin'
							],
							'_notfound'	 => true
						])->get('id'),
						'title'			 => T::out([
							'permissions_for_user' => [
								'en'		 => 'Permissions for {{user}}',
								'ru'		 => 'Разрешения для {{user}}',
								'_include'	 => [
									'user' => $User->getName()
								]
							]
						]),
						'permissions'	 => Permission::getUserPermissions($User)
					]
				]
			];
		} elseif ($com == 'grant') {

			self::required([
				'role'		 => true,
				'user_id'	 => true
					], $r);

			if (!in_array($r['role'], $user->get('role') == 'admin' ? ['guest',
						'staff',
						'admin',
						'visitor'] : ['guest','staff','visitor'])) {
				throw new Exception(T::out([
					'Has no permission to grant such role' => [
						'en' => 'Has no permission to grant such role',
						'ru' => 'Нет прав предоставить такую роль'
					]
				]));
			}

			if ($r['user_id'] == $user->get('id')) {
				throw new Exception(T::out([
					'can_not_grant_to_self' => [
						'en' => 'Can not grant any permission to self account',
						'ru' => 'Невозможно предоставить права для своего аккаунта'
					]
				]));
			}

			$hotel_id = Hotel::getBy([
						'id'		 => self::getHotelId($r),
						'_notfound'	 => true
					])->get('id');

			if ($r['role'] == 'visitor') {

				$link = self::getBy([
							'user_id'	 => $r['user_id'],
							'hotel_id'	 => $hotel_id
				]);

				if (!empty($link)) {
					$link->remove();
				}
			} else {

				self::getBy([
					'user_id'	 => $r['user_id'],
					'hotel_id'	 => $hotel_id,
					'_notfound'	 => [
						'user_id'	 => $r['user_id'],
						'hotel_id'	 => $hotel_id
					]
				])->set([
					'role'	 => $r['role'],
					'begin'	 => (new DateTime())->format('Y-m-d H:i:s')
				]);
			}

			return [
				'Site' => [
					'showResponseMessage' => [
						'message' => T::out([
							'role grant' => [
								'en' => 'User rights has been set',
								'ru' => 'Права пользователя установлены'
							]
						])
					]
				]
			];
		}


		return parent::action($r, $dontdie);
	}

	public static function restrictedOperations($operation = null) {

		$o = array_merge(parent::restrictedOperations(), [
			'find'				 => [
				'Staff' => [
					'outputUsersList' => [
						'html' => ''
					]
				]
			],
			'get_permissions'	 => true,
			'grant' => true
		]);

		return empty($operation)
				? $o
				: (empty($o[$operation])
						? false
						: $o[$operation]);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public static function getTable() {
		return 'hotel_userhotel';
	}

	function tableName() {
		return 'hotel_userhotel';
	}

	public static function findUser($r) {

		$hotel_id = empty($r['hotel_id'])
				? Hotel::getCurrent()->get('id')
				: $r['hotel_id'];

		$page = empty($r['page'])
				? 0
				: $r['page'];

		$filter = self::searchFilter($r);

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


		$screen = S::getBy([
					'key'		 => 'pagination_page_size',
					'_notfound'	 => [
						'key'		 => 'pagination_page_size',
						'val'		 => 3,
						'comment'	 => 'Size of page when pagination'
					]
				])->d('val');


		if (in_array('visitor', $filter)) {

			throw new Exception('Forbidden to find visitor');
			
			$query = "
				SELECT `hotel_user`.`id` as `user_id`
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
				SELECT	`user_id`
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
						? [
					'self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%'
						]
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
						? [
					'self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%'
						]
						: [
					'hotel_id'	 => $hotel_id,
					'self_id'	 => User::logged()->get('id'),
					'who'		 => '%' . $r['who'] . '%'
		]);

		if (!empty($db)) {
			while (($row = $db->read()) != false) {
				$users[] = User::getBy([
							'id' => $row['user_id']
				]);
			}
		}

		if (empty($users)) {

			return [
				'Staff'	 => [
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

		$line_template = H::getTemplate('pages/staff/inline.php', [], true);

		$h = [];
		$roles = [];
		$ids = []; //list of staff

		foreach ($users as $user) {

			$h[] = H::parse($line_template, [
						'name'		 => $user->get('first_name') . ' ' . $user->get('last_name') . ($user->get('net')
								? '<br/>' . $user->get('net')
								: ''),
						'email'		 => $user->get('email'),
						'user_id'	 => $user->get('id'),
						'staff'		 => in_array($user->get('role'), ['admin',
							'staff'])
								? 'isStaff'
								: ''
							], true);
			$roles[$user->get('id')] = $user->get('role');
			if ($user->get('role') == 'staff') {
				$ids[] = $user->get('id');
			}
		}

		//find user_permissions
		if (User::logged()->get('role') == 'admin') {
			//each permisison for each user
		}

		return [
			'Staff' => [
				'outputUsersList' => [
					'pages'	 => $pages,
					'page'	 => $page,
					'filter' => $r['filter'],
					'screen' => $screen,
					'html'	 => H::getTemplate('pages/staff/list.php', [
						'list' => join('', $h)
							], true),
					'roles'	 => $roles
				]
			]
		];
	}

	public function add($what) {
		if (empty($what['role']) || $what['role'] == 'guest') {
			$what['role'] = 'staff';
		}

		return parent::add($what);
	}

}
