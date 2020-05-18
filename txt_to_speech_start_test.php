<?php 
session_start();
include("admin/include/config.php");
$getData = "SELECT * FROM text_for_user WHERE status = 1 AND id = '".$_REQUEST['txt_id']."' ORDER BY id ASC";
$getDataExc = mysqli_query($conn, $getData);

$txtArr = array();
while ($getDataRow = mysqli_fetch_assoc($getDataExc)) {
    $txtArr[] = $getDataRow;
}

$isOnceSubmit = mysqli_num_rows($getUserTextDataExc);
if (isset($_POST['save_btn'])) {
    /*echo "<pre>";
    print_r($_POST);
    echo "<pre>";
    print_r($_SESSION['ocaWebUser']);*/
    $user_txt = mysqli_escape_string($conn, $_POST['hidden_txt_from_user']);
    $og_txt = mysqli_escape_string($conn, $_POST['txt_field']);
    $percent = 100;
    $similar_char = similar_text($user_txt, $og_txt, $percent); 
    $user_id = $_SESSION['ocaWebUser']['id'];

    $checkTextQry = "SELECT * FROM text_to_speech_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' AND text_id = '".$_REQUEST['txt_id']."' ";
    $checkTextExc = mysqli_query($conn, $checkTextQry);
    if(mysqli_num_rows($checkTextExc) <= 0){
        $insertQry = "INSERT INTO text_to_speech_by_user SET text_id = '".$_REQUEST['txt_id']."', text_by_user = '".$user_txt."', user_id = '".$user_id."', matching_characters = '".$similar_char."', matching_accuracy_result = '".$percent."' ";
        $insertExc = mysqli_query($conn, $insertQry);
        $txt_by_user_ins_id = mysqli_insert_id($conn);
    }

    if ($insertExc) {
        echo "<script>alert('Your record has been saved successfully!');</script>";
        echo "<script>window.location.href='txt_to_speech.php';</script>";
    }
}

include("include/header.php"); 
?>

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg overlay2">
        <h3>Text to speech</h3>
    </div>
    <!-- bradcam_area_end -->
    <div class="about_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <?php  
                    if(isset($_SESSION['ocaWebUser']) && !empty($_SESSION['ocaWebUser']))
                    {?>
                        
                        
                       <?php // echo "<pre>";
                       $cnt = 1;
                        foreach ($txtArr as $key => $value) {
                            
                            // print_r($value);
                            $getUserTextData = "SELECT * FROM text_to_speech_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' AND text_id = '".$value['id']."' ORDER BY id ASC ";
                            $getUserTextDataExc = mysqli_query($conn, $getUserTextData);
                            $getUserTextDataRow1 = mysqli_fetch_assoc($getUserTextDataExc);
                            if (mysqli_num_rows($getUserTextDataExc) > 0) {
                                if ($getUserTextDataRow1['matching_accuracy_result'] == 0 || ($getUserTextDataRow1['matching_accuracy_result'] > 0 && $getUserTextDataRow1['matching_accuracy_result'] <= 49) ) {
                                    $result_color = "red";
                                }elseif ($getUserTextDataRow1['matching_accuracy_result'] >= 50 && $getUserTextDataRow1['matching_accuracy_result'] <= 75) {
                                    $result_color = "orange";
                                }elseif ($getUserTextDataRow1['matching_accuracy_result'] >= 76 && $getUserTextDataRow1['matching_accuracy_result'] <= 100) {
                                    $result_color = "green";
                                }
                                ?>
                                <h2 align="center"><?php echo (isset($getUserTextDataRow1['matching_accuracy_result'])) ? "Your accuracy is: <strong style='color:".$result_color."'>".number_format($getUserTextDataRow1['matching_accuracy_result'],2)." %</strong>" : ""; ?></h2><br><br>
                            <?php }else{ ?>
                                <form action="" method="post" autocomplete="off">
                                    <h3>Please read carefully the below text. After read properly, you have to speak out.</h3><br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <textarea rows="5" name="txt_field" id="txt_field" class="form-control" readonly><?php echo $value['text_content']; ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="hidden_txt_from_user" id="note-textarea" value="">
                                            <button type="button" id="start-record-btn" class="btn btn-danger" title="Start Recording">Start Recognition</button>
                                            <button type="button" id="pause-record-btn" class="btn btn-warning" title="Pause Recording">Pause Recognition</button>
                                            <button type="submit" name="save_btn" class="btn btn-info" title="Save Note">Save Note</button> 
                                            <br>
                                        </div>
                                    </div>
                                    <p id="recording-instructions">Press the <strong>Start Recognition</strong> button and allow access.</p><br><br>
                                </form>
                            <?php }
                        $cnt++; } ?>
                        
                    <?php }else{ ?>
                        <h2>Please do Login first.</h2>
                    <?php } ?>
                    <div style="height: 120px">
                        <a href="txt_to_speech.php" class="btn btn-dark" style="width: 150px;height: auto;"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    

<?php include("include/footer.php"); ?>
    
    
</body>

</html>

<?php  
if(!isset($_SESSION['ocaWebUser']) && empty($_SESSION['ocaWebUser']))
{?>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#loginBtn").trigger("click");
    });



</script>
<?php } ?>
