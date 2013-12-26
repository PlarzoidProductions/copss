<?php

require_once("db_players.php");
require_once("db_countries.php");
require_once("db_states.php");

class Choices {

	//choices arrays


	function Choices() {
		//do nothing, really
	}

        function getConfigureModes(){
            //$modes = array("countries", "states", "game_systems", "game_system_factions", "game_sizes");
            $ret = array(   array("text"=>"Countries", "value"=>"countries"),
                            array("text"=>"States", "value"=>"states"),
                            array("text"=>"Game Systems", "value"=>"game_systems"),
                            array("text"=>"Factions", "value"=>"game_system_factions"),
                            array("text"=>"Game Sizes", "value"=>"game_sizes")
                        );

            return $ret;
        }


/*
	function getRedeemFunctionChoices(){
		return array(array("text"=>"Spend Points", "value"=>"SPEND"),array("text"=>"Add Points", "value"=>"ADD"));
	}


	function getYesNoChoices($default="No"){
		$yes = array("value"=>1, "text"=>"Yes");
		$no  = array("value"=>0, "text"=>"No");
		//returns choices for a Yes/No select box, with the default set to $default
		if(($default=="No") || ($default=="no") || ($default=="NO") 
			|| ($default==false) || ($default == "false") || ($default=="FALSE")){
			return array($no, $yes);
		} else {	
			return array($yes, $no);
		}
	}

	
	function getPlayerListChoices(){
		$p = new Player();

		$players = $p->getActivePlayers();

		$ret = array(array("text"=>"", "value"=>0));

		if(empty($players)){return $ret;}	
		foreach($players as $player){
			$ret[] = array("text"=>$player[lastname].', '.$player[firstname], "value"=>$player[id]);
		}

		return $ret;
	}
	
	function getNumOpponentsChoices(){
		$ret = array();

		for($i=2; $i<=10; $i++){
			$ret[] = array('value'=>$i, 'text'=>$i);
		}

		return $ret;
	}

	function getEventPlayerCountChoices(){
		$ret = array();

		for($i=1; $i<=64; $i++){
			$ret[] = array('value'=>$i, 'text'=>$i);
		}
		
		return $ret;
	}

	function getEvents(){

		$ret = array();
		$ret[] = array('value'=>'', 'text'=>'');		

		$s_db = new Settings();

		$settings = $s_db->getSettings();

		for($i=1; $i<=20; $i++){	
			if(($settings['event'.$i]+0) > 0){
				$ret[]=array('value'=>'event'.$i, 'text'=>$settings['event'.$i.'name']);
			}
		}
		return $ret;
	}
*/
	function getStates($parent_id){
                $s = new States();

                $states = $s->getByParent($parent_id);

                if($states){
                    $ret = array();

                    foreach($states as $state){
                        $ret[] = array("value"=>$state[id], "text"=>$state[name]);
                    }

		    return $ret;
                }

                return null;
	}

	function getCountries(){

                $c = new Countries();
                $countries = $c->getAll();
                
                if($countries){
                    foreach($countries as $country){
                        $ret[] = array("value"=>$country[id], "text"=>$country[name]);
                    }
		    return $ret;
                }

                return null;
	}
}
?>
