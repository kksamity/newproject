<?php
include("admin/include/config.php");
/**
 * Request has Base64 Data
 * -----------------------
 * $_POST['audio'] is the Base64 encoded value of audio (WAV/MP3)
 */
if(isset($_POST['audio'])){
  $audio = base64_decode($_POST['audio']);
}

/**
 * Request has BLOB Data
 * ---------------------
 */
if(isset($_FILES['file'])){
  $audio = file_get_contents($_FILES['file']['tmp_name']);
  // print_r($audio);exit;
  
  $insertQry = "INSERT INTO uploads SET audio = '".$audio."' ";
    $insertExc = mysqli_query($conn, $insertQry);

    $getUserData = "SELECT `id` FROM `uploads` ORDER BY `id` DESC LIMIT 1";
    $getUserDataExc = mysqli_query($conn, $getUserData);
    $getUserDataRow = mysqli_fetch_assoc($getUserDataExc);
    $id = $getUserDataRow['id'];
  /*$sql = $dbh->prepare("INSERT INTO `uploads` (`audio`) VALUES(?)");
  $sql->execute(array($audio));
  
  $sql = $dbh->query("SELECT `id` FROM `uploads` ORDER BY `id` DESC LIMIT 1");
  $id = $sql->fetchColumn();*/
  
  echo "voice_master/examples/play.php?id=$id";
}
