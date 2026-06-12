<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'view');
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Permissions
        <small>Manage User Permissions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageUserPermission.php">User  Permission</a></li>
        <li class="active">Manage User Permission</li>
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
          <h3 class="box-title">Search </h3>
			<div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="javascript:void(0);" data-toggle="dropdown">Export</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php /*?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_USERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_USERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		
                                      
		
		<form name="searchForm" action="" method="post">
           <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Please Select User Level </label>				
				<?php $categoryDropDown = '<select class="form-control select2" name="userlevelId" onchange="document.searchForm.submit();">
											  								<option value="">Select User Level</option>';
											  $resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and  `status` = '1'",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['userlevelId'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
												}
											  }
											 echo $categoryDropDown .= '</select>';
											  ?>                          
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->        
		</form>
		
		
      </div>
	  
	  
      <div class="row">
        <div class="col-xs-12">    
		    <?php if($_REQUEST['userlevelId']){?>
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">User Permission List</h3>
            </div>
			
			
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
				  <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th width="25%;">Module Name</th>
                  <th>User Action</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				 <?php $sqlModules = selectSql(TBL_MODULES,'','ORDER BY name' );
						if($db->num_rows2($sqlModules)> 0){$counter = 1;
							while($rowModules = $db->fetch_object2($sqlModules)){
								$sqlPermission = executeSql("SELECT * FROM `".TBL_USER_PERMISSIONS."` WHERE `user_level_id` = '".addslashes($_REQUEST['userlevelId'])."' AND `module_id` = '".$rowModules->id."'  ");
								if($db->num_rows2($sqlPermission)){
									$resPermissionDetail = $db->fetch_assoc2($sqlPermission);
									//echo '<pre>';print_r($resPermissionDetail);echo '</pre>';
									$permissionStatus = $resPermissionDetail['status'];
									$permissionDate = $resPermissionDetail['last_modified'];
									$permissionMuser = $resPermissionDetail['last_modified_by'];
								}else{
									$permissionStatus = '';
									$permissionDate = '';
									$permissionMuser = '';
								}															
					?>
                <tr>
                  <td><?php echo $counter++;?>.&nbsp;</td>
                  <td><?=ucfirst($rowModules->name);?></td>
                  <td><?php 
						$sqlUserActions = selectSql(TBL_USER_ACTIONS,'','');
						$iCounterActions = 0;
						while($resUserActions = $db->fetch_object2($sqlUserActions)){
							$chkSql = "SELECT * FROM `".TBL_USER_PERMISSIONS."` WHERE FIND_IN_SET('".$resUserActions->id."',user_actions) AND `user_level_id` = '".addslashes($_REQUEST['userlevelId'])."' AND `module_id` = '".$rowModules->id."'";
								if($db->num_rows2(executeSql($chkSql)) > 0){
									$selected = 'checked="checked"';
								}else{
									$selected = '';
								}
							echo '<input '.$selected.' onClick="this.blur();" type="checkbox" class="flat-red" disabled name="actions[]" value="0">'.$resUserActions->name.'<br>';
							if($iCounterActions == '3'){
								//echo '<br>';
							}
							$iCounterActions++;
						}
					?></td>
                  <td><?=$permissionStatus=='1'?'<span onclick="#" style="color:green;cursor:pointer;">Active</span>':'<span   style="color:red;cursor:pointer;">Inactive</span>';?> </td>
				 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editUserPermissions.php?moduleId=<?=$rowModules->id?>&userlevelId=<?=$_REQUEST['userlevelId'];?>&action=edit';" /></td>
                </tr>
               <?php }?>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="5">---- No Record Found ---- </td>
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
		 <?php }?>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                    
<?php include_once("includes/footer.php")?>  















                                  