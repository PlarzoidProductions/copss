<?php

//$page already started up in index.php

$form_action="index.php?view=Add_Player";
$form_method="post";

//new user inputs
$page->register("firstname", "textbox", array("use_post"=>1, "box_size"=>40));
$page->register("lastname", "textbox", array("use_post"=>1, "box_size"=>40));
$page->register("email", "textbox", array("use_post"=>1, "box_size"=>40));
$page->register("location_type", "select", array("use_post"=>1, "get_choices_array_func"=>"getLocationType", 
			"get_choices_array_func_args"=>"no", "default_val"=>'1', "reloading"=>1));
$page->register("vip", "checkbox", array("use_post"=>1, "default_val"=>'0', "on_text"=>"Event VIP", "off_text"=>"Civilian")); 

$page->getChoices();

if($page->getVar("location_type")){
	$loc="getStates";
} else {
	$loc="getCountries";
} 

$page->register("location", "select", array("use_post"=>1, "get_choices_array_func"=>$loc,
                        "get_choices_array_func_args"=>"no"));

$page->register("forumname", "textbox", array("use_post"=>1, "box_size"=>40));
$page->register("newplayer_submit", "submit", array("value"=>"Add Player", "use_post"=>1));

//get choices from the choices class to populate stuff in the page class
$page->getChoices();

if($page->submitIsSet("newplayer_submit")){
	//perform adding of new player to DB & check for success
	$p = new Player();

	//error checking
	$errors = array();

	$firstname = $page->getVar("firstname");
	$lastname = $page->getVar("lastname");
	$email = $page->getVar("email");
	$forumname = $page->getVar("forumname");


	if(empty($firstname)){$errors[firstname]="Cannot be blank!";}
	if(empty($lastname)){$errors[lastname]="Cannot be blank!";}
	if(empty($email)){
		//$errors[email]="Cannot be blank!";
		$email="-";
	} /*else if (!preg_match("^[^@]{1,64}@[^@]{1,255}$", $email)){
		$errors[email]="Invalid e-mail!";
	}*/
	if(empty($forumname)){
		//$errors[forumname]="Cannot be blank!";
		$forumname="-";
	}
	
	$vip = $page->getVar("vip");

	if(empty($errors)){
		$success = $p->createNewPlayer($firstname, $lastname, $location, $forumname, $email, $vip);

		//include template for echoing back what the admin has entered for the new user
		$page->setDisplayMode("text");
		$template="include/templates/new_playerConfirmation.tpl";
	}
} 

if(($page->submitIsSet("newplayer_submit") && !empty($errors)) || !$page->submitIsSet("newplayer_submit")){

	$page->setDisplayMode("form");
	$template="include/templates/new_player.tpl";
}

include($template);

?>
