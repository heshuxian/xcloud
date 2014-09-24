$(document).ready(function () {
	var user_id = null;
	var trObj = null;
//	$('a.edit').click(function () {
//		user_id = $(this).parent().attr('user_id');
//		$.get('/account/getuserinfo',{user_id:user_id},function(data){
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
		user_id = $(this).parent().attr('user_id');
		trObj = $(this).parent().parent();
		bootbox.dialog({
			message: '<h3>确定删除？</h3>',
			buttons: {
				main: {
					label: "OK",
					className: "btn-danger",
					callback: function () {
						$.post('/account/deleteuser/', {user_id: user_id}, function(data){
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
									text: '<span>删除用户成功.</span>',
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