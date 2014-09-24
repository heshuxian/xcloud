$(document).ready(function(){
	$('#adddepartment_form').validate({
		// 设置验证规则
		rules : {
			txtName : {
				required : true,
				minlength : 2,
				remote: '/account/checkdepartment'
			},
			txtMemo : {
				required : true
			}
		},
		// 设置错误信息
		messages : {
			txtName : {
				required : '请填写部门名',
				minlength : '用户名最少2个字符',
				remote : '该部门名已存在，请填写其它站名'
			},
			txtMemo : {
				required : '请填写部门描述'
			}
		},
		errorClass:'alert-danger'
	});
	$('#btnSave').click(function(){
		var bRet = true;
		bRet &= $('#txtName').valid();
		bRet &= $('#txtMemo').valid();
		return bRet;
	});
	
});