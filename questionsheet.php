<?php

require_once(__DIR__."/partials/nav.php");

if (!has_role("hr")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: " . get_url("home.php")));
}
?>

<html>
<!--   
<div id="list1" class="dropdown-check-list" tabindex="100">
  <span class="anchor">Select Fruits</span>
  <ul class="items">
    <li><input type="checkbox" />Apple </li>
    <li><input type="checkbox" />Orange</li>
    <li><input type="checkbox" />Grapes </li>
    <li><input type="checkbox" />Berry </li>
    <li><input type="checkbox" />Mango </li>
    <li><input type="checkbox" />Banana </li>
    <li><input type="checkbox" />Tomato</li>
  </ul>
</div>-->
</html>