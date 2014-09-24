		<div class='widget'>
		<form class="form-horizontal" method='post' id='editdepartment_form' action='/account/updatedepartment'>
			<div class="widget widget-2">
				<div class="form-group">
					<label>部门ID(不可修改)</label>
					<input type="text" readonly value="<?php echo $departmentObj->id;?>" data-mask="" class="form-control" name = "department_id" id = "department_id" >
				</div>
				<div class="form-group">
					<label>部门名</label>
					<input type="text" value="<?php echo $departmentObj->name;?>" data-mask="" class="form-control" name = "txtName" id = "txtName" >
				</div>
				<div class="form-group">
					<label>部门描述:</label>
					<input type="text" data-mask="" value=<?php echo $departmentObj->memo?> class="form-control" name = "txtMemo" id = "txtMemo" >
				</div>
			</div>
			<div class="box-footer" style="text-align:center">
				<button type="submit" class="btn btn-primary">保存</button>
			</div>
		</form>
	</div>