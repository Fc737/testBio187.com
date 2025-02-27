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
    $points = se($_POST, "Points", "", false);


    $hasError = false;
    $errorLog= "";
    if(empty($q_title)){
        $hasError = true;
        $errorLog .= "Missing Question ,";
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
        $answers = json_encode($_POST['answers']); # Turn answers into json to store in db
        try{
            $stmt = $db -> prepare($query);
            $stmt -> execute([":id" => $id,':q_title' => $q_title, ':ans_type' => $ans_type, ':answers' => $answers, ':points' => $points]);
            flash("Question successfully created","success");
        } catch(PDOException $e){
            echo "<pre>".var_export($e,true)."</pre>";
        }


    } else {;
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
                    <div id="selection"></div>

                    <script>
                        var counter = 1;
                        var textbox = "";
                        var Choice = document.getElementById('Choice');
                        var customInput = document.getElementById('customInput_0');
                        var selection = document.getElementById("selection");
                        var HidBox = document.getElementById('HidBox');

                        function addBox() {
                            if (counter <= 4) {
                                var div = document.createElement("div");
                                div.setAttribute("class", "form-group");
                                div.setAttribute("id", "box_" + counter);

                                var textBox = "<label>Answer: " + counter + "</label><input type='text' name='answers[]' placeholder='Custom Choice' class='myinput form-control myinput' id='custom_" + counter + "'><input class='mybox' type='button' value='-' onclick='removeBox(this)'>";

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
                                customInput.classList.remove('hide');
                            }
                            else {
                                customInput.classList.add('hide');

                            }
                        })

                        Choice.addEventListener('change', function(){
                            if(this.value == "Multiple Choice") {
                                HidBox.classList.remove('hide');
                                HidBox.classList.add('mybox');
                            }
                            else {
                                HidBox.classList.remove('mybox')
                                HidBox.classList.add('hide');
                                counter;

                                /* for (counter > 2) {
                                  this.removeChild(this.childNodes[]);
                                  counter--;
                                } */
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

                </div>
                <br>
                <div class="row">

                    <label class="big" for="points">Points Assigned:</label>

                    <input type="text" name="Points" placeholder="" />

                </div>

                <div class="submit-btn">
                    <br>
                    <input type="submit" name="submit" style="background-color: skyblue; padding: 8px 28px; " />

                </div>

            </form>

        </div>

    </div>
    <br>
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
