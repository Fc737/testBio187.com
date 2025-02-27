<?php
require_once(__DIR__ . "/../lib/functions.php");
//Note: this is to resolve cookie issues with port numbers
$domain = $_SERVER["HTTP_HOST"];
if (strpos($domain, ":")) {
    $domain = explode(":", $domain)[0];
}
$localWorks = true; //some people have issues with localhost for the cookie params
//if you're one of those people make this false

//this is an extra condition added to "resolve" the localhost issue for the session cookie
if (($localWorks && $domain == "localhost") || $domain != "localhost") {
    session_set_cookie_params([
        "lifetime" => 60 * 60,
        "path" => "$BASE_PATH",
        "domain" => $_SERVER["HTTP_HOST"] || "localhost",
        "domain" => $domain,
        "secure" => true,
        "httponly" => true,
        "samesite" => "lax"
    ]);
}
session_start();

error_log("Session data: " . var_export($_SESSION, true));

?>
<!-- include css and js files -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js" integrity="sha512-8cU710tp3iH9RniUh6fq5zJsGnjLzOWLWdZqBMLtqaoZUA6AWIE34lwMB3ipUNiTBP5jEZKY95SfbNnQ8cCKvA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo get_url('styles.css'); ?>">
<nav class="navbar navbar-light sticky-top bg-light mb-1">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Lists</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav align-items-center flex-grow-1 pe-3">
                    <?php if (!isLoggedIn()): ?>
                    <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("home-guest.php");?>">Home</a>
                    </li>
                    <?php endif; ?>

                    <?php if (isLoggedIn()) : ?>
                        <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("home.php");?>">Home</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ((!has_role('hr') && !has_role('admin')) && isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo get_url("job_archive_user.php"); ?>"><span class="las la-clipboard"></span>Job Archive User</a>
                        </li>
                        <?php endif; ?>
                        <?php if(has_role('hr')): ?>     
                                   
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("hr.php");?>"><span class="las la-users"></span>Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("job_form.php");?>"><span class="las la-users"></span>Post a Job</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("job_archive.php"); ?>"><span class="las la-clipboard"></span>Job Archive</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("questionnaire3.php"); ?>"><span class="las la-clipboard"></span>Questionnaire</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo get_url("QuestionArchive.php"); ?>"><span class="las la-clipboard"></span>Question Archive</a>
                            </li>
                            <?php endif;?>
                            <?php if(has_role('admin')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("admin/admin.php");?>"><span class="las la-users"></span>Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("admin/admin.php?name=admins");?>"><span class="las la-users"></span>Admin List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("admin/admin.php?name=admins");?>"><span class="las la-clipboard"></span>HR List</a>
                                </li>
                                <li class="nav-item">
                                      <a class="nav-link" href="<?php echo get_url("admin/admin.php?name=c_users");?>"><span class="las la-application"></span>Candidates List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("job_archive_user.php"); ?>"><span class="las la-clipboard"></span>Job Archive User</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("job_archive.php"); ?>"><span class="las la-clipboard"></span>Job Archive</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("QuestionArchive.php"); ?>"><span class="las la-clipboard"></span>Question Archive</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_url("questionnaire3.php"); ?>"><span class="las la-clipboard"></span>Questionnaire</a>
                                </li>
                                <?php endif;?>
                                
                    </ul>
                </div>
            </div>
            
            <?php if(isLoggedIn()): ?>            
                <a class="navbar-brand" href="#">
                    <img src="<?php echo get_url("bio-187-logo.png");?>" alt="Bio187-logo" width="120" height="25">
                </a>
                <div class="btn-group">
                    <a href="#" class="dropdown-toggle text-black text-decoration-none"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="textnone"><?php echo $_SESSION['user']['l_name'].', '. $_SESSION['user']['f_name']?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo get_url("logout.php"); ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            
            <?php else:?>
                <div class="contianer">
                    <a class="btn btn-success" href="<?php echo get_url("login.php"); ?>">Login</a>
                    <a class="btn btn-success" href="<?php echo get_url("register.php"); ?>">Signup</a>
                </div>
            <?php endif?>
        </div>
    </nav>
