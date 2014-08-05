<?php
class MP_Master extends CI_Model
{
	function __construct(){
		// Call the Model constructor
		parent::__construct();
	}

	function Show($mainPlaceHolder,$headerPlaceHolder,$pageTitle,$data)
	{	
		$user = User::GetCurrentUser();
		$dbObj = $this->load->database('default',true);
		
		$data['site_name'] = $this->config->item('site_name');
		$data['headerPlaceHolder'] = $headerPlaceHolder;
		$data['pageTitle'] = $pageTitle;
		$data['mainPlaceHolder'] = $mainPlaceHolder;
		$data['user'] = $user;
		$this->load->view('master',$data);
	}
}
?>