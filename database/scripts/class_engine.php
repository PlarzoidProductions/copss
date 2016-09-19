<?php 

/******************************************************

	Class Engine

	Reads SQL files, creates table objects

******************************************************/


class Class_Engine{


    var $tables;
    var $sql_fptr;


	public function __construct(){
		$this->tables = array();
	} 


    /**********************************

    File & Dir Check Functions

    **********************************/

    public function isFileReadable($file){

        if(!file_exists($file)){
            throw new Exception("Unable to locate file: $file.");
            return false;
        }

        if(!is_readable($file)){
            throw new Exception("Unable to read file: $file.");
            return false;
        }

        return true;
    }


    public function isDirWriteable($dir){
    
        if(!is_dir($dir)){
            throw new Exception("Directory does not exist: $dir.");
            return false;
        }

        if(!is_writable($dir)){
            throw new Exception("Unable to write to output dir: $dir.");
            return false;
        }
 
        return true;
    }


    public function openSQL($file){
        try{
            if($this->isFileReadable($file)){
                $this->sql_fptr = fopen($file, "r");
            }
        } catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
    }


    /**********************************

    SQL Parse Functions

    **********************************/
    
	private function parseFile(){
		
		$line = " ";
        $table_open = false;

		while ($line){
			
			//Get a line from the file
			$line = fgets($this->sql_fptr);			

            if(!$table_open){
                //Check for TABLE CREATE and yank table name
	    		if( preg_match("~CREATE TABLE~", $line) &&
		       	   !preg_match("~VIEW~"  , $line) &&
			    	preg_match("~`[a-z_]+`\.`([a-z_]+)`~", $line, $matches)){

				    $table_name =  end($matches);
			    }
            } else {

                //Parse column
            
            }
		}
	}				

	private function parseColumn($line){
		
		

	}
}


/********************************************

Helper Classes

********************************************/


class Table{

    var $name;
    var $columns;

    public function __construct($name){
        $this->name = $name;
        $this->columns = array();
    }
}

class Column

    var $name;
    var $varname;
    var $type;
    var $null;

    public function __construct($name, $type, $null){
        $this->name = $name;
        $this->varname = "$".strtolower($name);
        $this->type = $type;
        $this->null = $null;
    }
}

?>
