<?php

require_once("include/classes/page.php");
require_once("include/classes/settings.php");

$form_action="iasettings.php";
$form_method="post";

$page = new Page();

$settings_db = new Settings();
$title = $settings_db->getName();

$settings = $settings_db->getRawSettings();
$settings = $settings[0];  //strip off container array

//var_dump($settings);

$setting_fields=array('name', 'numgames', 'numplayers', 'numlocations', 'numfactions',
	'event1name', 'event2name', 'event3name', 'event4name', 'event5name', 'event6name', 'event7name', 'event8name', 'event9name', 'event10name',
	'event11name', 'event12name', 'event13name', 'event14name', 'event15name', 'event16name', 'event17name', 'event18name', 'event19name', 'event20name'
	);

$setting_fields2=array('teamgame', 'newopponent', 'outofstate', 'fullypainted', 'fullypaintedall', 'scenariotable',
        'played25', 'played35', 'played50', 'played75', 'played100', 'playedUNBOUND', 'playedall', 'vsstaff',
	'event1', 'event2', 'event3', 'event4', 'event5', 'event6', 'event7', 'event8', 'event9', 'event10', 
	'event11', 'event12', 'event13', 'event14', 'event15', 'event16', 'event17', 'event18', 'event19', 'event20',
	'gametimelimit');

foreach($setting_fields as $s){
	$page->register($s, "textbox", array("use_post"=>1, "box_size"=>40, "default_val"=>$settings[$s]));
}

foreach($setting_fields2 as $s2){
	$page->register($s2, "textbox", array("use_post"=>1, "box_size"=>5, "default_val"=>$settings[$s2]));
}

	$new_settings = array();
	foreach($setting_fields as $s){
		$new_settings[$s] = $page->getVar($s);
	}

	foreach($setting_fields2 as $s2){
		$new_settings[$s2] = $page->getVar($s2);
	}

	$np = $settings[numplayers];
	$nl = $settings[numlocations];
	$nf = $settings[numfactions];
	$ng = $settings[numgames];

	unset($settings[numgames]);
	unset($settings[numplayers]);
	unset($settings[numfactions]);
	unset($settings[numlocations]);


	foreach(explode(";", $ng) as $chunk){
		$g = explode(",", $chunk);
		$settings[numgames][$g[0]]=$g[1];
	}

        foreach(explode(";", $nl) as $chunk){
                $l = explode(",", $chunk);
                $settings[numlocations][$l[0]]=$l[1];
        }

        foreach(explode(";", $nf) as $chunk){
                $f = explode(",", $chunk);
                $settings[numfactions][$f[0]]=$f[1];
        }

        foreach(explode(";", $np) as $chunk){
                $p = explode(",", $chunk);
                $settings[numplayers][$p[0]]=$p[1];
        }
	

	$page->setDisplayMode("text");

include("include/templates/scoring_breakdown.tpl");

?>
