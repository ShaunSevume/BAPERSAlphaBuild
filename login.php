<?php

// the imports
include 'config.php';
import('controllers/cont');
import('controllers/db.cont');
import('controllers/user.cont');

$db = new DBHelper();
$uc = new UserController($db);

// check if the user is already logged in
if ($uc->loggedin()) {
	header('Location: ' . WEB_CONFIG['home'] . 'index.php?v=dashboard');
}

// check if the user submitted the form
if (isset($_POST['staff-login'])) {
	$sid = $_POST['staff-id'];
	$sp = $_POST['staff-pass'];
	$uc->login($sid, $sp);
	if ($uc->success) {
		header('Location: ' . WEB_CONFIG['home'] . 'index.php?v=dashboard');
	} else {
		$e = 'Username or password is incorrect, please retry.';
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Bapers</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_CONFIG['asf']; ?>css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_CONFIG['asf']; ?>css/feather-icons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_CONFIG['asf']; ?>css/login.css">
</head>
<body>

	<div class="bg-image" style="background-image: url(<?php echo WEB_CONFIG['asr']; ?>images/login_bg.jpg);"></div>

	<div class="wrapper">
		<div class="container">
			<h3 class="title">
				Bloomsbury's Automated Process Execution Recording System
			</h3>
			<div class="content">
				<div class="bp-description">
				BIPL is a photographic laboratory which carries out specific tasks on its customers' work. BIPL is tasked with
meeting its clients deadlines as well as upholding the quality of work they produce. Presently BIPL technicians 
are able to perform around 30 standard tasks. Each Job BIPL receives, is assigned an urgency level. Urgent Jobs 
have a 6 hour deadline while normal jobs can be completed within 24. However the customer may request an even 
stricter deadline which will have a higher cost. 
				</div>
				<form class="bp-login-form" method="post">
					<?php
					if (isset($e)) {
						echo '<div style="padding:10px;margin-bottom:20px;background:#dc3545;color:#fff;border-radius:5px;">' . $e . '</div>';
					}
					?>
					<label>Staff ID</label>
					<input class="form-control" type="text" name="staff-id" autocomplete="off">
					<label>Password</label>
					<input class="form-control" type="password" name="staff-pass">
					<button class="btn btn-primary" type="submit" name="staff-login">Login</button>
				</form>
			</div>

			<div class="branding">
				<a href="http://localhost/xenon/" target="_blank"><img src="<?php echo WEB_CONFIG['asr']; ?>images/xenon_powerby_white.png" height="150px"></a>
			</div>
		</div>
	</div>


	<script type="text/javascript" src="<?php echo WEB_CONFIG['asf']; ?>js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo WEB_CONFIG['asf']; ?>js/popper.min.js"></script>
	<script type="text/javascript" src="<?php echo WEB_CONFIG['asf']; ?>js/bootstrap.min.js"></script>
</body>
</html>