<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		主页
		<small>添加用户</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> 主页</a></li>
		<li><a href="/account/usermanage"><i class="fa fa-dashboard"></i> 用户列表</a></li>
		<li class="active"><?php if(isset($userObj)){?>添加<?php } else{?>编辑<?php }?>用户</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="col-md-6 box box-info">
		 <div class="box-header">
			<h3 class="box-title"><?php if(isset($userObj)){?>添加<?php } else{?>编辑<?php }?>用户</h3>
		</div>
		<div class='col-md-4'>
		<?php if(isset($error_msg)){?>
		<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			<b><?php echo $error_msg;?></b>
		</div>
		<?php }?>
		<form method="post" id="adduser_form">
			<div class="box-body">
				<?php if(isset($userObj)){?>
					<input type='hidden' name='txtId' value="<?php echo $userObj->id;?>" />
				<?php }?>
				<div class="form-group">
					<label>账户名:</label>
					<input type="text" data-mask="" <?php if(!isset($userObj)){?>placeholder="请输入账户名" name = "txtUsername" id = "txtUsername" <?php }else{?> readonly <?php }?>class="form-control" value='<?php if(isset($userObj)) echo $userObj->username;?>'/>
				</div>
				<div class="form-group">
					<label>用户全名:</label>
					<input type="text" data-mask="" placeholder="请输入用户全名" class="form-control" name = "txtUserFullName" id = "txtUserFullName" value='<?php if(isset($userObj)) echo $userObj->full_name;?>'/>
				</div>
				<div class="form-group">
					<label>密码:</label>
					<input type="password" data-mask="" <?php if(isset($userObj)){?>placeholder="不修改请勿输入"  name = "txtPassword_edit" id = "txtPassword_edit"<?php }else{?>placeholder="请输入密码" name = "txtPassword" id = "txtPassword"<?php }?> class="form-control"/>
				</div>
				<div class="form-group">
					<label>确认密码:</label>
					<input type="password" data-mask="" <?php if(isset($userObj)){?>placeholder="不修改请勿输入"  name = "txtConfirmPassword_edit" id = "txtConfirmPassword_edit"<?php }else{?>placeholder="请输入确认密码" name = "txtConfirmPassword" id = "txtConfirmPassword"<?php }?> class="form-control"/>
				</div>
				<div class="form-group">
					<label>部门:</label>
					<select   class='form-control' id='selDepartment' name='selDepartment'>
						<option value='' selected='selected' >请选择</option>
						<?php foreach ($departmentList as $departmentObj){?>
						<option value=<?php echo $departmentObj->name;?><?php if(isset($userObj)){ if($userObj->department==$departmentObj->name){?> selected='selected'<?php }}?>> <?php echo $departmentObj->name;?></option>
						<?php }?>
					</select>
				</div>
				<div class="form-group">
					<label>虚拟机状态:</label>
					<select id='selVirtualMachine' class='form-control' name='selVirtualMachine'>
						<option value='' selected='selected' >请选择</option>
						<option value='a' <?php if(isset($userObj)){ if($userObj->virtual_machine=='a'){?> selected='selected' <?php }}?>>a</option>
						<option value='b' <?php if(isset($userObj)){ if($userObj->virtual_machine=='b'){?> selected='selected' <?php }}?>>b</option>
						<option value='c' <?php if(isset($userObj)){ if($userObj->virtual_machine=='c'){?> selected='selected' <?php }}?>>c</option>
					</select>
				</div>
			</div>
			<div class="box-footer" style="text-align:center">
				<button type="submit" class="btn btn-primary" id="btnSave">保存</button>
			</div>
		</form>
	</div>
	</div>
</section>
<!-- /.content -->