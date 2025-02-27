<?php
/**
 * This function returns a list of the users in the system: Candidate users, HR, or admins
 * Takes in 2 parameters 
 * @param table for the table name of required users
 * @param condition is optional 
 */
function showList($table= "", $condition= ""){
    $db = getDB();
    $list= [];
    $query = "";
    if ($table === 'Users'){
        $query = "SELECT f_name as firstname, l_name as lastname , email_address as email, created, isActive FROM Users";
    } else if ($table === 'Employees'){
        $query = "SELECT firstname, lastname, email, role, created, isActive FROM Employees";
    }
    
    $stmt = $db -> prepare($query);

    try{
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($list) == 0){
            return "No results were found";
        }
    } catch(PDOException $e) {
        var_export($e->getMessage());
    }
    return $list;
}

function updateUser($table = "", $users= []){
    $db = getDB();
    $query = "";
    $params = [];
    if ($table === 'Users'){
        $query = "UPDATE Users SET";
    } else{
        return false;
    }
    foreach($users as $user => $arr){
        $firstname = se($arr, "firstname", "", false);
        $lastname = se($arr, "lastname", "", false);
        $status = se($arr, "status", "", false);
        $email = se($arr, "email", "", false);

        $query .= " modified = CURRENT_TIMESTAMP";

        if ($firstname){
            $query .= ", f_name = :firstname";
            $params[":firstname"] = $firstname;
        }

        if ($lastname){
            $query .= ", l_name = :lastname";
            $params[":lastname"] = $lastname;
        }

        if ($status == "0" || $status == "1"){
            $query .= ", isActive = :status";
            $params[":status"] = $status;
        }
        
        $email_address = is_valid_email($email);
        if(!$email_address){
            $params = array();
            $query = "UPDATE Users SET";
            continue;
        }
        $query .= " WHERE email_address = :email";
        $params[":email"] = $email_address;
        $stmt = $db -> prepare($query);
        
        try{
            $stmt -> execute($params);
            $params = array();
            $query = "UPDATE Users SET";
        } catch(PDOException $e) {
            var_export($e -> getMessage());
        }
    }
}

function updateEmployee($table = "", $users= []){
    $db = getDB();
    $query = "";
    $params = [];
    if ($table === 'Employees'){
        $query = "UPDATE Employees SET";
    } else{
        return false;
    }
    foreach($users as $user => $arr){
        $firstname = se($arr, "firstname", "", false);
        $lastname = se($arr, "lastname", "", false);
        $status = se($arr, "status", "", false);
        $email = se($arr, "email", "", false);

        $query .= " modified = CURRENT_TIMESTAMP";

        if ($firstname){
            $query .= ", firstname = :firstname";
            $params[":firstname"] = $firstname;
        }

        if ($lastname){
            $query .= ", lastname = :lastname";
            $params[":lastname"] = $lastname;
        }

        if ($status == "0" || $status == "1"){
            $query .= ", isActive = :status";
            $params[":status"] = $status;
        }
        
        $email_address = is_valid_email($email);
        if(!$email_address){
            $params = array();
            $query = "UPDATE Employees SET";
            continue;
        }
        $query .= " WHERE email = :email";
        $params[":email"] = $email_address;
        $stmt = $db -> prepare($query);
        
        try{
            $stmt -> execute($params);
            $params = array();
            $query = "UPDATE Employees SET";
        } catch(PDOException $e) {
            var_export($e -> getMessage());
        }
    }
}

/**
 * 
 */
function validate($array){
    $error_log= array();
    $f_name = trim(se($array,"f_name","",false));
    $l_name = trim(se($array,"l_name","",false));
    $email = se($array,"email","",false);
    $email = sanitize_email($email);
    $confirm_email = se($array,"confirm_email","",false);
    $confirm_email = sanitize_email($confirm_email);
    $role = se($array,"role","",false);
    $password = se($array,"password","",false);
    $confirm_password = se($array,"confirm","",false);
    if(empty($f_name)){
        $hasError = true;
        array_push($error_log,"Empty first name");
    }
    
    if(empty($l_name)){
        $hasError = true;
        array_push($error_log,"Empty last name");
    }
    
    if(empty($password)){
        $hasError = true;
        array_push($error_log,"Empty password");
    }

    if(empty($role)){
        $hasError = true;
        array_push($error_log,"Empty role");
    }

    if (!(in_array($role, array("admin", "hr")))){
        $hasError = true;
        array_push($error_log,"Invalid role");
    }
    
    if (!is_valid_email($email)){
        $hasError= true;
        array_push($error_log,"Invalid email address");
    }
    
    if (!is_valid_email($confirm_email)){
        $hasError= true;
        array_push($error_log,"Invalid confirm email");
    }

    if($email !== $confirm_email){
        $hasError=true;
        array_push($error_log,"Unmatched emails");
    }

    if(empty($confirm_password)){
        $hasError = true;
        array_push($error_log,"Empty confirm password");
    }

    if (!is_valid_password($_POST["password"])){
        $hasError= true;
        array_push($error_log,"Invalid passwords");
    }

    if ($password !== $confirm_password){
        $hasError=true;
        array_push($error_log,"Unmatched passwords");
    }
    return $error_log;
}
?>
