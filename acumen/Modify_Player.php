<?php

/*--------------------------- Page to allow edits a player --------------------------*/

$form_action=$_SERVER[PHP_SELF]."?view=Edit_Users&subview=Modify_User";
$form_method="post";

$page->register("player_select", "select", array("use_post"=>1, "get_choices_array_func"=>"getPlayerListChoices", "get_choices_array_func_args"=>"no", "reloading"=>1));

$page->getChoices();

$player_selected = $page->getVar("player_select");

//get the selected user's info
$player_to_modify = new Player();//user is included in page class
$player_to_modify = $player_to_modify->findPlayerByUserID($player_selected);

//set form defaults accordingly
if(!is_object($player_to_modify)){
	return false;
}

//user forms to populate
$page->register("firstname", "textbox", array("use_post"=>1, "box_size"=>40, "default_val"=>$player_to_modify["firstname"]));
$page->register("lastname", "textbox", array("use_post"=>1, "box_size"=>40), "default_val"=>$player_to_modify["lastname"]));
$page->register("email", "textbox", array("use_post"=>1, "box_size"=>40), "default_val"=>$player_to_modify["email"]));
$page->register("code", "textbox", array("use_post"=>1, "box_size"=>40));
$page->register("forumname", "textbox", array("use_post"=>1, "box_size"=>40), "default_val"=>$player_to_modify["forumname"]));
$page->register("location_type", "textbox", array("use_post"=>1, "get_choices_array_func"=>"getCountries", 
			"get_choices_array_func_args"=>"no", "reloading"=>1));
:q

$page->register("submit_mods", "submit", array("value"=>"Submit Modifications", "use_post"=>1));

//get choices from the choices class to populate stuff in the page class
$page->getChoices();

//if user was successfully authenticated as an admin, echo some data
if(Session::isAdmin()){
	//if admin has submit data to be entered 
	if($page->submitIsSet("submit_mods")){
		//perform error checking on inputs

		//if username doesn't match what was there before
		if($page->getVar("uname")!==$default_uname){$username_errors = Check::validUsername($page->getVar("uname"));}

		//if a new password was entered
		$upass = $page->getVar("upass");
		if(!empty($upass)){$password_errors = Check::validPassword($upass);}
		//check the error logs, and update user DB & check for success
		if(empty($username_errors) && empty($password_errors)){
			$u = new User();
			$success = $u->updateUser($page->getVar("userid"), $page->getVar("uname"), 
						$page->getVar("upass"), $page->getVar("uadmin"), $page->getVar("uauth"));
		}


		//include template for echoing back what the admin has entered for the modified user
		$page->setDisplayMode("text");
		$template="include/templates/modify_userConfirmation.tpl";

	//admin has successfully loged in, but has yet to enter any new user data
	} else {

		//use template that allows admin to enter a new user into the DB
		$page->setDisplayMode("form");
		$template="include/templates/modify_user.tpl";
	}
} 

include($template);

?>
