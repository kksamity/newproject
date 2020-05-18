<?php 
session_start();
include("admin/include/config.php");
$getStoryData = "SELECT * FROM story_told_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' ORDER BY id DESC ";
$getStoryDataExc = mysqli_query($conn, $getStoryData);


$noOfStoryData = mysqli_num_rows($getStoryDataExc);

$storyDataAcc = 0;
while ($getStoryDataRow = mysqli_fetch_assoc($getStoryDataExc)) {
   $storyDataAcc +=$getStoryDataRow['matching_accuracy_result'];
}
$overallStoryDataAcc = $storyDataAcc/($noOfStoryData*100)*100;
$overallStoryDataAcc = number_format($overallStoryDataAcc,2);

if ($overallStoryDataAcc == 0 || ($overallStoryDataAcc > 0 && $overallStoryDataAcc <= 49) ) {
    $result_color3 = "red";
}elseif ($overallStoryDataAcc >= 50 && $overallStoryDataAcc <= 75) {
    $result_color3 = "orange";
}elseif ($overallStoryDataAcc >= 76 && $overallStoryDataAcc <= 100) {
    $result_color3 = "green";
}

$getSpeechData = "SELECT * FROM speech_to_speech_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' ORDER BY id DESC ";
$getSpeechDataExc = mysqli_query($conn, $getSpeechData);
$noOfSpeechData = mysqli_num_rows($getSpeechDataExc);

$speechDataAcc = 0;
while ($getSpeechDataRow = mysqli_fetch_assoc($getSpeechDataExc)) {
   $speechDataAcc +=$getSpeechDataRow['matching_accuracy_result'];
}
$overallSpeechDataAcc = $speechDataAcc/($noOfSpeechData*100)*100;
$overallSpeechDataAcc = number_format($overallSpeechDataAcc,2);


if ($overallSpeechDataAcc == 0 || ($overallSpeechDataAcc > 0 && $overallSpeechDataAcc <= 49) ) {
    $result_color2 = "red";
}elseif ($overallSpeechDataAcc >= 50 && $overallSpeechDataAcc <= 75) {
    $result_color2 = "orange";
}elseif ($overallSpeechDataAcc >= 76 && $overallSpeechDataAcc <= 100) {
    $result_color2 = "green";
}

$getTextData = "SELECT * FROM text_to_speech_by_user WHERE status = 1 AND user_id = '".$_SESSION['ocaWebUser']['id']."' ORDER BY id DESC ";
$getTextDataExc = mysqli_query($conn, $getTextData);
$noOftxtData = mysqli_num_rows($getTextDataExc);

$txtDataAcc = 0;
while ($getTextDataRow = mysqli_fetch_assoc($getTextDataExc)) {
   $txtDataAcc +=$getTextDataRow['matching_accuracy_result'];
}
$overallTextDataAcc = $txtDataAcc/($noOftxtData*100)*100;
$overallTextDataAcc = number_format($overallTextDataAcc,2);

if ($overallTextDataAcc == 0 || ($overallTextDataAcc > 0 && $overallTextDataAcc <= 49) ) {
    $result_color1 = "red";
}elseif ($overallTextDataAcc >= 50 && $overallTextDataAcc <= 75) {
    $result_color1 = "orange";
}elseif ($overallTextDataAcc >= 76 && $overallTextDataAcc <= 100) {
    $result_color1 = "green";
}

$overallAccuracy = 0;
$overallStatus = 0;
if ($overallTextDataAcc > 0 && $overallSpeechDataAcc > 0 && $overallStoryDataAcc > 0 ) {
    $totalObtained = $overallTextDataAcc + $overallSpeechDataAcc + $overallStoryDataAcc;
    $totalObtained = number_format($totalObtained,2);
    $overallAccuracy = $totalObtained/300*100;
    $overallStatus = 1;
    if ($overallAccuracy == 0 || ($overallAccuracy > 0 && $overallAccuracy <= 49) ) {
        $result_color4 = "red";
    }elseif ($overallAccuracy >= 50 && $overallAccuracy <= 75) {
        $result_color4 = "orange";
    }elseif ($overallAccuracy >= 76 && $overallAccuracy <= 100) {
        $result_color4 = "green";
}
}

include("include/header.php"); ?>

    <!-- slider_area_start -->
    <div class="slider_area">
        <div class="single_slider d-flex align-items-center justify-content-center slider_bg_1 overlay2">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-xl-9">
                        <div class="slider_text text-center">
                            <h3>Go Big with Transcript</h3>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- prising_area_start -->
    <div class="prising_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section_title text-center mb-100">
                        <h3>
                            Choose your Transcription
                        </h3>
                    </div>
                </div>
            </div>
            <?php if ($overallStatus==1) { ?>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="section_title text-center mb-100">
                            <h3>Your overall accuracy is: <?php echo "<strong style='color:".$result_color4."'>".number_format($overallAccuracy,2)." %</strong>"; ?></h3>
                        </div>
                    </div>
                </div>
            <?php } ?>
            
            <div class="row">
                <div class="col-xl-4 col-md-4 col-lg-4" align="center">
                    <h3><?php echo ($noOftxtData > 0) ? "Accuracy: <strong style='color:".$result_color1."'>".number_format($overallTextDataAcc,2)." %</strong>" : ""; ?></h3>
                </div>
                <div class="col-xl-4 col-md-4 col-lg-4" align="center">
                    <h3><?php echo ($noOfSpeechData > 0) ? "Accuracy: <strong style='color:".$result_color2."'>".number_format($overallSpeechDataAcc,2)." %</strong>" : ""; ?></h3>
                </div>
                <div class="col-xl-4 col-md-4 col-lg-4" align="center">
                    <h3><?php echo ($noOfStoryData > 0) ? "Accuracy: <strong style='color:".$result_color3."'>".number_format($overallStoryDataAcc,2)." %</strong>" : ""; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-4 col-lg-4" align="center">
                    <div class="single_prising">
                        <div class="prising_icon blue">
                            <i class="fa fa-text-width"></i>
                            <i class="fa fa-long-arrow-right"></i>
                            <i class="fa fa-microphone"></i>
                        </div>
                        <h3>Text to Speech</h3>
                        <a href="txt_to_speech.php" class="boxed_btn_green2">Start Now</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-lg-4" align="center">
                    <div class="single_prising">
                        <div class="prising_icon" style="color: navy">
                            <i class="fa fa-volume-up"></i>
                            <i class="fa fa-long-arrow-right"></i>
                            <i class="fa fa-microphone"></i>
                        </div>
                        <h3>Speech to Speech</h3>
                        <a href="speech_to_speech.php" class="boxed_btn_green2">Start Now</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-lg-4" align="center">
                    <div class="single_prising">
                        <div class="prising_icon" style="color: purple">
                            <i class="fa fa-file-audio-o"></i>
                            <i class="fa fa-long-arrow-right"></i>
                            <i class="fa fa-microphone"></i>

                        </div>
                        <h3>Story Telling</h3>
                        <a href="story_telling.php" class="boxed_btn_green2">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- prising_area_end -->

    <!-- dedicated_support_start -->
    <div class="dedicated_support support_bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-md-8">
                    <div class="support_info">
                        <h3>24h Dedicated Support</h3>
                        <p>Our set he for firmament morning sixth subdue darkness creeping gathered divide our let god
                            moving. Moving in fourth air night bring upon you’re it beast.</p>
                        <div class="get_started">
                            <a class="boxed_btn_green" href="#">
                                <span>Get Start Now</span>
                            </a>
                            <a href="#" class="phone_num">
                                +10 267 367 678 2678
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- dedicated_support_end -->



    <!-- latest_new_area_start -->
    <div class="latest_new_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section_title text-center mb-100">
                        <h3>
                            What is Transcription?
                        </h3>
                        <p>This is a phonetic transcription of the conversations that were recorded on tape.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- latest_new_area_end -->

   
<?php include("include/footer.php"); ?>

</body>

</html>