<?php

include_once('db_users.php');

class Session {

	function init () {
		session_start();
	}

	function userid() {
		return $_SESSION[userid];
	}

	function isLoggedIn() {
		return $_SESSION[is_logged_in];
	}

	function isNotLoggedIn() {
		return !$_SESSION[is_logged_in];
	}

	function isAdmin() {
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

	function isNotAdmin() {
		return !Session::isAdmin();
	}

        function getUsername() {
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

	function getUserID() {
		if(Session::isLoggedIn()){
			return $_SESSION[userid];
		}
		return false;
	}

	function isAuthorized($level) {
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
	
	function authenticate($uname, $upass) {
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

	function login($u) {
		$_SESSION[userid] = $u[id];
		$_SESSION[is_logged_in] = true;
		$_SESSION[is_admin] = $u[admin];
	}

	function logout() {
		unset($_SESSION[userid]);
		unset($_SESSION[is_logged_in]);
		unset($_SESSION[is_admin]);
	}

}

?>
