<?php

/**
 * Description of Service
 *
 * @author valera261104
 */
class Service extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		Permission::check(get_called_class(), $com, $user, $hotel);

		if ($com == 'set') {

			self::required([
				'id' => true
					], $r);

			$set = $r;

			unset($set['id']);

			$service = self::getBy([
						'id'		 => $r['id'],
						'_notfound'	 => true
			]);

			return [
				'Site'		 => [
					'showResponseMessage' => [
						'message' => T::out([
							'changes_saved' => [
								'en' => 'Changes saved',
								'ru' => 'Изменения сохранены'
							]
						])
					]
				],
				'Service'	 => [
					empty($r['dontUpdateForm'])
							? 'showService'
							: 'nothing'	 => $service->set($set)->getData(),
					'showPermissions'														 => [
						'html' => Permission::getServicePermissions($service)
					],
					'updateServiceTitle'													 => [
						'id'		 => $service->get('id'),
						'title'		 => $service->fget('title'),
						'visibility' => $service->get('visibility')
					]
				/* 'initServiceList'														 => [
				  'html'			 => self::getTree(),
				  'open_parent'	 => $service->get('parent_id')
				  ? $service->get('parent_id')
				  : $service->get('id'),
				  'open_child'	 => $service->get('parent_id')
				  ? $service->get('id')
				  : 0
				  ] */
				]
			];
		} elseif ($com == 'list') {

			if (!empty($r['id'])) {
				$service = self::getBy([
							'id'		 => $r['id'],
							'_notfound'	 => true
				]);
			}

			return empty($r['id'])
					? [
				'Service' => [
					'initServiceList' => [
						'html'	 => self::getTree($hotel, empty($r['id'])
										? false
										: $r['id']),
						'id'	 => empty($r['id'])
								? 0
								: $r['id']
					]
				]
					]
					: [
				'Service' => [
					'initServiceList'	 => [
						'html'	 => self::getTree($hotel, empty($r['id'])
										? false
										: $r['id']),
						'id'	 => empty($r['id'])
								? 0
								: $r['id']
					],
					'showService'		 => $service->getData(),
					'changeTitle'		 => empty($r['list'])
							? false
							: [
						'id'	 => $r['id'],
						'title'	 => $service->fget('title'),
							],
					'showPermissions'	 => [
						'html' => Permission::getServicePermissions($service)
					],
					'showWorkTime'		 => [
						'html'		 => Worktime::getWorkTime($service),
						'service_id' => $service->get('id')
					]
				]
			];
		} elseif ($com == 'load') {

			self::required([
				'id' => true
					], $r);

			$service = self::getBy([
						'id'		 => $r['id'],
						'_notfound'	 => true
			]);

			return [
				'Service' => [
					'showService'		 => $service->getData(),
					'changeTitle'		 => empty($r['list'])
							? false
							: [
						'id'	 => $r['id'],
						'title'	 => $service->fget('title'),
							],
					'showPermissions'	 => [
						'html' => Permission::getServicePermissions($service)
					],
					'showWorkTime'		 => [
						'html'		 => Worktime::getWorkTime($service),
						'service_id' => $service->get('id')
					]
				]
			];
		} elseif ($com == 'selection') {
			return self::getSelectionTree();
		} elseif ($com == 'remove') {

			self::required([
				'id' => true
					], $r);

			$service = self::getBy([
						'id'		 => $r['id'],
						'_notfound'	 => true
					])->remove();

			$t = T::getBy([
						'id' => $service->get('title', 'raw')
			]);

			if (!empty($t)) {
				$t->remove();
			}

			$t = T::getBy([
						'id' => $service->get('description', 'raw')
			]);

			if (!empty($t)) {
				$t->remove();
			}


			$children = self::getBy([
						'parent_id'	 => $service->get('id'),
						'_return'	 => [
							0 => 'object'
						]
			]);

			if (!empty($children)) {
				foreach ($children as $child) {
					$child->remove();
				}
			}

			$parent = $service->get('parent_id')
					?
					self::getBy([
						'id'		 => $service->get('parent_id'),
						'_notfound'	 => true
					])
					: false;

			return [
				'Site'		 => [
					'showResponseMessage' => [
						'message' => T::out([
							'serviсe_removed' => [
								'en' => 'Service removed',
								'ru' => 'Сервис удален'
							]
						])
					]
				],
				'Service'	 => [
					'showService'		 => !empty($parent)
							? $parent->getData()
							: null,
					'showPermissions'	 => [
						'html' => !empty($parent)
								? Permission::getServicePermissions($parent)
								: ''
					],
					'initServiceList'	 => [
						'html'	 => self::getTree($hotel, empty($parent)
										? false
										: $parent->get('id')),
						'id'	 => empty($parent)
								? false
								: $parent->get('id')
					]
				]
			];
		} elseif ($com == 'create') {

			self::required([
				'type' => true
					], $r);

			//check integrity of parent_id
			if (!empty($r['parent_id'])) {

				self::getBy([
					'id'		 => $r['parent_id'],
					'hotel_id'	 => $hotel->get('id'),
					'_notfound'	 => T::out([
						'parent_service_should_have_the_same_hotel' => [
							'en' => 'Parent service shoulв be linked with the same hotel',
							'ru' => 'Родительский сервис должен относиться к тому же отелю'
						]
					])
				]);
			}

			$newService = Service::getBy([
						'id'		 => '_new',
						'_notfound'	 => [
							'parent_id'		 => empty($r['parent_id'])
									? null
									: $r['parent_id'],
							'type'			 => $r['type'],
							'visibility'	 => 'hidden',
							'hotel_id'		 => $hotel->get('id'),
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
			]);

			return [
				'Service' => [
					'showService'		 => $newService->getData(),
					'showPermissions'	 => [
						'html' => Permission::getServicePermissions($newService)
					],
					'initServiceList'	 => [
						'html'	 => self::getTree($hotel, empty($r['parent_id'])
										? false
										: $r['parent_id']),
						'id'	 => empty($r['parent_id'])
								? false
								: $r['parent_id']
					]
				]
			];
		} elseif ($com == 'upload') {
			self::upload($r);
		} else {
			return [];
		}
	}

	public function outWorktime() {

		$worktimes = Worktime::getBy([
					'service_id' => $this->get('id'),
					'_order'	 => '`order` DESC',
					'_return'	 => [
						0 => 'object'
					]
		]);

		$h = [];

		if (empty($worktimes)) {
			return T::out([
						'worktime_always' => [
							'en' => 'Daily, 24 hours, no breaks',
							'ru' => 'Ежедневно, круглосуточно без перерыва'
						]
			]);
		} else {
			foreach ($worktimes as $worktime) {
				$h[] = $worktime->outWorktime();
			}
			return join('<br/>', $h);
		}
	}

	//check if service is available in specified datetime
	public function isAvailable($datetime) {

		if (Worktime::getBy([
					'service_id' => $this->get('id'),
					'_return'	 => 'count'
				]) == 0) { // 24/7
			return $this;
		}

		$breaks = Worktime::getBy([
					'service_id' => $this->get('id'),
					'type'		 => 'break',
					'_return'	 => [0 => 'object']
		]);

		if (!empty($breaks)) {
			foreach ($breaks as $break) {
				if ($break->inInterval($datetime)) {
					throw new Exception($break->outWorktime());
				}
			}
		}

		$worktimes = Worktime::getBy([
					'service_id' => $this->get('id'),
					'type'		 => 'work',
					'_return'	 => [0 => 'object']
		]);

		if (!empty($worktimes)) {
			foreach ($worktimes as $worktime) {
				if ($worktime->inInterval($datetime)) {
					return $this;
				}
			}
		}

		throw new Exception($this->outWorktime());
	}

	public static function restrictedOperations($operation = null) {

		$o = array_merge(parent::restrictedOperations(), [
			'list'	 => [
				'Service' => [
					'initServiceList' => [
						'html' => ''
					]
				]
			],
			'load'	 => [
				'nothing' => 'nothing'
			],
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
				'Service' => [
					'showLogo' => [
						'html'	 => empty($image)
								? ''
								: '<img src="' . Environment::get('self_url') . $image . '?s=' . mt_rand(1, 10000) . '"/>',
						'id'	 => $obj->get('id')
					]
				]
		]]);
	}

	public function getData() {

		$data = $this->toArray(['id' => true]);
		$data['title'] = [
			'preview'	 => $this->fget('title'),
			'value'		 => $this->get('title', 'raw')
		];
		$data['description'] = [
			'preview'	 => $this->fget('description'),
			'value'		 => $this->get('description', 'raw')
		];

		return array_merge($data, $this->getLogo());
	}

	public function getLogo() {

		$image = $this->get(['image' => 0]);

		$show_logo = [
			'uploader'	 => H::getTemplate(false, [
				'id'	 => $this->get('id'),
				'object' => 'Service',
				'label'	 => T::out([
					'change_service_background' => [
						'en' => 'Change background',
						'ru' => 'Изменить фон'
					]
				])
					], true, false, Environment::addTheSlash(Environment::get('vh2015')) . 'templates/form/uploader.php'),
			'html'		 => empty($image)
					? ''
					: '<img src="' . Environment::get('self_url') . $image . '"/>',
			'id'		 => $this->get('id')
		];

		return $show_logo;
	}

	//only for selector list
	public function outputChildrenServices($hotel, $level = 0) {

		$services = Service::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'parent_id'	 => $this->get('id'),
					'visibility' => 'visible',
					'_return'	 => [0 => 'object']
		]);

		$h = [];

		$space = '';
		for ($i = 0; $i < $level; $i++) {
			$space .= '&nbsp;&nbsp;';
		}

		if (!empty($services)) {
			foreach ($services as $service) {
				if (in_array($service->get('type'), [
							'category',
							'list'
						])) {
					$h[] = '<option value="null" disabled="disabled" value="null" style="font-weight:bold; font-size:1.1rem;">' . $space . $service->fget('title') . '</option>';
					$h[] = $service->outputChildrenServices($hotel, $level + 1);
				} else {
					$h[] = '<option value="' . $service->get('id') . '">' . $space . $service->fget('title') . ' (' . $service->get('price') . ' EUR)</option>';
				}
			}
			return join('', $h);
		} else {
			return '';
		}

		
	}

	public static function getSelectionTree($parent_id = null) {

		//TODO:remake this

		$services = self::getBy([
					'parent_id'	 => empty($parent_id)
							? 'is null'
							: $parent_id,
					'_order'	 => '`order`',
					'_return'	 => [0 => 'object']
		]);

		$h = ['<ul>'];

		foreach ($services as $record) {
			if ($record->get('type') == 'category') {
				$h[] = '<li><span class="cp select_Service id_' . $record->get('id') . '">' . $record->get('title') . '</span>';
				$h[] = self::getSelectionTree($record->get('id'));
				$h[] = '</li>';
			} else {
				$h[] = '<li class="cp select_Service id_' . $record->get('id') . '">' . $record->get('title') . '</li>';
			}
		}

		$h[] = '</ul>';

		return join('', $h);

		/*
		  $root = self::getBy([
		  'type'		 => 'category',
		  'parent_id'	 => empty($parent_id)
		  ? 'is null'
		  : $parent_id,
		  '_order'	 => '`order`',
		  '_return'	 => [0 => 'object']
		  ]);

		  $h = ['<ul>'];
		  foreach ($root as $record) {

		  $h[] = '<li><span class="cp select_Service id_' . $record->get('id') . '">' . $record->get('title') . '</span>';

		  $children = self::getBy([
		  'parent_id'	 => $record->get('id'),
		  '_order'	 => '`order`',
		  '_return'	 => [
		  0 => 'object'
		  ]
		  ]);

		  if (!empty($children)) {
		  $h[] = '<ul>';
		  foreach ($children as $record) {
		  $h[] = '<li class="cp select_Service id_' . $record->get('id') . '">' . $record->get('title') . '</li>';
		  }
		  $h[] = '</ul>';
		  }

		  $h[] = '</li>';
		  }
		  $h[] = '</li>';

		  return join('', $h); */
	}

	public static function getTree($hotel, $parent_id = null) {

		$category_template = H::getTemplate('pages/service/category_line', [], true);
		$service_template = H::getTemplate('pages/service/service_line', [], true);

		$services = self::getBy([
					'hotel_id'	 => $hotel->get('id'),
					'parent_id'	 => empty($parent_id)
							? 'is null'
							: $parent_id,
					'_order'	 => '`order`',
					'_return'	 => [0 => 'object']
		]);

		$h = [];

		foreach ($services as $service) {

			$h[] = $service->get('type') == 'service'
					? H::parse($service_template, [
						'id'		 => $service->get('id'),
						'title'		 => $service->get('title'),
						'visibility' => $service->get('visibility') == 'hidden'
								? 'opacity05'
								: ''
							], true)
					: H::parse($category_template, [
						'id'		 => $service->get('id'),
						'title'		 => $service->get('title'),
						'childern'	 => '',
						'visibility' => $service->get('visibility') == 'hidden'
								? 'opacity05'
								: ''
							], true);
		}

		if (!empty($parent_id)) {
			$h[] = H::getTemplate('pages/service/new_service', [
						'id' => $parent_id
							], true);
		}

		return empty($h)
				? ''
				: join('', $h);

		$root = self::getBy([
					'type'		 => 'category',
					'parent_id'	 => empty($parent_id)
							? 'is null'
							: $parent_id,
					'_order'	 => '`order`',
					'_return'	 => [0 => 'object']
		]);


		$h = false;

		if (!empty($root)) {
			$h = [];
			foreach ($root as $service) {

				$children = self::getBy([
							'parent_id'	 => $service->get('id'),
							'_order'	 => '`order`',
							'_return'	 => [
								0 => 'object'
							]
				]);

				$children_html = [];

				if (!empty($children)) {
					foreach ($children as $child) {
						if ($child->get('type') == 'service') {
							$children_html[] = H::parse($ch_template, [
										'id'		 => $child->get('id'),
										'title'		 => $child->get('title'),
										'visibility' => $child->get('visibility') == 'hidden'
												? 'opacity05'
												: ''
											], true);
						} else {
							$children_html[] = H::parse($sl_template, [
										'id'		 => $child->get('id'),
										'title'		 => $child->get('title'),
										'children'	 => join('', $children_html),
										'visibility' => $child->get('visibility') == 'hidden'
												? 'opacity05'
												: ''
											], true);
						}
					}
				}

				$h[] = H::parse($sl_template, [
							'id'		 => $service->get('id'),
							'title'		 => $service->get('title'),
							'children'	 => join('', $children_html),
							'visibility' => $service->get('visibility') == 'hidden'
									? 'opacity05'
									: ''
								], true);
			}
		}

		return empty($h)
				? ''
				: join('', $h);
	}

	public function getCrumbs($start_id = null) {

		if ($this->get('parent_id')) {
			return self::getBy([
						'id' => $this->get('parent_id')
					])->getCrumbs($start_id) . ' / ' . '<span class="' . ($this->get('id') != $start_id
							? 'crumb_goto_service'
							: 'crumb_active') . ' id_' . $this->get('id') . '">' . $this->fget('title') . '</span>';
		} else {
			return '<span class="goto_all_services"><i class="fa fa-arrow-left" aria-hidden="true"></i>  ' . T::out([
						'guest_service_h2_back' => [
							'en' => 'Return to all hotel services',
							'ru' => 'Вернуться ко всем сервисам отеля'
						]
					]) . '</span>' . ' / ' . '<span class="' . ($this->get('id') != $start_id
							? 'crumb_goto_service'
							: 'crumb_active') . ' id_' . $this->get('id') . '">' . $this->fget('title') . '</span>';
		}
	}

	public static function f() {
		return [
			'title'		 => 'Service',
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
				'hotel_id'		 => [
					'Hotel' => [
						'id' => 'ON DELETE CASCADE'
					]
				]
			],
			'create'	 => [
				'hotel_id'		 => "bigint unsigned default null comment 'Hotel'",
				'title'			 => "bigint unsigned default null comment 'Service name'",
				'description'	 => "bigint unsigned default null comment 'Service description'",
				'parent_id'		 => "bigint unsigned default null comment 'Parent service'",
				'order'			 => "int default 0 comment 'Sort order'",
				'price'			 => "int default 0 comment 'Price'",
				'visibility'	 => "enum('visible','hidden') default 'hidden' comment 'Moderation status'",
				'type'			 => "enum('service','category','list') default 'service' comment 'Service/category flag'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
