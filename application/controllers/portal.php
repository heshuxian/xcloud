<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mp_cloud');
		$this->load->model('mp_master');
	}

	public function test()
	{
		$client = new NovaClient();
		$ret = $client->Login("admin","3f368f49fb504702");
//		$client->GetInstance("e8d0a273-26ce-4e91-b507-c9a403c0f1cc");
//		$client->CreatInstance("bef51907-2f51-400f-b802-ad08e2e5a1c2","test_1");
	}

	public function GetUserGuest()
	{
		$user = $this->input->post('username');
		$pass = $this->input->post('password');
		if(User::ValidUser($user,$pass,false) == 1)
		{
			$user = User::GetUserByName($user);
			if(!empty($user->instance_id))
			{
				$ret = array();
				$conn = libvirt_connect('qemu:///system', false);
				$res = libvirt_domain_lookup_by_uuid_string($conn, $user->instance_id);
				if($res)
				{
					$port = libvirt_domain_xml_xpath($res, '/domain/devices/graphics[@type="spice"]/@port');
					$ret["port"] = $port[0];
					//user libxml to parse password out
					$xml = simplexml_load_file("/var/lib/nova/instances/".$user->instance_id."/libvirt.xml");
					$ret["pass"] = strval($xml->devices->graphics[1]["password"]);
					//$pass = libvirt_domain_xml_xpath($res, '/domain/devices/graphics/@pass');
					echo json_encode($ret);
					return;
				}else{
					echo json_encode(array("ret"=>1, "msg"=>"Guest is not started"));
					return;
				}
			}else{
				echo json_encode(array("ret"=>1, "msg"=>"No guest alloc"));
				return;
			}
		}
		echo json_encode(array("ret"=>1,"msg"=>"Incorrect Password"));

	}
	public function index()
	{
		$this->login();
		//		$data = array();
		//		$content = $this->load->view('index', $data, TRUE);
		//		$this->mp_master->Show($content, "", "主页" , $data);
	}
	public function login()
	{
		$data = array();
		$data['site_name'] =  $this->config->item('site_name');
		$data['pageTitle'] = '登录';
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('txtUsername','用户名','trim|required');
			$this->form_validation->set_rules('txtPassword', "密码",'required');
			if ($this->form_validation->run())
			{
				$username = $this->input->post('txtUsername');
				$password = $this->input->post('txtPassword');
				if($username != 'admin')
				{
					redirect("/login");
				}
				if(User::LogInUser($username, $password))
				{
					redirect("/account");
				}else{
					$data['msg'] = "您的用户名密码不匹配!";
				}
			}
			$data['msg'] = "登录失败";
		}
		$data['scriptExtra'] = '<script type="text/javascript" src="/public/js/jquery.validate.min.js"></script>';
		$data['scriptExtra'] .= '<script type="text/javascript" src="/public/js/login.js"></script>';
		$this->load->view('login',$data);
	}
}
