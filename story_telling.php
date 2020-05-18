<?php 
session_start();
include("admin/include/config.php");
$getData = "SELECT * FROM story_for_user WHERE status = 1 ORDER BY id DESC ";
$getDataExc = mysqli_query($conn, $getData);

$storyArr = array();
while ($getDataRow = mysqli_fetch_assoc($getDataExc)) {
    $storyArr[] = $getDataRow;
}


@$submit = $_POST['process'];
@$word = $_POST['texttospeech'];

$voice = new COM("SAPI.SpVoice");

if($_SERVER["REQUEST_METHOD"] == "POST" and isset($submit) and !empty($word)){
	$voice->Speak($word);
}

if (isset($_POST['save_btn'])) {
    /*echo "<pre>";
    print_r($_POST);
    echo "<pre>";
    print_r($_SESSION['ocaWebUser']);*/
    $user_txt = mysqli_escape_string($conn, $_POST['hidden_txt_from_user']);
    $og_txt = mysqli_escape_string($conn, $_POST['texttospeech']);
    $percent = 100;
    $similar_char = similar_text($user_txt, $og_txt, $percent); 
    $user_id = $_SESSION['ocaWebUser']['id'];

    $insertQry = "INSERT INTO story_told_by_user SET story_id = '".$getDataRow['id']."', story_by_user = '".$user_txt."', user_id = '".$user_id."', matching_characters = '".$similar_char."', matching_accuracy_result = '".$percent."' ";
    $insertExc = mysqli_query($conn, $insertQry);
    $txt_by_user_ins_id = mysqli_insert_id($conn);

    if ($insertExc) {
        echo "<script>alert('Your record has been saved successfully!');</script>";
        echo "<script>window.location.href='story_telling.php';</script>";
    }
}

/*if (isset($_POST['process'])) {
    //get the text 
    $text = substr($_POST['texttospeech'], 0);
    
    //we are passing as a query string so encode it, space will become +
    $text = urlencode($text);

    //give a file name and path to store the file
    $file  = 'filename';
    $file = "audio/" . $file . ".mp3";

    //now get the content from the Google API using file_get_contents
    $mp3 = file_get_contents("http://translate.google.com/translate_tts?tl=en&q=$text");

    //save the mp3 file to the path
    file_put_contents($file, $mp3);
}
*/
include("include/header.php"); 
?>

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg overlay2">
        <h3>Story Telling</h3>
    </div>
    <!-- bradcam_area_end -->
    <div class="about_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <?php  
                    if(isset($_SESSION['ocaWebUser']) && !empty($_SESSION['ocaWebUser']))
                    {?>
                        <h3>Test your story summarization capability.</h3><br><br>
                        <?php 
                        $cnt = 1;
                        foreach ($storyArr as $key => $value) {
                            $getUserStoryData = "SELECT * FROM story_told_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' AND story_id = '".$value['id']."' ORDER BY id DESC ";
                            $getUserStoryDataExc = mysqli_query($conn, $getUserStoryData);
                            $getUserStoryDataRow1 = mysqli_fetch_assoc($getUserStoryDataExc);                         
                        
                       
                            if ($getUserStoryDataRow1['matching_accuracy_result'] == 0 || ($getUserStoryDataRow1['matching_accuracy_result'] > 0 && $getUserStoryDataRow1['matching_accuracy_result'] <= 49) ) {
                                $result_color = "red";
                            }elseif ($getUserStoryDataRow1['matching_accuracy_result'] >= 50 && $getUserStoryDataRow1['matching_accuracy_result'] <= 75) {
                                $result_color = "orange";
                            }elseif ($getUserStoryDataRow1['matching_accuracy_result'] >= 76 && $getUserStoryDataRow1['matching_accuracy_result'] <= 100) {
                                $result_color = "green";
                            }
                            ?>
                            
                            <form action="" method="post" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-1">
                                        <b style="color: black"><?php echo $cnt.")"; ?></b>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="text-align:center;font-weight: bold;font-size: 22px"><?php echo ucwords($value['story_title']); ?></span>
                                    </div>
                                    <?php if(mysqli_num_rows($getUserStoryDataExc) > 0){?>
                                    <div class="col-md-3">
                                        <h3 align="center"><?php echo (isset($getUserStoryDataRow1['matching_accuracy_result'])) ? "Your accuracy is: <br><strong style='color:".$result_color."'>".number_format($getUserStoryDataRow1['matching_accuracy_result'],2)." %</strong>" : ""; ?></h3>
                                    </div>
                                    <?php } else{?>
                                    <div class="col-md-3" align="center">
                                        <a href="story_telling_start_test.php?stry_id=<?php echo $value['id']; ?>" class="btn btn-success" style="width: 150px;height: auto;" title="Start Test">Start</a>
                                    </div>
                                    <?php }?>
                                </div>
                                <br><br>
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
