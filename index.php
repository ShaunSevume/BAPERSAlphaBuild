<?php

// The imports
include 'config.php';
import('router');
import('controllers/cont');
import('controllers/db.cont');
import('controllers/user.cont');
import('entities/user.ent');

// The objects
$db = new DBHelper();
$router = new Router(APP_DATA['views']);
$uc = new UserController($db);

// check login
if (!$uc->loggedin()) {
	header('Location: ' . WEB_CONFIG['home'] . 'login.php');
}

// information
$data['router'] = $router;
$data['user'] = $uc->getUser($_SESSION['sid']);
$data['db'] = $db;

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo APP_DATA['app_name'].' | ' . $router->title; ?></title>

	<!-- CSS -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_CONFIG['asf']; ?>css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_CONFIG['asf']; ?>css/feather-icons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_CONFIG['asf']; ?>css/main.css">

	<!-- The Javascript -->
	<script type="text/javascript" src="<?php echo WEB_CONFIG['asf']; ?>js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo WEB_CONFIG['asf']; ?>js/popper.min.js"></script>
	<script type="text/javascript" src="<?php echo WEB_CONFIG['asf']; ?>js/bootstrap.min.js"></script>
</head>
<body>

	<?php
	$router->renderComponent('menu', $data);
	?>

	<!-- The main content -->
	<div class="col-md-10 bp-main">
		<div class="bp-title-bar">
		<?php
		echo $router->breadcrumbs;
		?>
		</div>

		<div class="bp-content">
		<?php
		$router->renderView($data);
		?>
		</div>
	</div>
	
	<!-- Branding -->
	<div class="xenon-branding">
		<a href="http://localhost/xenon/" target="_blank">
			<img src="<?php echo WEB_CONFIG['asr']; ?>images/xenon_powerby.png" alt="">
		</a>
	</div>

	<script type="text/javascript">
	$(function() {
		$('input').attr('autocomplete', 'off');
	});
	</script>
</body>
</html>