<?php 

/******************************************************

	Class Engine

	Reads SQL files, creates table objects

******************************************************/


class Class_Engine{


    private $tables = array();
    private $sql_fptr;

    private $user_func_start = " ///////////////////////////////////////////////////////////\n".
                               " //\n".
                               " //     END OF AUTOMATED PORTION OF FILE\n".
                               " //     Put any custom functions below.\n".
                               " //     DO NOT DELETE THIS COMMENT\n".
                               " //\n".
                               " ///////////////////////////////////////////////////////////\n\n";

    private $user_func_stop  = " ///////////////////////////////////////////////////////////\n".
                               " //\n".
                               " //     END OF FILE.  ANYTHING AFTER THIS WILL BE LOST.\n".
                               " //     DO NOT DELETE THIS COMMENT\n".
                               " //\n".
                               " ///////////////////////////////////////////////////////////\n";


	public function __construct(){}

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

	public function closeSQL(){
		fclose($this->sql_fptr);
	}


    /**********************************

    SQL Parse Functions

    **********************************/
    
	public function parseFile(){
		
		$line = " ";
        $table_open = false;

		while ($line){
			
			//Get a line from the file
			$line = fgets($this->sql_fptr);			

            //If the table hasn't been opened, look for a CREATE TABLE statement
            if(!$table_open){
                //Check for TABLE CREATE and yank table name
	    		if( preg_match("~CREATE TABLE~", $line) &&
		       	   !preg_match("~VIEW~"  , $line) &&
			    	preg_match("~`[a-z_]+`\.`([a-z_]+)`~", $line, $matches)){


                    //If we found a table, create one and set the 'table_open' flag
                    if(count($matches)){
                        $table = new Table(end($matches));
                        $table_open=true;
                    }
			    }

            //Else, the table is open, and we should parse the line for a column
            } else {

                //Parse the line, see if it's a column
                $column = $this->parseColumn($line);

                //If we got something, add it to the table
                if($column){
                    $table->addColumn($column);

                //If we didn't, close the table, add it to the list
                } else {
                    $table_open = false;

                    if(count($table->getColumns()) > 0){
                        $this->tables[] = $table;
                    }
                }
            }
		}
	}				

	private function parseColumn($line){
		$column_pattern = "~`([a-zA-Z_]+)`\s+([A-Z]+)~";

        $column = null;

        if(!preg_match("~INDEX~", $line) && 
            preg_match($column_pattern, $line, $matches)){

            if(count($matches)){
                $column = new Column($matches[1], $matches[2], preg_match("~NOT\sNULL~", $line));
            }
        }

        return $column;
	}

	/********************************************

	Data Abstraction Layer File Creation Functions

	********************************************/

    public function writeClasses($dir){

		var_dump($this->tables);

        foreach($this->tables as $t){

			echo "Writing class file for ".$t->getName()."\n\n";
            
			$user_fns = null;

            //Check to see if the class file already exists
			if(file_exists($dir.$t->getName().".php")){
				$user_fns = $this->getUserFns($dir."/".$t->getName().".php");
				system("mv ".$dir.$t->getName().".php ".$dir."archive/".$t->getName().".php.old");
			}

			//Write the new file
			$fptr = fopen($dir."/".$t->getName().".php", "w");
			
			fwrite($fptr, $this->getFileHeader());
			fwrite($fptr, $this->getTableDescription($t));
			fwrite($fptr, $this->openTableClass($t));
			fwrite($fptr, $this->getAccessFns($t));
			fwrite($fptr, $this->getDatabaseFns());
			
			fwrite($fptr, $this->user_func_start);
			fwrite($fptr, $user_fns);
			fwrite($fptr, $this->user_func_stop);

			fwrite($fptr, $this->closeTableClass());

			fclose($fptr);
		}
	}

	private function getFileHeader(){

        return 
            "<?php\n".
            "///////////////////////////////////////////////\n".
            "//\n".
            "//  FILE WRITTEN BY SCRIPT database/scripts/create_classes.php\n".
            "//\n".
            "///////////////////////////////////////////////\n\n".
            "require_once(\"query.php\");\n\n";
    }

    private function getTableDescription($table){

        $output = 
            "///////////////////////////////////////////////\n".
            "//\n".
            "//     Table Description\n".
            "//\n";

        foreach($table->getColumns() as $c){
            $output .= 
            "// ".$c->getName()." - ".$c->getType()."\n";
        }
        $output .= 
            "//\n".
            "///////////////////////////////////////////////\n\n";

        return $output;
    }

    private function openTableClass($table){

		$columns = $table->getColumns();

        $output = 
            "class ".$table->getClassName()." {\n\n".
	        "    private \$db;\n".
			"    private \$table = \"".$table->getName()."\";\n\n";

        //Write column variables
        foreach($columns as $c){
            $output .= 
            "    private ".$c->getVarname()." = null;\n";
        }
        $output .= "\n";

        //Write varlist
        $output .=
            "    private \$varlist = array(\n";
        foreach($columns as $c){
            $output .=
            "        \"".$c->getName()."\"";
            if($c != end($columns)){ $output .= ",\n"; }
        }
        $output .= ");\n\n";

        //Write constructor
        $output .= 
            "    public function __construct(){\n".
            "        \$this->db = Query::getInstance();\n".
            "    }\n\n";
        
        return $output;
    }

	private function closeTableClass(){
		return "}\n";
	}

    private function getAccessFns($table){

		$output = 
            "    ///////////////////////////////////////////////////\n".
            "    //\n".
            "    //  Data Access Functions (Setters & Getters)\n".
            "    //\n".
            "    ///////////////////////////////////////////////////\n\n";

        //Write setters & getters
        foreach($table->getColumns() as $c){
            $access_fn = 
            "    ///////////////////////////////////////////////\n".
			"    // Functions for ".$c->getName()."\n".
            "    ///////////////////////////////////////////////\n";

			$access_fn .= $this->getColumnCheckFn($table->getName(), $c);

			$access_fn .=
            "    public function set".$c->getFnName()."(".$c->getVarname()."){\n".
            "       if(\$this->check".$c->getFnName()."(".$c->getVarname()."){\n".
            "           \$this->".strtolower($c->getName())." = ".$c->getVarname().";\n".
            "       }\n".
            "    }\n\n";
            
            $access_fn .=
            "    public function get".$c->getFnName()."(".$c->getVarname()."){\n".
            "        return \$this->".strtolower($c->getName())." = ".$c->getVarname().";\n".
            "    }\n\n\n";

			$output .= $access_fn;
        }

        return $output;
    }

	private function getColumnCheckFn($table_name, $col){
		$check_fn = 
			"	public function check".$col->getFnName()."(".$col->getVarname()."){\n";

		if($col->isNull()){
			$check_fn .=
			"	 	//Not allowed to be NULL\n".
			"		if(Check::isNull(".$col->getVarname().")){\n".
			"			echo \"$table_name->".$col->getName()." cannot be null!\";\n".
			"		}\n";
        } else {
            $check_fn .=
            "       //Allowed to be NULL\n".
            "       if(Check::isNull(".$col->getVarname().")){ return null; }\n";
        }

        switch($col->getType()){
        	case "INT":
        	case "BIGINT":
            	$fn = "notInt";
				$ret = "return intVal(".$col->getVarname().");";
            	break;
        	case "VARCHAR":
            	$fn = "notString";
				$ret = "return ".$col->getVarname().";";
            	break;
        	case "TINYINT":
        	case "BOOLEAN":
            	$fn = "notBool";
				$ret = "return intVal(".$col->getVarname().");";
            	break;
        	case "FLOAT":
				$fn = "notFloat";
				$ret = "return floatVal(".$col->getVarname().");";
				break;
        	case "DOUBLE":
            	$fn = "notFloat";
				$ret = "return doubleVal(".$col->getVarname().");";
            	break;
            case "DATETIME":
            case "TIMESTAMP":
                $fn = null;
                $ret = "return date(\"Y-m-d H:i:s\", ".$col->getVarname().");\n";
                break;
        	default :
            	$fn = "isNull";
                $ret = "return ".$col->getVarname().";";
		}

        if(!is_null($fn)){
            $check_fn .=
            "       //Check the value\n".
            "       if(Check::$fn(".$col->getVarname().")){\n".
            "           echo \"$table_name->".$col->getName()." is invalid!\";\n".
            "           return false;\n".
            "       }\n\n";
        }

        $check_fn .=
            "       $ret\n".
            "   }\n\n";

        return $check_fn;
    } 

    private function getDatabaseFns(){

        $output = 
'	///////////////////////////////////////////////////
	//
	//	Commit (Insert/Update) to DB Function(s)
	//
	///////////////////////////////////////////////////
	
	public function commit(){

    	if($this->filterId($this->id)){
        	return $this->updateRow();
	    } else {
    	    return $this->insertRow();
	    }
	}

	private function insertRow(){

	    //Check for good data, first
    	foreach($varlist as $vname=>$valFn){
        	if(!$this->$valFn($this->$vname)) return false;
	    }

	    //Create the array of variables names and value calls
    	$c_names = "";
	    $v_calls = "";
    	$values = array();
	    foreach(array_keys($varlist) as $v){
    	    $c_names .= "$v";
        	$v_calls .= ":$v";
        	$values[":$v"] = $this->$v;

	        if($v != end(array_keys($varlist)){
    	        $c_names .= ", ";
        	    $v_calls .= ", ";
	        }
    	}

	    //Build the query
    	$sql = "INSERT INTO $this->table ($c_names) VALUES ($v_calls)";

	    return $this->db->insert($sql, $values);
	}

	private function updateRow(){

    	//Check for good data, first
    	foreach($varlist as $vname=>$valFn){
        	if(!$this->$valFn($this->$vname)) return false;
    	}

    	//Create the array of variables names and value calls
    	$c_str = "";
    	$values = array(":id"=>$this->id);
    	foreach(array_keys($varlist) as $v){
        	$c_str .= "$v=:$v";
        	$values[":$v"] = $this->$v;

        	if($v != end(array_keys($varlist)){
            	$c_str .= ", ";
        	}
    	}

    	//Build the query
    	$sql = "UPDATE $this->table SET $c_str WHERE id=:id";

    	return $this->db->update($sql, $values);
	}

';

		return $output;
    }
    

	/********************************************

    File Creation Helper Functions

    ********************************************/

    private function getUserFns($file){

        if($this->isFileReadable($file)){
            $contents = file_get_contents($file);
            
            $user_start = strpos($contents, $this->user_func_start)+strlen($this->user_func_start);
            $user_stop = strpos($contents, $this->user_func_stop)-1;

			if(($user_start == false) || ($user_stop == false)) return null;

            $user_funcs = substr($contents, $user_start, $user_stop-$user_start);

            unset($contents);
            return $user_funcs;
        }
    }

    private function archiveFile($file){
        system("mv $file archive/$file.old");
    }

    public static function getFnName($name){
        $str = "";
        $parts = explode("_", $name);
        foreach($parts as $p){
            $str .= ucfirst($p);
        }
        return $str; 
	}
}


/********************************************

Helper Classes

********************************************/


class Table{

    private $table_name;
    private $class_name;
	private $columns;

    public function __construct($t_name){
        $this->table_name = $t_name;
		$this->class_name = Class_Engine::getFnName($t_name);
        $this->columns = array();
    }

    public function getName(){
        return $this->table_name;
    }

    public function getClassName(){
        return $this->class_name;
    }

    public function addColumn($col){
        $this->columns[] = $col;
    }

    public function getColumns(){
        return $this->columns;
    }
}

class Column {

    private $col_name;
    private $col_fn_name;
    private $varname;
    private $col_type;
    private $is_null;

    public function __construct($c_name, $c_type, $is_null){
        $this->col_name = $c_name;
        $this->varname = "$".strtolower($c_name);
        $this->col_fn_name = Class_Engine::getFnName($c_name);
        $this->col_type = $c_type;
        $this->is_null = $is_null;
    }

    public function getName(){
        return $this->col_name;
    }

    public function getVarname(){
        return $this->varname;
    }

    public function getFnName(){
        return $this->col_fn_name;
    }

    public function getType(){
        return $this->col_type;
    }

    public function isNull(){
        return $this->is_null;
    }
}

?>
