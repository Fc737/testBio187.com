<?php require_once(__DIR__ . "/partials/nav.php");?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
    </head>
    <style>
        body {
            background-color: #C0DCFC;
            font-family: Times New Roman;
            font-size: 18px;
        }
    </style>
    <body>
        <div class="container bg-white p-4 shadow-sm">
            <h1>Register</h1>
            <form action="" method="POST" style="width: 100%;">
                
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="f_name">First name</label>
                        <input class="form-control" type="text" id="f_name" name="f_name" required maxlength="25" required/>
                    </div>
                    <div class="col-1">
                        <label class="form-label" for="middle">M</label>
                        <input class="form-control" type="text" name="middle" maxlength="2"/>
                    </div>
                    <div class="col">
                        <label class="form-label" for="l_name">Last name</label>
                        <input class="form-control" type="text" name="l_name" required maxlength="25" required/>
                    </div>
                </div>
                
                <div class="row mb-3 g-3">
                    <div class="col-12">
                        <input class="btn btn-primary" type="button" value="Find Me" onclick="here()">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="street">Street </label>
                        <input class="form-control" id="street" type="text" name="street" required maxlength="50" required/>
                    </div>
                    <div class="col">
                        <label class="form-label" for="city">City</label>
                        <input class="form-control" id="city" type="text" name="city" required maxlength="25" required/>
                    </div>
                    <div class="col">
                        <label class="form-label" for="state">State</label>
                        <select class= "form-select" name="state" id="state">
                            <option value=" ">Select</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label" for="zip">Zip Code</label>
                        <input class="form-control" id="zip" type="number" name="zip" required>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" id="email" name="email" required />
                    </div>
                    
                    <div class="col-6">
                        <label class="form-label" for="email">Confirm Email</label>
                        <input class="form-control" type="email" id="confirm_email" name="confirm_email" required />
                    </div>

                    <div class="col">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" type="text" name="username" required maxlength="30" required/>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="pw">Password</label>
                        <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
                    </div>
                    <div class="col">
                        <label class="form-label" for="confirm">Confirm password</label>
                        <input class="form-control" type="password" id="confirm_pw" name="confirm" required minlength="8" />
                    </div>
                </div>
                <input type="submit" class="mt-3 btn btn-primary" value="Register" />
            </form>
        </div>
    </body>
</html>

<link rel="stylesheet" href="styles.css">
<?php
// require(__DIR__."/lib/sanitizers.php");
// require(__DIR__."/lib/safer_echo.php");
// require(__DIR__."/lib/db.php");
// echo "<pre>".var_export($_POST,true)."</pre>";

$hasError = false;
if (isset($_POST["f_name"])&& isset($_POST["l_name"])&& isset($_POST["street"])&&isset($_POST["city"])&&
    isset($_POST["state"])&& isset($_POST["zip"])&& isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])){
        
        $error_log= "";
        $f_name = se($_POST["f_name"],null,"",false);
        $l_name = se($_POST["l_name"],null,"",false);
        $street = se($_POST["street"],null,"",false);
        $city = se($_POST["city"],null,"",false);
        $state = se($_POST["state"],null,"",false);
        $zip = se($_POST["zip"],null,"",false);
        $email = sanitize_email($_POST["email"]);
        $confirm_email = sanitize_email($_POST["confirm_email"]);
        $username = se($_POST["username"],null,"",false);
        $username = strtolower($username); //lowercase username
        $password = se($_POST["password"],null,"",false);
        $confirm_password = se($_POST["confirm"],null,"",false);
        $middle = (isset($_POST["middle"])&& strlen($_POST["middle"]) == 1) ? $_POST["middle"] : NULL;
        if(empty($f_name)){
            $hasError = true;
            $error_log .= "Empty f_name,";
        }
        
        if(empty($l_name)){
            $hasError = true;
            $error_log .= "Empty l_name,";

        }
        if(empty($street)){
            $hasError = true;
            $error_log .= "Empty street,";
        }
        if(empty($city)){
            $hasError = true;
            $error_log .= "Empty city,";
        }

        if(empty($state)){
            $hasError = true;
            $error_log .= "Empty state,";
        }

        if(empty($zip)){
            $hasError = true;
            $error_log .= "Empty zip,";

        }
        
        if(empty($password)){
            $hasError = true;
            $error_log .= "Empty pw,";

        }
        
        if (!is_valid_email($email)){
            $hasError= true;
            $error_log .= "Invalid Email,";
        }
        
        if (!is_valid_email($confirm_email)){
            $hasError= true;
            $error_log .= "Invalid cEmail,";
        }

        if($email !== $confirm_email){
            $hasError=true;
            $error_log .= "Unmatched emails,";
        }
        

        if(empty($confirm_password)){
            $hasError = true;
            $error_log .= "Empty cpw,";
        }

        if (!is_valid_password($_POST["password"])){
            $hasError= true;
            $error_log .= "Invalid password,";
        }

        if ($password !== $confirm_password){
            $error_log .= "Unmatched password,";
            $hasError=true;
        }  

        if (empty($username)){
            $hasError= true;
            $error_log .= "Invalid username,";
        }
        
        
        if(!$hasError){
            $hash = password_hash($password,PASSWORD_BCRYPT);
            $db = getDB();
            try{
                $query="INSERT INTO Users(f_name, middle_initial, l_name, email_address, username, password) 
                Values(:f_name, :middle_initial, :l_name,:email_address,:username, :password)";
                $stmt = $db->prepare($query);
                $stmt->execute([":f_name" => $f_name , ":middle_initial"=> $middle, ":l_name" => $l_name, ":email_address" => $email, ":username" => $username,":password" => $hash]);
                
                $id = $db -> lastInsertId();
                $query="INSERT INTO Address(users_id, street, city, state, zip_code) 
                Values(:uid, :street, :city,:state,:zip_code)";
                $stmt = $db->prepare($query);
                $stmt->execute([":uid" => $id, ":street" => $street, ":city" => $city, ":state" => $state, "zip_code" => $zip]);
                
                flash("Account Created Successfully", "success");                
            }
            catch(PDOException $e){
                flash("Error occured", "warning");
            }
        }
        else{
            $error_arr = explode(',',$error_log);
            foreach($error_arr as $err){
                if($err != ""){
                    flash($err, "warning");
                }
            } 
        }
    }

?>
<?php require_once(__DIR__ . "/partials/flash.php");?>
<script>
    let street = document.getElementById("street");
    let city = document.getElementById("city");
    let state = document.getElementById("state");
    let zip = document.getElementById("zip");
    function here(){
        const successfulLookup = position => {
            const { latitude, longitude } = position.coords;
            fetch(`https://api.opencagedata.com/geocode/v1/json?q=${latitude}+${longitude}&key=cf4bee3d451743afacd78018023405b3`)
            .then((response) => response.json())
            .then(data =>{
                components = data.results[0].components ;
                console.log(components)
                var street_str= "";
                street.value = components.road;
                city.value = components.town;
                state.value = components.state_code;
                zip.value = components.postcode;
            })

        };
        window.navigator.geolocation.getCurrentPosition(successfulLookup,console.log);
    }
    let password = document.getElementById("pw");    
    let password_confirm = document.getElementById("pw");    
    document.createElement("div");
    document.appendChild()
    password.addEventListener("input",(event))
</script>
