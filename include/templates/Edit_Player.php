<?php

//$page already started up in index.php

$form_action="index.php?view=Edit_Player";
$form_method="post";

//new user inputs
$page->register("player", "select", array("use_post"=>1, "get_choices_array_func"=>"getPlayerListChoices",
				"get_choices_array_func_args"=>"", "reloading"=>1));

$page->register("updateplayer_submit", "submit", array("value"=>"Update Player", "use_post"=>1));
$page->register("deleteplayer_submit", "submit", array("value"=>"Delete Player", "use_post"=>1));

$page->getChoices();

$pid = $page->getVar("player");

$selected=false;

if(!(empty($pid) || ($pid=="0"))){

	$selected=true;

	$p = new Player();

	$pinfo = $p->findPlayerByID($pid);
	$pinfo = $pinfo[0];

	if(strlen($pinfo[location]) == 2){
                $loc="getStates";
		$locType=1;
        } else {
                $loc="getCountries";
		$locType=0;
        }

	if($pinfo[staff]==TRUE){
		$pinfo[staff]="Y";
	} else {
		$pinfo[staff]="N";
	}

	$page->register("firstname", "textbox", array("use_post"=>1, "box_size"=>40, "default_val"=>$pinfo[firstname]));
	$page->register("lastname", "textbox", array("use_post"=>1, "box_size"=>40, "default_val"=>$pinfo[lastname]));
	$page->register("email", "textbox", array("use_post"=>1, "box_size"=>40, "default_val"=>$pinfo[email]));
	$page->register("location_type", "select", array("use_post"=>1, "get_choices_array_func"=>"getLocationType",
                        "get_choices_array_func_args"=>"no", "reloading"=>1, "default_val"=>$locType));

	$page->register("location", "select", array("use_post"=>1, "get_choices_array_func"=>$loc,
                        "get_choices_array_func_args"=>"no", "default_val"=>$pinfo[location]));

	$page->register("forumname", "textbox", array("use_post"=>1, "box_size"=>40, "default_val"=>$pinfo[forumname]));
	$page->register("updateplayer_submit", "submit", array("value"=>"Update Player", "use_post"=>1));

	$page->getChoices();

	if($page->submitIsSet("updateplayer_submit")){
		$page->register("vip", "checkbox", array("use_post"=>1, "on_text"=>"Event VIP", "off_text"=>"Civilian"));
	} else {
		$page->register("vip", "checkbox", array("use_post"=>1, "default_val"=>$pinfo[staff], "on_text"=>"Event VIP", "off_text"=>"Civilian"));
	}
}

if($page->submitIsSet("updateplayer_submit")){
	//perform adding of new player to DB & check for success
	$p = new Player();

	//error checking
	$errors = array();

	$pid = $page->getVar("player");
	$firstname = $page->getVar("firstname");
	$lastname = $page->getVar("lastname");
	$email = $page->getVar("email");
	$forumname = $page->getVar("forumname");


	if(empty($firstname)){$errors[firstname]="Cannot be blank!";}
	if(empty($lastname)){$errors[lastname]="Cannot be blank!";}
	if(empty($email)){
		$errors[email]="Cannot be blank!";
	} /*else if (!preg_match("^[^@]{1,64}@[^@]{1,255}$", $email)){
		$errors[email]="Invalid e-mail!";
	}*/
	if(empty($forumname)){$errors[forumname]="Cannot be blank!";}
	
	$vip = $page->isChecked("vip");

	if(empty($errors)){
		$success = $p->updatePlayerByPlayerID($pid, $firstname, $lastname, $location, $forumname, $email, $vip);

		//include template for echoing back what the admin has entered for the new user
		$page->setDisplayMode("text");
		$template="include/templates/modify_playerConfirmation.tpl";
	}
}

if($page->submitIsSet("deleteplayer_submit")){
	$p = new Player();

	$pid = $page->getVar("player");

	$p->archivePlayer($pid);
} 

if(($page->submitIsSet("updateplayer_submit") && !empty($errors)) || !$page->submitIsSet("updateplayer_submit")){

	$page->setDisplayMode("form");
	$template="include/templates/modify_player.tpl";
}

include($template);

?>
