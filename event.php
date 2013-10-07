<?php

require_once("include/classes/page.php");
//require_once("include/classes/player.php"); //included in settings, in choices, in page

$page = new Page();

$form_action="event.php";
$form_method="post";

$s_db = new Settings();
$title = $s_db->getName();

//register global information
$page->register("numplayers", "select", array("use_post"=>1, "get_choices_array_func"=>"getEventPlayerCountChoices",
                        "get_choices_array_func_args"=>"no", "default_val"=>'2', "reloading"=>1));

$page->register("event_chooser", "select", array("use_post"=>1,  "get_choices_array_func"=>"getEvents",
			"get_choices_array_func_args"=>"no"));

$page->register("submit_event", "submit", array("use_post"=>1, "value"=>"Award Event Points"));

//see how many players we have
$numplayers = $page->getVar("numplayers");

//register variables for each
for($i=1; $i<=$numplayers; $i++){
	$page->register("player".$i, "select", array("get_choices_array_func"=>"getPlayerListChoices", "get_choices_array_func_args"=>"no"));

}

//populate select boxes
$page->getChoices();

//if game was submitted, do some bookkeeping
if($page->submitIsSet("submit_event")){

	$p = new Player();	

	$event = $page->getVar("event_chooser");

	//extract info from input
	for($i=1; $i<=$numplayers; $i++){
		$pid = $page->getVar("player".$i);
		if(!empty($pid)){
			$p->setEvent($event, $pid, true);
			$p->updatePoints($pid);
		}
	}
}

if($page->submitIsSet("submit_event")){
        $page->setDisplayMode("text");
} else {
        $page->setDisplayMode("form");
}

include("include/templates/default_header.tpl");
include("include/templates/eventReport_header.tpl");
include("include/templates/eventReport.tpl");
include("include/templates/eventReport_footer.tpl");
include("include/templates/default_footer.tpl");

?>
