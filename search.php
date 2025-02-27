<?php
require_once(__DIR__."/partials/nav.php");
$db=getdb();
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$query3 = "SELECT q.id, q.q_title, qs.setTitle, q.points, q.ans_type 
          FROM Questions q 
          JOIN QuestionSets qs ON q.id = qs.question_id";
if (!empty($keyword)) {
    $query3 .= " WHERE q.q_title LIKE :keyword OR qs.setTitle LIKE :keyword";
}

$stmt3 = $db->prepare($query3);
if (!empty($keyword)) {
    $stmt3->bindValue(':keyword', '%' . $keyword . '%');
}

try {
    $stmt3->execute();
    $result2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<html  lang="en">
    <head>
        <style>
            body {
            background-color: #C0DCFC;
            font-family: Times New Roman;
            font-size: 18px;
            }

            .item {
                display:inline-block;
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
        </style>
    </head>
    <body>
        <form method="GET" action="">
            <label for="keyword">Search keyword:</label>
            <input class="form-control" type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>">
            <input type="submit" value="Search">
        </form>    
        <?php
        $groupedQuestions = array();

        // Group questions by setTitle
        foreach ($result2 as $row) {
            $setTitle = $row['setTitle'];
            if (!isset($groupedQuestions[$setTitle])) {
                $groupedQuestions[$setTitle] = array();
            }
            $groupedQuestions[$setTitle][] = $row;
        }
        ?>

        <?php foreach ($groupedQuestions as $setTitle => $questions): ?>
            <div class="group-container">
            <strong>Set Title: <?php echo $setTitle; ?> (<?php echo count($questions); ?> questions) </strong>
            <?php foreach ($questions as $row): ?>
                <div id="hori" class="">
                    <div class="row skills">
                        <ul>
                            <li class="item">Question: <?php echo $row['q_title']; ?></li>
                            <li class="item">Answer Type: <?php echo $row['ans_type']; ?></li>
                            <?php if ($row['ans_type'] === "Multiple Choice"): ?>
                                Points Assigned:
                                <?php $points = json_decode($row['points']); ?>
                                <?php foreach ($points as $point): ?>
                                    <li class="item"><b><?php echo $point; ?></b></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                Points Assigned: <li class="item"><b><?php echo $row['points']; ?></b></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <br>
        <?php endforeach; ?>
    </body>
</html>