<!DOCTYPE html>
<html lang="en">

<?php
require_once(__DIR__ . "/../partials/nav.php");
require_once(__DIR__."/admin_functions.php");

if (!has_role("admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: " . get_url("home.php")));
}
?>
<?php 
    $results = array();
    if(isset($_POST["Register"])){
        $error_arr = validate($_POST);
        error_log("error data: " . var_export($error_arr, true) . " COunt :".count($error_arr));
        if (empty($error_arr)){
            $firstname = se($_POST, "f_name", "", false);
            $lastname = se($_POST, "l_name", "", false);
            $role = se($_POST, "role", "", false);
            $email = se($_POST, "email", "", false);
            $password = se($_POST, "password", "", false);
            $number =se($_POST,'phone', '', false);
            $hash = password_hash($password,PASSWORD_BCRYPT);
            $db = getDB();
            try{               
                $query="INSERT INTO Employees (firstname, lastname, email, role, password, work_phone_number) 
                Values(:firstname, :lastname, :email, :role, :password, :work_phone_number)";
                $stmt = $db->prepare($query);
                $stmt->execute([":firstname" => $firstname, ":lastname" => $lastname, ":role" => $role, ":email" => $email, 
                ":password" => $hash, ":work_phone_number" => $number]);
                
                flash("Account Created Successfully","success");
            }
            catch(PDOException $e){
                echo "<pre>".var_export($e->getMessage(),true)."</pre>"; 
            }
        }
        else{
            foreach($error_arr as $err){
                if($err != ""){
                    flash($err, "warning");
                }
            }
        }
    }

    if(isset($_POST["submit"])){        
        if (isset($_POST["u_type"]) && isset($_POST["users"])){
            if ($_POST["u_type"] === "Users"){
                updateUser($_POST["u_type"], $_POST["users"]);
            }
            
            else if ($_POST["u_type"] === "Employees"){
                updateEmployee($_POST["u_type"], $_POST["users"]);
            }
        }
    }

    if (isset($_GET["name"])){
        if ($_GET['name'] === "admins"){
            $results = showList("Employees");

        } elseif ($_GET['name'] === "c_users"){
            $results = showList("Users");
        }

    }
?>

<head>
    <meta charset="UTF-8">
    <title>BIO187 Admin</title>
</head>

<body>
    <div class="container bg-white">
        <h1>Create Account</h1>
        <form action="" method="POST" style="width: 100%;">

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label" for="f_name">First name</label>
                    <input class="form-control" type="text" id="f_name" name="f_name" required maxlength="25" />
                </div>
                <div class="col">
                    <label class="form-label" for="l_name">Last name</label>
                    <input class="form-control" type="text" name="l_name" required maxlength="25" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label" for="role">Choose a Role:</label>
                    <select class= "form-select" name="role" id="role">
                        <option value=" ">Select</option>
                        <option value="hr">Hr</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3 g-3">
                <div class="col-6">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" required />
                </div>

                <div class="col-6">
                    <label class="form-label" for="email">Confirm Email</label>
                    <input class="form-control" type="email" id="email" name="confirm_email" required />
                </div>

                <input type="hidden" name="usertype" value="admin">

                <div class="col">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" type="text" name="username" required maxlength="30" />
                </div>

                <div class="col">
                    <label class="form-label" for="phone">Phone Number</label>
                    <div class="input-group">
                        <input class="form-control" type="tel" id="phone" name="phone" >
                    </div>
                </div>

            </div>
            <div class="row mb-3 g-3">
                <div class="col">
                    <label class="form-label" for="pw">Password</label>
                    <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
                </div>
                <div class="col">
                    <label class="form-label" for="confirm">Confirm password</label>
                    <input class="form-control" type="password" name="confirm" required minlength="8" />
                </div>
            </div>
            <input type="submit" class="my-3 btn btn-primary" name="Register" value="Register" />
        </form>
    </div>

    <?php if (count($results) > 0):?>
        <div class="container bg-white">
            <form class="table" action="" method="POST">
                <table class="table table-striped align-middle w-75">
                    <thead>
                        <?php $type = isset($results[0]['role'])? "Employees": "Users";?>
                        <input type="hidden" name="u_type" value="<?php echo $type;?>">
                        <th scope="col">First name</th>
                        <th scope="col">Last name </th>
                        <th scope="col">Email address</th>
                        <th scope="col">Joined</th>
                        <?php if (isset($results[0]['role'])):?>
                            <th scope="col">Role</th>
                        <?php endif;?>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </thead>
                    <tbody>
                        <?php $counter=0;?>
                        <?php foreach($results as $row): ?>
                            <?php $status = $row['isActive'];?>
                            <tr scope="row">
                                <td>
                                    <input class="form-control" type="text" name="users[<?php echo "user${counter}"?>][firstname]" value="<?php se($row,'firstname');?>">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="users[<?php echo "user${counter}"?>][lastname]" value="<?php se($row,'lastname');?>">
                                </td>
                                <td>
                                    <input class="form-control email" type="text" name="users[<?php echo "user${counter}"?>][email]" value="<?php se($row,'email');?>">
                                </td>
                                <td><?php se($row,'created');?></td>
                                <?php if (isset($row['role'])):?>
                                    <td><?php se($row,'role');?></td>
                                <?php endif;?>
                                <td>
                                    <?php if((int)$status == 0){
                                        echo "Suspended";    
                                    } elseif ((int)$status == 1){
                                        echo "Active";
                                    }?>
                                </td>
                                <td>
                                    <select class="form-select" name="users[<?php echo "user${counter}"?>][status]">
                                        <option value="">Select</option>
                                        <option value="Demote">Demote</option>
                                        <option value="Promote">Promote</option>
                                        <option value="0">Suspend</option>
                                        <option value="1">Activate</option>
                                    </select>
                                </td>
                            </tr>
                        <?php $counter++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <input type="submit" class="my-3 btn btn-primary" name= "submit" value="modify" />

            </form>
        </div>
    <?php endif;?>
</body>

<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<?php
require(__DIR__ . "/../partials/flash.php"); ?>
</html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // The purpose of using JQuery is to only the send the data that is affected by change 
    $(document).ready(function(){
        $('tr input, tr select').on('change', function(){
            $(this).addClass("changed");
            $(this).closest("tr").find("input.email").addClass("changed");
        });

        $('form.table').on('submit', function(){
            $('tr input:not(.changed), tr select:not(.changed)').prop('disabled', true);
        });
    });

    const phoneInputField = document.querySelector("#phone");
    const form = document.querySelector("");
    var iti = window.intlTelInput(phoneInputField, {
        utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });
    phoneInputField.addEventListener('blur', () => {
        if (iti.isValidNumber()){
            var phoneNumber = iti.getNumber();
            console.log(phoneNumber);
            // phoneInputField.classList.value = "form-control is-valid"
        } else {
            // phoneInputField.classList.value = "form-control is-invalid"
        }
    });
    
</script>