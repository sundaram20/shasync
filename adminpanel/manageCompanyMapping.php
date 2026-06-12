<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'view');
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_COMPANY_MAPPING."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_COMPANY_MAPPING."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Mapping status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Mapping status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'delete');
	$delSql = "DELETE FROM `".TBL_COMPANY_MAPPING."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$sqlDelUserLevel = selectRow(TBL_COMPANY_MAPPING," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One Mapping has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Mapping';
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_COMPANY_MAPPING."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_COMPANY_MAPPING."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_COMPANY_MAPPING."` WHERE `id` IN (".addslashes($deleteIds).")";
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}
// ----------cate---------
$sql = " SELECT * FROM `".TBL_COMPANY_MAPPING."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'";
if($_REQUEST['company_id'] != ''){
	$sql .= " AND `company_id` = '".addslashes($_REQUEST['company_id'])."%'";
}
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
}
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Channel Integration
        <small>Manage Company Mapping</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Company Mapping</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>
		  <div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editCompanyMapping.php">Add Company Mapping</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                  <?php /*?>  <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY_MAPPING;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo c;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
                  
                  </ul>
                </div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">            
			<div class="col-md-6">
              <div class="form-group">
                <label>Company</label>				
				 <?php $categoryDropDown = '<select class="form-control select2" name="company_id">
											    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY," where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['company_id'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div>
            <!-- /.col -->  
			<div class="col-md-6">
              <div class="form-group">
                <label>Status</label>				
				<?php 
					if($_REQUEST['status'] == '1'){
							$selected1 = 'selected="selected"';
					}elseif($_REQUEST['status'] == '0'){
							$selected0 = 'selected="selected"';
					}
				  echo $statusDropDown = '<select class="form-control select2" name="status"> <option value="">Both</option>
				  <option '.$selected1.' value="1">Active</option>
				  <option '.$selected0.' value="0">Inactive</option>
				  </select>';?>
              </div>
              <!-- /.form-group -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Rate Mapping List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>
				  <th>Shop</th>
				  <th>Channel</th>
                  <th>Company</th>
				  <th>Mapping Id</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
				  <td><?php echo selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$row->id_shop."'");   ?></td>
				  <td><?php echo selectColumn(TBL_CHANNEL_MANAGER,'name'," WHERE `id` = '".$row->channel_id."'");   ?></td>
				  <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."'");   ?></td>
                  <td><?=$row->booking_engine_name;?></td>				 
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCompanyMapping.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCompanyMapping.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCompanyMapping.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageCompanyMapping.php?delId=<?=encryptor('encrypt',$row->id)?>&action=delete&page=<?=$_REQUEST['page']?>';}"/></td>
                </tr>
               <?php }?> 
			   <tr>
                     <td align="left" colspan="6">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>
				<tr>	 
					  <td align="right" colspan="6"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>
                 </tr>                 
				<?php }?>
                </tbody>                
              </table>			  
            </div>
		  </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                   
<?php include_once("includes/footer.php")?>  