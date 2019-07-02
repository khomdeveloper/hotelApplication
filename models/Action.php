<?php

/* */

class Action extends M {

	public static function f() {
		return [
			'title'	 => 'Possible actions on observe',
			'create' => [
				'action' => "text comment 'Action description in JSON'",
				'key'	 => "tinytext comment 'Action shortkey'"
			]
		];
	}

	public function setFor($user_id) {

		UserAction::getBy([
			'user_id'	 => $user_id,
			'action_id'	 => $this->get('id'),
			'_notfound'	 => [
				'user_id'	 => $user_id,
				'action_id'	 => $this->get('id')
			]
		]);

		return $this;
	}

	public static function standart($key) {


		if ($key == 'update_milestones') {
			return Action::getBy([
						'key'		 => 'update_milestones',
						'_notfound'	 => [
							'key'	 => 'update_milestones',
							'action' => json_encode([
								'Milestone' => [
									'updateList' => [
										'data' => 'none'
									]
								]
							])
						]
			]);
		} elseif ($key == 'update_deals') {
			return Action::getBy([
						'key'		 => 'update_deals',
						'_notfound'	 => [
							'key'	 => 'update_deals',
							'action' => json_encode([
								'Agreement' => [
									'list' => [
										'data' => 'none'
									]
								]
							])
						]
			]);
		} elseif ($key == 'update_balance'){
			return Action::getBy([
						'key'		 => 'update_balance',
						'_notfound'	 => [
							'key'	 => 'update_balance',
							'action' => json_encode([
								'User' => [
									'updateBalance' => [
										'data' => 'none'
									]
								]
							])
						]
			]);
		}
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
