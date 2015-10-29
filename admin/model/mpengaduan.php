<?php
/* contoh models */

class helper_model extends Database {
	
	var $user;
	function __construct()
	{
		$sessi = new Session;
		$getUserSes = $sessi->get_session('login');
		$this->user = $getUserSes['login']['login'];
		$this->prefix = "lelang"; 
	}

	

    
}
?>
