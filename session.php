<?php

	session_start();
	
	require_once 'class.user.php';
	$session = new User();
	
	if(!$session->is_logged_in())
	{
		$session->redirect('index.php');
	}
?>