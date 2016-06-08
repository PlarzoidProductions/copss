<?php

require_once("check.php");

class Choices {

	//choices arrays


	function Choices() {
		//do nothing, really
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
	
	function getIntegerChoices($min, $max, $step){
		$ret = array();
            if(Check::notInt($min)){echo "Bad min value!";}
            if(Check::notInt($max)){echo "Bad max value!";}
            if(Check::notInt($step)){echo "Bad step value!";}

		for($i=$min; $i<=$max; $i+=$step){
			$ret[] = array('value'=>$i, 'text'=>$i);
		}

		return $ret;
	}
	
    function sortDirectionChoices(){
        return array(array("text"=>"Descending", "value"=>"1"),
                     array("text"=>"Ascending", "value"=>"0"));
    }

}

?>
