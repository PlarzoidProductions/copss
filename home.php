<?php

require_once("classes/session.php");

Session::init();

//If we're here and not logged in, turn and run
/*
if(!Session::isLoggedIn()){
    include("login.php");
    return;
}
*/

//Pull the view from the request variable
if(isset($_REQUEST["view"])){
	$view = $_REQUEST["view"];
} else {
    $view = "leaderboard";
}

if((@include("acumen/$view.php")) == false){;
    include ("acumen/404.php");
}
?>

