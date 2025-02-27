<?php
$counter = 0;
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
    $point = se($_POST, "point", "", false);
    $points = array(se($_POST, "points", "", false));

    $hasError = false;
    $errorLog= "";
    if(empty($q_title)){
        $hasError = true;
        $errorLog .= "Missing Question, ";
    }
    if(empty($ans_type)){
        $hasError = true;
        $errorLog .= "Missing Answers, ";
    }
    
    if(empty($point)){
        foreach($_POST["points"] as $key => $value){
            if (empty($value)){
                $counter++;
            }
            }
        
        if ($counter == sizeof($points)) {
            $hasError = true;
            $errorLog .= "Missing Point(s) , ";
        } 
    }
    
    if(empty($points)){
        $points=$point;
    }

    // Insert into database
    if(!$hasError){
        $db = getDB();
        $id = $_SESSION['user']['id'];
        $query = "INSERT INTO Questions(hr_id_q, q_title, ans_type, answers, points) 
            VALUES(:id, :q_title, :ans_type, :answers, :points)";
        
        $answers = json_encode($_POST['answers']);
        $points = json_encode($_POST['points']); # Turn answers into json to store in db # Turn answers into json to store in db
        try{
            $stmt = $db -> prepare($query);
            $stmt -> execute([":id" => $id,':q_title' => $q_title, ':ans_type' => $ans_type, ':answers' => $answers, ':points' => $points]);
            flash("Questionnaire successfully created","success");
        } catch(PDOException $e){
            echo "<pre>".var_export($e,true)."</pre>";
        }
    } else {
        $error_arr = explode(',',$errorLog);
        foreach($error_arr as $err){
            if($err != ""){
                flash($err, "warning");
                header("location: questionnaire2.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Questionnaire Builder</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
        var y;
        var x;
        var counter;

        /* function MakeTextboxes()
        {
            var d=document.getElementById("Choice");
            var displaytext=d.options[d.selectedIndex].text;
            if (displaytext == "Multiple Choice") {
                //The commented was used to test if the value was correct in an open box
                //document.getElementById("txtvalue").value=displaytext;
                var x = document.createElement("INPUT");
                x.setAttribute("type", "text");
                document.body.appendChild(x);
                y = true;
            }
            else {
                y = false;
            }
        }*/

        function Button4TextBox()
        {
            if (y == true) {
                var x = document.createElement("INPUT");
                x.setAttribute("type", "text");
                document.body.appendChild(x);
            }
        }
    </script>

</head>

<style>
    h1 {
        text-align: center;
    }

    input {

        width: 50%;
        font-family: Times New Roman;
        font-size: 25px;
        border: 1px solid #aaaaaa;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background-color: #C0DCFC;
        font-family: Times New Roman;
        font-size: 28px;
    }

    #hori {
        position: relative;
        border-radius: 10px;
        box-shadow: 0px 2px black;
        margin: 10px;
    }

    #hori {
        font-size: 24px;
        background: lightgray;
        padding: 20px;
    }

    .hide {
        width: 0;
        height: 0;
        opacity: 0;
    }

    .myBIGBOX {
      display: inline;
    }
    
    .myBIGBOX label{
      display:inline-block;
    }
    
    .myBIGBOX input{
      display:block;
    }
    
    .myBIGBOX button{
      display:inline;
    }
</style>



<body>

<div class="container">
    <div>
        <div id="hori" class="">

            <form action="" method="POST">
                <h1>Questionnaire Creator</h1>
                <div>
                    <div class="row">

                        <label class="big" for="q_title">Question:</label>

                        <input type="text" name="question_title" placeholder="What are your top 3 projects?"/>

                    </div>
                    <br>
                    <div class="form-group">
                        <label class="big" for="ans_type">Answer Type:</label>
                        <select name="answer_type" id="Choice">
                            <option selected value="">Select answer type</option>
                            <option value="Open Ended">Open Ended</option>
                            <option value="Y/N">Yes or No</option>
                            <option value="Multiple Choice" id="Choice">Multiple Choice</option>
                        </select>
                        <input type="text" class="hide" placeholder="Custom Choice" name="answers" id="customInput">
                        <input type="button" class="hide" value="+" onclick="addBox()" id="HidBox">
                    </div>
                    <div id="selection" class="myBIGBOX"></div>

</div>
                <br>
                <div class="row">

                    <label class="big" for="points" id='pointsLab'>Points Assigned:</label>

                    <input type="text" class="big" name="point" id='pointsBox' placeholder="" />

                </div>

                <div class="submit-btn">
                    <br>
                    <input type="submit" name="submit" style="background-color: skyblue; padding: 8px 28px; " />

                </div>

            </form>

        </div>

    </div>
    <br>
        <script>
            var counter = 1;
            var textbox = "";
            var Choice = document.getElementById('Choice');
            var customInput = document.getElementById('customInput_0');
            var selection = document.getElementById("selection");
            var HidBox = document.getElementById('HidBox');
            var pointsLab = document.getElementById('pointsLab');
            var pointsBox = document.getElementById('pointsBox');
                        
            function addBox() {
                if (counter <= 4) {
                    var div = document.createElement("div");
                    var div2 = document.createElement("div");
                    div.setAttribute("class", "myBIGBOX");
                    div.setAttribute("id", "box_" + counter);
                    div2.setAttribute("class", "myBIGBOX");
                    div2.setAttribute("id", "box_" + counter);
                                
                                
                    var textBox = "<label>Answer: " + counter + "</label><input type='text' name='answers[]' placeholder='Custom Choice' class='myinput form-control myinput' id='custom_" + counter + "'><label>Points</label><input class='mybox' name='points[points_'" + counter + "]'> <input class='mybox' display='inline' type='button' value='-' onclick='removeBox(this)'>";
                                //var textBox2 = "<label>Answer: " + counter + "</label><input type='text' name='answers[]' placeholder='Custom Choice' class='myinput form-control myinput' id='custom_" + counter + "'><input class='mybox' type='button' value='-' onclick='removeBox(this)'>";
                                
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
                        pointsBox.classList.add('big')
                        }
                    })
                    
            </script>

                    <!-- /*if ($setter) {
                        <div>
                            <div class="row">
                                <label for="Multiple Choices">Multiple Choice Selection:</label>
                                <input type="text" name="question_title" placeholder="Fill in with fake answer" style="width: 200%; height: 40px;" />
                            </div>
                        <div>
                            <div class="row">
                                <label for="Multiple Choices">Multiple Choice Selection:</label>
                                <input type="text" name="question_title" placeholder="Fill in with fake answer" style="width: 200%; height: 40px;" />
                            </div>
                        <div>
                            <div class="row">
                                <label for="Multiple Choices">Multiple Choice Selection:</label>
                                <input type="text" name="question_title" placeholder="Fill in with fake answer" style="width: 200%; height: 40px;" />
                            </div>
                    }
                   */ ?>-->

    <div id="hori">
        <form action="">
            <h1>Created Questions</h1>
            <div class="row skills">
                <div class="col">
                    <label class="big" for="q_title">Questions</label>
                </div>
                <div class="col">
                    <label class="big" for="q_title">Answer Type</label>
                </div>
                <div class="col">
                    <label class="big" for="q_title">Assigned Points</label>
                </div>
            </div>
        </form>
    </div>

</div>

</body>

</html>
<?php require_once(__DIR__."/partials/flash.php");?>
