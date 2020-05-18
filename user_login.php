<?php 
include("admin/include/config.php");

if (isset($_POST['sign_in'])) {
	// echo "<pre>";print_r($_POST);exit;

	$username = (isset($_POST['username']) && $_POST['username']!='' )?$_POST['username']:"";
    $password = (isset($_POST['pass_key']) && $_POST['pass_key']!='' )?$_POST['pass_key']:""; 
     
    if($password!="" &&  $username!='')
    {        
        $query = " SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."' AND status=1";
       
        $result = mysqli_query($conn,$query);
        $row = mysqli_fetch_assoc($result);   
        if(mysqli_num_rows($result) > 0 )
        {      
            $_SESSION['ocaWebUser'] = $row;
            header('Location: index.php');
            exit;
        }
        else
        {
            echo "<script>alert('Invalid username / password')</script>";
            echo "<script>window.location.href='index.php';</script>";
            exit;
        }
    } 
}
?>