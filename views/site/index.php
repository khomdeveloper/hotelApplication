<?php
M::session();

unset($_SESSION['confirmed']); //this session check if some state is confirmed

$_SESSION['404_error'] = !H::getHRURL();

if (!empty($_REQUEST['locale'])) {
	$_SESSION['locale'] = $_REQUEST['locale'];
}

$user = User::getBy();

//check for autologin
if (empty($user) && !empty($_REQUEST['token'])) {
	$user = User::getBy([
				'token' => $_REQUEST['token']
	]);

	if (!empty($user)) {

		$code = md5(microtime());

		$user = $user->set([
					'token' => $code
				])->cash();

		$_SESSION['password_reset'] = $code;
	}
}

//check email
if (!empty($_REQUEST['email_confirm'])) {
	$u = User::confirmEmail($_REQUEST['email_confirm']);
	if (empty($user) && !empty($u)) {
		$user = $u;
	}
}


//$serviceAvailable = Landing::isAvailable();

$serviceAvailable = true;

if (empty($serviceAvailable)) { //if service is not available all redirects to landing
	$_REQUEST['page'] = 'landing';
	$_SESSION['landing'] = true;
} else {
	unset($_SESSION['landing']);
}


if (empty($user)) {
	if (empty($_REQUEST['page']) ||
			in_array($_REQUEST['page'], [
				'main',
				'staff',
				'hotel',
				'service',
				'user',
				'visits',
				'order',
				'orders',
				'pin',
				'review'])) {
		$_REQUEST['page'] = 'login';
	}

	if ($_REQUEST['page'] == 'landing' && !empty($serviceAvailable)) {
		$_REQUEST['page'] = 'login';
	}
} else {

	if (!empty($_SESSION['password_reset'])) {
		$_REQUEST['page'] = 'user';
	} elseif (empty($_REQUEST['page'])) {

		if ($user->get('role') == 'guest') {
			$_REQUEST['page'] = 'order';
		} elseif (in_array($user->get('role'), ['staff',
					'admin'])) {
			$_REQUEST['page'] = $user->get('role') == 'staff'
					? 'visits'
					: 'staff';
		} else {
			$_REQUEST['page'] = 'help';
		}
	} elseif ($_REQUEST['page'] == 'landing' && !empty($serviceAvailable)) {
		$_REQUEST['page'] = 'help';
	}
}

//check for invitation
if (isset($_REQUEST['invite'])) {
	$_SESSION['invitation'] = $_REQUEST['invite'];
}

class Path {

	public static function get() {
		return dirname(dirname(Yii::app()->request->scriptFile));
	}

}

include 'scripts.php';
?>
<div id="fb-root"></div>
<?php
if (isset($_REQUEST['admin'])) {
	if ($_REQUEST['admin'] == 'unsession') {
		T::removeCash();
		User::removeCash();
		//print_r($_SESSION);
	}
}

if (A::isAdmin()) {
	include Environment::get('vh2015') . '/admin.php';
	include 'dialog.php';
} else {

	include 'counter.php';

	//Visit::view();
	//T::export('export_t');
	?>
	<img src="<?php B::outURL(); ?>images/landing.jpg" class="background_image" style="display:none;"/>

	<div class="pages on_login"> 
		<?php
		include 'pages/header/header.php';
		H::getPage('login');
		H::getPage('contacts');
		H::getPage('help');
		H::getPage('staff');
		H::getPage('user');
		H::getPage('landing');
		H::getPage('hotel');
		H::getPage('service');
		H::getPage('visits');
		H::getPage('order');
		H::getPage('orders');
		H::getPage('review');
		H::getPage('pin');
		?>
	</div>
	<div class="main_preloader" style="display:none;">
		<?php include 'preloader.php'; ?>
	</div>

	<div class="main_dialog_host"></div>
	<?php include 'pages/footer/footer.php'; ?>
	<script type="text/javascript">

		var Index = {
			init: function() {
				var that = this;
				if (!window.A){
					setTimeout(function(){
						that.init();
					},100);
					return false;
				}
				
				A.w(['$'], function() {
					$(document).ready(function() {

						//load styles
						A.w(['B'], function() {
							
							B.loadFiles([
								'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
								'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css',
								'<?php echo Environment::get('vh2015_url'); ?>css/usial.css',
								'<?php B::outUrl(); ?>css/local.css'
							], function() {
								$('.main_preloader_2').fadeOut();
								window.StylesReady = true;
							});

							//load awesome
							B.loadStyle('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css', function() {

							});

							B.loadRemote('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', function() {
								window.BOOTSTRAP_loaded = true;
							});
						});


						A.documentReady = true;
						A.w(['B', 'T'], function() {
							T.initLocaleMenu({
								current: '<?php echo T::getLocale(); ?>',
								host: $('.choose_locale')
							});
						});

	<?php
	if (in_array($_REQUEST['page'], [
				'help',
				'landing',
				'login'])) { //public pages 
		?>
							A.w(['B', 'Site'], function() {
								Site.initMenu('<?php echo $_REQUEST['page']; ?>');
							});
	<?php } else { ?>
							A.w(['Site'], function() {
								Site.switchToAfterLogin = '<?php echo $_REQUEST['page']; ?>';
							});
	<?php } ?>

						A.w(['B', 'U', 'User', 'StylesReady', 'Login'], function() {
							User.setDialogCSS();
	<?php if (empty($user) && $_REQUEST['page'] == 'help') { ?>
								Login.needToCallLoginOnShow = true;
	<?php } else { ?>
								User.login();
	<?php } ?>
						});

					});
				});

<?php /* ?>
//translated cash 
				A.w(['T'], function() {
					T.data = <?php echo json_encode(T::all()); ?>;
				});
 <?php */ ?>
			}
		}; //of index

		Index.init();
	</script>

	<?php
}