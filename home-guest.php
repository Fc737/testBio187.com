<?php
require_once(__DIR__ . "/partials/nav.php");

$postings = array();
$db = getDB();
$query = "SELECT jp.id, jp.title, hr_id, company_name, location, jp.description AS description, salary,
skills, job_status, jp.created, js.description AS work_status
  FROM JobPosting AS jp 
INNER JOIN JobSettings as js ON jp.work_status = js.id";
$stmt = $db->prepare($query);
try {
    $stmt->execute();
    $jobList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($jobList) != 0) {
        $postings = $jobList;
    } else {
        echo "<h1>No Jobs Found</h1>";
    }
} catch (PDOException $e) {
    echo "<pre>".var_export($e->getMessage(),true)."</pre>"; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Main</title>
</head>

<body>
    <?php if ($jobList > 0) : ?>
        <div class="container">
        <?php foreach ($jobList as $job) :
            $created = se($job, 'created', "", false);
            $created = ($created !== "") ? explode(' ', $created)[0] : "";
        ?>
            <div id="hori" class="">
                <h4 class="mb-1 jobTitle"><a href=""><?php echo $job['title'] . ' - ' . $job['location'] . ' - ' . $job['id']; ?></a></h4>
                <ul class="mb-2">
                    <li class="item">Job Title: <b><?php echo $job['title']; ?></b></li>
                    <li class="item">Work Status: <b><?php echo $job['work_status']; ?></b></li>
                    <li class="item">Posted On: <b><?php echo $created;?></b></li>
                    <li class="item">Pay Rate: <b><?php echo '$'.$job['salary']; ?></b></li>
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
                <!-- job_skills End -->
                <div class="apply">
                    
                </div>
            </div>
        <?php endforeach; ?>
        </div>

    <?php endif; ?>

</body>

<style>
    .jobTitle {
        margin-top: 0;
    }

    body {
        background-color: #C0DCFC;
        font-family: Times New Roman;
        font-size: 18px;
    }

    .item {
        display: inline-block;
        margin-right: 14px;
    }
    .open{
        color: darkgreen;
        background-color: lightgreen;
        padding: 5px;
        border-radius: 1px;
        
    }
    #hori {
        position: relative;
        border-radius: 10px;
        box-shadow: 0px 2px black;
        margin: 10px;
    }

    #hori {
        font-size: 20px;
        background: lightgray;
        padding: 20px;
    }

    #hori2 {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    a{
        text-decoration: none;
        color: rgb(66, 134, 244);
    }
    a:hover{
        color: red;
    }

    ul {
        padding-left: 0;
    }

    .apply {
        position: absolute;
        right: 2%;
        top: 10%;
    }
</style>
<?php require_once(__DIR__."/partials/flash.php");?>
</html>