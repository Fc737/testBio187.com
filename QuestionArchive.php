<?php
require_once(__DIR__ . "/partials/nav.php");

if (!has_role("hr") && (!has_role("admin"))) {
  flash("You don't have permission to view this page", "warning");
  die(header("Location: " . get_url("home.php")));
}
//$q_title = se($_POST, "question_title", "", false);
//$ans_type = se($_POST, "answer_type", "", false);
//$point = json_encode($_POST['points']);
if (isset($_POST["submit"])) {
  $QuestionId = se($_POST, "jobId", "", false);

  $setTitle = se($_POST, "setTitle", "", false);



  $hasError = false;
  $errorLog = "";
  if (empty($setTitle)) {
    $hasError = true;
    $errorLog .= "Missing Title ,";
  }

  if (!$hasError) {
    $db = getDB();
    //$jobpost_id = $_GET['jobId'];
    //$SelectedQuestions = array();
    $id = $_SESSION['user']['id'];
    $query = "INSERT INTO QuestionSets(setTitle, question_id)
VALUES(:setTitle, :question_id)";

    //for every question in array, input new row in table
    if (!empty($_POST['SelectedQuestions'])) {
      foreach ($_POST['SelectedQuestions'] as $question_id) {
        try {
          $stmt = $db->prepare($query);
          $stmt->execute([':setTitle' => $setTitle, ':question_id' => $question_id]);
          flash("Question Set successfully created", "success");
        } catch (PDOException $e) {
          echo "
<pre>" . var_export($e, true) . "</pre>";
        }
      }
    }
    //$answers = json_encode($_POST['question_id']); # Turn questions into json to store in db # Turn answers into json to store in db
  } else {;
    $error_arr = explode(',', $errorLog);
    foreach ($error_arr as $err) {
      if ($err != "") {
        flash($err, "warning");
        header("location: QuestionArchive.php");
        exit;
      }
    }
  }


  /*if (isset($_POST['SelectedQuestions'])) {
print_r($_POST['SelectedQuestions']);
}*/

  //$question_id = json_encode($_POST['question_id']); # Turn questions into json to store in db # Turn answers into json to store in db

  /*try{
$stmt = $db -> prepare($query);
$stmt -> execute([':set_title' => $set_title, ':question_id' => $question_id]);
flash("Question Set successfully created","success");
}catch(PDOException $e){
echo "
<pre>".var_export($e,true)."</pre>";
}


} else {;
$error_arr = explode(',',$errorLog);
foreach($error_arr as $err){
if($err != ""){
flash($err, "warning");
header("location: QuestionArchive.php");
exit;
}
}

}*/
}

$db = getDB();
$questionList = array();
$id_hr = $_SESSION['user']['id'];
$query2 = "SELECT * FROM Questions WHERE hr_id_q = :id ORDER BY id DESC LIMIT 10 ";
$stmt = $db->prepare($query2);
try {
  $stmt->execute([":id" => $id_hr]);
  $questionList = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($questionList) == 0) {
    echo "<center>
  <h1>No Questions Found!</h1>
</center>";
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
  <title>Question Archive</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<style>
  h1 {
    text-align: center;
  }

  input {

    width: 50%;
    font-family: Times New Roman;
    font-size: 18px;
    border: 1px solid #aaaaaa;
  }

  * {
    box-sizing: border-box;
  }

  body {
    background-color: #C0DCFC;
    font-family: Times New Roman;
    font-size: 18px;
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

  body {
    margin: 10px;
    padding: 0;
  }

  header {
    background-color: white;
    border: solid 0.5px black;
    border-left: none;
    padding: 22px;
  }

  .content-body,
  .content-body2 {
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

  .content-body2 {
    padding-bottom: 180px;
  }


  .content-body h2 {
    margin-bottom: 20px;
  }

  .content-body form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

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
    height: 40px;
    /* Adjusted height for the input text fields */
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
    padding-bottom: 12px;
  }


  .q-row {
    width: 90%;
    padding: 12px;
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
    background-color: #d4d0d0;
    border: solid 2px black;
    border-radius: 8px;
    justify-content: space-between;
  }

  .q-row2 {

    flex-direction: row;
  }

  .q-row2 {
    border: none;
  }

  .center {
    text-align: center;
    justify-content: center;
  }

  .white-btn {
    background-color: white;
    width: 50%;
    margin-left: 12px;
    border-radius: 8px;
  }

  .q-row2 {
    align-items: center;
    padding: 5px;
    padding-top: 36px;
    display: flex;
    flex-direction: row;
    text-align: center;


  }

  .first-row {
    display: flex;
    flex-direction: column;
    margin: auto;
    background-color: #d4d0d0;
    justify-content: center;
  }

  .width {
    width: 90%;
    padding: 8px;
    align-items: center;
  }

  .border-white {
    background-color: #fff;
    width: 40%;
    border: none;
    padding: 8px;
    margin-left: 8px;
    margin-right: 25px;
    border-radius: 8px;
  }

  .small-white {
    margin-left: -25px;
    padding-left: -20px;
    background-color: #fff;
    border: none;
    height: 47px;
    float: right;
    padding-right: 18px;
    border-radius: 8px;
  }

  .small-white:hover {
    cursor: pointer;
  }

  .q-column {
    padding: 5px;
    align-items: center;
    display: block;
  }

  .down {
    padding-top: 8px;
  }

  .q-button {
    padding: 5px 36px;
    background-color: skyblue;
    color: black;
    border-width: 2px;
    border-style: outset;
    border-image: initial;
  }

  .custom-set button {

    padding: 5px 10px;
    background-color: skyblue;
    box-sizing: border-box;
    border-style: outset;

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

  .border-invisible {
    border: none;
    padding: 5px 8px;
    background-color: #d4d0d0;
  }

  .border-1 {
    width: 200px
  }

  .border-2 {
    width: 62px;
    padding-right: 24px;
  }

  .border-4 {
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
    display: inline-block;
    display: flex;
  }

  .dropdown-options {
    display: none;
    position: absolute;
    margin-top: 48px;
    width: 400px; //300
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

  .left-section {
    display: flex;
    flex-direction: column;
  }

  .left-section div {
    padding-right: 12px;
    padding-bottom: 16px;
    line-height: 1.6;
  }

  .left-section span:not(:last-child) {}

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

  .form-group {
    margin-bottom: 15px;
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
    padding: 18px 24px;
    background-color: skyblue;
    box-sizing: border-box;
    border-style: outset;

    border-image: initial;
    border: none;
    color: black;
    border-width: 2px;
    cursor: pointer;
    font-size: 18px;
  }

  #save-button:hover {
    background-color: #bad6f0;
    color: black;
  }

  #cancel-button {
    background-color: white;
    color: black;
    float: right;
    border: none;
    padding-top: 0;
    padding-right: 0;
  }

  .clear {
    clear: both;
  }

  @media (min-width: 481px) and (max-width: 767px) {
    .q-row {
      flex-direction: column;
    }

  }

  @media (min-width: 768px) and (max-width: 1023px) {
    .q-row {
      flex-direction: row;
      align-items: center;
    }

    .left-section {
      align-items: center;
      flex-direction: row;
    }


  }

  @media (min-width: 1024px) {
    .left-section {
      align-items: center;
      flex-direction: row;
    }

    .left-section div {
      padding-right: 64px;
      padding-bottom: 3px;
    }

    .q-row {
      flex-direction: row;
      margin: 5px 12px;
      align-items: center;
    }

    .q-row2 {
      flex-direction: row;
    }

    .border-white {
      width: 50%;
    }
  }

  @media (min-width: 768px) and (max-width: 1136px) {
    .q-row2 {
      flex-direction: row;

    }
  }
</style>

<body>

  <div class="container">
    <section>
      <div class="content-body">
        <h2>Question Editor</h2>
        <?php if ($questionList > 0) : ?>
          <?php foreach ($questionList as $question) :
            $created = se($question, 'created', "", false);
            $created = ($created !== "") ? explode(' ', $created)[0] : "";
          ?>
            <div class="q-row">
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
              </div>
              <div class="right-section">
                <button class="edit-button" id="save-button" onclick="openEditOverlay(1); return false;">Edit</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>


        <div id="edit-overlay1" class="edit-overlay">
          <div class="edit-screen">
            <button class="cancel-button" id="cancel-button" onclick="closeEditOverlay(1)"><i class="fa fa-times"></i></button>
            <div class="clear"></div>
            <h2>Edit Data</h2>
            <div class="form-group">
              <label class="lab-lab" for="question-input1">Question:</label>
              <input class="txt-txt" type="text" id="question-input1" placeholder="Enter question">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="answer-type-input1">Answer Type:</label>
              <input class="txt-txt" type="text" id="answer-type-input1" placeholder="Enter answer type">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="points-input1">Points:</label>
              <input class="txt-txt" type="text" id="points-input1" placeholder="Enter points">
            </div>
            <button class="save-button" id="save-button" onclick="saveChanges(1); return false;">Save</button>
          </div>
        </div>

        <div id="edit-overlay2" class="edit-overlay">
          <div class="edit-screen">
            <button class="cancel-button" id="cancel-button" onclick="closeEditOverlay(2)"><i class="fa fa-times"></i></button>
            <div class="clear"></div>
            <h2>Edit Data</h2>
            <div class="form-group">
              <label class="lab-lab" for="question-input2">Question:</label>
              <input class="txt-txt" type="text" id="question-input2" placeholder="Enter question">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="answer-type-input2">Answer Type:</label>
              <input class="txt-txt" type="text" id="answer-type-input2" placeholder="Enter answer type">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="points-input2">Points:</label>
              <input class="txt-txt" type="text" id="points-input2" placeholder="Enter points">
            </div>
            <button class="save-button" id="save-button" onclick="saveChanges(2); return false;">Save</button>
          </div>
        </div>

        <div id="edit-overlay3" class="edit-overlay">
          <div class="edit-screen">
            <button class="cancel-button" id="cancel-button" onclick="closeEditOverlay(3)"><i class="fa fa-times"></i></button>
            <h2 class="clear">Edit Data</h2>
            <div class="form-group">
              <label class="lab-lab" for="question-input3">Question:</label>
              <input class="txt-txt" type="text" id="question-input3" placeholder="Enter question">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="answer-type-input3">Answer Type:</label>
              <input class="txt-txt" type="text" id="answer-type-input3" placeholder="Enter answer type">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="points-input3">Points:</label>
              <input class="txt-txt" type="text" id="points-input3" placeholder="Enter points">
            </div>
            <button class="save-button" id="save-button" onclick="saveChanges(3); return false;">Save</button>
          </div>
        </div>

        <div id="edit-overlay4" class="edit-overlay">
          <div class="edit-screen">
            <button class="cancel-button" id="cancel-button" onclick="closeEditOverlay(4)"><i class="fa fa-times"></i></button>
            <h2 class="clear">Edit Data</h2>
            <div class="form-group">
              <label class="lab-lab" for="question-input4">Question:</label>
              <input class="txt-txt" type="text" id="question-input4" placeholder="Enter question">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="answer-type-input4">Answer Type:</label>
              <input class="txt-txt" type="text" id="answer-type-input4" placeholder="Enter answer type">
            </div>
            <div class="form-group">
              <label class="lab-lab" for="points-input4">Points:</label>
              <input class="txt-txt" type="text" id="points-input4" placeholder="Enter points">
            </div>
            <button class="save-button" id="save-button" onclick="saveChanges(4); return false;">Save</button>
          </div>
        </div>

      </div>
    </section>
    
    <section>
      <form action="" method="POST">
        <div class="content-body2">
          <h2>Question Set Creator</h2>
          <div class="first-row">
            <div class="q-row2">
              <div class="q-column">
                <label for="title">Title:</label>
                <input name='setTitle' class="border-white" type="text" id="title" placeholder="">
              </div>

              <div class="dropdown-container">
                <label class="down" for="title">Select:</label>
                <div class="white-btn">
                  <input class="border-white" type="text" id="textInput" disabled placeholder="">
                  <button class="small-white" id="dropdownButton">▼</button>
                </div>
                <div class="dropdown-options clear">
                  <?php if ($questionList > 0) : ?>
                    <?php foreach ($questionList as $question) :
                      $created = se($question, 'created', "", false);
                      $created = ($created !== "") ? explode(' ', $created)[0] : "";
                    ?>
                          <label for="option1"><input type="checkbox" id="option1" name="SelectedQuestions[]" value="<?php echo $question['id'];?>"><li class="form-control"><?php echo $question['q_title']; ?></li></label>
                      <!--<label for="option2"><input type="checkbox" id="option2" name="selectButton" value="What are your best questions"> What are your best questions</label>
                              <label for="option3"><input type="checkbox" id="option3" name="selectButton" value="What are your best questions"> What are your best questions</label>
                              <label for="option4"><input type="checkbox" id="option4" name="selectButton" value="What are your best questions"> What are your best questions</label>
                              <label for="option5"><input type="checkbox" id="option5" name="selectButton" value="What are your best questions"> What are your best questions</label>-->
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>

            </div>
            <div class="q-row2 center">
              <div class="q-column">
                <p>Click to create a custom Set:</p>
              </div>
              <div class="q-column">
                <button name="submit" class="q-button" type="submit" onclick="createSet()">Create</button>
              </div>
            </div>
          </div>
          <?php 
          
          $db2 = getDB();
            $questionSet = array();
            $query3 = "SELECT setTitle FROM QuestionSets ORDER BY setTitle";
            $stmt2 = $db2->prepare($query3);
            try {
              $stmt2->execute();
              $questionSet = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if (count($questionSet) == 0) {
                  echo "<center>
                    <h1>No Question Sets Found!</h1>
                    </center>";
                }
                } catch (PDOException $e) {
                  echo "Error: " . $e->getMessage();
                }
            ?>

          <?php
            $groupedQuestions = array();

        // Group questions by setTitle
            foreach ($questionSet as $row) {
              $setTitle = $row['setTitle'];
              if (!isset($groupedQuestions[$setTitle])) {
                $groupedQuestions[$setTitle] = array();
              }
              $groupedQuestions[$setTitle][] = $row;
            }
          
          foreach ($groupedQuestions as $setTitle => $questions): ?>
            <div class="q-row">
            <div class="left-section">
              <span><b>Question Set: </b></span> <span><?php echo "&nbsp;" . $setTitle . "&nbsp;" . "&nbsp;". "&nbsp;";?> </span>
              <span><b>Questions Counted: </b></span> <span><?php echo "&nbsp;" . count($questions); ?> </span>
            </div>
            </div>
          <?php endforeach; ?>
      
          </div>
  <script>
    function toggleOptions() {
      var optionsContainer = document.getElementById("optionsContainer");
      optionsContainer.classList.toggle("show");
    }

    function openEditScreen(rowIndex) {
      document.getElementById(`editScreen${rowIndex}`).style.display = 'flex';

      // Populate the edit screen with existing values from the selected row
      document.getElementById(`editQuestion${rowIndex}`).value = document.getElementById(`question${rowIndex}`).value;
      document.getElementById(`editAnswerType${rowIndex}`).value = document.getElementById(`answerType${rowIndex}`).value;
      document.getElementById(`editPoints${rowIndex}`).value = document.getElementById(`points${rowIndex}`).value;
    }

    function closeEditScreen(rowIndex) {
      document.getElementById(`editScreen${rowIndex}`).style.display = 'none';
    }

    function saveChanges(rowIndex) {
      // Update the original values with the edited values
      document.getElementById(`question${rowIndex}`).value = document.getElementById(`editQuestion${rowIndex}`).value;
      document.getElementById(`answerType${rowIndex}`).value = document.getElementById(`editAnswerType${rowIndex}`).value;
      document.getElementById(`points${rowIndex}`).value = document.getElementById(`editPoints${rowIndex}`).value;
      closeEditScreen(rowIndex);
    }

    function createSet() {
      const title = document.getElementById('setTitle').value;
      const checkboxes = document.querySelectorAll('input[name="SelectedQuestions[]"]:checked');
      const selectedOptionsCount = checkboxes.length;

      const customSetSection = document.getElementById('customSet');

      const setElement = document.createElement('div');
      setElement.classList.add('set');

      const titleElement = document.createElement('span');
      titleElement.textContent = 'Title: ';
      setElement.appendChild(titleElement);

      const setTitleElement = document.createElement('span');
      setTitleElement.textContent = title;
      setElement.appendChild(setTitleElement);

      const numQuestionsElement = document.createElement('span');
      numQuestionsElement.textContent = ' Number of Questions: ';
      setElement.appendChild(numQuestionsElement);

      const setCountElement = document.createElement('span');
      setCountElement.textContent = selectedOptionsCount;
      setElement.appendChild(setCountElement);

      const editButton = document.createElement('button');
      editButton.textContent = 'Edit';
      editButton.addEventListener('click', () => {
        // Handle edit functionality
        // You can access the title, selected options count, and perform necessary actions
      });
      setElement.appendChild(editButton);

      customSetSection.appendChild(setElement);

      // Clear the fields
      document.getElementById('title').value = '';
      checkboxes.forEach(checkbox => {
        checkbox.checked = false;
      });

      // Change layout to column
      customSetSection.classList.remove('row');
    }

    document.getElementById('dropdownButton').addEventListener('click', function(event) {
      event.preventDefault();
      var dropdownOptions = document.querySelector('.dropdown-options');
      dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' : 'block';
    });

    document.getElementById('textInput').addEventListener('input', function() {
      var inputWidth = this.offsetWidth + 'px';
      document.querySelector('.dropdown-options').style.minWidth = inputWidth;
    });

    function openEditOverlay(index) {
      document.getElementsByClassName('edit-overlay')[index - 1].style.display = 'block';
      return false;
    }

    function closeEditOverlay(index) {
      document.getElementById('edit-overlay' + index).style.display = 'none';
    }

    function saveChanges(index) {
      var questionInput = document.getElementById('question-input' + index).value;
      var answerTypeInput = document.getElementById('answer-type-input' + index).value;
      var pointsInput = document.getElementById('points-input' + index).value;

      document.getElementById('question' + index).textContent = questionInput;
      document.getElementById('answer-type' + index).textContent = answerTypeInput;
      document.getElementById('points' + index).textContent = pointsInput;

      document.getElementsByClassName('edit-overlay')[index - 1].style.display = 'none';
      return false;
    }
  </script>
</body>

</html>
<?php require_once(__DIR__ . "/partials/flash.php"); ?>