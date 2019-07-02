<?php

/**
 * User on the field (projection of U into the game
 */
class User extends U {

	public static function logged($reload = false) {

		$user = self::getBy();

		if (empty($user)) {
			M::ok([
				'User' => [
					'reload' => [
						'data' => true
					]
				]
			]);
		}

		return empty($reload)
				? $user
				: User::getBy([
					'id' => $user->get('id')
		]);
	}

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];


		if ($com == 'isOnline') {

			$user = self::getBy();

			if (empty($user)) {
				return [
					'O'		 => [
						'stop' => []
					],
					//reload page if lost login session - rude but effective
					'User'	 => [
						'reload' => []
					]
				];
			} else {

				//!!! не могу напрямую $user потому что тогда YII пытается insert

				self::getBy([
					'id' => $user->get('id')
				])->set([
					'online' => time()
				]);

				//here we check which is necessary to Run
				$userAction = UserAction::getBy([
							'user_id' => $user->get('id')
				]);



				if (!empty($userAction)) {

					$action = Action::getBy([
								'id' => $userAction->get('action_id')
							])->get('action');

					$userAction->remove();
				} else {
					$action = [];
				}

				return array_merge([
					'O' => [
						'reStart'	 => [],
						'ping'		 => [],
						'set'		 => [
							'frequency' => S::getBy([
								'key'		 => 'Frequency of checking if user is online',
								'_notfound'	 => [
									'key'	 => 'Frequency of checking if user is online',
									'val'	 => '7000'
								]
							])->get('val')
						],
					]
						], $action);
			}
		} elseif ($com === 'set_email') {

			self::required([
				'email' => true
					], $r);

			$email = filter_var(trim($r['email']), FILTER_VALIDATE_EMAIL);

			$user = self::getBy([
						'id' => self::logged()->get('id'),
			]);

			if (empty($email)) {
				M::ok([
					'User' => [
						'error' => [
							'message'	 => T::out([
								'email_expected_WER2' => [
									'en'		 => 'Email address expected instead {{what}}',
									'ru'		 => 'Ожидается адрес электронной почты вместо {{what}}',
									'_include'	 => [
										'what' => $r['email']
									]
								]
							]),
							'callback'	 => [
								'setConfirmed' => [
									'sent_to_email' => $user->get('email')
								]
							]
						]
					]
				]);
			}

			$code = md5(microtime() . 'salt_ypvjh');

			$user->set([
				'email'		 => $email,
				'confirmed'	 => $code
			])->sendEmailConfirmation($code);

			//send the same code to another email

			return [
				'User' => [
					'setConfirmed' => [
						'sent_to_email' => $email
					]
				]
			];
		} elseif ($com == 'change_pass') {

			self::required([
				'new_pass'		 => true,
				'repeated_pass'	 => true
					], $r);

			if ($r['new_pass'] !== $r['repeated_pass']) {
				throw new Exception(T::out([
					'repeated_pass_is_not_the_same' => [
						'en' => 'Passes are not the same',
						'ru' => 'Пароли не совпадают'
					]
				]));
			}

			$md5 = S::getBy([
						'key'		 => 'md5 pass',
						'_notfound'	 => [
							'key'		 => 'md5 pass',
							'val'		 => 1,
							'comment'	 => 'Store pass as md5 (0,1)'
						]
					])->d('val');

			$user = self::logged('reload');

			if (isset($r['resetPassword']) && $user->get('token') == $r['resetPassword']) {

				$user = $user->set([
					'token' => 0
				]);

				unset($_SESSION['password_reset']);
			} elseif (empty($r['pass']) || ($user->get('pass') !== (empty($md5)
							? $r['pass']
							: md5($r['pass'])))) {
				throw new Exception(T::out([
					'current_pass_incorrect' => [
						'en' => 'Incorrect current pass',
						'ru' => 'Неправильный текущий пароль'
					]
				]));
			} else {
				throw new Exception(T::out([
					'current_pass_incorrect_or_exipred_reset_link' => [
						'en' => 'Incorrect current pass or expired reset link',
						'ru' => 'Неправильный текущий пароль или устаревшая ссылка восстановления'
					]
				]));
			}

			$user = $user->set([
				'pass' => empty($md5)
						? $r['new_pass']
						: md5($r['new_pass']),
			]);

			//TODO: send email if this is completed

			return [
				'User' => [
					'profileInit' => array_merge($user->getUserData(), [
						'email'		 => $user->fget('email'),
						'login'		 => $user->fget('login'),
						'files'		 => join('', $user->uploadedFilesHTML()),
						'message'	 => T::out([
							'changes_saved' => [
								'en' => 'Changes saved',
								'ru' => 'Изменения сохранены'
							]
						])
					])
				]
			];
		} elseif ($com == 'change_login') {

			self::required([
				'login'	 => true,
				'pass'	 => true
					], $r);

			$email = filter_var(trim($r['login']), FILTER_VALIDATE_EMAIL);

			if (empty($email)) {
				throw new Exception(T::out([
					'email_expected_WER2' => [
						'en'		 => 'Email address expected instead {{what}}',
						'ru'		 => 'Ожидается адрес электронной почты вместо {{what}}',
						'_include'	 => [
							'what' => $r['login']
						]
					]
				]));
			}

			$user = self::logged('reload');

			$md5 = S::getBy([
						'key'		 => 'md5 pass',
						'_notfound'	 => [
							'key'		 => 'md5 pass',
							'val'		 => 1,
							'comment'	 => 'Store pass as md5 (0,1)'
						]
					])->d('val');

			if ($user->get('pass') !== (empty($md5)
							? $r['pass']
							: md5($r['pass']))) {
				throw new Exception(T::out([
					'current_pass_incorrect' => [
						'en' => 'Incorrect current pass',
						'ru' => 'Неправильный текущий пароль'
					]
				]));
			}

			$code = md5(microtime() . 'salt_ypvjh');

			$user = $user->set([
						'login'		 => $email,
						'confirmed'	 => $code
					])->sendEmailConfirmation($code);

			return [
				'User' => [
					'profileInit' => array_merge($user->getUserData(), [
						'email'		 => $user->fget('email'),
						'login'		 => $user->fget('login'),
						'files'		 => join('', $user->uploadedFilesHTML()),
						'message'	 => T::out([
							'changes_saved' => [
								'en' => 'Changes saved',
								'ru' => 'Изменения сохранены'
							]
						])
					])
				]
			];
		} elseif ($com == 'set') {

			$user = self::logged('reload');

			if (!empty($r['email'])) {

				$email = filter_var(trim($r['email']), FILTER_VALIDATE_EMAIL);

				if (empty($email)) {
					throw new Exception(T::out([
						'email_expected_WER2' => [
							'en'		 => 'Email address expected instead {{what}}',
							'ru'		 => 'Ожидается адрес электронной почты вместо {{what}}',
							'_include'	 => [
								'what' => $r['email']
							]
						]
					]));
				}

				$code = md5(microtime() . 'salt_ypvjh');

				$user->set([
					'email'		 => $email,
					'confirmed'	 => $code
				])->sendEmailConfirmation($code);

				//TODO: some message that email reconfirmed
				//send the same code to another email

				/*
				  return [
				  'User' => [
				  'setConfirmed'	 => [
				  'sent_to_email' => $email
				  ],
				  'profileInit'	 => [
				  'name'	 => $user->fget('name'),
				  'email'	 => $user->fget('email'),
				  'files'	 => join('', $user->uploadedFilesHTML())
				  ]
				  ]
				  ]; */
			};

			//check birthday
			//check country
			//check gender
			//locale?
			//filter
			$f = [];
			foreach ($r as $key => $val) {
				if (in_array($key, [
							'first_name',
							'last_name',
							'phone',
							'birthday',
							'gender',
							'country',
							'locale'])) {
					$f[$key] = $val;
				}
			}

			if (isset($r['first_name']) || isset($r['last_name'])) {
				$f['name'] = join(' ', [
					isset($r['first_name'])
							? $r['first_name']
							: $user->get('first_name'),
					isset($r['last_name'])
							? $r['last_name']
							: $user->get('last_name')
				]);
			}

			$user = $user->set($f);

			return [
				'User' => [
					'profileInit' => array_merge($user->getUserData(), [
						'email'		 => $user->fget('email'),
						'login'		 => $user->fget('login'),
						'files'		 => join('', $user->uploadedFilesHTML()),
						'message'	 => T::out([
							'changes_saved' => [
								'en' => 'Changes saved',
								'ru' => 'Изменения сохранены'
							]
						])
					])
				]
			];
		} elseif ($com === 'profile') {

			$user = User::logged('reload');

			return [
				'User' => [
					'profileInit' => array_merge($user->getUserData(), [
						'email'	 => $user->fget('email'),
						'login'	 => $user->fget('login'),
						'files'	 => join('', $user->uploadedFilesHTML())
					])
				]
			];
		} elseif ($com === 'confirm_email') {

			self::required([
				'code' => true
					], $r);

			self::getBy([
				'id'		 => self::logged()->get('id'),
				'confirmed'	 => $r['code'],
				'_notfound'	 => true
			])->set([
				'confirmed' => 'ok'
			]);

			//TODO: return some message

			M::ok([
				'success' => 'ok'
			]);
		} elseif ($com === 'check_confirmed') {

			$user = self::getBy([
						'id' => self::logged()->get('id')
			]);

			$confirmed_email = $user->get('confirmed_email');

			if ($confirmed_email) { //email present
				return [
					'User' => [
						'setConfirmed' => [
							'confirmed_email' => $confirmed_email
						]
					]
				];
			} elseif (S::getBy([
						'key'		 => 'need_email_confirmation',
						'_notfound'	 => [
							'key'		 => 'need_email_confirmation',
							'val'		 => 0,
							'comment'	 => '0/1 notneed/need to confirm email'
						]
					])->d('val') && $user->get('email') && (!$user->get('confirmed') || $user->get('confirmed') == '0')) { //need to send
				$code = md5(microtime() . 'salt_ypvjh');

				$user->set([
					'confirmed' => $code
				])->sendEmailConfirmation($code);

				//return confirmation dialog
				return [
					'User' => [
						'setConfirmed' => [
							'sent_to_email' => $user->get('email')
						]
					]
				];
			} elseif (S::getBy([
						'key'		 => 'need_email_confirmation',
						'_notfound'	 => [
							'key'		 => 'need_email_confirmation',
							'val'		 => 0,
							'comment'	 => '0/1 notneed/need to confirm email'
						]
					])->d('val') && $user->get('email') && $user->get('confirmed')) { //already sent
				return [
					'User' => [
						'setConfirmed' => [
							'sent_to_email' => $user->get('email')
						]
					]
				];
			} elseif (!$user->get('email')) {

				return [
					'User' => [
						'setConfirmed' => [
							'need_email' => $user->get('email')
						]
					]
				];
			} else {
				return [
					'no_check' => 'true'
				];
			}
		} elseif ($com === 'delete_uploaded') {

			self::required([
				'image_id' => true
					], $r);

			$user = User::logged('reload')->set([
				'photo' => null
			]);

			F::removeSimilar($user->get('folder'), $r['image_id']);

			return [
				'User' => [
					'changeAvatar' => [
						'image' => B::baseURL() . 'images/user_logo.png'
					]
				]
			];
		} elseif ($com === 'update_balance') {
			$user = User::getBy([
						'id' => User::logged()->get('id')
					])->cash();

			return [
				'User' => [
					'showBalance' => [
						'balance' => $user->get('money')
					]
				]
			];
		} elseif ($com === 'choose_avatar') {
			return [
				'User' => [
					'chooseAvatar' => [
						'files' => join('', User::getBy([
									'id' => self::logged()->get('id')
								])->uploadedFilesHTML())
					]
				]
			];
		} elseif ($com === 'set_avatar') {

			self::required([
				'image_id' => true
					], $r);

			$user = User::getBy([
						'id' => User::logged()->get('id')
			]);

			return [
				'User' => [
					'changeAvatar' => [
						'image' => $user->set([
							'photo' => $user->get([
								'image' => $r['image_id']
							])
						])->cash()->get('photo')
					]
				]
			];
		} elseif ($com === 'no_avatar') {

			User::getBy([
				'id' => self::logged()->get('id')
			])->set([
				'photo' => ''
			])->cash();

			return [
				'User' => [
					'changeAvatar' => [
						'image' => 'images/user_logo.png'
					]
				]
			];
		} elseif ($com == 'grant') {

			self::required([
				'role'		 => true,
				'user_id'	 => true
					], $r);

			$user = User::logged('reload');

			if ($user->get('role') != 'admin') {
				throw new Exception(T::out([
					'you_are_not_admin' => [
						'en' => 'Only admin can grant role',
						'ru' => 'Только админ может предоставить доступ'
					]
				]));
			}

			if (!in_array($r['role'], ['guest',
						'staff',
						'admin'])) {
				throw new Exception(T::out([
					'error_uncknown_role' => [
						'en' => 'Uncknown user role',
						'ru' => 'Неизвестные права пользователя'
					]
				]));
			}

			if (self::getBy([
						'id'		 => $r['user_id'],
						'_notfound'	 => true
					])->set([
						'role' => $r['role']
					])->get('id') == $user->get('id')) {
				throw new Exception(T::out([
					'can_not_grant_to_self' => [
						'en' => 'Can not grant any permission to self account',
						'ru' => 'Невозможно предоставить права для своего аккаунта'
					]
				]));
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
		} elseif ($com === 'upload') {

			self::upload($r);
		}

		return parent::action($r, $dontdie);
	}

	public static function uploadFail($e) {
		M::jsonp([
			'parent.A.run' => [
				'Site' => [
					'showResponseMessage' => [
						'error'		 => 1,
						'message'	 => $e->getMessage()
					]
				]
			]
		]);
	}

	public static function uploadSuccess($r, $obj) {
		M::jsonp([
			'parent.A.run' => [
				'User' => [
					'changeAvatar' => [
						'image' => $obj->set([
							'photo' => $obj->get([
								'image' => 0
							])
						])->cash()->get('photo')
					]
				]
		]]);
	}

	public function getConfirmURL() {
		return B::setProtocol('https:', Yii::app()->params['app_source_path']) . '?email_confirm=' . $this->get('confirmed');
	}

	public function getName() {
		return $this->get('first_name') . ' ' . $this->get('last_name');
	}

	public function getRecallTemplate() {
		return 'email/recall';
	}

	public function getAccessURL() {
		return B::setProtocol('https:', Yii::app()->params['app_source_path']) . '?token=' . $this->getAccessToken();
	}

	public function sendEmailConfirmation($code) {

		if (S::getBy([
					'key'		 => 'need_email_confirmation',
					'_notfound'	 => [
						'key'		 => 'need_email_confirmation',
						'val'		 => 0,
						'comment'	 => '0/1 notneed/need to confirm email'
					]
				])->d('val')) {

			Mail::send([
				'to'		 => $this->get('email'),
				'from_name'	 => 'TEST',
				'reply_to'	 => 'admin@khom.biz',
				'priority'	 => 0,
				'subject'	 => T::out([
					'email_confirmation_subject' => [
						'en' => 'Email confirmation on TEST',
						'ru' => 'Подтверждение адреса электронной почты на TEST'
					]
				]),
				'html'		 => H::getTemplate('email/confirmation', [
					'name'	 => 'TEST',
					'code'	 => $code,
					'link'	 => $this->getConfirmURL()
						], 'addDelimeters')
			]);
		}

		return $this;
	}

	public static function removeCash() {
		//die('stop');
		unset($_SESSION['pin']);
		parent::removeCash();
	}

	public static function confirmEmail($code) {
		$user = User::getBy([
					'confirmed' => $code
		]);

		return empty($user)
				? false
				: $user->set([
					'confirmed' => 'ok'
				])->cash();
	}

	public function getUserData() {
		return [
			'id'			 => $this->get('id'),
			'net'			 => $this->fget('net'),
			'uid'			 => $this->d('uid'),
			'photo'			 => $this->get('photo'),
			'name'			 => $this->fget('name'),
			'first_name'	 => $this->fget('first_name'),
			'last_name'		 => $this->fget('last_name'),
			'phone'			 => $this->fget('phone'),
			'gender'		 => $this->fget('gender'),
			'country'		 => $this->fget('country'),
			'birthday'		 => $this->fget('birthday'),
			'role'			 => $this->get('role'),
			'hotel'			 => $this->get('hotel'),
			'resetPassword'	 => empty($_SESSION['password_reset'])
					? 0
					: $_SESSION['password_reset']
		];
	}

	public function get($what, $data = null) {

		if ($what == 'birthday') {
			return explode(' ', $this->birthday)[0];
		}

		if ($what == 'hotel') {

			if (!empty($_SESSION['current_hotel'])) {
				return $_SESSION['current_hotel'];
			}

			$link_with = UserHotel::getBy([
						'user_id' => $this->get('id')
			]);

			if (!empty($link_with)) {
				Hotel::setCurrent($link_with->get('hotel_id'));
				return $link_with->get('hotel_id');
			} else {
				return Hotel::getBy([
							'id'	 => '!=0',
							'_order' => '`order`'
						])->get('id');
			}
		}

		if ($what == 'role') { //$data expected hotel_id
			
			$link_with = UserHotel::getBy(array_merge(empty($data)
									? (
									empty($_SESSION['current_hotel'])
											? [
										'user_id' => $this->get('id')
											]
											: [
										'user_id'	 => $this->get('id'),
										'hotel_id'	 => $_SESSION['current_hotel']
											]
									)
									: [
								'user_id'	 => $this->get('id'),
								'hotel_id'	 => $data								
			],[
				'_return'	 => [0 => 'object']
			]));

			if (!empty($link_with)) {				
				if (empty($_SESSION['current_hotel'])) {
					Hotel::setCurrent(current($link_with)->get('hotel_id'));
				}

				$role = 'visitor';
				
				//var_dump($link_with);
				
				foreach ($link_with as $link){
					
					if ($link->get('role') == 'admin'){
						return 'admin';
					}
					
					if ($link->get('role') == 'staff'){
						$role = 'staff';
					}
					
					if ($link->get('role') == 'guest' && !in_array($role,[
						'admin','staff'
					])) {
						$role = 'guest';
					}
					
					if ($link->get('role') == 'past' && !in_array($role,[
						'admin',
						'staff',
						'guest'
					])) {
						$role ='past';
					}
					
				}

				return $role;
			}

			return 'visitor';
		}

		return parent::get($what, $data);
	}

	public static function f() {
		return M::extend(parent::f(), [
					'title'	 => 'User',
					'create' => [
						'online' => "bigint unsigned default null comment 'Last user visit timestamp'",
					//'role'	 => "enum('admin','staff','guest') default 'guest' comment 'User role in system'"
					]
		]);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
