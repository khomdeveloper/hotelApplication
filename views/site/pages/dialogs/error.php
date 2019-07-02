<?php

if ($_GET['error'] == 'amount_expected_not_zero') {
	T::w([
		'amount_expected_not_zero' => [
			'en' => 'Positive value expected',
			'ru' => 'Ожидается положительная величина'
		]
	]);
}