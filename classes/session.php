<?php

include_once('db_users.php');

class Session {

	public static function init () {
		session_start();
	}

	public static function userid() {
		return $_SESSION[userid];
	}

	public static function isLoggedIn() {
		return $_SESSION[is_logged_in];
	}

	public static function isNotLoggedIn() {
		return !$_SESSION[is_logged_in];
	}

	public static function isAdmin() {
            if(isset($_SESSION[is_admin])) return $_SESSION[is_admin];

	    if(Session::isLoggedIn()){
		$u = new Users();
		$u = $u->getbyId($_SESSION[userid]);

		if($u){
		    if($u[0][admin]) {
			return true;
		    } 
		}
	    }
	    return false;
	}

	public static function isNotAdmin() {
		return !Session::isAdmin();
	}

        public static function getUsername() {
                if(Session::isLoggedIn()){
                        $u = new Users();
                        $u = $u->getById($_SESSION[userid]);
                        
                        if($u){
                		return $u[0][username];
			} else {
		
                		return "Failed to find user in database!";
			}
		} else {
			return false;
		}
        }	

	public static function getUserID() {
		if(Session::isLoggedIn()){
			return $_SESSION[userid];
		}
		return false;
	}

	public static function isAuthorized($level) {
		//Firstly, everyone is authorized to see public pages
                if(!strcmp($level, "PUBLIC")){return true;}//remember, strcmp returns 0 on match :p

		if(Session::isLoggedIn()){
			//before we go any further, admins are always authorized
			if(Session::isAdmin()){return true;}

			$u = new User();
			$u = $u->getById($_SESSION[userid]);
			
			if($u){

				if($u->getAuthLevel() >= $level) {
					return true;
				}
			
			}
		}
		return false;
	}
	
	public static function authenticate($uname, $upass) {
                $u = new Users();
                $u = $u->getByUsername($uname);

                //strip wrapper
                if(is_array($u)) $u = $u[0];

		if ($u){
			if(!strcmp($upass, $u[password])){
				Session::login($u);
				return $u;
			}
		}
		return false;
	}

	public static function login($u) {
		$_SESSION[userid] = $u[id];
		$_SESSION[is_logged_in] = true;
		$_SESSION[is_admin] = $u[admin];
	}

	public static function logout() {
		unset($_SESSION[userid]);
		unset($_SESSION[is_logged_in]);
		unset($_SESSION[is_admin]);
	}

}

?>
