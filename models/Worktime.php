<?php

/**
 * Breaks and work intervals
 *
 * @author valera261104
 */
class Worktime extends M {

	public static function action($r, $dontdie = false) {

		$com = empty($r[get_called_class()])
				? false
				: $r[get_called_class()];

		$user = User::logged();
		$hotel = Hotel::getCurrent();
		Permission::check(get_called_class(), $com, $user, $hotel);
		
		if ($com == 'worktime') { //get worktime for service
		} elseif ($com == 'set') {

			if (empty($r['id'])) {
				self::required([
					'weekday'	 => true,
					'begin'		 => true,
					'end'		 => true,
					'service_id' => true
						], $r);

				$set = $r;
				$set['_notfound'] = $r;

				$set['_notfound']['reason'] = T::getBy([
							'key'		 => '_new',
							'_notfound'	 => [
								'key'	 => md5(microtime()),
								'en'	 => '',
								'ru'	 => ''
							]
						])->get('id');

				$worktime = self::getBy($set);
			} else {

				$worktime = self::getBy([
							'id'		 => $r['id'],
							'_ntofound'	 => true
						])->set($r);
			}



			$service = Service::getBy([
						'id'		 => $worktime->get('service_id'),
						'_notfound'	 => true
			]);

			return [
				'Service' => [
					'showWorkTime' => [
						'html'		 => self::getWorkTime($service),
						'service_id' => $service->get('id')
					]
				]
			];
		} elseif ($com == 'remove') {

			self::required([
				'id' => true
					], $r);

			$service = Service::getBy([
						'id' => self::getBy([
							'id' => $r['id']
						])->remove()->get('service_id')
			]);

			return [
				'Service' => [
					'showWorkTime' => [
						'html'		 => self::getWorkTime($service),
						'service_id' => $service->get('id')
					]
				]
			];
		} elseif ($com == 'load') {

			self::required([
				'id' => true
					], $r);

			return [
				'Worktime' => [
					'load' => [
						'data' => self::getBy([
							'id'		 => $r['id'],
							'_notfound'	 => true
						])->getData()
					]
				]
			];
		}
	}

	public function getData() {

		$output = $this->toArray([
			'id' => true
		]);

		$output['reason'] = [
			'preview'	 => T::getBy([
				'id' => $this->get('reason')
			])->get(T::getLocale()),
			'value'		 => $this->get('reason')
		];

		return $output;
	}

	public static function getWorkTime($service) {

		$intervals = self::getBy([
					'service_id' => $service->get('id'),
					'_return'	 => [0 => ' object'],
					'_order'	 => '`order` DESC'
		]);

		$line_template = H::getTemplate('pages/service/worktime_line.php', [], true);

		$h = [];

		if (!empty($intervals)) {

			foreach ($intervals as $interval) {

				$weekday = $interval->get('weekday');

				if (strpos($weekday, ',')) {
					$days = explode(',', $weekday);
				} else {
					$days = [$weekday];
				}

				$h1 = [];
				foreach ($days as $day) {
					$h1[] = T::out([
								'week_day_' . $day => [
									'en' => $day,
									'ru' => $day
								]
					]);
				}

				$h[] = H::parse($line_template, [
							'day'	 => join(', ', $h1),
							'type'	 => T::out([
								'interval_type_' . $interval->get('type') => [
									'en' => $interval->get('type'),
									'ru' => $interval->get('type')
								]
							]),
							'id'	 => $interval->get('id'),
							'begin'	 => $interval->get('begin'),
							'end'	 => $interval->get('end')
								], true);
			}
		}

		return H::getTemplate('pages/service/worktime.php', [
					'list' => join('', $h)
						], true);
	}

	public static function getDays($begin = null, $end = null) {

		$norm = [
			'SU' => 'Sun',
			'MO' => 'Mon',
			'TU' => 'Tue',
			'WD' => 'Wed',
			'TH' => 'Thu',
			'FR' => 'Fri',
			'SA' => 'Sat'
		];
		
		if (empty($begin)) {
			return array_values($norm);
		}
		
		if (is_array($begin)) {

			$res = [];

			foreach ($begin as $el) {
				$res[] = isset($norm[$el])
						? $norm[$el]
						: $el;
			}

			return $res;
		}

		if (!is_array($begin) && empty($end)) {
			return [isset($norm[$el])
						? $norm[$el]
						: $el];
		}

		if (!is_array($begin) && !empty($end)) { //SU - MO
			$begin = isset($norm[$begin])
					? $norm[$begin]
					: $begin;
			$end = isset($norm[$end])
					? $norm[$end]
					: $end;

			$begin_n = array_search($begin, array_values($norm));
			$end_n = array_search($end, array_values($norm));

			if ($end_n > $begin_n) {

				$res = [];

				for ($i = $begin_n; $i <= $end_n; $i++) {
					$res[] = array_values($norm)[$i];
				}

				return $res;
			}
		}

		return array_values($norm);
	}

	public function outWorktime() {

		$s = [];


		if ($this->get('type') == 'break') {

			$s[] = $this->get('reason')
					? T::getBy([
						'id' => $this->get('reason')
					])->get(T::getLocale())
					: T::out([
						'break_(in service description)' => [
							'en' => 'Break',
							'ru' => 'Перерыв'
						]
			]);
		}

		$weekday = $this->get('weekday');

		if (empty($weekday)) {
			$s[] = T::out([
						'daily' => [
							'en' => 'daily',
							'ru' => 'ежедневно'
						]
			]);
		} else {

			if (strpos($weekday, '-') !== false) { //MO - WE
				$a = explode('-', $weekday);
				$b = [];
				foreach ($a as $val) {
					$b[] = T::out([
								'weekday_' . trim($val) => [
									'en' => trim($val),
									'ru' => trim($val)
								]
					]);
				}

				$s[] = join(' - ', $b);
			}

			if (strpos($weekday, ',') !== false) { //MO,TU,WD
				$a = explode(',', $weekday);
				$b = [];
				foreach ($a as $val) {
					$b[] = T::out([
								'weekday_' . trim($val) => [
									'en' => trim($val),
									'ru' => trim($val)
								]
					]);
				}

				$s[] = join(', ', $b);
			}
		}

		$a = explode(':', $this->get('begin'));

		$s[] = $a[0] . ':' . $a[1];

		$s[] = '-';

		$a = explode(':', $this->get('end'));

		$s[] = $a[0] . ':' . $a[1];

		return join(' ', $s);
	}

	public function get($what, $data = false) {

		if ($what == 'days') {

			$weekday = $this->get('weekday');

			if (empty($weekday)) {
				return self::getDays();
			} else {

				if (strpos($weekday, '-')) {
					$a = explode('-', $weekday);
					return self::getDays(trim($a[0]), trim($a[1]));
				} elseif (strpos($weekday, ',')) {
					return self::getDays(explode(',', $weekday));
				} else {
					return self::getDays($weekday);
				}
			}
		}

		return parent::get($what, $data);
	}

	public function inInterval($datetime) {

		$days = $this->get('days');

		$day = $datetime->format('D');
		
		if (in_array($day, $days)) {

			$timestamp = (new DateTime($datetime->format('H:i:s')))->getTimestamp();
			$begin = (new DateTime($this->get('begin')))->getTimestamp();
			$end = (new DateTime($this->get('end')))->getTimestamp();

			if ($begin <= $timestamp && $timestamp <= $end){
				return true;
			} else {
				return false;
			}
			
			
		} else {
			return false;
		}
	}

	public static function f() {
		return [
			'title'		 => 'Working time',
			'datatype'	 => [
				'service_id' => [
					'Service' => [
						'id' => 'ON DELETE CASCADE'
					]
				],
				'reason'	 => [
					'T' => [
						'id' => true
					]
				]
			],
			'create'	 => [
				'weekday'	 => "tinytext comment 'Day of week SU,MO,TU...'",
				'service_id' => "bigint unsigned default null comment 'Service'",
				'reason'	 => "bigint unsigned default null comment 'Reason'",
				'begin'		 => "time default null comment 'Interval begin'",
				'end'		 => "time default null comment 'Interval end'",
				'type'		 => "enum('work','break') default 'work' comment 'Interval status'",
				'order'		 => "int default 0 comment 'Order'"
			]
		];
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
