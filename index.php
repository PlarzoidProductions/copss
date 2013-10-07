<?php

require_once("include/classes/page.php");
//include("include/classes/settings.php");

$testing = 1;

$form_action="index.php";
$form_method="post";

$page = new Page();
$page->register("view", "hidden");

$views=array("Add_Player", "Edit_Player", "List_Players", "Report_Game", "Redeem_Points", "Scoring_Breakdown");
$view = $page->getVar("view");

$s = new Settings();
$title = $s->getName();

include("include/templates/default_header.tpl");
include("include/templates/createTabs.php");
include("include/templates/default_footer.tpl");

?>
