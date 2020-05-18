<?php 
session_start();
include("admin/include/config.php");
$getData = "SELECT * FROM speech_for_user WHERE status = 1 ORDER BY id DESC ";
$getDataExc = mysqli_query($conn, $getData);

$speechArr = array();
while ($getDataRow = mysqli_fetch_assoc($getDataExc)) {
    $speechArr[] = $getDataRow;
}

include("include/header.php"); 
?>

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg overlay2">
        <h3>Speech to speech</h3>
    </div>
    <!-- bradcam_area_end -->
    <div class="about_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <?php  
                    if(isset($_SESSION['ocaWebUser']) && !empty($_SESSION['ocaWebUser']))
                    {?>
                        <h3>Test your speech understanding capability.</h3>
                        <?php 
                        $cnt = 1;
                        foreach ($speechArr as $key => $value) { 
                            
                            $getUserSpeechData = "SELECT * FROM speech_to_speech_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' AND speech_id = '".$value['id']."' ORDER BY id DESC";
                            $getUserSpeechDataExc = mysqli_query($conn, $getUserSpeechData);
                            $getUserSpeechDataRow1 = mysqli_fetch_assoc($getUserSpeechDataExc);
                        
                        
                            if ($getUserSpeechDataRow1['matching_accuracy_result'] == 0 || ($getUserSpeechDataRow1['matching_accuracy_result'] > 0 && $getUserSpeechDataRow1['matching_accuracy_result'] <= 49) ) {
                                $result_color = "red";
                            }elseif ($getUserSpeechDataRow1['matching_accuracy_result'] >= 50 && $getUserSpeechDataRow1['matching_accuracy_result'] <= 75) {
                                $result_color = "orange";
                            }elseif ($getUserSpeechDataRow1['matching_accuracy_result'] >= 76 && $getUserSpeechDataRow1['matching_accuracy_result'] <= 100) {
                                $result_color = "green";
                            }
                            ?>
                            <br><br>
                        
                            <form action="" method="post" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-1">
                                        <b style="color: black"><?php echo $cnt.")"; ?></b>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="text-align:center;font-weight: bold;font-size: 22px"><?php echo ucwords($value['speech_title']); ?></span>
                                    </div>
                                    <?php if(mysqli_num_rows($getUserSpeechDataExc) > 0){?>
                                    <div class="col-md-3">
                                        <h3 align="center"><?php echo (isset($getUserSpeechDataRow1['matching_accuracy_result'])) ? "Your accuracy is: <br><strong style='color:".$result_color."'>".number_format($getUserSpeechDataRow1['matching_accuracy_result'],2)." %</strong>" : ""; ?></h3>
                                    </div>
                                    <?php } else{?>
                                    <div class="col-md-3" align="center">
                                        <a href="speech_to_speech_start_test.php?spch_id=<?php echo $value['id']; ?>" class="btn btn-success" style="width: 150px;height: auto;" title="Start Test">Start</a>
                                    </div>
                                    <?php }?>
                                </div><br><br>
                            </form>
                        <?php $cnt++; }
                    }else{ ?>
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
