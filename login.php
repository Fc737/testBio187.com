<?php require_once(__DIR__."/partials/nav.php")?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Login</title>
</head>
<style>
    body {
        background-color: #C0DCFC;
        font-family: Times New Roman;
        font-size: 18px;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="col my-5">
            <img src="bio-187-logo.png" class="img-fluid position-relative start-50 translate-middle-x"
                alt="bio-187-logo" width="800" width="200">
        </div>

        <div class="col position-relative start-50 translate-middle-x w-50">
            <form class="p-4 bg-light rounded-3 shadow-lg" method="post" action="">
                <div class="my-4">
                    <h2>Login</h2>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="email" placeholder="Dummy">
                    <label class="email">Username/Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" name="password" placeholder="Dummy">
                    <label class="">Password</label>
                </div>
                <div class="">
                    <button class="btn btn-primary" type="submit" name="login_user">Login</button>
                </div>
                <p class="my-2">
                    Still not a member?
                    <a href="register.php">Sign up</a>
                </p>
                <p>
                    If you are an admin or HR, use this link:
                    <a href="login_employee.php">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>


</html>

<?php

if(isset($_POST["email"]) && isset($_POST["password"])){
    $emailOrUsername = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    $hasError = false;
    
    
    if (str_contains($emailOrUsername, "@")) {
        if (!is_valid_email($emailOrUsername)) {
            $hasError = true;
            flash("Invalid Email", "danger");
        }
    } else {
        $emailOrUsername = strtolower($emailOrUsername); //lowercase username
        if (empty($emailOrUsername)) {
            $hasError = true;
            flash("Missing Email/Username", "danger");
        }
    }

    if(empty($password)){
        $hasError = true;
        flash("Missing password", "danger");
    }

    if (!is_valid_password($password)) {
        $hasError = true;
        flash("Invalid Password", "danger");
    }

    if (!$hasError){
        $db = getDB();
        $stmt = $db ->prepare("SELECT id, f_name, middle_initial, l_name, email_address, username, password, isActive from Users 
        where email_address = :email OR username = :username");
        try{
            $r = $stmt -> execute([":email" => $emailOrUsername, ":username" => $emailOrUsername]);
            if ($r) {
                $user = $stmt -> fetch(PDO::FETCH_ASSOC);
                if ($user){
                    $hash = $user["password"];
                    unset($user["password"]);
                    if (password_verify($password,$hash)){
                        if($user['isActive']  == 0){
                            flash("Your account is suspended", "info");
                            die;
                        }
                        $_SESSION["user"]["id"] = $user["id"];
                        $_SESSION["user"]["f_name"] = $user["f_name"];
                        $_SESSION["user"]["l_name"] = $user["l_name"];
                        $_SESSION["user"]["email"] = $user["email_address"];
                        $_SESSION["user"]["username"] = $user["username"];

                        if(!is_null($user["middle_initial"])){
                            $_SESSION["user"]["middle"]= $user["middle_intial"];
                        }
                        die(header("Location: home.php"));
                    } else {
                        flash("Incorrect Password", "danger");
                    }
                } else {
                    flash("Email/Username is not found", "danger");
                }
            } 
        } catch (PDOException $e){
            echo "<pre>".var_export($e->getMessage(),true)."</pre>"; 
        }
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");?>
