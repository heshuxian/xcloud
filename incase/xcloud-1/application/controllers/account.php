<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
	var $user = null;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mp_master');
		if(!User::IsAuthenticated())
		{
			redirect('/login');
		}
		$this->user = User::GetCurrentUser();
		$this->load->model('mp_cloud');
	}

	public function index()
	{
		$data = array();
		$data['actTab'] = "index";
		$content = $this->load->view('account/index', $data, TRUE);
		$scriptExtra = '';
		$this->mp_master->Show($content, $scriptExtra, "主页" , $data);
	}
	public function usermanage()
	{
		$data = array();
		$data['actTab'] = 'usermanage';
		$user_name = null;
		$full_name = null;
		$department = null;
		$virtual_machine = null;
		$nosearch = true;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$user_name = $this->input->post('txtUserName');
			$full_name = $this->input->post('txtFullName');
			$department = $this->input->post('selDepartment');
			$virtual_machine = $this->input->post('selVirtualMachine');
		}
		if($user_name != null)
			$nosearch = false;
		if($full_name != null)
			$nosearch = false;
		if($department != null)
			$nosearch = false;
		if($virtual_machine != null)
			$nosearch = false;
		if($nosearch == true)
			$data['userList'] = $userList = $this->mp_cloud->Get_UserList();
		else
			$data['userList'] = User::SearchUser($user_name,$full_name,$department,$virtual_machine);
		$data['departmentList'] = $this->mp_cloud->Get_DepartmentList();
		$content = $this->load->view('account/usermanage', $data, TRUE);
		$scriptExtra = '<script type="text/javascript" src="/public/js/usermanage.js"></script>';
		$this->mp_master->Show($content, $scriptExtra , "用户管理" , $data);
	}
	public function deleteuser()
	{
		$jsonRet = array();
		$user_id = $this->input->post('user_id');
		$ret = User::DeleteUser($user_id);
		if($ret){
			$jsonRet['ret'] = 0;
		}
		else{
			$jsonRet['ret'] = 1;
			$jsonRet['msg'] = '删除失败';
		}
		echo json_encode($jsonRet);
		return;
	}
	// 	public function updateuser()
	// 	{
	// 		$user_id = $this->input->post('user_id');
	// 		if($_SERVER['REQUEST_METHOD'] == 'POST')
	// 		{
	// 			$this->load->library('form_validation');
	// 			$this->form_validation->set_rules('txtUserFullName',"用户全名",'trim|required');
	// 			$this->form_validation->set_rules('selVirtualMachine',"虚拟机状态",'trim|required');
	// 			$this->form_validation->set_rules('selDepartment',"部门",'trim|required');
	// 			if ($this->form_validation->run() == TRUE && $this->input->post('txtPassword') == $this->input->post('txtConfirmPassword'))
	// 			{
	// 				$username = $this->input->post('txtUsername');
	// 				$password = $this->input->post('txtPassword');
	// 				$confirm_password = $this->input->post('txtConfirmPassword');
	// 				$fullname = $this->input->post('txtUserFullName');
	// 				$virtualmachine = $this->input->post('selVirtualMachine');
	// 				$department = $this->input->post('selDepartment');
	// 				$ret = User::UpdateUserinfo($user_id,$username,$fullname,$virtualmachine,$department,$password);
	// 				if($ret){
	// 					redirect('/account/usermanage');
	// 				}
	// 				else{
	// 					echo "<script language=\"JavaScript\">\r\n";
	// 					echo " alert(\"Fail to modify!\");\r\n";
	// 					echo " history.back();\r\n";
	// 					echo "</script>";
	// 				}
	// 			}elseif($this->input->post('txtPassword') != $this->input->post('txtConfirmPassword'))
	// 			{
	// 				echo "<script language=\"JavaScript\">\r\n";
	// 				echo " alert(\"Confirm password must be the same as password!\");\r\n";
	// 				echo " history.back();\r\n";
	// 				echo "</script>";
	// 			}else{
	// 				echo "<script language=\"JavaScript\">\r\n";
	// 				echo " alert(\"Please input complete the information!\");\r\n";
	// 				echo " history.back();\r\n";
	// 				echo "</script>";
	// 			}
	// 		}
	// 	}

	public function getuserinfo()
	{
		$jsonRet = array();
		$user_id = $this->input->get('user_id');
		$data['userObj'] = User::GetUserById($user_id);
		$data['departmentList'] = $this->mp_cloud->Get_DepartmentList();
		if($data['userObj'] != null)
		{
			$jsonRet['ret'] = 0;
			$jsonRet['html'] = $this->load->view('/account/edituserinfo',$data,TRUE);
		}else{
			$jsonRet['ret'] = 1;
			$jsonRet['msg'] = '获取用户信息失败，无法编辑用户信息';
		}
		echo json_encode($jsonRet);
		return;
	}
	function adduser()
	{
		$data = array();
		$id = $this->input->get('user_id');
		$data['actTab'] = 'usermanage';
		$data['departmentList'] = $this->mp_cloud->Get_DepartmentList();
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->load->library('form_validation');
			$id = $this->input->post('txtId');
			if(!$id){
				$this->form_validation->set_rules('txtUsername',"用户登陆名",'trim|required');
				$this->form_validation->set_rules('txtPassword',"密码",'trim|required');
				$this->form_validation->set_rules('txtConfirmPassword',"确认密码",'trim|required');
				$this->form_validation->set_rules('txtUserFullName',"用户全名",'trim|required');
				$this->form_validation->set_rules('selDepartment',"部门",'trim|required');
				$this->form_validation->set_rules('selVirtualMachine',"虚拟机状态",'trim|required');
			}else
			{
				$this->form_validation->set_rules('txtUserFullName',"用户全名",'trim|required');
				$this->form_validation->set_rules('selVirtualMachine',"虚拟机状态",'trim|required');
				$this->form_validation->set_rules('selDepartment',"部门",'trim|required');
			}
			if ($this->form_validation->run() == TRUE)
			{
				if(!$id)
				{
					$username = $this->input->post('txtUsername');
					$password = $this->input->post('txtPassword');
					$fullname = $this->input->post('txtUserFullName');
					$virtualmachine = $this->input->post('selVirtualMachine');
					$department = $this->input->post('selDepartment');
				}else {
					$password = $this->input->post('txtPassword_edit');
					$fullname = $this->input->post('txtUserFullName');
					$virtualmachine = $this->input->post('selVirtualMachine');
					$department = $this->input->post('selDepartment');
				}
				if($id){
					if(User::UpdateUserinfo($id,$fullname,$virtualmachine,$department,$password))
					{
						redirect('/account/usermanage');
					}else{
						$data['error_msg'] = '修改用户失败，请重试';
					}
				}else{
					if(User::CreateUser($username,$password,$fullname,$virtualmachine,$department)){
						redirect('/account/usermanage');
					}else{
						$data['error_msg'] = '创建用户失败，请重试';
					}
				}
			}
		}
		if($id){
			$data['userObj'] = User::GetUserById($id);
		}
		$content = $this->load->view('account/adduser', $data, TRUE);
		$scriptExtra = '<script type="text/javascript" src="/public/js/jquery.validate.min.js"></script>';
		$scriptExtra .= '<script type="text/javascript" src="/public/js/adduser.js"></script>';
		$this->mp_master->Show($content, $scriptExtra , "添加用户" , $data);
	}

	function checkaccount()
	{
		$username = trim($this->input->get('txtUsername'));
		if(empty($username))
		{
			echo 'false';
			return;
		}
		$user = User::GetUserByName($username);
		echo $user == null? 'true' :'false';
		return;
	}
	function checkdepartment()
	{
		$departmentname = trim($this->input->get('txtName'));
		if(empty($departmentname))
		{
			echo 'false';
			return;
		}
		$department = $this->mp_cloud->Get_DepartmentByName($departmentname);
		echo $department == null? 'true' :'false';
		return;
	}
	public function departmentmanage()
	{
		$data = array();
		$data['actTab'] = 'department';
		$department_name = null;
		$memo = null;
		$nosearch = true;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$department_name = $this->input->post('txtName');
			$memo = $this->input->post('txtMemo');
		}
		if($department_name != null)
			$nosearch = false;
		if($memo != null)
			$nosearch = false;
		if($nosearch == true)
			$data['departmentList'] = $userList = $this->mp_cloud->Get_DepartmentList();
		else
			$data['departmentList'] = User::SearchDepartment($department_name,$memo);
		$data['allDepartmentList'] = $userList = $this->mp_cloud->Get_DepartmentList();
		$content = $this->load->view('account/departmentmanage', $data, TRUE);
		$scriptExtra =  '<script type="text/javascript" src="/public/js/departmentmanage.js"></script>';
		$this->mp_master->Show($content, $scriptExtra, "部门管理" , $data);
	}
	public function getdepartmentinfo()
	{
		$jsonRet = array();
		$department_id = $this->input->get('department_id');
		$data['departmentObj'] = $departmentObj = $this->mp_cloud->Get_Department($department_id);
		if($departmentObj != null)
		{
			$jsonRet['ret'] = 0;
			$jsonRet['html'] = $this->load->view('/account/editdepartment',$data,TRUE);
		}else{
			$jsonRet['ret'] = 1;
			$jsonRet['msg'] = '获取部门信息失败';
		}
		echo json_encode($jsonRet);
		return;
	}
	public function deletedepartment()
	{
		$jsonRet = array();
		$department_id = $this->input->post('department_id');
		if($this->mp_cloud->Delete_Department($department_id))
		{
			$jsonRet['ret'] = 0;
		}else{
			$jsonRet['ret'] = 1;
			$jsonRet['msg'] = '删除失败';
		}
		echo json_encode($jsonRet);
	}
	public function updatedepartment()
	{
		$department_id = $this->input->post('department_id');
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('txtName',"部门名",'trim|required');
			$this->form_validation->set_rules('txtMemo',"部门描述",'trim|required');
			if ($this->form_validation->run() == TRUE )
			{
				$name = $this->input->post('txtName');
				$memo = $this->input->post('txtMemo');
				$ret = $this->mp_cloud->Update_Department($department_id,$name,$memo);
				if($ret){
					redirect('/account/departmentmanage');
				}
				else{
					echo "<script language=\"JavaScript\">\r\n";
					echo " alert(\"Fail to modify!\");\r\n";
					echo " history.back();\r\n";
					echo "</script>";
				}
			}else{
				echo "<script language=\"JavaScript\">\r\n";
				echo " alert(\"Please input complete the information!\");\r\n";
				echo " history.back();\r\n";
				echo "</script>";
			}
		}
	}
	public function adddepartment()
	{
		$data = array();
		$data['actTab'] = 'department';
		$id = $this->input->get("department_id");
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->load->library('form_validation');
			$id = $this->input->post("txtId");
			if(!$id)
			{
				$this->form_validation->set_rules('txtName',"部门名",'trim|required');
				$this->form_validation->set_rules('txtMemo',"部门描述",'trim|required');
			}else
			{
				$this->form_validation->set_rules('txtMemo',"部门描述",'trim|required');
			}
			if ($this->form_validation->run() == TRUE)
			{
				if(!$id)
				{
					$name = $this->input->post('txtName');
					$memo = $this->input->post('txtMemo');
					if(!$this->mp_cloud->Save_Department($name,$memo))
						$data['error_msg'] = '创建部门失败,请重试';
					else
						redirect("/account/departmentmanage");
				}else
				{
					$memo = $this->input->post('txtMemo');
					if(!$this->mp_cloud->Update_Department($id,$memo))
						$data['error_msg'] = '更新部门失败,请重试';
					else
						redirect("/account/departmentmanage");
				}
			}
		}
		if($id)
			$data['departmentObj'] = $this->mp_cloud->Get_Department($id);
		$data['departmentList'] = $this->mp_cloud->Get_DepartmentList();
		$content = $this->load->view('account/adddepartment', $data, TRUE);
		$scriptExtra = '<script type="text/javascript" src="/public/js/jquery.validate.min.js"></script>';
		$scriptExtra .= '<script type="text/javascript" src="/public/js/adddepartment.js"></script>';
		$this->mp_master->Show($content, $scriptExtra , "添加部门" , $data);
	}
	/*	public function adddepartment($id = 0)
	 {
	$data = array();
	$data['actTab'] = 'department';
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	$this->load->library('form_validation');
	$this->form_validation->set_rules('txtName',"部门名",'trim|required');
	$this->form_validation->set_rules('txtMemo',"部门描述",'trim|required');
	if ($this->form_validation->run() == TRUE)
	{
	$name = $this->input->post('txtName');
	$memo = $this->input->post('txtMemo');
	if($this->mp_cloud->Get_DepartmentByName($name))
	{
	echo "<script language=\"JavaScript\">\r\n";
	echo " alert(\"DepartmentName exists aready!\");\r\n";
	echo " history.back();\r\n";
	echo "</script>";
	return;
	}
	if($this->mp_cloud->Save_Department($name,$memo)){
	redirect('/account/departmentmanage');
	}else{
	$data['error_msg'] = '创建部门失败，请重试';
	}
	}else{
	$data['error_msg'] = '填写信息不全，创建站点失败';
	}
	}
	$data['departmentList'] = $this->mp_cloud->Get_DepartmentList();
	$content = $this->load->view('account/adddepartment', $data, TRUE);
	$scriptExtra = '<script type="text/javascript" src="/public/theme/scripts/jquery-validation/dist/jquery.validate.min.js"></script>';
	$this->mp_master->Show($content, '' , "添加部门" , $data);
	}*/
	/*
	 public function monitor()
	 {
	if($this->user->user_role != 'admin'){
	show_404();
	}
	$data = array();
	$data['actTab'] = 'monitor';
	$data['station_id'] = $station_id = $this->input->get('station_id');
	$data['stationList'] = $this->mp_cloud->Get_StationList();
	$content = $this->load->view('account/index', $data, TRUE);
	$scriptExtra = '<script type="text/javascript" src="/public/js/account/usermanage.js"></script>';
	$this->mp_master->Show($content, $scriptExtra, "监控管理" , $data);
	}*/
	public function logout()
	{
		User::LogOutUser();
		redirect('/login');
	}
}
