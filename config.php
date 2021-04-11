<?php

// Timezone
date_default_timezone_set('Europe/London');

// sessions
session_start();

// Backend Config
$web_config = [];
$web_config['home'] = 'http://localhost/bapers/';
$web_config['root'] = dirname(__FILE__).'/';
$web_config['asb'] = $web_config['root'].'assets/';
$web_config['asf'] = $web_config['home'].'assets/';
$web_config['asr'] = $web_config['home'].'resources/';
define('WEB_CONFIG', $web_config);
unset($web_config);

// import functionality
function import($inc) {
	include WEB_CONFIG['asb'].'includes/'.$inc.'.php';
}

// app configuration
$app_data = [];
$app_data['app_name'] = 'BAPERS';
$app_data['db_info'] = ['host' => 'localhost', 'user' => 'root', 'pass' => 'test', 'dbname' => 'bapers'];
$app_data['ftp_info'] = ['host' => 'localhost', 'user' => 'root', 'pass' => ''];
$app_data['bapers_info'] = ['host' => 'localhost', 'port' => 51257];

// views
$views = array(
	'dashboard' => array(
		'title' => 'Dashboard',
		'icon' => 'activity'
	),
	'jobs' => array(
		'title' => 'Jobs',
		'icon' => 'grid',
		'permission' => array('om', 'sm', 't'),
		'subs' => array(
			'viewjob' => array(
				'title' => 'Process Job',
				'args' => 'jid',
				'hidden' => true,
				'permission' => array('om', 'sm', 't')
			),
			'addjob' => array(
				'title' => 'Create Job',
				'permission' => array('om', 'sm', 'r')
			)
		)
	),
	'payments' => array(
		'title' => 'Payments',
		'icon' => 'dollar-sign',
		'permission' => array('om', 'sm', 'r'),
		'subs' => array(
			'recordpayment' => array(
				'title' => 'Record Payment',
				'args' => 'jid',
				'hidden' => true,
				'permission' => array('om', 'sm', 'r')
			)
		)
	),
	'customers' => array(
		'title' => 'Customers',
		'icon' => 'users',
		'permission' => array('om', 'sm', 'r'),
		'subs' => array(
			'createcustomer' => array(
				'title' => 'Create Customer',
				'permission' => array('om', 'sm', 'r')
			),
			'editcustomer' => array(
				'title' => 'Edit Customer',
				'args' => 'cid',
				'hidden' => true
			)
		)
	),
	'reports' => array(
		'title' => 'Reports',
		'icon' => 'file',
		'permission' => 'om',
		'args' => 'dir',
		'subs' => array(
			'genreport' => array(
				'title' => 'Generate Report',
				'permission' => array('om')
			),
			'automation' => array(
				'title' => 'Reports Automation',
				'permission' => array('om')
			)
		)
	),
	'admin' => array(
		'title' => 'Admin',
		'icon' => 'user-check',
		'permission' => 'om',
		'subs' => array(
			'createstaff' => array(
				'title' => 'Create Staff',
				'permission' => 'om'
			),
			'editstaff' => array(
				'title' => 'Edit Staff',
				'args' => 'sid',
				'hidden' => true
			),
			'createtasktype' => array(
				'title' => 'Create Task Type',
				'permission' => 'om',
			),
			'edittasktype' => array(
				'title' => 'Edit Task Types',
				'args' => 'ttid',
				'hidden' => true,
				'permission' => 'om'
			),
			'dbbackup' => array(
				'title' => 'Database Backup',
				'permission' => 'om'
			),
			'dbrestore' => array(
				'title' => 'Database Restore',
				'hidden' => true,
				'permission' => 'om'
			)
		)
	)
);

$app_data['views'] = $views;
$app_data['debug_mode'] = false;
$app_data['list_pagination_limit'] = 10;
$app_data['alert_iteration_mins'] = 15;
$app_data['job_late_alert_hours'] = 2;

define('APP_DATA', $app_data);
unset($views);
unset($app_data);

?>