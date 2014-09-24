<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		主页
		<small>部门管理</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> 主页</a></li>
		<li class="active">部门管理</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-body">
			<h3 class="box-title">部门列表</h3>
		<div class='widget col-md-8'>
		<form method="post">
		<div class='row'>
			<div class="col-lg-6">
				<div class="form-group">
					<label>部门名</label>
					<input type="text" id="txtName" placeholder="请输入要查询的部门名" class="form-control" name = "txtName">
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label>部门描述</label>
					<input type="text" id="txtMemo" placeholder="请输入要查询的部门描述" class="form-control" name = "txtMemo">
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id='search_department'>搜索部门</button>
				<br/>
				<br/>
				<a class=" btn btn-primary" id="add_department" href="/account/adddepartment"><i></i>添加部门</a>
			</div>
			<br/>
		</div>
		</form>
		</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered">
				<tbody><tr>
					<th style="width:5%">No.</th>
					<th style="width:10%">部门名</th>
					<th>部门描述</th>
					<th style="width:10%">操作</th>
				</tr>
				<?php $i = 1 ;foreach ($departmentList as $departmentObj){?>
				<tr>
					<td class="center"><?php echo $i++;?></td>
					<td><?php echo $departmentObj->name;?></td>
					<td><?php echo $departmentObj->memo;?></td>
					<td class="center actions">
						<div class="btn-group" department_id='<?php echo $departmentObj->id;?>'>
							<a href="/account/adddepartment?department_id=<?php echo $departmentObj->id?>" title='修改' class="btn btn-small btn-primary">
							<i class="fa fa-edit"></i></a>
							<a href="#" title='删除' class="btn btn-small btn-primary delete">
							<i class="fa fa-fw fa-times-circle"></i></a>
						</div>
					</td>
				</tr>
				<?php }?>
				</tbody></table>
		</div>
	</div>
</section>
<!-- /.content -->