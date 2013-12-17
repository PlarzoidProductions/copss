<?php

include("classes/session.php");

Session::init();

//Hijack the $_SESSION var for now
$_SESSION[userid] = 1;
$_SESSION[is_logged_in] = true;
$_SESSION[is_admin] = true;


//If we're here and not logged in, turn and run
if(!Session::isLoggedIn()){
    include("login.php");
    return;
}

//Pull the view from the request variable
$view = $_REQUEST[view];

//if request var is empty, pick a default view
if(empty($view)){
    $view = "register_player";
}

include ("acumen/$view.php");

?>

