<?php 
session_start();
include("admin/include/config.php");
$getData = "SELECT * FROM story_for_user WHERE status = 1 AND id = '".$_REQUEST['stry_id']."' ORDER BY id DESC ";
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

    $insertQry = "INSERT INTO story_told_by_user SET story_id = '".$_REQUEST['stry_id']."', story_by_user = '".$user_txt."', user_id = '".$user_id."', matching_characters = '".$similar_char."', matching_accuracy_result = '".$percent."' ";
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
                    {
                        $cnt = 1;
                        foreach ($storyArr as $key => $value) {
                            $getUserTextData = "SELECT * FROM story_told_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' AND story_id = '".$value['id']."' ORDER BY id DESC ";
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
                        <?php }else{?> 
                            <h1 style="text-align: center;"><?php echo $value['story_title']; ?></h1><br><br>
                            <form action="" method="post" autocomplete="off">
                                <input type="hidden" name="texttospeech" value="<?php echo $value['story_content']; ?>">
                                <input type="hidden" name="hidden_txt_from_user" id="note-textarea" value="">
                                <h3>Listen carefully this audio first and after that you have to summarized that.</h3>
                                
                                <input type = "submit" class = "btn btn-warning" name="process" title="Play Audio" value="Play Audio">

                                <input type="button" id="start-record-btn" class="btn btn-success" title="Start Recording" value="Summarize">

                                <button type="submit" name="save_btn" class="btn btn-info" title="Submit Speech">Submit Story</button> <br><br>
                                <p id="recording-instructions">Press the <strong>Summarize</strong> button and allow access.</p><br><br>
                            </form>
                        <?php } 
                        $cnt++; } 
                    }else{ ?>
                        <h2>Please do Login first.</h2>
                    <?php } ?>
                    <div style="height: 120px">
                        <a href="story_telling.php" class="btn btn-dark" style="width: 150px;height: auto;"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
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
