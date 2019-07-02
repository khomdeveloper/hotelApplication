<?php

class Countries extends M {

	public static function fields($which = null) {

		$fields = [
			'title'		 => 'Countries',
			'required'	 => [
				'code'		 => 0,
				'title_en'	 => 0,
				'title_ru'	 => 0
			],
			'blank'		 => [
				'code'		 => 'UN',
				'title_en'	 => 'Other',
				'title_ru'	 => 'Прочие'
			],
			'create'	 => [
				'code'		 => "tinytext comment 'ISO country code'",
				'title_en'	 => "tinytext comment 'English title'",
				'title_ru'	 => "tinytext comment 'Russian title'",
				'order' => "int default 0 comment 'Sort order'"
			]
		];

		return empty($which)
				? $fields
				: (isset($fields[$which])
						? $fields[$which]
						: $fields);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

}
