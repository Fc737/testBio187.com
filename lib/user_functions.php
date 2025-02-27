<?php

/**
 * Functions checks whether a user is logged in or not
 */
function isLoggedIn($redirect = false){
    $loggedIn = isset($_SESSION["user"]);
    
    if(!$loggedIn && $redirect){
        die(header("Location: login.php"));
    }

    return $loggedIn;
}

/**
 * Return user
 */
function get_user_id()
{
    if (isLoggedIn()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "id", false, false);
    }
    return false;
}

function has_role($role)
{
    if (isLoggedIn() && isset($_SESSION["user"]["role"])) {
        if ($_SESSION["user"]["role"] === $role) {
            return true;
        }
    }
    return false;
}
?>
