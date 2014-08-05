<?php
class MP_Cloud extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function Get_UserList()
	{
		$dbObj = $this->load->database('default',TRUE);
		return $dbObj->get('user')->result();
	}
	function Get_DepartmentList($user_id = FALSE)
	{
		$dbObj = $this->load->database('default',TRUE);
		return $dbObj->get('department')->result();
	}
	function Get_Department($id)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('id',$id);
		return $dbObj->get('department')->row();
	}
	function Get_DepartmentByName($name){
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('name',$name);
		return $dbObj->get('department')->row();
	}
	function Update_Department($id,$memo)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('id',$id);
		if($memo)
		{
			$dbObj->set('memo',$memo);
		}
		return $dbObj->update('department');
	}
	function Delete_Department($id)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('id',$id);
		return $dbObj->delete('department');
	}
	function Save_Department($name,$memo)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->set('name',$name);
		$dbObj->set('memo',$memo);
		return $dbObj->insert('department');
	}
	function SearchDepartment($department_name,$memo)
	{
		$dbObj = $this->load->database('default',TRUE);
		if($department_name != null)
			$dbQuery = $dbObj->like('name', $department_name);
		if($memo != null)
			$dbQuery = $dbObj->like('memo', $memo);
		return $dbQuery->get('department')->result();
	}
}


?>