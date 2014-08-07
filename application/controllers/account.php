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
		$client = new NovaClient();
		$ret = $client->Login("admin","3f368f49fb504702");
		$imageList = $client->GetImageList();
		if ($imageList != null)
			$data['imageList'] = $imageList;
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
	function adduser()
	{
		$data = array();
		$id = $this->input->get('user_id');
		$data['actTab'] = 'usermanage';
		$data['departmentList'] = $this->mp_cloud->Get_DepartmentList();
		$client = new NovaClient();
		$ret = $client->Login("admin","3f368f49fb504702");
		$imageList = $client->GetImageList();
		$instantList = $client->GetInstantList();
		$userList = $this->mp_cloud->Get_UserList();
		if($id)
		{
			$data['userObj'] = $user = User::GetUserById($id);
			$username = $data['userObj']->username;
			$num = count($instantList->servers);
			for($i=0; $i < $num; $i++)
			{
				if($user->machine_id != $instantList->servers[$i]->image->id)
				{
					unset($instantList->servers[$i]);
				}
			}
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->load->library('form_validation');
			$id = $this->input->post('txtId');
			
			$this->form_validation->set_rules('txtUserFullName',"用户全名",'trim|required');
			$this->form_validation->set_rules('selVirtualMachine',"虚拟机系统",'trim|required');
			$this->form_validation->set_rules('selDepartment',"部门",'trim|required');
			$this->form_validation->set_rules('selAppointMachine',"指定虚拟机",'trim|required');
			if(!$id){
				$this->form_validation->set_rules('txtUsername',"用户登陆名",'trim|required');
				$this->form_validation->set_rules('txtPassword',"密码",'trim|required');
				$this->form_validation->set_rules('txtConfirmPassword',"确认密码",'trim|required');
			}
			if ($this->form_validation->run() == TRUE)
			{
				if(!$id)
					$username = $this->input->post('txtUsername');
				$password = $this->input->post('txtPassword');
				$fullname = $this->input->post('txtUserFullName');
				$machine_id = $this->input->post('selVirtualMachine');
				$department = $this->input->post('selDepartment');
				$instant_id = $this->input->post('selAppointMachine');
				if ($imageList != null)
					foreach ($imageList->images as $imageObj)
						if($machine_id == $imageObj->id)
							$virtualmachine = $imageObj->name;
				if ($instantList != null)
					foreach ($instantList->servers as $serverObj)
						if($instant_id == $serverObj->id)
							$instant_name = $serverObj->name;
				$instant_name = $virtualmachine."-".$username;
				if($instant_id == 'auto_distribute')
				{
					error_log("virtualmachine:");
					error_log($virtualmachine);
					$client = new NovaClient();
					$ret = $client->Login("admin","3f368f49fb504702");
					$newInstantObj = $client->CreatInstant($machine_id,$instant_name);
					if($newInstantObj != null)
					{
						$instant_id = $newInstantObj->server->id;
					}
				}else
				{
					$client = new NovaClient();
					$ret = $client->Login("admin","3f368f49fb504702");
					$upDateInstant = $client->UpDateInstant($instant_id, $instant_name);
					foreach($userList as $userObj)
						if($userObj->instant_id == $instant_id)
							user::UpdateUserinfo($userObj->id,$userObj->full_name,$userObj->virtual_machine,$userObj->department,'',$userObj->machine_id,'','');
				}
				if($id){
					if(User::UpdateUserinfo($id,$fullname,$virtualmachine,$department,$password,$machine_id,$instant_id,$instant_name))
					{
						redirect('/account/usermanage');
					}else{
						$data['error_msg'] = '修改用户失败，请重试';
					}
				}else{
					if(User::CreateUser($username,$password,$fullname,$virtualmachine,$department,$machine_id,$instant_id,$instant_name)){
						redirect('/account/usermanage');
					}else{
						$data['error_msg'] = '创建用户失败，请重试';
					}
				}
			}
		}
		if($imageList != null)
			$data['imageList'] = $imageList;
		if($instantList != null)
			$data['instantList'] = $instantList;
		$content = $this->load->view('account/adduser', $data, TRUE);
		$scriptExtra = '<script type="text/javascript" src="/public/js/jquery.validate.min.js"></script>';
		$scriptExtra .= '<script type="text/javascript" src="/public/js/adduser.js"></script>';
		$this->mp_master->Show($content, $scriptExtra , "添加用户" , $data);
	}
	function getinstantlist()
	{
		$jsonRet = array();
		$list = array();
		$image_id = $this->input->post('image_id');
		$client = new NovaClient();
		$ret = $client->Login("admin","3f368f49fb504702");
		$instantList = $client->GetInstantList();
		$j = 0;
		foreach($instantList->servers as $instantObj)
		{
			if($image_id == $instantObj->image->id)
			{
				$list[$j] = $instantObj->name;
				$list['$j'] = $instantObj->id;
				$j++;
			}
		}
		$jsonRet['num'] = $j;
		$jsonRet['list'] = $list;
		echo json_encode($jsonRet);
		return; 
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
			$data['departmentList'] = $this->mp_cloud->SearchDepartment($department_name,$memo);
		$data['allDepartmentList'] = $userList = $this->mp_cloud->Get_DepartmentList();
		$content = $this->load->view('account/departmentmanage', $data, TRUE);
		$scriptExtra =  '<script type="text/javascript" src="/public/js/departmentmanage.js"></script>';
		$this->mp_master->Show($content, $scriptExtra, "部门管理" , $data);
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
