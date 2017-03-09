<?php
	require_once('session.php');
	$user_logout = new User();
	
	$user_logout->logout();
	$user_logout->redirect('index.php');
?>