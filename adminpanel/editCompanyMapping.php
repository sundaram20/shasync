<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['booking_engine_name'])){
		$err++;
		$err_booking_engine_name = '<font style="color:red;font-weight:normal;" ><br>Please enter mapping id.</font>';
	}
	if(empty($_POST['company_id'])){
		$err++;
		$err_company_id = '<font style="color:red;font-weight:normal;" ><br>Please select Company.</font>';
	}else if($db->num_rows2(selectSql(TBL_COMPANY_MAPPING,"WHERE  `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `company_id` = '".addslashes($_POST['company_id'])."' AND `channel_id` = '".addslashes($_POST['channel_id'])."'",''))){
		$err++;
		$err_company_id = '<font style="color:red;font-weight:normal;" ><br>Company all-ready mapped in our database.</font>';
	}	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'add');
			$addSql = "   	INSERT INTO `".TBL_COMPANY_MAPPING."` SET 
							`booking_engine_name` = '".addslashes($_POST['booking_engine_name'])."',
							`id_shop` = '".addslashes($_POST['shop_id'])."',
							`channel_id` = '".addslashes($_POST['channel_id'])."',
							`id_shop_group` = '1',
							`company_id` = '".addslashes($_POST['company_id'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Mapping details has been added sucessfully.';
				header("location:manageCompanyMapping.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Mapping has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY_MAPPING,'update');
			$editSql = "   	UPDATE `".TBL_COMPANY_MAPPING."` SET 
							`booking_engine_name` = '".addslashes($_POST['booking_engine_name'])."',
							`id_shop` = '".addslashes($_POST['shop_id'])."',
							`channel_id` = '".addslashes($_POST['channel_id'])."',
							`id_shop_group` = '1',
							`company_id` = '".addslashes($_POST['company_id'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Mapping  details has been updated sucessfully.';
				header("location:manageCompanyMapping.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Mapping details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Mapping details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_COMPANY_MAPPING."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'";
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
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Rate Mappping</a></li>   
			  </ul>
			
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
                  <label for="shop_id">Shop<font color="#FF0000">*</font></label>
                   <?php $shopDropDown = '<select class="form-control select2" name="shop_id" onchange="getCompanyMapping(this.value,'.$row->company_id.');">
											    <option value="">Select Shop</option>';
											  $resCat = selectSql(TBL_SHOP," where status='1' and `id` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['shop_id'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else if($row->id_shop == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$shopDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $shopDropDown .= '</select>';
											  ?>
				<?php echo $err_shop_id;?>
                </div>
				
				  <div class="form-group">
                  <label for="channel_id">Channel<font color="#FF0000">*</font></label>
                   <?php $channelDropDown = '<select class="form-control select2" name="channel_id">
											    <option value="">Select Channel</option>';
											  $resCat = selectSql(TBL_CHANNEL_MANAGER," where status='1'",' ORDER BY `id`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['channel_id'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else if($row->channel_id == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$channelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $channelDropDown .= '</select>';
											  ?>
				<?php echo $err_channel_id;?>
                </div>
				
				 <div class="form-group">
                  <label for="hotel_id">Company<font color="#FF0000">*</font></label>
                   <?php $categoryDropDown = '<select class="form-control select2" name="company_id" id="company_id">
											    <option value="">Select Shop First</option>';											 
											 	echo $categoryDropDown .= '</select>';
											  ?>
				<?php echo $err_company_id;?>
                </div>
				
				<div class="form-group">
                  <label for="booking_engine_name">Mapping Name<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter mapping name" id="booking_engine_name" name="booking_engine_name" value="<?php if($_POST) echo $_POST['booking_engine_name'];else echo stripslashes($row->booking_engine_name);?>" data-parsley-required>
				<?php echo $err_booking_engine_name;?>
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
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageCompanyMapping.php"); '>
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
<script>
window.onload = function() {

				getCompanyMapping('<?php echo $row->id_shop; ?>','<?php echo $row->company_id; ?>');
				
				
				 };
							
</script>

