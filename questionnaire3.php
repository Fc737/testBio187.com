
<?php
require_once(__DIR__."/partials/nav.php");

if (!has_role("hr")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: " . get_url("home.php")));
}

// Checked if logged in and an HR

// This is for a test
// if (isset($_POST)) {
// 	var_export($_POST);
// }
// Validate that incoming data is true
if (isset($_POST["submit"])){
  $q_title = se($_POST, "question_title", "", false);
  $ans_type = se($_POST, "answer_type", "", false);
  $QuestionId = se($_POST, "questionId", "", false);


  $hasError = false;
  $errorLog= "";
  
  if(empty($q_title)){
      $hasError = true;
      $errorLog .= "Missing Question ,";
  }
  if(empty($ans_type)){
      $hasError = true;
      $errorLog .= "Missing Answer Type ,";
  }

  if($ans_type == "Multiple Choice") {
      $points = json_encode($_POST["point"]);

  }
  else if ($ans_type == "Y/N" || $ans_type == "Open Ended") {
      $points = $_POST["singlepoint"];
  }
  if(empty($points)){
      $hasError = true;
      $errorLog .= "Missing Points ,";
  }


    // Insert into database
    if(!$hasError){
      $db = getDB();
      $id = $_SESSION['user']['id'];
      $query = "INSERT INTO Questions(hr_id_q, q_title, ans_type, answers, points) 
          VALUES(:id, :q_title, :ans_type, :answers, :points)";

      $answers = json_encode($_POST['answers']); # Turn answers into json to store in db # Turn answers into json to store in db
      try{
          $stmt = $db -> prepare($query);
          $stmt -> execute([":id" => $id,':q_title' => $q_title, ':ans_type' => $ans_type, ':answers' => $answers, ':points' => $points]);
          flash("Question successfully created","success");
      }catch(PDOException $e){
          echo "<pre>".var_export($e,true)."</pre>";
      }
  }
}

// Display Questions as they are created with a limit of 10 per page!
$db = getDB();
$questionList = array();
$id_hr = $_SESSION['user']['id'];
$query2 = "SELECT * FROM Questions WHERE hr_id_q = :id ORDER BY id DESC LIMIT 10 ";
$stmt = $db->prepare($query2);
try {
  $stmt->execute([":id" => $id_hr]);
  $questionList = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($questionList) == 0) {
      echo "<center><h1>No Questions Found!</h1></center>";
  }
} catch (PDOException $e) {
  echo "error";
}

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <title>Questionnaire Builder</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>

    <style>
      body {
          margin: 0 auto;
      padding: 0;
      display: flex;
      flex-direction: column;
    /* justify-content: space-between; */
      align-items: center;
  }
      body {
          background-color: #C0DCFC;
          font-family: Times New Roman;
          font-size: 18px;
      }
  .container {
    margin: 0 auto;
    padding: 0;
  }

  header {
    background-color: white;
    border: solid 0.5px black;
    border-left: none;
    padding: 22px;
  }

  .navbar {
    width: 100%;
    padding: 18px;
    padding-bottom: 8px;
    /* width: 0; */
    /* overflow: hidden; */
    transition: width 0.3s;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
  }

  .navbar ul {
    list-style-type: none;
    padding: 0;
    margin: auto;
    margin-top: 32px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
    text-align: center;
  }

  h4{
        margin-top: 0;
    }


  .navbar li {
    margin-bottom: 10px;
  }

  .navbar a {
    display: block;
    padding: 5px;
    text-decoration: none;
    color: #333;
  }

  .nav-icon {
    display: block;
    margin-right: 10px;
    cursor: pointer;
  }

  .close-icon {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 18px;
    cursor: pointer;
  }

  .content {
    min-width: 480px;
    border: solid 0.5px black;
    border-radius: 5px;
    flex-grow: 1;
    background-color: skyblue;
    margin-left: 12px;
    padding: 10px;
  }

  .content-navbar {
    display: flex;
    justify-content: space-between;
    background-color: white;
    padding: 12px 30px;
    border: solid 0.5px black;
    margin: 5px;
  }

  .content {
    flex-grow: 0;
    width: calc(100% - 200px);
  }

  .nav-icon {
    display: block;
  }

  .content-body, .content-body2 {
    min-width: 480px;
    display: flex;
    flex-direction: column;
    border: solid 0.5px black;
    align-items: center;
    width: 80%;
    margin: 0 auto;
    margin-top: 20px;
    background-color: white;
  }

  .content-body2{
    padding-bottom: 180px;
  }
  

  .content-body h2 {
    margin-bottom: 20px;
  }

  /**.content-body form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  } */

  .content-body form.border {
    padding: 10px;
  }

  .content-body .row {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
  }

  .content-body .row label {
    margin-bottom: 5px;
  }

  .content-body .row input[type="text"] {
    width: calc(100% - 40px);
    padding: 5px;
    height: 40px; /* Adjusted height for the input text fields */
  }

  .content-body .row.answer-type {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
  }

  .content-body .row.answer-type .column {
    display: flex;
    flex-direction: column;
    margin-right: 10px;
  }

  .content-body .row.answer-type .column input[type="text"] {
    width: 140px;
    padding: 5px;
    margin-bottom: 5px;
  }

  .content-body .row.answer-type .column:last-child {
    margin-right: 0;
  }

  .content-body .row.answer-type .column .points-label {
    margin-bottom: 5px;
  }

  .content-body .row.answer-type .column .points-input {
    width: 40px;
  }

  .content-body .submit-btn {
    margin-top: 20px;
  }

  .answer-button {
    width: 180px;
    height: 55px;
    padding: 10px;
    font-size: 14px;
    background-color: #fff;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 5px;
  }

  .answer-button:hover {
    background-color: #ccc;
  }

  .answer-button:active {
    background-color: #aaa;
  }

  .options-container {
    display: none;
    margin-top: 10px;
  }

  .options-container.show {
    display: block;
    border: solid 0.5px black;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .options-container button {
    width: 100%;
    border: none;
    cursor: pointer;
    background-color: #fff;
    padding-top: 5px;
    padding-bottom: 8px;
  }

  .options-container button:hover {
    background-color: #ccc;
  }

  .options-container button:active {
    background-color: #aaa;
  }

  .big {
    font-size: 19px;
    font-family: sans-serif;
    /**padding-bottom: 12px;*/
    
    
  }

  
  .q-row {
    width: 90%;
    padding: 12px;
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
    background-color: #d4d0d0;
    border:solid 2px black;
    border-radius: 8px;
    justify-content: space-between;
  }

  .q-row2{
    
    flex-direction: row;
  }

  .q-row2{
    border: none;
  }

  .center{
    text-align: center;
    justify-content: center;
  }

  .q-row2{
    align-items: center;
    padding: 5px;
    display: flex;
    flex-direction: row;
    
   
  }

  .first-row{
    display: flex;
    flex-direction: column;
    margin: auto;
    background-color: #d4d0d0;
    justify-content: center;
  }

  .width{
    width: 90%;
    padding:8px;
    align-items:center ;
  }

  .border-white{
    background-color: #fff;
    width: 40%;
    border: none;
    padding: 8px;
    margin-left: 8px;
    margin-right: 25px;
    border-radius: 8px;
  }

  .small-white{
    margin-left: -25px;
    padding-left: -20px;
    background-color: #fff;
    border: none;
    height: 28px;
  }

  .small-white:hover{
    cursor: pointer;
  }
  
  .q-column {
    padding: 5px;
    align-items: center;
    display: block;
  }
  
  .q-button {
    padding: 5px 10px;
    background-color: skyblue;
    color:black;
    border-width: 2px;
    border-style: outset;
    border-color: buttonborder;
    border-image: initial;
    cursor: pointer;

  }

  .custom-set button{
   
      padding: 5px 10px;
      background-color: skyblue;
      box-sizing: border-box;
      border-style: outset;
      border-color: buttonborder;
      border-image: initial;
      color: black;
      border-width: 2px;
    
  }
  
  .q-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
  }
  
  .editContent {
    background-color: #fff;
    padding: 20px;
  }
  
  .close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 30px;
    cursor: pointer;
  }

  .border-invisible{
    border:none;
    padding: 5px 8px;
    background-color: #d4d0d0;
  }

  .border-1{
    width: 200px
  }

  .border-2{
    width: 62px;
    padding-right: 24px;
  }

  .border-4{
    width: 50px;
    text-align: center;
  }

  .custom-set {
    display: flex;
    flex-direction: column;
    
  }

  .custom-set span {
    margin-right: 36px;
  }

  .dropdown-container {
    position: relative;
    display: flex;
    align-items: center;
  }
  
  .dropdown-options {
    display: none;
    position: absolute;
    width: auto;
    min-width: 100%;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 5px;
    box-sizing: border-box;
  }
  
  .dropdown-options label {
    display: block;
    margin: 5px;
  }

  .row {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .left-section {
    display: flex;
    flex-direction: column;
  }

  .left-section div{
    padding-right: 12px;
    padding-bottom: 16px;
    line-height: 1.6;
  }

  .left-section span:not(:last-child) {
    
  }
  
  .right-section {
    float: right;
  }
  
  .edit-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    text-align: center;
  }
  
  .edit-screen {
    display: inline-block;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    margin-top: 20vh;
    max-width: 400px;
  }
  
  .edit-screen h2 {
    margin-top: 0;
  }

  .q-row3{
    background-color: #d4d0d0;
    width:90%;
    padding: 12px; 
    border-radius: 8px;
    padding-top: 24px;
    border: solid 2px black;
    box-shadow: 2px 2px 4px #888;
    box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.5);
    margin-bottom: 24px;   
  }

  .form-control {
    width: 100%;
    padding: 10px;
    font-size: 16px;
  }

  .form-control1{
    width:30%;
    padding: 10px;
    font-size: 16px;
    margin-right:12px;
  }

  .form-control2{
    width: 57%;
    padding: 10px;
    font-size: 16px;
  }

  .my-left{
    text-align: center;
  }

  .form-control3{
    margin-left:5px;
    text-align: center;
    justify-content: center;
    align-items: center;
    width:5%;
    padding: 10px;
    font-size: 16px;
  }

  .form-control4{
    width:30%;
    font-size: 16px;
    padding: 10px;
  }

  .flex{
    display: flex;
    align-items: center;
  }
  
  .form-group {
   /** margin-bottom: 15px; */
  }

  .mybox{
    margin-left: 5px;
    text-align: center;
    justify-content: center;
    align-items: center;
    width: 5%;
    padding: 10px;
    font-size: 16px;
  }

  .mb-2 label{
    max-width: 100px;
    width: 100%;
  }
  
  .myBIGBOX{
    display: flex;
    padding-bottom: 12px;
    align-items: center;
    padding-top: 15px;
  }

  .myBIGBOX label{
    font-size: 19px;
    font-family: sans-serif;
    max-width: 100px;
    width: 100%;
  }

  .myBIGBOX input{
        display:block;
    }

    .myBIGBOX button{
        display:inline;
    }

  .lab-lab {
    display: block;
  }
  
  .txt {
    width: 100%;
    padding: 5px;
    border-radius: 3px;
    border: 1px solid #ccc;
  }
  
  #save-button {
    padding: 8px 15px;
    background-color: skyblue;
      box-sizing: border-box;
      border-style: outset;
      border-color: buttonborder;
      border-image: initial;
      border:none;
      color: black;
      border-width: 2px;
    cursor: pointer;
  }
  
  #save-button:hover {
    background-color: #bad6f0;
    color:black;
  }
  
  #cancel-button{
    background-color: white;
    color:black;
    float:right;
    border:none;
    padding-top: 0;
    padding-right: 0;
  }

  .clear{
    clear: both;
  }

  .hide{
        width: 0;
        height: 0;
        opacity: 0;
    }

    .apply {
        position: absolute;
        right: 2%;
        top: 10%;
    }

    #hori {
        position: relative;
        border-radius: 10px;
        box-shadow: 0px 2px black;
        margin: 10px;
        }

    #hori {
        font-size: 18px;
        background: lightgray;
        padding: 20px;
    }

  @media (min-width: 481px) and (max-width: 767px) { 
    .q-row{
      flex-direction: column;
    }

   }

   @media (min-width: 768px) and (max-width: 1023px) { 
      .q-row{
        flex-direction: row;
        align-items: center;
      }

      .left-section{
        align-items: center;
        flex-direction: row;
      }


    }

    @media (min-width: 1024px) { 

      .left-section{
        align-items: center;
        flex-direction: row;
      }

      .left-section div{
        padding-right: 64px;
        padding-bottom: 3px;
      }

      .q-row{
        flex-direction: row;
        margin: 5px 12px;
        align-items: center;
      }

      .q-row2{
        flex-direction: row;
      }

      .border-white{
        width: 50%;
      }
     }

     @media (min-width: 768px) and (max-width: 1136px) {
      .q-row2{
        flex-direction: row;
        
      }
     }

    </style>



    <body>

    <div class="container">
        <section>
        <div class = "content-body">
            <h1>Questionnaire Creator</h1>
            <div class = "q-row3">

                <form action="" method="POST">
                    <div>
                        <div class = "mb-2 flex">

                            <label class="big" for="q_title">Question:</label>

                            <input class="form-control" type="text" name="question_title" placeholder="What are your top 3 projects?"/>

                        </div>
                        <br>
                        <div class="form-group mb-2 flex">
                            <label class="big" for="answer_type">Answer Type:</label>
                            <select class="form-control1" name="answer_type" id="Choice">
                                <option selected value="">Select answer type</option>
                                <option value="Open Ended">Open Ended</option>
                                <option value="Y/N">Yes or No</option>
                                <option value="Multiple Choice" id="Choice">Multiple Choice</option>
                            </select>
                            <input type="text" class="hide form-control2" placeholder="Custom Choice" name='answers' id="customInput">
                            <input type="button" class="hide form-control3" value="+" onclick="addBox()" id="HidBox">
                        </div>
                        <div id="selection" class=""></div>

                    </div>
                    <br>
                    <div class="mb-2 flex">

                        <label class="big" for="points" id='pointsLab'>Points Assigned:</label>

                        <input class="form-control" type="text" name='singlepoint' id='pointsBox' placeholder="" />

                    </div>

                    <div class="submit-btn">
                        <br>
                        <input type="submit" name="submit" style="background-color: skyblue; padding: 8px 28px; " />

                    </div>

                </form>

            </div>

        </div>
        <br>

        <br>

        <!--

        This section is where previous questions made are shown as well as an edit option for them

        -->
        </section>
        <section>

        <div class ="content-body2">
          <h1>Created Questions</h1>
        
        
        <?php
        $counter = 0;
        $identification = [];
        
        
 
        if (isset($_POST["SaveChange"]))
        {
            
            $q_title = se($_POST, "question_title_edit", "", false);

            $ans_type = se($_POST, "AnswerEdit", "", false);
            $points = se($_POST, "singlepointerEdit", "", false);
            //$editPoints = document.getElementsById('');

            
            $hasError = false;
            $errorLog = "";

            if(empty($q_title)){
                $hasError = true;
                $errorLog .= "Missing Question Title";
            }


           if(empty($ans_type)){
                $hasError = true;
                $errorLog .= "Missing Answer Type";
            }

            if (!$hasError){
                $u_id = $_SESSION['user']['id'];
                $db = getDB();
                $query = "UPDATE Questions SET q_title = :q_title, ans_type = :ans_type, answers = :answers, points = :points 
                WHERE hr_id_q = :hr_id_q AND id = :id";
        
                $stmt = $db->prepare($query);
                try{
                    $stmt->execute([":hrId" => $u_id,  ":q_title" => $q_title, ":ans_type" => $ans_type, //":points" => $singlepointerEdit,
                    ]);
                    flash("Question edited successfully","success");
                } catch(PDOException $e){
        
                }
            } else {
                if ($errorLog !== ""){
                    $listOfErrors = explode(',',$errorLog);
                    foreach ($listOfErrors as $err){
                        if($err != ""){
                            flash(trim($err),"info");
                        }
                    }
                }
            }

        }

        

      ?>
        
        <?php if ($questionList > 0) : ?>
            <?php foreach ($questionList as $question) :
                $created = se($question, 'created', "", false);
                $created = ($created !== "") ? explode(' ', $created)[0] : "";
                //array_push($identification, $question['id']);

                ?>

            <div id = "hori" class="q-row">
                <h4 class="mb-1"></h4>
              <div class="left-section">
                <div>
                    <span><b>Question:</b></span> <span id="question1"><?php echo $question['q_title']; ?></span>
                </div>
                <div>
                    <span><b>Answer Type:</b></span> <span id="answer-type1"><?php echo $question['ans_type']; ?></span>
                </div>
                  <div>
                    <span><?php if ($question['ans_type'] === 'Multiple Choice'): ?>
                            <b>Points Assigned:</b>
                            <?php $pointer=json_decode($question['points']); ?>

                            <?php foreach ($pointer as $point): ?>
                                <li class="item"><?php echo $point; ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <b>Points Assigned:</b> <li class="item"><?php echo $question['points']; ?></li>
                        <?php endif; ?></span>
                  </div>
                <div>
                  <form method = "post">
                    <a class="edit-button mr-2 mb-2 btn btn-primary btn-sm position-static edit" style = "padding: 8px 15px; background-color: skyblue; box-sizing: border-box; border-style: outset; border-color: buttonborder;  border-image: initial; border: none; color: black; border-width: 2px; cursor: pointer; " data-bs-toggle="modal" onclick="edit(this)" data-bs-target="#formModal">Edit</a>
                  </form>
                </div>
              </div>
             
                <div class="apply">
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class=" modal fade" id="formModal" aria-labelledby="formModal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog ">
                    <div class="modal-content content-body ">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Question</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="model-body q-row3">
                            <form action="" method="POST" id="form1">
                                <div class="mb-2 flex">
                                    <label class="form-label big" for="q_title">Question:</label>
                                    <input class="form-control" type="text" name="question_title_edit" placeholder="What are your top 3 projects?"/>
                                </div>
    
                                <div class="input-group mb-2 flex">
                                
                                
                                    <label class="form-label big" for="answer_type">Answer Type:</label>
                                    <select class="form-select form-control1" name="AnswerEdit" id="ChoiceEdit">
                                        <option selected value="">Select answer type</option>
                                        <option value="Open Ended">Open Ended</option>
                                        <option value="Y/N">Yes or No</option>
                                        <option value="Multiple Choice">Multiple Choice</option>
                                    </select>
                                    <input type="text" class="hide form-control2" placeholder="Custom Choice" name='answer_type_edit' id="customInput">
                                    <input type="button" class="hide form-control3" value="+" onclick="addBoxEdit()" id="edit_HidBox">
                                </div>
                                <div id="edit_selection" class=""></div>
    
                                <div class="mb-2 flex">
                                    <label class="form-label big" for="points" id='edit_pointsLab'>Points Assigned:</label>
                                    <input class="form-control" type="text" name='singlepointerEdit' id='pointsBox' placeholder="" />
                                </div>
    
                            </form>
                            
                                <div class="model-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: skyblue; padding: 8px 28px;">Close</button>
                                    <button type="submit" form="form1" name="SaveChange" class="btn btn-primary" style="background-color: skyblue; padding: 8px 28px;">Save Changes</button>
                                </div>
                                
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </body>
    <script>

//Sets up variables to iterate over multiple choice selection
var counter = 1;
//Sets up creation of box and function for creating input(s) of multiple choice points/answers
var Choice = document.getElementById('Choice');
var selection = document.getElementById("selection");
var HidBox = document.getElementById('HidBox');
var pointsLab = document.getElementById('pointsLab');
var pointsBox = document.getElementById('pointsBox');

//Sets up the same as above except for edit box
var counts = 1;
var ChoiceEdit = document.getElementById('ChoiceEdit');

var edit_selection = document.getElementById("edit_selection");
var edit_HidBox = document.getElementById('edit_HidBox');
var edit_pointsLab = document.getElementById('edit_pointsLab');
var edit_pointsBox = document.getElementById('edit_pointsBox');

//Allows Multiple Choice to create additional boxes when creating the questions
function addBox() {
    if (counter <= 4) {
        var div = document.createElement("div");
        div.setAttribute("class", "myBIGBOX");
        div.setAttribute("id", "box_" + counter);



        var textBox = "<label>Answer: " + counter + "</label><input type='text' name='answers[]' placeholder='Custom Choice' class='myinput form-control2 myinput' id='custom_" + counter + "'><label class='my-left'>Points: </label><input type='text' class='form-control4' name='point[]'> <input class='mybox' display='inline' type='button' value='-' onclick='removeBox(this)'>";

        div.innerHTML = textBox;


        selection.appendChild(div);
        counter++;
    }
}

function removeBox(ele)
{
    ele.parentNode.remove();
    counter--;

}

//Allows Multiple Choice to create additional boxes when in edit mode
function addBoxEdit() {
        if (counts <= 4) {
            var div = document.createElement("div");
            div.setAttribute("class", "myBIGBOXEdit myBIGBOX");
            div.setAttribute("id", "box_" + counts);

            var EdittextBox = "<label>Answer: " + counts + "</label><input type='text' name='Editanswers[" + counts + "]' placeholder='Custom Choice' class='myinput form-control2 myinput' id='custom_" + counts + "'><label class='my-left'>Points</label><input type='text' class='form-control4' name='Editpoint[]'> <input class='mybox' display='inline' type='button' value='-' onclick='removeBoxEdit(this)'>";
            div.innerHTML = EdittextBox;


            edit_selection.appendChild(div);
            counts++;
        }
    }



function removeBoxEdit(ele)
{
    ele.parentNode.remove();
    counts--;

}

//A specific function that allows the custom hide class to replace the original form-control
ChoiceEdit.addEventListener('change', function(){
    if(this.value == "Multiple Choice") {
        edit_HidBox.classList.remove('hide');
        edit_HidBox.classList.add('form-control');
    }
    else {
        edit_HidBox.classList.remove('form-control')
        edit_HidBox.classList.add('hide');

    }

    if(this.value == "Multiple Choice") {
        edit_pointsLab.classList.remove('big');
        edit_pointsLab.classList.add('hide');
    }
    else {
        edit_pointsLab.classList.remove('hide')
        edit_pointsLab.classList.add('big');

    }

    if(this.value == "Multiple Choice") {
        edit_pointsBox.classList.remove('big');
        edit_pointsBox.classList.add('hide');
    }
    else {
        edit_pointsBox.classList.remove('hide')
        edit_pointsBox.classList.add('big');
    }
})

//A specific function that allows the custom hide class to replace the original mybox
Choice.addEventListener('change', function(){
    if(this.value == "Multiple Choice") {
            HidBox.classList.remove('hide');
            HidBox.classList.add('mybox');
    }
    else {
            HidBox.classList.remove('mybox')
            HidBox.classList.add('hide');

    }

    if(this.value == "Multiple Choice") {
            pointsLab.classList.remove('big');
            pointsLab.classList.add('hide');
    }
    else {
            pointsLab.classList.remove('hide')
            pointsLab.classList.add('big');

    }

    if(this.value == "Multiple Choice") {
            pointsBox.classList.remove('big');
            pointsBox.classList.add('hide');
    }
    else {
            pointsBox.classList.remove('hide')
            pointsBox.classList.add('big');
    }
    })

function edit(anchor) {
    var form = document.querySelector('.modal-body form');
    const parentDiv = anchor.closest("div#hori");
    const lst = parentDiv.querySelector("h4").textContent.split('-');
    form.question_title_edit.value = lst[0].trim();
    form.singlepointerEdit.value = singlepointerEdit[0].trim();
    form.AnswerEdit.value = parentDiv.querySelector('#AnswerEdit').textContent;
    form.Editanswers.value = Editanswers[0].trim();
}

</script>
</html>
<?php require_once(__DIR__."/partials/flash.php");?>
