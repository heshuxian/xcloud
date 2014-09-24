$(document).ready(function () {
	var department_id = null;
	var trObj = null;
//	$('a.edit').click(function () {
//		department_id = $(this).parent().attr('department_id');
//		$.get('/account/getdepartmentinfo',{department_id:department_id},function(data){
//			eval('var ret=' + data);
//			if(ret.ret){
//				var n = notyfy({
//					text: '<span>'+ ret.msg +'</span>',
//					type: 'error',
//					timeout: true,
//					layout: 'topCenter',
//					closeWith: ['hover','click','button']
//				});
//			}else{
//				bootbox.dialog({
//					message: ret.html,
//				});
//			}
//		});		
//	});
	$('a.delete').click(function () {
		department_id = $(this).parent().attr('department_id');
		trObj = $(this).parent().parent();
		bootbox.dialog({
			message: '<h3>确定删除？</h3>',
			buttons: {
				main: {
					label: "OK",
					className: "btn-danger",
					callback: function () {
						$.post('/account/deletedepartment/', {department_id: department_id}, function(data){
							eval('var ret=' + data);
							if(ret.ret)
							{
								var n = notyfy({
									text: '<span>'+ ret.msg +'</span>',
									type: 'error',
									timeout: true,
									layout: 'topCenter',
									closeWith: ['hover','click','button']
								});
							}
							else
							{
								var n = notyfy({
									text: '<span>删除部门成功.</span>',
									type: 'success',
									layout: 'topCenter',
									closeWith: ['hover','click','button']
								});
								trObj.remove();
								setTimeout("window.location.reload()",1000);
							}
						});
					}
				}
			}
		});
	});
});