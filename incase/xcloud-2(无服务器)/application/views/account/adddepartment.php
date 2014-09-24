<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		主页
		<small>添加部门</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> 主页</a></li>
		<li><a href="/"><i class="fa fa-dashboard"></i> 部门列表</a></li>
		<li class="active">添加部门</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="col-md-6 box box-info">
		 <div class="box-header">
			<h3 class="box-title">添加部门</h3>
		</div>
		<div class='col-md-4'>
			<?php if(isset($error_msg)){?>
			<div class="alert alert-danger alert-dismissable">
				<i class="fa fa-ban"></i>
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<b><?php echo $error_msg;?></b>
			</div>
			<?php }?>
			<form method= "post" id='adddepartment_form'>
				<div class="box-body">
					<?php if(isset($departmentObj)){?>
					<input type='hidden' name='txtId' value="<?php echo $departmentObj->id;?>" />
					<?php }?>
					<div class="form-group">
						<label>部门名:</label>
						<input type="text" data-mask="" <?php if(isset($departmentObj)){?> readonly value=<?php echo $departmentObj->name?> <?php }else{?> placeholder="请输入部门名" name="txtName" id="txtName"<?php }?> class="form-control"/>
					</div>
					<div class="form-group">
						<label>部门描述:</label>
						<input type="text" data-mask="" <?php if(isset($departmentObj)){?> value='<?php echo $departmentObj->memo?>' <?php }else{?> placeholder="请输入部门描述" <?php }?> class="form-control" name="txtMemo" id="txtMemo"/>
					</div>
				</div>
				<div class="box-footer" style="text-align:center">
					<button type="submit" class="btn btn-primary" id='btnSave'>保存</button>
				</div>
			</form>
		</div>
	</div>
</section>
<!-- /.content -->