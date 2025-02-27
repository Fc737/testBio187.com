<?php

require_once(__DIR__ . "/partials/nav.php");

// Check if the user is logged in and the user is a regular user
// Validate data

if (!isLoggedIn()) {
    flash("You need to login/create an account in order to apply", "warning");
    die(header("Location: " . get_url("home.php")));
}

if(isset($_POST['submit'])) {
    $f_name = trim(se($_POST, "firstname", "", false));
    $l_name = trim(se($_POST, "lastname", "", false));
    $email = se($_POST, "email", "", false);
    $email = sanitize_email($email);
    $birth_date = se($_POST, "birthday", "", false);
    $date = se($_POST, "date", "", false);
    $visa = se($_POST, "visa", "", false);
    $file = se($_POST, "filename", "", false);
    //$qId[] = se($_POST, "qId[]", false);
    //$answer[] = $_POST["answer[]"];

    $hasError = false;
    $error_log = "";

    if (empty($f_name)) {
        $hasError = true;
        $error_log .= "Empty first name field";
    }

    if (empty($l_name)) {
        $hasError = true;
        $error_log .= "Empty last name field";
    }

    if (empty($file)) {
        $hasError = true;
        $error_log .= "Missing resume field";
    }

    if (!(in_array($visa, array('yes', 'no')))) {
        $hasError = true;
        $error_log .= "Invalid visa input";
    }

    if (!is_valid_email($email)) {
        $hasError = true;
        $error_log .= "Invalid email address";
    }
/*
    if (empty($qID)) {
        $hasError = true;
        $error_log .= "Empty Question ID";
    }

    if (empty($answer)) {
        $hasError = true;
        $error_log .= "Empty answer";
    }
*/
    if (!$hasError) {
        flash("Your application successfully submitted and is under review by the hr", "success");
    } else {
        foreach ($error_log as $err) {
            flash(trim($err), "info");
        }
    }


    if (!$hasError) {
        $db = getDB();
        $id = get_user_id();
        $jobpost_id = $_GET['jobId'];
        //echo $jobpost_id;
        $query = "INSERT INTO JobSubmission(user_post_id, jobpost_id, fir_name, las_name, e_mail, b_day, start_date, visa, resume) 
            VALUES(:user_post_id, :jobpost_id, :f_name, :l_name, :email, :birth_date, :date, :visa, :file)";
       
        try {
            $stmt = $db->prepare($query);
            $stmt->execute([":user_post_id"=>$id,":jobpost_id"=>$jobpost_id,':f_name'=>$f_name, ':l_name'=>$l_name, ':email'=>$email, ':birth_date'=> $birth_date, ":date"=>$date, ':visa'=>$visa, ':file'=>$file]);
          
            flash("Application successfully Submitted!", "success");
        } catch (PDOException $e) {
            echo "<pre>" . var_export($e, true) . "</pre>";
        }

        foreach ($_POST['question'] as $question_id => $answer) {

    
            // Store the user's response in the CompletedQuestions table
            $sql = "INSERT INTO CompletedQuestions (user_id, jobpost_id, question_id, answer) VALUES ('$id', '$jobpost_id', '$question_id', '$answer')";
            if ($db->query($sql) !== TRUE) {
                //echo "Error: " . $sql . "<br>" . $db->error;
            }
        }
    }   
    else {
        $error_arr = explode(',',$error_log);
        foreach ($error_arr as $err) {
            if ($err != "") {
                flash($err, "warning");
            }
        }
    }

}
$db = getDB();
$jobpost_id = $_GET['jobId'];
$query2 = "SELECT Questions.id, Questions.q_title, Questions.points, Questions.answers, Questions.ans_type
               FROM Questions JOIN QuestionSets ON Questions.id = QuestionSets.question_id 
               JOIN JobPosting ON QuestionSets.setTitle = JobPosting.setTitle WHERE JobPosting.id = :jobpost_id";
    try {
    $stmt2 = $db->prepare($query2);
    $stmt2->bindParam(':jobpost_id', $jobpost_id);
    $stmt2->execute();
    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e) {
        echo "<pre>" . var_export($e, true) . "</pre>";
    }
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    * {
        box-sizing: border-box;
    }

    body {
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
    
    #job{
        text-align: center;
    }

    input {
        padding: 10px;
        width: 50%;
        font-family: Times New Roman;
        font-size: 25px;
        border: 1px solid #aaaaaa;
    }

    select {
        font-size: 28px;
        font-family: Times New Roman;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #e43d3d;
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

</style>

<body>

    <form id="regForm" method="POST" action="">
        <h1>Application Form</h1>
        <?php if(isset($_GET)):?>
            <?php $jobId = se($_GET,'jobId', "", false);
            $title =se($_GET,'title', "", false)?>
            <h5 id="job"><?php echo $title.' - '. $jobId;?></h5>
        <?php endif;?>
        <div>
            First Name <p><input placeholder="John" type="text" name="firstname" required></p>
            Last Name <p><input placeholder="Evans" type="text" name="lastname"></p>
            Date of Birth <p><input type="date" id="birthday" name="birthday"></p>
            Email Address <p><input placeholder="abc@email.com" name="email"></p>
            Phone No. <p><input type="tel" placeholder="123-456-7890" name="phone"></p>
            Expected Starting Date <p><input type="date" id="date" name="date"></p>
            <label for="visa">Do you have work visa?</label>
            <select name="visa" id="visa">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <br><br><br>

            <b>Upload your Resume<br><br>
            <input type="file" id="myFile" name="filename">

            <?php foreach ($rows as $row): ?>
                <p>
                <strong><?php echo $row['id']; ?>:</strong> <?php echo $row['q_title']; ?>
                </p>
                <?php if ($row['ans_type'] === 'Open Ended'): ?>
                    <input type="textbox" name="question[<?php echo $row['id']; ?>]" required>
                <?php elseif ($row['ans_type'] === 'Multiple Choice'): ?>
                    <?php $options = json_decode($row['answers']); ?>
                    <?php foreach ($options as $option): ?>
                        <label>
                            <input type="radio" name="question[<?php echo $row['id']; ?>]" value="<?php echo $option; ?>" required>
                            <?php echo $option; ?>
                        </label>
                    <?php endforeach; ?>
                <?php elseif ($row['ans_type'] === 'Y/N'): ?>
                    <label>
                        <input type="radio" name="question[<?php echo $row['id']; ?>]" value="yes" required> Yes
                    </label>
                    <label>
                        <input type="radio" name="question[<?php echo $row['id']; ?>]" value="no" required> No
                    </label>
                <?php endif; ?>
                <hr>
            <?php endforeach; ?>        
            <div style="text-align:center;margin-top:40px;">
                <button type="submit" name="submit" form="regForm" id="submit">Submit</button>
            </div>
        </div>
    </form>
</body>
</html>
<?php require(__DIR__ . "/partials/flash.php");?>