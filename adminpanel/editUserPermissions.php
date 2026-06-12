<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	//------------------------------
	if(!is_array($_REQUEST['userActions'])){
		$err++;
		$err_userActions = '<font style="color:red;font-weight:normal;" ><br>Please select user actions.</font>';
	}elseif(count($_REQUEST['userActions'])==0){
		$err++;
		$err_userActions = '<font style="color:red;font-weight:normal;" ><br>Please select user actions.</font>';
	}	
	//---------------------------------
	if($err == 0){//No error
		if($_POST['Save'] == 'Save'){//add
			$sqlSelectPermission = "  	SELECT * FROM `".TBL_USER_PERMISSIONS."`
										WHERE `module_id` = '".addslashes($_REQUEST['moduleId'])."'
										AND `user_level_id` = '".addslashes($_REQUEST['userlevelId'])."'";
			if($db->num_rows2(executeSql($sqlSelectPermission))>0){
				checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'edit');
				$sqlUpdatePermission = "	UPDATE `".TBL_USER_PERMISSIONS."` 
											SET `user_actions` = '".implode(',',$_REQUEST['userActions'])."' 
											,`last_modified` = '".currenDateTime()."'
											,`status` = '".addslashes($_POST['status'])."'
											,`last_modified_by` = '".$_SESSION['userId']."'
											WHERE `module_id` = '".addslashes($_REQUEST['moduleId'])."' 
											AND `user_level_id` = '".addslashes($_REQUEST['userlevelId'])."'";
			}else{
				checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'update');
				$sqlUpdatePermission = "	INSERT INTO `".TBL_USER_PERMISSIONS."` 
											SET `user_actions` = '".implode(',',$_REQUEST['userActions'])."' 
											,`module_id` = '".addslashes($_REQUEST['moduleId'])."' 
											,`user_level_id` = '".addslashes($_REQUEST['userlevelId'])."'
											,`date_created` = '".currenDateTime()."'
											,`last_modified` = '".currenDateTime()."'
											,`status` = '".addslashes($_POST['status'])."'
											,`last_modified_by` = '".$_SESSION['userId']."'";	
											
			}
		
			if(executeSql($sqlUpdatePermission)){
				$_SESSION['successMsg'] = 'User level permission has been saved sucessfully.';
				header('location:manageUserPermissions.php');
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User level permission has not been saved. Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'User level permission has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(isset($_REQUEST['moduleId']) && isset($_REQUEST['userlevelId'])){
	$sqlUserLevelPermission = "  	SELECT * FROM `".TBL_USER_PERMISSIONS."`
									WHERE `module_id` = '".addslashes($_REQUEST['moduleId'])."'
									AND `user_level_id` = '".addslashes($_REQUEST['userlevelId'])."'";
	$db->query($sqlUserLevelPermission);
	if($db->num_rows() > 0){
		$rowUserLevelPermission = $db->fetch_object();
	}						
}?>
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
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add/Edit User Level Permissions </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->     
			    
			 <form name="form1"  method="post" enctype="multipart/form-data">
			  <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
			  <input type="hidden" value="<?php echo $_REQUEST['moduleId'];?>" name="moduleId" />
			  <input type="hidden" value="<?php echo $_REQUEST['userlevelId'];?>" name="userlevelId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="userlevelId">User Level<font color="#FF0000">*</font></label>
                 <?php $categoryDropDown = '<select class="form-control select2">';
											  $resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `status` = '1'",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['userlevelId'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
														$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
													}elseif($rowUserLevelPermission->user_level_id == $resultUserLevel->id){
														$selected = 'selected="selected"';
														$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
													}else{
														$selected = '';
													}
													
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                                            <?php echo $err_userlevelId;?>
                </div>
				 <div class="form-group">
                  <label for="module">Module Name<font color="#FF0000">*</font></label>
                   <input type="text" class="form-control" disabled="disabled"  value="<?php echo selectColumn(TBL_MODULES,'name',"WHERE `id` = '".addslashes($_REQUEST['moduleId'])."'");?>" is="module" />
                </div>
				
				<div class="form-group">
                  <label for="username">User Actions<font color="#FF0000">*</font></label>
                  <?php 
					$sqlUserActions = selectSql(TBL_USER_ACTIONS,'','');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_USER_PERMISSIONS."` WHERE FIND_IN_SET('".$resUserActions->id."',user_actions) AND `user_level_id` = '".addslashes($_REQUEST['userlevelId'])."' AND `module_id` = '".addslashes($_REQUEST['moduleId'])."'";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'checked="checked"';
						}else if($_POST[$selected]){
						$selected = 'checked="checked"';
						}													
						else{
							$selected = '';
						}
						echo '&nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-red" '.$selected.'  name="userActions[]" value="'.$resUserActions->id.'">'.$resUserActions->name.'&nbsp;&nbsp;&nbsp;';
						if($iCounterActions == '3'){
							//echo '<br>';
						}
						$iCounterActions++;
					}
					?>
				 <?php echo $err_userActions;?>
                </div>
				
				
				
				
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserLevelPermission->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserLevelPermission->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php if($rowUserLevelPermission->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserLevelPermission->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserLevelPermission->last_modified));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserLevelPermission->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='Save' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageUserPermissions.php"); '>
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>							
<?php include_once("includes/footer.php")?>