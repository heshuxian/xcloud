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
		$instanceList = $client->GetInstanceList();
		$userList = $this->mp_cloud->Get_UserList();
		if($id)
		{
			$data['userObj'] = $user = User::GetUserById($id);
			$username = $data['userObj']->username;
			$num = count($instanceList->servers);
			for($i=0; $i < $num; $i++)
				if($user->machine_id != $instanceList->servers[$i]->image->id)
				unset($instanceList->servers[$i]);
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
				$instance_id = $this->input->post('selAppointMachine');
				if ($imageList != null)
					foreach ($imageList->images as $imageObj)
						if($machine_id == $imageObj->id)
							$virtualmachine = $imageObj->name;
				if ($instanceList != null)
					foreach ($instanceList->servers as $serverObj)
						if($instance_id == $serverObj->id)
							$instance_name = $serverObj->name;
				$instance_name = $virtualmachine."-".$username;
				if($id)   //edit userinfo
				{
					if($user->instance_id != $instance_id)  //edit instance information
					{
						$delete_instance_id = $user->instance_id;
						if($instance_id == 'auto_distribute')
						{
							$client = new NovaClient();
							$ret = $client->Login("admin","3f368f49fb504702");
							$newInstanceObj = $client->CreatInstance($machine_id,$instance_name);
							if($newInstanceObj != null)
							{
								$instance_id = $newInstanceObj->server->id;
							}
						}else
						{
							$client = new NovaClient();
							$ret = $client->Login("admin","3f368f49fb504702");
							$upDateInstance = $client->UpDateInstance($instance_id, $instance_name);
							foreach($userList as $userObj)
								if($userObj->instance_id == $instance_id)
									user::UpdateUserinfo($userObj->id,$userObj->full_name,$userObj->virtual_machine,$userObj->department,'',$userObj->machine_id,'','');
						}
						$client->DeleteInstance($delete_instance_id);
					}
					if(User::UpdateUserinfo($id,$fullname,$virtualmachine,$department,$password,$machine_id,$instance_id,$instance_name))
						redirect('/account/usermanage');
					else
						$data['error_msg'] = '修改用户失败，请重试';
				}else   // creat new user
				{
					if($instance_id == 'auto_distribute')
					{
						$client = new NovaClient();
						$ret = $client->Login("admin","3f368f49fb504702");
						$newInstanceObj = $client->CreatInstance($machine_id,$instance_name);
						if($newInstanceObj != null)
						{
							$instance_id = $newInstanceObj->server->id;
							$instance_password = $newInstanceObj->server->adminPass;
						}
					}else
					{
						$client = new NovaClient();
						$ret = $client->Login("admin","3f368f49fb504702");
						$upDateInstance = $client->UpDateInstance($instance_id, $instance_name);
						foreach($userList as $userObj)
							if($userObj->instance_id == $instance_id)
							{
								$instance_password = $userObj->instance_password;
								//delete instance information from the old user
								user::UpdateUserinfo($userObj->id,$userObj->full_name,$userObj->virtual_machine,$userObj->department,'',$userObj->machine_id,'','','');
							}
					}
					if(User::CreateUser($username,$password,$fullname,$virtualmachine,$department,$machine_id,$instance_id,$instance_name,$instance_password)){
						redirect('/account/usermanage');
					}else{
						$data['error_msg'] = '创建用户失败，请重试';
					}
				}
			}
		}
		if($imageList != null)
			$data['imageList'] = $imageList;
		if($instanceList != null)
			$data['instanceList'] = $instanceList;
		$content = $this->load->view('account/adduser', $data, TRUE);
		$scriptExtra = '<script type="text/javascript" src="/public/js/jquery.validate.min.js"></script>';
		$scriptExtra .= '<script type="text/javascript" src="/public/js/adduser.js"></script>';
		$this->mp_master->Show($content, $scriptExtra , "添加用户" , $data);
	}
	function getinstancelist()
	{
		$jsonRet = array();
		$list = array();
		$image_id = $this->input->get('image_id');
		$client = new NovaClient();
		$ret = $client->Login("admin","3f368f49fb504702");
		$instanceList = $client->GetInstanceList();
		foreach($instanceList->servers as $instanceObj)
		{
			$obj = new stdClass();
			if($image_id == $instanceObj->image->id)
			{
				$obj->name = $instanceObj->name;
				$obj->value = $instanceObj->id;
				array_push($list, $obj);
			}
		}
		if(count($list) > 0){
			$jsonRet['ret'] = 0;
			$jsonRet['list'] = $list;
		}else{
			$jsonRet['ret'] = 1;
		}
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
