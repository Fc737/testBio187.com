<?php
// Retrive jobs by HR id
require_once(__DIR__ . "/partials/nav.php");
require_once(__DIR__ . "/lib/functions.php");

// Check if this user is an HR or admin
if (!has_role("hr") && (!has_role("admin"))) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: " . get_url("home.php")));
}

if (isset($_POST["edit"])) {
    // Edit stuff in here
    $title = se($_POST, "job_title", "", false);
    $location = se($_POST, "location", "", false);
    $description = se($_POST, "description", "", false);
    $payRate = se($_POST["salary"], "pay_rate", "", false);
    $paymentType = se($_POST["salary"], "payment_type", "", false);
    $work_status = se($_POST, "work_status", "", false);
    $jobId = se($_POST, "jobId", "", false);

    $hasError = false;
    $errorLog = "";
    if (empty($title)) {
        $hasError = true;
        $errorLog .= "Missing Title ,";
    }

    if (empty($description)) {
        $hasError = true;
        $errorLog .= "Missing Description ,";
    }

    if (empty($location)) {
        $hasError = true;
        $errorLog .= "Missing Location ,";
    }

    if(empty($work_status)){
        $hasError = true;
        $errorLog .= "Missing work-status ,";
    }

    if (empty($payRate)) {
        $hasError = true;
        $errorLog .= "Missing pay rate ,";
    }

    if (empty($paymentType)) {
        $hasError = true;
        $errorLog .= "Missing payment type ,";
    }
    
    if (empty($jobId)) {
        $hasError = true;
        $errorLog .= "Missing job id ,";
    }

    if (!(in_array($paymentType, array('Hour', 'Day', 'Month', 'Year')))) {
        $hasError = true;
        $errorLog .= "Invalid payment type ,";
    }

    if (!(in_array($work_status, array('1', '2')))) {
        $hasError = true;
        $errorLog .= "Invalid work-status ,";
    }

    $options = array('options' => array('min_range' => 0));
    if (!filter_var($payRate, FILTER_VALIDATE_FLOAT, $options)) {
        $hasError = true;
        $errorLog .= "Invalid salary";
    }

    if (!(in_array($work_status,array('1','2')))){
		$hasError = true;
		$errorLog .= "Invalid work-status ,";
	}
    
    if (!$hasError){
        $u_id = $_SESSION['user']['id'];
        $salary = $payRate.' / '.$paymentType; # Job Salary
        $db = getDB();
        $query = "UPDATE JobPosting SET title = :title, location = :location,
        description = :description, work_status = :work_status, salary = :salary
        WHERE hr_id = :hrId AND id = :jobId";

        $stmt = $db->prepare($query);
        try{
            $stmt->execute([":title" => $title, ":location" => $location, ":description" => $description,
            ":work_status" => $work_status, ":salary" => $salary, ":hrId" => $u_id, ":jobId" => $jobId]);
            flash("Job saved successfully","success");
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

$db = getDB();
$jobList = array();
$id = $_SESSION['user']['id'];
$query = "SELECT * FROM JobPosting WHERE hr_id = :id";
$stmt = $db->prepare($query);
try {
    $stmt->execute([":id" => $id]);
    $jobList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($jobList) == 0) {
        echo "<center><h1>No Jobs Found</h1></center>";
    }
} catch (PDOException $e) {
    echo "error";
} ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!--<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>-->

</head>

<body>
    <?php if ($jobList > 0) : ?>
        <?php foreach ($jobList as $job) :
            $created = se($job, 'created', "", false);
            $created = ($created !== "") ? explode(' ', $created)[0] : "";
        ?>
            <div id="hori" class="">
                <h4 class="mb-1"><a href=""><?php echo $job['title'] . ' - ' . $job['location'] . ' - ' . $job['id']; ?></a></h4>
                <ul class="mb-2">
                    Job Title: <li class="item" id="jobTitle"><b><?php echo $job['title']; ?></b></li>
                    Work Status: <li class="item" id="work_status" value="<?php echo $job['work_status']; ?>"><b><?php echo $job['work_status']; ?></b></li>
                    Posted On: <li class="item"><b><?php echo $created  ?></b></li>
                    Pay Rate: <li class="item" id="pay_rate"><b><?php echo $job['salary']; ?></b></li>
                </ul>
                <div id="hori2" class="word-break-word"><?php echo $job['description']; ?></div>
                <div class="job_skills mt-2">
                    <ul class="mb-2">
                        <?php
                        $skills = json_decode($job['skills'], true);
                        foreach ($skills as $skill) : ?>
                            <li class="item"><?php echo $skill; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- job-listing End -->
                <div class="apply">
                    <a class="mr-2 mb-2 btn btn-primary btn-sm position-static edit" data-bs-toggle="modal" onclick="edit(this)" data-bs-target="#formModal">Edit</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="modal fade" id="formModal" aria-labelledby="formModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Job Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="form1">
                        <div class="mb-2">
                            <label class="form-label" for="job_title">Job Title</label>
                            <input class="form-control" id="job_title" name="job_title">
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="company_name">Company Name</label>
                            <input class="form-control" id="company_name"  name="company_name">
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="location">Location</label>
                            <input class="form-control" id="location" name="location">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                        </div>

                        <div class="input-group mb-2">
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
                        <label class="form-label" for="status">Work Status:</label>
                            <select class="form-select" name="work_status" id="status">
                                <option value="">Select</option>
                                <option value="1">Full-time</option>
                                <option value="2">Part-time</option>
                            </select>
                        <input type="hidden" id="jobId" name="jobId">
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form1" name="edit" value="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    h4 {
        margin-top: 0;
    }

    .item {
        display: inline-block;
        margin-right: 14px;
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
        font-size: 15px;
        background: lightgray;
        padding: 20px;
    }

    #hori2 {
        overflow: hidden;

        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* number of lines to show */
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    ul {
        padding-left: 0px;
    }

    .apply {
        position: absolute;
        right: 2%;
        top: 10%;
    }
</style>

<script>
    function edit(anchor) {
        var form = document.querySelector('.modal-body form');
        const parentDiv = anchor.closest("div#hori");
        const lst = parentDiv.querySelector("h4").textContent.split('-');
        const salary = parentDiv.querySelector('#pay_rate').textContent.split('/');
        form.job_title.value = lst[0].trim();
        form.location.value = lst[1].trim();
        form.jobId.value = lst[2].trim();
        form.description.value = parentDiv.querySelector('#hori2').textContent;
        form.payRate.value = salary[0].trim();
        form.work_status.value = parentDiv.querySelector('#work_status').textContent;
        form.selectPayment.value = salary[1].trim();
    }
</script>
<?php require(__DIR__ . "/partials/flash.php");;?>
</html>