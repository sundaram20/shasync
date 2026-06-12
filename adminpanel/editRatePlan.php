<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_PLAN,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}else if($db->num_rows2(selectSql(TBL_RATE_PLAN,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes($_POST['name'])."'",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Rate Plan name all-ready exists in our database.</font>';
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_PLAN,'add');
			$addSql = "   	INSERT INTO `".TBL_RATE_PLAN."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`tax_detail` = '".addslashes($_POST['tax_detail'])."',
							`tax_percent` = '".addslashes($_POST['tax_percent'])."',
							`remarks` = '".addslashes($_POST['remarks'])."',
							`inclusion` = '".implode(',',$_REQUEST['inclusion'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Rate Plan details has been added sucessfully.';
				header("location:manageRatePlan.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Rate Plan has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_PLAN,'update');
			$editSql = "   	UPDATE `".TBL_RATE_PLAN."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`tax_detail` = '".addslashes($_POST['tax_detail'])."',
							`tax_percent` = '".addslashes($_POST['tax_percent'])."',
							`remarks` = '".addslashes($_POST['remarks'])."',
							`inclusion` = '".implode(',',$_REQUEST['inclusion'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Rate Plan '.selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageRatePlan.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Rate Plan '.selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Rate Plan details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE_PLAN."`
								 WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}						
}	
							

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Rate Manager
        <small>Manage Rate Plan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Rate Plan</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Rate Plan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				 <div class="form-group">
                  <label for="name">Rate Plan Name<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter rate plan name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
				<?php echo $err_name;?>
                </div>
				
				 <div class="form-group">
                  <label for="username">Inclusions<font color="#FF0000">*</font></label>
                  <?php 
					$sqlUserActions = selectSql(TBL_RATE_INCLUSION,'where status="1" and id_shop="'.addslashes($_SESSION['shop']).'"','order by display_order');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_RATE_PLAN."` WHERE FIND_IN_SET('".$resUserActions->id."',inclusion)  AND `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'checked="checked"';
						}else if($_POST[$selected]){
						$selected = 'checked="checked"';
						}													
						else{
							$selected = '';
						}
						echo '&nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-red" '.$selected.'  name="inclusion[]" value="'.$resUserActions->id.'">&nbsp;&nbsp;'.$resUserActions->name.'&nbsp;&nbsp;&nbsp;';
						if($iCounterActions == '3'){
							//echo '<br>';
						}
						$iCounterActions++;
					}
					?>
				 <?php echo $err_userActions;?>
                </div>
				
				
				<div class="form-group">
					 <label for="tax_detail">Tax Detail<font color="#FF0000">*</font></label> 
						
						 <select class="form-control" name="tax_detail" id="tax_detail" data-parsley-errors-container="#tax_detailError" data-parsley-required>
							<option value="1" <?php if($_REQUEST['tax_detail']=='1'){echo 'selected="selected"';}elseif($row->tax_detail=='1'){echo 'selected="selected"';} ?>>Inclusive Of Taxes</option>
							<option value="2" <?php if($_REQUEST['tax_detail']=='2'){echo 'selected="selected"';}elseif($row->tax_detail=='2'){echo 'selected="selected"';} ?>>Exclusive Of Taxes</option>								
						</select>
						
					  <span id="company_credibilityError"><?php echo $err_tax_detail;?></span>
					</div>
				
				
				<div class="form-group">
                  <label for="tax_percent">Tax Percent<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter tax percent" id="tax_percent" name="tax_percent" value="<?php if($_POST) echo $_POST['tax_percent'];else echo stripslashes($row->tax_percent);?>" data-parsley-type="digits" data-parsley-length="[1, 2]">
				<?php echo $err_tax_percent;?>
                </div>
				
				<div class="form-group">
                  <label for="remarks">Remarks</label>
				    <textarea class="form-control" name="remarks" id="remarks"  rows="1" placeholder="Enter remarks" automcomplete="off"><?php if($_POST) echo $_POST['remarks'];else echo stripslashes($row->remarks);?></textarea>
                  
				<?php echo $err_remarks;?>
                </div>
								
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php if($row->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRatePlan.php"); '>
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


