<?php

/*************************************************
*
*	This just redirects the page to include whatever the chosen view is, and set sthe default view
*
*************************************************/

//Register the class autoloader
spl_autoload_register(function ($class_name){

	$filename = strtolower($class_name).".php";

	//Look for the core classes
	if(file_exists("classes/$filename")){
		include_once "classes/$filename";
		return;
	}
	

	//Look in the DAL
	else if(file_exists("classes/data_abstraction_layer/db_$filename")){
		include_once "classes/data_abstraction_layer/db_$filename";
		return;
	}

	//give up
	else {		
		throw new Exception("Unable to autoload class: $class_name");
	}
});

//Pull the view from the request variable
if(isset($_REQUEST["view"])){
	$view = $_REQUEST["view"];
} else {
    $view = "register_player";
}


//show the chosen page
if((@include("acumen/$view.php")) == false){;
    include ("acumen/404.php");
}

?>

