<?php 
session_start();
include("admin/include/config.php");
$getData = "SELECT * FROM text_for_user WHERE status = 1 ORDER BY id ASC";
$getDataExc = mysqli_query($conn, $getData);

$txtArr = array();
while ($getDataRow = mysqli_fetch_assoc($getDataExc)) {
    $txtArr[] = $getDataRow;
}

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

    $insertQry = "INSERT INTO text_to_speech_by_user SET text_id = '".$getDataRow['id']."', text_by_user = '".$user_txt."', user_id = '".$user_id."', matching_characters = '".$similar_char."', matching_accuracy_result = '".$percent."' ";
    $insertExc = mysqli_query($conn, $insertQry);
    $txt_by_user_ins_id = mysqli_insert_id($conn);

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
                        <h3>Please read carefully the below text. After read properly, you have to speak out.</h3><br>
                        <input type="hidden" name="hidden_txt_counter[]" id="hidden_txt_counter" value="<?php echo count($txtArr); ?>">
                        <?php 
                        $cnt = 1;
                        foreach ($txtArr as $key => $value) {
                            
                            // print_r($value);
                            $getUserTextData = "SELECT * FROM text_to_speech_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' AND text_id = '".$value['id']."' ";
                            $getUserTextDataExc = mysqli_query($conn, $getUserTextData);
                            $getUserTextDataRow1 = mysqli_fetch_assoc($getUserTextDataExc);
                            
                            if ($getUserTextDataRow1['matching_accuracy_result'] == 0 || ($getUserTextDataRow1['matching_accuracy_result'] > 0 && $getUserTextDataRow1['matching_accuracy_result'] <= 49) ) {
                                $result_color = "red";
                            }elseif ($getUserTextDataRow1['matching_accuracy_result'] >= 50 && $getUserTextDataRow1['matching_accuracy_result'] <= 75) {
                                $result_color = "orange";
                            }elseif ($getUserTextDataRow1['matching_accuracy_result'] >= 76 && $getUserTextDataRow1['matching_accuracy_result'] <= 100) {
                                $result_color = "green";
                            }?>
                            
                            <form action="" method="post" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-1 pt-5">
                                        <b style="color: black"><?php echo $cnt.")"; ?></b>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea rows="5" name="txt_field[]" id="txt_field<?php echo $cnt; ?>" class="form-control" readonly><?php echo $value['text_content']; ?></textarea>
                                    </div>
                                    <?php if(mysqli_num_rows($getUserTextDataExc) > 0){?>
                                    <div class="col-md-3 pt-5">
                                        <h3 align="center"><?php echo (isset($getUserTextDataRow1['matching_accuracy_result'])) ? "Your accuracy is: <br><strong style='color:".$result_color."'>".number_format($getUserTextDataRow1['matching_accuracy_result'],2)." %</strong>" : ""; ?></h3>
                                    </div>
                                    <?php } else{?>
                                    <div class="col-md-3 pt-5" align="center">
                                        <a href="txt_to_speech_start_test.php?txt_id=<?php echo $value['id']; ?>" class="btn btn-success" style="width: 150px;height: auto;" title="Start Test">Start</a>
                                    </div>
                                    <?php }?>
                                </div>
                                <br><br>
                            </form>
                            
                        <?php $cnt++; } ?>
                    <?php }else{ ?>
                        <h2>Please do Login first.</h2>
                    <?php } ?>
                    <div style="height: 120px"></div>
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
