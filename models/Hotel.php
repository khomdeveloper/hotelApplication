<?php

/**
 * Description of Hotel
 *
 * @author valera261104
 */
class Hotel extends M {

	public static function action($r, $dontdie = false) { //Hotel controller
		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()]; 

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		Permission::check(get_called_class(), $com, $user, $hotel);

		if ($com == 'set_current') {

			self::required([
				'hotel_id' => true
					], $r);

			self::setCurrent($r['hotel_id']);

			$user = User::getBy([
						'id' => $user->get('id')
			]);

			$hotel = self::getCurrent();

			return [
				'Hotel' => [
					'onChange' => array_merge($user->getUserData(), [
						'email'		 => $user->fget('email'),
						'login'		 => $user->fget('login'),
						'files'		 => join('', $user->uploadedFilesHTML()),
						'logotypes'	 => !empty($hotel) && $hotel->get(['image' => 0])
								? '<img src="' . (B::baseURL() . $hotel->get(['image' => 0])) . '?s='. mt_rand(1, 10000) .'" alt="" style="max-height:40px; float:right;"/>'
								: ''
					])
				]
			];
		} elseif ($com == 'get_logo') {

			$hotel = self::getCurrent();

			return [
				'Hotel' => [
					'showSmallLogo' => [
						'logotypes' => !empty($hotel) && $hotel->get(['image' => 0])
								? '<img src="' . (B::baseURL() . $hotel->get(['image' => 0])) . (empty($r['cash']) ? '?s='. mt_rand(1, 10000) : '') .'" alt="" style="max-height:40px; float:right;"/>'
								: ''
					]
				]
			];
		} elseif ($com == 'set') {

			self::required([
				'id' => true
					], $r);

			$set = $r;

			unset($set['id']);

			Staff::getBy([
				'hotel_id'	 => self::getBy([
					'id'		 => $r['id'],
					'_notfound'	 => true
				])->set($set)->get('id'),
				'user_id'	 => User::logged()->get('id'),
				'role'		 => ['admin',
					'staff'],
				'_notfound'	 => T::out([
					'admin_access_denied' => [
						'en' => 'Admin access denied',
						'ru' => 'Отказано в административном доступе'
					]
				])
			]);

			return [
				'Site' => [
					'showResponseMessage' => [
						'message' => T::out([
							'changes_saved' => [
								'en' => 'Changes saved',
								'ru' => 'Изменения сохранены'
							]
						])
					]
				]
			];
		} elseif ($com == 'load') {

			$hotel = self::getCurrent();

			$output = $hotel->toArray(['id' => true]);

			$output['title'] = [
				'preview'	 => $hotel->fget('title'),
				'value'		 => $hotel->get('title', 'raw')
			];
			$output['description'] = [
				'preview'	 => $hotel->fget('description'),
				'value'		 => $hotel->get('description', 'raw')
			];
			$output['address'] = [
				'preview'	 => T::getBy([
					'id' => $hotel->get('address')
				])->get(T::getLocale()),
				'value'		 => $hotel->get('address')
			];

			$image = $hotel->get(['image' => 0]);

			return [
				'Site'	 => [
					'fillForm' => [
						'_Hotel' => $output
					]
				],
				'Hotel'	 => [
					'showLogo' => [
						'uploader'	 => H::getTemplate(false, [
							'id'	 => $hotel->get('id'),
							'object' => 'Hotel',
							'label'	 => T::out([
								'change_hotel_logo' => [
									'en' => 'Change logo',
									'ru' => 'Изменить логотип'
								]
							])
								], true, false, Environment::addTheSlash(Environment::get('vh2015')) . 'templates/form/uploader.php'),
						'html'		 => empty($image)
								? ''
								: '<img src="' . Environment::get('self_url') . $image . '"/><div class="area_selector"></div>',
						'id'		 => $hotel->get('id')
					]
				]
			];
		} elseif ($com == 'cut') {

			$image_file = $hotel->get(['image' => 0]);

			if (empty($image_file)) {
				throw new Exception('Image not found');
			}

			$path = Environment::addTheSlash(Environment::get('site_root')) . $image_file;

			if (!file_exists($path)) {
				throw new Exception('Image not found');
			}

			require Environment::addTheSlash(B::getSelfPath()) . 'Image.php';

			$img = Image::getBy([
						'name' => $path
			]);

			$img->crop([
				'left'	 => round($img->get('width') * $r['left'] / 100),
				'top'	 => round($img->get('height') * $r['top'] / 100),
				'width'	 => round($img->get('width') * $r['width'] / 100),
				'height' => round($img->get('height') * $r['height'] / 100),
			])->save($path);

			return [
				'Hotel' => [
					'showLogo' => [
						'html'	 => '<img src="' . Environment::get('self_url') . $hotel->get(['image' => 0]) . '?s=' . mt_rand(1, 10000) . '"/><div class="area_selector"></div>',
						'id'	 => $hotel->get('id')
					],
					'showSmallLogo' => [
						'logotypes' => !empty($hotel) && $hotel->get(['image' => 0])
								? '<img src="' . (B::baseURL() . $hotel->get(['image' => 0])) . '?s='. mt_rand(1, 10000) .'" alt="" style="max-height:40px; float:right;"/>'
								: ''
					]
				]
			];
		} elseif ($com == 'upload') {
			self::upload($r);
		}
	}

	public static function getCurrent() {

		if (!empty($_SESSION['current_hotel'])) {
			return self::getBy([
						'id' => $_SESSION['current_hotel']
			]);
		} else {
			return self::getBy([
						'id' => User::logged()->get('hotel')
			]);
		}
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

		$image = $obj->get(['image' => 0]);

		M::jsonp([
			'parent.A.run' => [
				'Hotel' => [
					'showLogo'		 => [
						'html'	 => empty($image)
								? ''
								: '<img src="' . Environment::get('self_url') . $image . '?s=' . mt_rand(1, 10000) . '"/><div class="area_selector"></div>',
						'id'	 => $obj->get('id')
					],
					'showSmallLogo'	 => [
						'logotypes' => !empty($image)
								? '<img src="' . (B::baseURL() . $image) . '?s='. mt_rand(1, 10000) . '" alt="" style="max-height:40px; float:right;"/>'
								: ''
					]
				]
		]]);
	}

	public static function restrictedOperations($operation = null) {

		$o = array_merge(parent::restrictedOperations(), [
			'upload' => [
				'nothing' => 'nothing'
			]
		]);

		return empty($operation)
				? $o
				: (empty($o[$operation])
						? false
						: $o[$operation]);
	}

	public static function setCurrent($id) {

		$hotel = self::getBy([
					'id' => $id
		]);

		$_SESSION['current_hotel'] = empty($hotel)
				? Hotel::getBy([
					'id'	 => '!=0',
					'_order' => '`order`'
				])->get('id')
				: $hotel->get('id');

		return $_SESSION['current_hotel'];
	}

	public static function f() {
		return [
			'title'		 => 'Hotel',
			'datatype'	 => [
				'title'			 => [
					'T' => [
						'id' => true
					]
				],
				'description'	 => [
					'T' => [
						'id' => true
					]
				],
				'address'		 => [
					'T' => [
						'id' => true
					]
				],
				'country'		 => [
					'Countries' => [
						'id' => true
					]
				]
			],
			'create'	 => [
				'title'			 => "bigint unsigned default null comment 'Hotel name'",
				'description'	 => "bigint unsigned default null comment 'Hotel description'",
				'address'		 => "bigint unsigned default null comment 'Street address'", //T
				'postcode'		 => "tinytext comment 'Post code'",
				'city'			 => "tinytext comment 'City'",
				'country'		 => "bigint unsigned default null comment 'Country'",
				'phone'			 => "tinytext comment 'Phone'",
				'logo_pos'		 => "tinytext comment 'Logo position'",
				'order'			 => "int default 0 comment 'Sort order'",
				'visibility'	 => "enum('visible','hidden') default 'hidden' comment 'Moderation status'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
