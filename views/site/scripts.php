<?php

//TODO: move it to find.php

J::loadScripts([
	'folder'	 => [
		'javascript'
	],
	'async'		 => true,
	'minify'	 => false,
	'frameworks' => [
		'jquery' => strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? null : true
	]
]);

if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
?>
<script type="text/javascript" src="//localhost/escrow.com/../vendor/jquery-1.12.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="//localhost/escrow.com/../vendor/font-awesome-4.6.3/css/font-awesome.min.css"/>
<?php }