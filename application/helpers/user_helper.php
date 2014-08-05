<?php
/**
 * Smartmeter static Class
 *
 */
class User{

	static $userRole = array();

	function AutoSign(&$outScript)
	{
		$token = $this->input->get("token");
		if($token)
		{
			$dbObj = $this->load->database('yunqi', TRUE);
			$dbObj->where('token', $token);
			$row = $dbObj->get('user_sign_token')->row();
			if(count($row))
			{
				$dbObj->set('token','');
				$dbObj->where('id', $row->id);
				$dbObj->update('user_sign_token');

				$_SESSION["SMARTMETER_REALM"] = $row->realm;
				User::LogInUserId($row->user_id, FALSE, $outScript);

				return true;
			}
		}
		return false;
	}

	function CreateUser($username,$password,$full_name,$virtual_machine,$department)
	{
		$dbObj = $this->load->database('default', TRUE);
		$dbObj->set("username", $username);
		$dbObj->set('password', md5($password));
		$dbObj->set("full_name", $full_name);
		$dbObj->set("department", $department);
		$dbObj->set('virtual_machine', $virtual_machine);
		return $dbObj->insert('user');
	}

// 	function UpdateUserBindingphone($id, $phone)
// 	{
// 		$dbObj = $this->load->database('default',TRUE);
// 		$dbObj->where('id', $id);
// 		$dbObj->set('phone', $phone);
// 		$dbObj->update('user');
// 	}
	function UpdateUserinfo($id,$full_name,$virtual_machine,$department,$password)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('id', $id);
		$dbObj->set('full_name', $full_name);
		$dbObj->set('virtual_machine', $virtual_machine);
		$dbObj->set('department', $department);
		if($password != '')
		{
 			$dbObj->set('password', md5($password));
		}
		return $dbObj->update('user');
	}
	function UpdateUserPasswd($id, $password)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('id', $id);
		$dbObj->set('password', md5($password));
		$dbObj->update('user');
		return $dbObj->get_where('user',array('id'=>$id))->row();
	}
// 	function UpdateLastSendCode($id,$times)
// 	{
// 		$dbObj = $this->load->database('default',TRUE);
// 		$dbObj->where('id', $id);
// 		$dbObj->set('last_sendcode', 'now()', FALSE);
// 		$dbObj->set('today_send_times',$times);
// 		$dbObj->update('user');
// 		return $dbObj->get_where('user',array('id'=>$id))->row();
// 	}
	/**
	 * regernate User's password
	 *
	 * @param string|object $user
	 * @param string $errMsg
	 * @return newPassword or null if error
	 */
	function GeneratePassword($user){
		$dbObj = $this->load->database('default',TRUE);
		if(is_string($user)){
			$user = User::GetUserByName($user);
		}
		if($user){
			$this->load->helper('string');
			$newPassword = random_string('alnum', 12);
			$dbObj->set('password',md5($newPassword));
			$dbObj->where('id',$user->id);
			$dbObj->update('user');
			return $newPassword;
		}
		return null;
	}
	/**
	 * Change User's Password
	 *
	 * @param string|object $user
	 * @param old password $oldPassword
	 * @param new password $newPassword
	 * @param error message $errorMsg
	 * @return bool
	 */
	function ChangePassword($user,$oldPassword,$newPassword,&$errorMsg){
		$this->load->database('default');
		if(is_string($user)){
			$user = User::GetUserByName($user);
		}
		if(!strcmp($user->password,md5($oldPassword))){
			$user->password = md5($newPassword);
			$this->db->where('id',$user->id);
			$this->db->update('user',$user);
			return true;
		}
		$errorMsg = "Incorrect old password!";
		return false;
	}
	function SetInterest($user_id, $interestStr)
	{
		$this->load->database('default');
		$this->db->set('interest', $interestStr);
		$this->db->where('user_id',$user_id);
		$this->db->update('user');
	}
	/**
	 * Get User by Email,UserId,Username
	 *
	 * @param string(email,userid,username) $name
	 * @return Object or Null
	 */

	function GetUserById($id)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbQuery = $dbObj->get_where('user',array('id'=>$id));
		if ($dbQuery->num_rows() > 0){
			return $dbQuery->row();
		}
		return null;
	}
	function GetUserByName($name)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbQuery = $dbObj->get_where('user',array('username'=>$name));
		if ($dbQuery->num_rows() > 0){
			return $dbQuery->row();
		}
		return null;
	}
	function SearchUser($user_name,$full_name,$department,$virtual_machine)
	{
		$dbObj = $this->load->database('default',TRUE);
		if($user_name != null) 
			$dbQuery = $dbObj->like('username', $user_name);
		if($full_name != null)
			$dbQuery = $dbObj->like('full_name', $full_name);
		if($department != null)
			$dbQuery = $dbObj->like('department', $department);
		if($virtual_machine != null)
			$dbQuery = $dbObj->like('virtual_machine', $virtual_machine);
		return $dbQuery->get('user')->result();
	}
	/**
	 * Get Current User
	 *
	 * @return object or null
	 */
	function GetCurrentUser($bForceLoad = false){
		if(User::IsAuthenticated()){
			$userid = $_SESSION["SMARTMETER_USERNAME"];
			$user =  User::GetUserById($userid, $bForceLoad);
			if($user)
			return $user;
			else{
				$this->load->helper("cookie");
				unset($_SESSION['SMARTMETER_USERNAME']);
				unset($_SESSION['SMARTMETER_HASH']);
				unset($_SESSION["SMARTMETER_USERID"]);
				delete_cookie("SMARTMETER_HASH");
			}
		}
		$this->load->library('session');
		$this->session->set_userdata('returnUrl', $_SERVER['REQUEST_URI']);
		header("Location:".site_url('/login'));
		exit();
		//return null;
	}


	/**
	 *
	 * Add/Remove user from role
	 * @param $username
	 * @param $role
	 */
/*	function MarkUserRole($username, $role)
	{
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('user_id', $username);
		$dbObj->where('role',$role);
		$dbQuery = $dbObj->get('user_roles');
		if($dbQuery->num_rows() > 0)
		{
			$dbObj->where('id', $dbQuery->row()->id);
			$dbObj->delete('user_roles');
			return FALSE;
		}
		$dbObj->set('user_id',$username);
		$dbObj->set('role',$role);
		$dbObj->insert('user_roles');
		return TRUE;
	}


	function IsUserInRole($username, $role)
	{
		if(empty($role))
		{
			debug_print_backtrace();
			return;
		}
		if(empty($username)){
			//test myself
			if(!User::IsAuthenticated()){
				return FALSE;
			}
			$username = $_SESSION["SMARTMETER_USERNAME"];
		}
		if(isset(User::$userRole[$username."_".$role])){
			return User::$userRole[$username."_".$role];
		}
		$dbObj = $this->load->database('default',TRUE);
		$dbObj->where('role',$role);
		$dbObj->where('user_id',$username);
		$dbQuery = $dbObj->get('user_roles');

		User::$userRole[$username."_".$role] = ($dbQuery->num_rows() > 0);
		return User::$userRole[$username."_".$role];
	}*/
	/**
	 * Is User logged in
	 *
	 * @return bool
	 */
	function IsAuthenticated(){
		if(isset($_SESSION["SMARTMETER_USERNAME"])){
			//for performance reason
			return true;
		}else{
			//test if cookie exists
			$this->load->helper("cookie");
			$username = get_cookie("SMARTMETER_USERNAME");
			if(isset($username)){
				$user = User::GetUserByName($username);
				if($user){
					if(!strcmp(get_cookie("SMARTMETER_HASH"),md5($username.$user->password))){
						$_SESSION["SMARTMETER_USERNAME"] = $username;
						$_SESSION["SMARTMETER_HASH"] = md5($username.$user->password);
						return true;
					}
				}
			}
		}
		return false;
	}
	/*
	 * Validate user
	 */
	/**
	 * Validate User
	 *
	 * @param string $userName
	 * @param string $pwd
	 * @param string(Is pwd in md5 format) $isMd5
	 * @return -1,user not exists; -2, user expire. -3, password incorrect, 1 passed
	 */
	function ValidUser($userName,$password,$isMd5=false){
		$user = User::GetUserByName($userName, true);
		if(!$user){
			return -1;
		}
		if(!$isMd5){
			$password = md5($password);
		}
		if(!strcmp($user->password,$password)){
			return 1;
		}else{
			return -3;
		}
	}

	/**
	 * LogIn user, if success, user is kept logged in.
	 *
	 * @param username $username
	 * @param string  $password
	 * @param string $isMd5
	 * @return bool
	 */
	function LogInUser($username,$password,$isMd5=false,$isRememberMe=false){

		if(1 == User::ValidUser($username,$password,$isMd5)){
			$user = User::GetUserByName($username);
			return User::LogInUserObj($user,$isRememberMe);
		}
		return false;
	}
	function LogInUserObj($user,$isRememberMe=false){
		$username = $user->id;
		$password = $user->password;
		$sData = array( 'SMARTMETER_USERNAME' => $username, 'SMARTMETER_HASH' =>  md5($username.$password));
		if($isRememberMe){
			$this->load->helper('cookie');

			set_cookie("SMARTMETER_USERNAME",$username,86400*7);
			set_cookie("SMARTMETER_HASH",$sData["SMARTMETER_HASH"],86400*7);
		}
		$_SESSION["SMARTMETER_USERID"] = $user->id;
		$_SESSION['SMARTMETER_USERNAME'] = $username;
		$_SESSION['SMARTMETER_HASH'] = $sData["SMARTMETER_HASH"];
		return true;
	}

	function DeleteUser($userid)
	{
		//Scott 2013-4-17, need to do more works here. Pending
		//Only remove user
		$dbObj = $this->load->database('default', true);
		$dbObj->where('id',$userid);
		return $dbObj->delete('user');
	}
	function Save($user){
		$dbObj = $this->load->database('default', true);
		$dbObj->where('id',$user->id);
		$dbObj->update('user',$user);
	}
	function LogInUserId($userid,$isRememberMe=false,&$outScript=''){
		$user = User::GetUserByName($userid);
		if(!$user){
			return false;
		}
		User::LogInUserObj($user,$isRememberMe,$outScript);
		return true;
	}

	/**
	 * Logout User
	 *
	 */
	function LogOutUser(){
		$this->load->helper("cookie");
		unset($_SESSION['SMARTMETER_USERNAME']);
		unset($_SESSION['SMARTMETER_HASH']);
		unset($_SESSION["SMARTMETER_USERID"]);

		delete_cookie("SMARTMETER_HASH");

	}
}


?>
