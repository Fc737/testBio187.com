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
    $title = se($_POST, "job_title", "", false);
    $company = se($_POST, "company_name", "", false);
    $description = se($_POST, "description", "", false);
    $location = se($_POST, "location", "", false);
    $payRate = se($_POST["salary"], "pay_rate", "", false);
    $paymentType = se($_POST["salary"], "payment_type", "", false);
    $work_status = se($_POST, "work_status", "", false);
	$setTitle = se($_POST, "setTitle", "", false); //Gabi initialized right here @Parker

    $hasError = false;
    $errorLog= "";
    if(empty($title)){
        $hasError = true;
        $errorLog .= "Missing Title ,";
    }

    if(empty($company)){
        $hasError = true;
        $errorLog .= "Missing Company ,";
    }

    if(empty($description)){
        $hasError = true;
        $errorLog .= "Missing Description ,";
    }

    if(empty($location)){
        $hasError = true;
        $errorLog .= "Missing Location ,";
    }

    if(empty($work_status)){
        $hasError = true;
        $errorLog .= "Missing work-status ,";
    }

    if(empty($payRate)){
        $hasError = true;
        $errorLog .= "Missing pay rate ,";
    }

    if(empty($paymentType)){
        $hasError = true;
        $errorLog .= "Missing payment type ,";
    }
	
	if(!(in_array($paymentType,array('Hour','Week','Day','Month','Year')))){
		$hasError = true;
        $errorLog .= "Invalid payment type ,";
	}

	if (!(in_array($work_status,array('1','2')))){
		$hasError = true;
		$errorLog .= "Invalid work-status ,";
	}

    $options = array('options' => array('min_range' => 0));
    if (! filter_var($payRate,FILTER_VALIDATE_FLOAT,$options)){
        $hasError = true;
        $errorLog .= "Invalid salary";
    }
    // Iterate over array of skills to check if a skills is missing
    foreach($_POST["skills"] as $key => $value){
        if (empty($value)){
            $hasError = true;
            $str = explode('_', $key);
            $errorLog .= "$str[0] $str[1] is missing ,";
        }
    }

    // Insert into database
    if(!$hasError){
        $db = getDB();
		$id = $_SESSION['user']['id'];
        $query = "INSERT INTO JobPosting(hr_id, title, company_name, location, description, work_status, salary, skills, setTitle) 
            VALUES(:id, :title, :company, :location, :desc, :status, :salary, :skills, :setTitle)";
        $salary = $payRate.' / '.$paymentType; # Job Salary
        $skills = json_encode($_POST['skills']); # Turn skills into json to store in db
        try{
            $stmt = $db -> prepare($query);
            $stmt -> execute([":id" => $id,':title' => $title, ':company' => $company, ':location' => $location, ':desc' => $description,  ":status" => $work_status,':salary' => $salary, ':skills' => $skills, ':setTitle'=> $setTitle]);
            flash("Job successfully created","success");
		} catch(PDOException $e){
            echo "<pre>".var_export($e,true)."</pre>";
        }
    
    } else {;
		$error_arr = explode(',',$errorLog);
            foreach($error_arr as $err){
                if($err != ""){
                    flash($err, "warning");
                }
            }
        }
}

$db=getdb();
$setTitles=array();
//query one to display questionset names for dropdrown -GA
$query = "SELECT DISTINCT setTitle FROM QuestionSets";
$stmt = $db->prepare($query);
try{
    $stmt->execute();
    $setTitles = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "error";
}

//query two to display quetionset rows -GA
$query2 = "SELECT q.id, q.q_title, qs.setTitle, q.points, q.ans_type FROM Questions q JOIN QuestionSets qs ON q.id = qs.question_id;";
$stmt2 = $db->prepare($query2);
try{
    $stmt2->execute();
    $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "error";
}

?>

<script type="text/javascript">
// Popup window code
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=700,width=600,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')
}
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Register</title>


</head>
<style>
	#description {
		height: 100px;
		display: grid;
		gap: 10px;
		grid-gap: 100px;
	}

	#myDIV {
		padding-top: 20px;
		padding-right: 10px;
		padding-bottom: 20px;
		padding-left: 10px;
	}

	* {
		box-sizing: border-box;
	}

	body.siteStyle {
        background-color: #C0DCFC;
        font-family: Times New Roman;
        font-size: 18px;
	}

	#regForm {
		background-color: #ffffff;
		margin: 100px auto;
		font-family: Raleway;
		padding: 40px;
		width: 70%;
		min-width: 300px;
	}

	h1 {
		text-align: center;
	}

	input {

		width: 50%;
		font-family: Times New Roman;
		font-size: 20px;
		border: 1px solid #aaaaaa;
	}

	select {
		font-size: 22px;
		font-family: Times New Roman;
	}

	/* Mark input boxes that gets an error on validation: */
	input.invalid {
		background-color: #e43d3d;
	}

	/* Hide all steps by default: */
	.tab {
		display: none;
	}

	button {
		background-color: #04AA6D;
		color: #fbfafa;
		border: none;
		padding: 10px 30px;
		font-size: 17px;
		font-family: Raleway;
		cursor: pointer;
	}

	button:hover {
		opacity: 0.8;
	}

	#prevBtn {
		background-color: #bbbbbb;
	}

	/* Make circles that indicate the steps of the form: */
	.step {
		height: 15px;
		width: 15px;
		margin: 0 2px;
		background-color: #bbbbbb;
		border: none;
		border-radius: 50%;
		display: inline-block;
		opacity: 0.5;
	}

	.step.active {
		opacity: 1;
	}

	/* Mark the steps that are finished and valid: */
	.step.finish {
		background-color: #fcfcfc;
	}
</style>

    <body class="siteStyle">
        <form id="regForm" action="" method="POST">
            <h1>Job Posting Form</h1>
            <div>
                Job Title <p><input class="form-control" oninput="this.className = ''" name="job_title"></p>
                Company Name <p><input class="form-control" oninput="this.className = ''" name="company_name"></p>
                Location <p><input class="form-control" id="location" name="location" oninput="this.className = ''"></p>
                Description <p>
                    <textarea class="form-control" name="description" id="description" rows="50"></textarea>
                <div id="myDIV">
                    <div class="row skills">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Skill 1" name="skills[skill_1]" aria-label="skill 1">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Skill 2" name="skills[skill_2]" aria-label="skill 2">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Skill 3" name="skills[skill_3]" aria-label="skill 3">
                        </div>
                    </div>

                    <div class="row">
                        <p>
                            <div class="input-group">
                                <span class="input-group-text" id="dollarSign">$</span>
                                <input type="text" id="payRate" class="form-control" name="salary[pay_rate]" placeholder="Pay rate">
                                <span class="input-group-text" id="perSpan">per</span>
                                <select name="salary[payment_type]" id="selectPayment" class="form-select">
                                    <option selected value="">Select payment type</option>
                                    <option value="Hour">Hour</option>
                                    <option value="Day">Day</option>
                                    <option value="Week">Week</option>
                                    <option value="Month">Month</option>
                                    <option value="Year">Year</option>
                                </select>
                            </div>
                        </p>
                    </div>

                    <label class="form-label" for="status">Work Status:</label>
                    <select class="form-select" name="work_status" id="status">
                        <option value="">Select</option>
                        <option value="1">Full-time</option>
                        <option value="2">Part-time</option>
                    </select>
                    <br>
                    <label for="setTitle">Select Set Title:</label>
                    <select class="form-control" id="setTitle" name="setTitle" >
                        <?php
                        // Loop through each set_title and create an option for dropdown
                        foreach ($setTitles as $setTitle) {
                            echo "<option value=\"$setTitle\">$setTitle</option>";
                        }
                        ?>
                    </select>
                    <p><a href="JavaScript:newPopup('search.php');">Search Questions</a></p>
                    <br><br><br>
                    <button type="submit" name="submit" id="submit">Submit</button>
                </div>
        </form>


        <script>
            let button = document.getElementById("addButton");

            function addSkill() {
                var divSkills = document.getElementsByClassName("skills")[0];
                var div = document.createElement("div");
                var inputTag = document.createElement("input");
                inputTag.setAttribute("class", "form-control");
                inputTag.setAttribute("placeholder", "Skill");
                div.setAttribute("class", "col");
                div.appendChild(inputTag);
                divSkills.appendChild(div);
            }

            button.addEventListener('click', () => {
                addSkill();
            });
        </script>
    </body>

</html>

<?php require_once(__DIR__."/partials/flash.php");?>