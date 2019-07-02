<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Permission
 *
 * @author valera261104
 */
class Permission extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		self::check(get_called_class(), $com, $user, $hotel);

		if ($com == 'set') {
			/*
			  if (User::logged()->get('role') !== 'admin') {
			  throw new Exception(T::out([
			  'admin_access_denied' => [
			  'en' => 'Admin access denied',
			  'ru' => 'Отказано в административном доступе'
			  ]
			  ]));
			  } */

			if (empty($r['id'])) { //check income data for creation
				self::required([
					'object'	 => true,
					'operation'	 => true,
					'staff_id'	 => true
						], $r);

				if (!self::permittedObjects($r['object'])) {
					throw new Exception(T::out([
						'permitted_objects' => [
							'en'		 => 'This object {{object}} is not premitted',
							'ru'		 => 'Объект {{object}} не допустим',
							'_include'	 => [
								'object' => $r['object']
							]
						]
					]));
				}

				//TODO: process multiselect
				//check controlled operations

				if (!call_user_func([
							$r['object'],
							'restrictedOperations'
								], $r['operation'])) {
					throw new Exception('Uncknown operation');
				}

				$set = [
					'staff_id'	 => $r['staff_id'],
					'operation'	 => $r['operation'],
					'object'	 => $r['object'],
					'object_id'	 => empty($r['object_id'])
							? 0
							: $r['object_id']
				];

				$permission = self::getBy([
							'staff_id'	 => $set['staff_id'],
							'operation'	 => $set['operation'],
							'object'	 => $set['object'],
							'object_id'	 => $set['object_id'],
							'_notfound'	 => $set
				]);
			} else {

				//edit  TODO: check problem with callback

				$permission = self::getBy([
							'id'		 => $r['id'],
							'_notfound'	 => true
						])->set($r);
			}


			$staff = Staff::getBy([
						'id'		 => $r['staff_id'],
						'_notfound'	 => true
			]);

			$user = User::getBy([
						'id'		 => $staff->get('user_id'),
						'_notfound'	 => true
			]);

			return $r['callback'] == 'Staff' //come from staff page
					? [
				'Staff' => [
					'showUserPermissions' => [
						'staff_id'		 => $staff->get('id'),
						'title'			 => T::out([
							'permissions_for_user' => [
								'en'		 => 'Permissions for {{user}}',
								'ru'		 => 'Разрешения для {{user}}',
								'_include'	 => [
									'user' => $user->getName()
								]
							]
						]),
						'permissions'	 => Permission::getUserPermissions($user)
					]
				]
					]
					: [
				'Service' => [
					'showPermissions' => [
						'html' => Permission::getServicePermissions(Service::getBy([
									'id' => $permission->get('object_id')
						]))
					]
				]
			];
		} elseif ($com == 'get_available_operations') {

			if (empty($r['object']) || !self::permittedObjects($r['object'])) {
				return [
					'no' => 'object'
				];
			} else {


				return [
					'Permission' => [
						'setAvailbleOperations' => [
							'html' => self::getRestrictedOperationsHTML($r)
						]
					]
				];
			}
			/*
			  } elseif ($com == 'create') { //deprecated
			  self::required([
			  'staff_id' => true
			  ], $r, 'throw');

			  if (User::logged()->get('role') !== 'admin') {
			  throw new Exception(T::out([
			  'admin_access_denied' => [
			  'en' => 'Admin access denied',
			  'ru' => 'Отказано в административном доступе'
			  ]
			  ]));
			  }

			  $permission = self::getBy([
			  'id'		 => '_new',
			  '_notfound'	 => [
			  'group'		 => 'reception',
			  'object'	 => 'Service',
			  'staff_id'	 => $r['staff_id'],
			  'object_id'	 => Service::getBy([
			  'id'		 => '!=0',
			  '_notfound'	 => [
			  'hotel_id'		 => Hotel::getCurrent()->get('id'),
			  'title'			 => T::getBy([
			  'id'		 => '_new',
			  '_notfound'	 => [
			  'key'	 => md5(microtime()),
			  'en'	 => 'New service'
			  ]
			  ])->get('id'),
			  'description'	 => T::getBy([
			  'id'		 => '_new',
			  '_notfound'	 => [
			  'key'	 => md5(microtime()),
			  'en'	 => 'New service description',
			  ]
			  ])->get('id')
			  ]
			  ])->get('id'),
			  'operation'	 => 'load'
			  ]
			  ]);

			  return [
			  'Permission' => [
			  'load' => [
			  'data' => $permission->toArray(['id' => true])
			  ]
			  ]
			  ];
			 */
		} elseif ($com == 'remove') {

			self::required([
				'id' => true
					], $r);
			/*
			  if (User::logged()->get('role') !== 'admin') {
			  throw new Exception(T::out([
			  'admin_access_denied' => [
			  'en' => 'Admin access denied',
			  'ru' => 'Отказано в административном доступе'
			  ]
			  ]));
			  }
			 */

			$permission = self::getBy([
						'id'		 => $r['id'],
						'_notfound'	 => true
					])->remove();

			$staff = Staff::getBy([
						'id'		 => $permission->get('staff_id'),
						'_notfound'	 => true
			]);

			$user = User::getBy([
						'id'		 => $staff->get('user_id'),
						'_notfound'	 => true
			]);


			return [
				'Staff'		 => empty($r['service_id'])
						? [
					'showUserPermissions' => [
						'staff_id'		 => $staff->get('id'),
						'title'			 => T::out([
							'permissions_for_user' => [
								'en'		 => 'Permissions for {{user}}',
								'ru'		 => 'Разрешения для {{user}}',
								'_include'	 => [
									'user' => $user->getName()
								]
							]
						]),
						'permissions'	 => Permission::getUserPermissions($user)
					]
						]
						: null,
				'Service'	 => empty($r['service_id'])
						? null
						: [
					'showPermissions' => [
						'html' => Permission::getServicePermissions(Service::getBy([
									'id' => $r['service_id']
						]))
					]
						]
			];
		} elseif ($com == 'get_groups') {

			return [
				'Permission' => [
					'showGroups' => [
						'html' => self::getGroups($r)
					]
				]
			];
		}
	}

	/*
	  public static function restrictedOperations($operation = null){

	  $o = array_merge(parent::restrictedOperations(), [
	  'set' => true
	  ]);

	  return empty($operation)
	  ? $o
	  : (empty($o[$operation])
	  ? false
	  : $o[$operation]);
	  } */

	public static function check($obj, $com, $user = false, $hotel = false, $id = false) {

		if (empty($user)) {
			$user = User::logged();
		}

		if (empty($hotel)) {
			$hotel = Hotel::getCurrent();
		}

		$restriction = call_user_func([
			$obj,
			'restrictedOperations'
				], $com);

		//check if com in restricted operations
		if (empty($restriction)) { //no restriction for this operation
			return true;
		}

		$staff = UserHotel::getBy([
					'user_id'	 => $user->get('id'),
					'hotel_id'	 => $hotel->get('id'),
					'role'		 => ['admin',
						'staff'],
					'_notfound'	 => is_array($restriction)
							? false
							: T::out([
								'premission_restricted_nouserlinkwithhotel' => [
									'en'		 => 'User {{user_id}} is not a staff or admin in hotel {{hotel_id}}. Permission denied!',
									'ru'		 => 'Пользователь {{user_id}} не является администратором или сотрудником отеля {{hotel_id}}. Доступ запрещен!',
									'_include'	 => [
										'user_id'	 => $user->get('id'),
										'hotel_id'	 => $hotel->get('id')
									]
								]
							])
		]);

		if (empty($staff)) {

			$restriction['Site'] = [
				'showResponseMessage' => [
					'message'	 => T::out([
						'premission_restricted_nouserlinkwithhotel' => [
							'en'		 => 'User {{user_id}} is not a staff or admin in hotel {{hotel_id}}. Permission denied!',
							'ru'		 => 'Пользователь {{user_id}} не является администратором или сотрудником отеля {{hotel_id}}. Доступ запрещен!',
							'_include'	 => [
								'user_id'	 => $user->get('id'),
								'hotel_id'	 => $hotel->get('id')
							]
						]
					]),
					'error'		 => true
				]
			];

			if ($com == 'upload') {
				M::jsonp([
					'parent.A.run' => $restriction
				]);
			} else {
				M::ok($restriction);
			}
		}

		if ($staff->get('role') == 'admin') { //admin of hotel has full access for all operations
			return true;
		}

		//check permissions for staff

		$permission = Permission::getBy([
					'staff_id'	 => $staff->get('id'),
					'object'	 => $obj,
					'operation'	 => $com,
					'_notfound'	 => is_array($restriction)
							? false
							: T::out([
								'permission_restricted_error (when action)' => [
									'en'		 => 'Operation {{com}} with object {{object}} is restricted for you',
									'ru'		 => 'Операция {{com}} с объектом {{object}} запрещена для вас.',
									'_include'	 => [
										'com'	 => $com,
										'object' => $obj
									]
								]
							])
		]);

		if (empty($permission)) {

			$restriction['Site'] = [
				'showResponseMessage' => [
					'message'	 => T::out([
						'permission_restricted_error (when action)' => [
							'en'		 => 'Operation {{com}} with object {{object}} is restricted for you',
							'ru'		 => 'Операция {{com}} с объектом {{object}} запрещена для вас.',
							'_include'	 => [
								'com'	 => $com,
								'object' => $obj
							]
						]
					]),
					'error'		 => true
				]
			];

			if ($com == 'upload') {
				M::jsonp([
					'parent.A.run' => $restriction
				]);
			} else {
				M::ok($restriction);
			}
			
		}

		//TODO: and here check the id (for services)

		return true;
	}

	public static function getRestrictedOperationsHTML($r) {
		$o = call_user_func([
			$r['object'],
			'restrictedOperations'
				], false);

		$h = [];

		if (!empty($o) && is_array($o)) {

			foreach ($o as $key => $val) {
				if (!empty($val)) {
					$h[] = '<option value="' . $key . '">' . (empty($val) || is_array($val) || $val === true
									? $key
									: $val) . '</option>';
				}
			}
		}

		return join('', $h);
	}

	public static function getGroups($r) {

		//deprecated

		$permissions = self::getBy([
					'staff_id'	 => array_keys(Staff::getBy([
								'hotel_id'	 => Hotel::getCurrent()->get('id'),
								'role'		 => [
									'admin',
									'staff'
								],
								'_return'	 => ['id' => 'object'],
					])),
					'_return'	 => [0 => 'object'],
					'_order'	 => '`group`'
		]);

		$html = [];

		if (!empty($permissions)) {

			$line = H::getTemplate('pages/staff/category_line', [], true);

			$group = [];
			foreach ($permissions as $permission) {
				if (empty($group[$permission->get('group')])) {
					$group[$permission->get('group')] = [
						'staff'	 => [],
						'class'	 => []
					];
				}

				$staff = Staff::getBy([
							'id'	 => $permission->get('staff_id'),
							'role'	 => [
								'admin',
								'staff'
							]
				]);

				if (!empty($staff)) {

					$user = User::getBy([
								'id' => $staff->get('user_id')
					]);

					if (!empty($user)) {
						$group[$permission->get('group')]['staff'][$user->get('id')] = $user->getName();
					}
				}

				//services
				if (self::permittedObjects($permission->get('object'))) {

					if ($permission->get('object_id') * 1 == 0) { //all objects of class Object
					} else { //object ids
						$Object = call_user_func([
							$permission->get('object'),
							'getBy'
								], [
							'id' => $permission->get('object_id')
						]);


						if (!empty($Object)) {

							$title = $permission->get('object') . ' ' . ($Object->get('title')
											? $Object->get('title')
											: $Object->get('id'));

							$group[$permission->get('group')]['class'][$Object->get('id')] = $title;
						}
					}
				}
			}

			print_r($group);
		}
	}

	/**
	 * return Objects under permissions
	 */
	public static function permittedObjects($object = null) {

		//TODO: get it from settings or from glob Model

		$objects = [
			'Service'		 => T::out([
				'Service_t' => [
					'en' => 'Services',
					'ru' => 'Сервисы'
				]
			]),
			'Translation'	 => T::out([
				'T_translations' => [
					'en' => 'Translations',
					'ru' => 'Переводы'
				]
			]),
			'Permission'	 => T::out([
				'Permissions_t' => [
					'en' => 'Permissions',
					'ru' => 'Допуски'
				]
			]),
			'Hotel'			 => T::out([
				'Hotel_t' => [
					'en' => 'Hotel',
					'ru' => 'Отель'
				]
			]),
			'Staff'			 => T::out([
				'UserHotel_t2' => [
					'ru' => 'Персонал',
					'en' => 'Staff'
				]
			]),
			'Guest'			 => T::out([
				'Guest_t2' => [
					'ru' => 'Посетители',
					'en' => 'Guest'
				]
			]),
			'Order'			 => T::out([
				'Order_t2' => [
					'ru' => 'Заказы',
					'en' => 'Order'
				]
			]),
			'Review'			 => T::out([
				'Reviews_t2' => [
					'ru' => 'Отзывы',
					'en' => 'Reviews'
				]
			]),
			'Worktime' => T::out([
				'Worktime_t2' => [
					'ru' => 'Время работы',
					'en' => 'Working time'
				]
			])
		];

		return empty($object)
				? $objects
				: isset($objects[$object]);
	}

	public static function f() {
		return [
			'title'		 => 'Premission',
			'datatype'	 => [
				'staff_id' => [
					'UserHotel' => [
						'id' => 'ON DELETE CASCADE'
					]
				]
			],
			'create'	 => [
				//'group'		 => "tinytext comment 'Group'",
				'staff_id'	 => "bigint unsigned default null comment 'Staff'",
				'object'	 => "tinytext comment 'Object'",
				'object_id'	 => "int unsigned default null comment 'Object id'",
				'operation'	 => "tinytext comment 'Operation'"
			]
		];
	}

	public static function getUserPermissions($user) {

		$permissions = Permission::getBy([
					'staff_id'	 => array_keys(Staff::getBy([
								'user_id'	 => $user->get('id'),
								'hotel_id'	 => Hotel::getCurrent()->get('id'),
								'_return'	 => ['id' => 'object']
					])),
					'_return'	 => [0 => 'object']
		]);

		$h1 = [];

		if (!empty($permissions)) {
			$template = H::getTemplate('pages/staff/permission_line.php', [], true);

			foreach ($permissions as $permission) {

				if (self::permittedObjects($permission->get('object')) && $permission->get('object_id') * 1 != 0) {
					$service = call_user_func([
						$permission->get('object'),
						'getBy'
							], ['id' => $permission->get('object_id')]);
				} else {
					$service = false;
				}

				$h1[] = H::parse($template, [
							'permission_id'	 => $permission->get('id'),
							'group'			 => $permission->get('group'),
							'object'		 => join(' ', [
								$permission->get('object'),
								empty($service)
										? ''
										: '"' . $service->fget('title') . '"'
							]),
							'operation'		 => $permission->get('operation')
								], true);
			}
		}

		return H::getTemplate('pages/staff/permissions', [
					'list' => join('', $h1)
						], true);
	}

	public static function getServicePermissions($service) {

		//make it multi object
		$permissions = Permission::getBy([
					'object'	 => 'Service',
					'object_id'	 => $service->get('id'),
					'_return'	 => [0 => 'object']
		]);

		//add parent permissions
		if ($service->get('parent_id')) {

			$parent = Service::getBy([
						'id' => $service->get('parent_id')
			]);

			if (!empty($parent)) {

				$parent_permissions = Permission::getBy([
							'object'	 => 'Service',
							'object_id'	 => $parent->get('id'),
							'_return'	 => [0 => 'object']
				]);

				$permissions = array_merge(empty($permissions)
								? []
								: $permissions, empty($parent_permissions)
								? []
								: $parent_permissions);
			}
		}

		//add total Class permissions

		$total_permissions = Permission::getBy([
					'object'	 => 'Service',
					'object_id'	 => 0,
					'_return'	 => [0 => 'object']
		]);

		$permissions = array_merge(empty($permissions)
						? []
						: $permissions, empty($total_permissions)
						? []
						: $total_permissions);


		$h1 = [];

		if (!empty($permissions)) {
			$template = H::getTemplate('pages/service/permission_line.php', [], true);

			foreach ($permissions as $permission) {

				$staff = Staff::getBy([
							'id'		 => $permission->get('staff_id'),
							'role'		 => [
								'staff',
								'admin'
							],
							'hotel_id'	 => Hotel::getCurrent()->get('id')
				]);

				if (!empty($staff)) {

					$user = User::getBy([
								'id' => $staff->get('user_id')
					]);

					if (!empty($user)) {
						$h1[] = H::parse($template, [
									'permission_id'	 => $permission->get('id'),
									'user'			 => $user->getName(),
									'operation'		 => $permission->get('operation')
										], true);
					}
				}
			}
		}

		return H::getTemplate('pages/service/permissions', [
					'list' => join('', $h1)
						], true);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
