<?php
		$hostname = '91.208.99.2:3354';
		$username = 'forpinpo_zxsdewe';
		$password = 'ksjdfh87236sjdhf58764387';
		$dbname = 'forpinpo_backlin';
		$connreadwrite = mysql_connect($hostname, $username, $password);
		
		//Apologise if didn't connect properly
		if(!$connreadwrite) {
		
		//clear the data arrays
		$_POST = array();
		$_GET = array();
		$_SESSION = array();
		$_SESSION[SUCCESS] = '';
		$_SESSION[ERROR] = 'Could not establish connection';
		header("location: backlinks.php");
		exit;
		}

		##Select the DB to use
		$db = mysql_select_db ($dbname);

		//Apologise if it didn't work
		if(!$db) {
		
		//clear the data arrays
		$_POST = array();
		$_GET = array();
		$_SESSION = array();
		$_SESSION[SUCCESS] = '';
		$_SESSION[ERROR] = 'Could not connect to DB';
		header("location: backlinks.php");
		exit;
		}	
?>