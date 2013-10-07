<?php

/*#################################################
#
# Redeem_Points.php
#
# handle redeeming earned points
#################################################*/

require_once("include/classes/payout.php");
require_once("include/classes/player.php");

$form_action="index.php?view=Redeem_Points";
$form_method="post";

$player_db = new Player();

//Let's register some variables

$page->register("player", "select", array("use_post"=>1, "get_choices_array_func"=>"getPlayerListChoices", "get_choices_array_func_args"=>"no", "reloading"=>1));
$page->register("function", "select", array("use_post"=>1, "get_choices_array_func"=>"getRedeemFunctionChoices", "get_choices_array_func_args"=>""));
$page->register("points", "textbox", array("use_post"=>1, "box_size"=>10));
$page->register("notes", "textbox", array("use_post"=>1, "box_size"=>80));
$page->register("redeem_points", "submit", array("value"=>"Redeem Points", "use_post"=>1));

$page->getChoices();

//handle press of the button
if($page->submitIsSet("redeem_points")){

	$pid = $page->getVar("player");
	$pts = $page->getVar("points");
	$nts = $page->getVar("notes");
	$func= $page->getVar("function");

	$payout_db = new Payout();

	$playerinfo = $player_db->findPlayerByID($pid);
	$playerinfo = $playerinfo[0];

	if($func=="ADD"){
		$payout_db->makePayout($pid, $pts, $nts);

                $redemptions = $payout_db->getPayoutsByPlayerID($pid);
	} else {

	        if($playerinfo['points'] >= $pts){
			$payout_db->makePayout($pid, $pts*-1, $nts);

			$redemptions = $payout_db->getPayoutsByPlayerID($pid);

		} else {
	
			$error = "Not enough points, play more games!";

		}
	}
}

if($page->submitIsSet("redeem_points") && empty($error)){
	$page->setDisplayMode("text");

	include("include/templates/payout_listing.tpl");
} else {

	$page->setDisplayMode("form");

	if($page->getVar("player")){
		$playerinfo = $player_db->findPlayerByID($page->getVar("player"));
		$playerinfo = $playerinfo[0];
	}

	$page->setDisplayMode("form");

	include("include/templates/payout_form.tpl");
}

?>
