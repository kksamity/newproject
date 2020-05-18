<?php 
	include('admin/include/config.php');
	unset($_SESSION['ocaWebUser']);
	session_destroy();
	header("location:index.php");
	exit;

?>