<div class='widget'>
	<form class="form-horizontal" method='post' id='edituserinfo_form' action='/account/updateuser'>
		<div class="widget widget-2">
			<div class="form-group">
				<label>用户ID(不可修改)</label>
				<input type="text" readonly value="<?php echo $userObj->id;?>" data-mask="" class="form-control" name = "user_id" id = "user_id" >
			</div>
			<div class="form-group">
				<label>登录用户名(不可修改)</label>
				<input type="text" readonly value="<?php echo $userObj->username;?>" data-mask="" class="form-control" name = "txtUsername" id = "txtUsername" >
			</div>
			<div class="form-group">
				<label>用户全名:</label>
				<input type="text" data-mask="" value=<?php echo $userObj->full_name?> class="form-control" name = "txtUserFullName" id = "txtUserFullName" >
			</div>
			<div class="form-group">
				<label>密码(不修改请勿输入):</label>
				<input type="password" data-mask="" class="form-control" name = "txtPassword" id = "txtPassword" >
			</div>
			<div class="form-group">
				<label>确认密码(不修改请勿输入):</label>
				<input type="password" data-mask=""  class="form-control" name = "txtConfirmPassword" id = "txtConfirmPassword" >
			</div>
			<div class="form-group">
				<label>部门:</label>
				<select   class='form-control' id='selDepartment' name='selDepartment'>
<!-- 						<option value=''>请选择</option> -->
					<?php foreach ($departmentList as $departmentObj){?>
					<option value=<?php echo $departmentObj->name;?> <?php if($userObj->department==$departmentObj->name){?>selected='selected'<?php }?>> <?php echo $departmentObj->name;?></option>
					<?php }?>
				</select>
			</div>
			<div class="form-group">
				<label>虚拟机状态:</label>
				<select id='selVirtualMachine' class='form-control' name='selVirtualMachine'>
<!-- 						<option value='' selected='selected' >请选择</option> -->
					<option value='a' <?php if($userObj->virtual_machine=='a')?>selected='selected'>a</option>
					<option value='b' <?php if($userObj->virtual_machine=='b')?>selected='selected'>b</option>
					<option value='c' <?php if($userObj->virtual_machine=='c')?>selected='selected'>c</option>
				</select>
			</div>
		</div>
		<div class="box-footer" style="text-align:center">
			<button type="submit" class="btn btn-primary" id='btnSave'>保存</button>
		</div>
	</form>
</div>