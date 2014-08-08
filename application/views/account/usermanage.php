<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		主页
		<small>人员管理</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> 主页</a></li>
		<li class="active">人员管理</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-body">
			<h3 class="box-title">人员列表</h3>
		<div class='widget col-md-8'>
<!-- 		<div class='box-body'> -->
		<form method="post">
			<div class='row'>
				<div class="col-lg-4">
					<div class="form-group">
						<label>账户名</label>
						<input type="text" id="txtUserName" placeholder="请输入要查询的账户名" class="form-control" name = "txtUserName">
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label>用户全名</label>
						<input type="text" id="txtFullName" placeholder="请输入要查询的用户全名" class="form-control" name = "txtFullName">
					</div>
				</div>
			</div>
			<div class='row'>
			<div class="col-lg-4">
					<div class="form-group">
						<label>部门</label>
						<select   class='form-control' id='selDepartment' name='selDepartment'>
							<option value='' selected='selected' >请选择</option>
							<?php foreach ($departmentList as $departmentObj):?>
							<option value=<?php echo $departmentObj->name;?>> <?php echo $departmentObj->name;?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label>虚拟机系统</label>
						<select id='selVirtualMachine' class='form-control' name='selVirtualMachine'>
							<option value='' selected='selected' >请选择</option>
							<?php if(isset($imageList)){ for($i=0; $i<count($imageList->images); $i++){?>
							<option value='<?php echo $imageList->images[$i]->id?>' <?php if(isset($userObj)){ if($userObj->machine_id==$imageList->images[$i]->id){?> selected='selected' <?php }}?>><?php echo $imageList->images[$i]->name?></option>
							<?php }}?>
						</select>
					</div>
	<!-- 			</div> -->
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id='search_user'>搜索用户</button>
				<br/>
				<br/>
				<a class=" btn btn-primary" href='/account/adduser'><i></i>添加用户</a>
			</div>
			<br/>
		</form>
		</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered">
				<tbody><tr>
					<th style="width: 5%">No.</th>
					<th>账号</th>
					<th>用户全名</th>
					<th>部门</th>
					<th>虚拟机系统</th>
					<th>虚拟机实例</th>
					<th style="width: 10%">操作</th>
				</tr>
				<?php $i = 1 ;foreach ($userList as $userObj):?>
				<tr>
					<td class="center"><?php echo $i++;?></td>
					<td><?php echo $userObj->username;?></td>
					<td><?php echo $userObj->full_name;?></td>
					<td><?php echo $userObj->department;?></td>
					<td><?php echo $userObj->virtual_machine;?></td>
					<td><?php echo $userObj->instance_name;?></td>
					<td class="center actions">
						<div class="btn-group" user_id=<?php echo $userObj->id;?>>
							<a href="/account/adduser?user_id=<?php echo $userObj->id;?>" title='修改' class="btn btn-small btn-primary">
							<i class="fa fa-edit"></i></a>
							<a href="#" title='删除' class="btn btn-small btn-primary delete">
							<i class="fa fa-fw fa-times-circle"></i></a>
						</div>   
					</td>
				</tr>
				<?php endforeach;?>
			</tbody></table>
		</div><!-- /.box-body -->
	</div>
</section>
<!-- /.content -->