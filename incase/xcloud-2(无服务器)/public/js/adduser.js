$(document).ready(function(){
	$('#adduser_form').validate({
		// 设置验证规则
		rules :{
			txtUsername : {
				required : true,
				minlength : 5,
				remote: '/account/checkaccount'
			},
			txtPassword : {
				required : true,
				minlength: 5
			},
			txtPassword_edit : {
				minlength: 5
			},
			txtConfirmPassword : {
				required : true,
				minlength: 5,
				equalTo: "#txtPassword"
			},
			txtConfirmPassword_edit : {
				minlength: 5,
				equalTo: "#txtPassword_edit"
			},
			txtUserFullName : {
				required : true,
				minlength : 2
			},
			selDepartment : {
				required : true
			},
			selVirtualMachine : {
				required :true
			}
		},
		// 设置错误信息
		messages :{
			txtUsername : {
				required : '请填写用户登陆名',
				minlength : '用户名最少5个字符',
				remote : '该用户名已被注册，请填写其它用户名'
			},
			txtPassword : {
				required : '请填写登陆密码',
				minlength: '密码至少5个字符'
			},
			txtPassword_edit : {
				minlength: '密码至少5个字符'
			},
			txtConfirmPassword_edit : {
				minlength: '密码至少5个字符',
				equalTo: '请填写和上面一致的密码'
			},
			txtConfirmPassword : {
				required : '请填写登陆密码',
				minlength: '密码至少5个字符',
				equalTo: '请填写和上面一致的密码'
			},
			txtUserFullName : {
				required : '请填写用户全名',
				minlength : '用户全名至少2个字符'
			},
			selDepartment : {
				required : '请选择部门'
			},
			selVirtualMachine :{
				required : '请选择虚拟机类型'
			}
		},
		errorClass:'alert-danger'
//		errorElement:"div"
	});
	$('#btnSave').click(function(){
		var bRet = true;
		bRet &= $('#txtUsername').valid();
		bRet &= $('#txtUserFullName').valid();
		bRet &= $('#txtPassword').valid();
		bRet &= $('#txtPassword_edit').valid();
		bRet &= $('#txtConfirmPassword_edit').valid();
		bRet &= $('#txtConfirmPassword').valid();
		bRet &= $('#selDepartment').valid();
		bRet &= $('#selVirtualMachine').valid();
		return bRet;
	});
});