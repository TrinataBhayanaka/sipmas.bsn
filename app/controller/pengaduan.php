<?php

class pengaduan extends Controller {
	
	var $models = FALSE;
	var $view;
	var $reportHelper;
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
		$getUserLogin = $this->isUserOnline();
		$this->user = $getUserLogin[0];
    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
	}
	
	function index(){
		
        $this->view->assign('user',$this->user);
		return $this->loadView('pengaduan/pengaduan');
    }

    function detail()
    {
    	return $this->loadView('pengaduan/detail');
    }
}

?>
