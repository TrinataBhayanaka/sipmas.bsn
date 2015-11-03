<?php

class tatacara extends Controller {
	
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
		
		$data=$this->contentHelper->getContent(2,1);
// pr($data);
        $this->view->assign('data',$data[0]);
        $this->view->assign('user',$this->user);
		return $this->loadView('tatacara');
    }
}

?>
