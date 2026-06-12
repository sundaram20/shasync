<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_MAPPING,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['booking_engine_id'])){
		$err++;
		$err_booking_engine_id = '<font style="color:red;font-weight:normal;" ><br>Please enter mapping id.</font>';
	}
	if(empty($_POST['hotel_id'])){
		$err++;
		$err_hotel_id = '<font style="color:red;font-weight:normal;" ><br>Please select hotel.</font>';
	}else if($db->num_rows2(selectSql(TBL_HOTEL_MAPPING,"WHERE  `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `hotel_id` = '".addslashes($_POST['hotel_id'])."' AND `channel_id` = '".addslashes($_POST['channel_id'])."'",''))){
		$err++;
		$err_hotel_id = '<font style="color:red;font-weight:normal;" ><br>Hotel all-ready mapped in our database.</font>';
	}	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_MAPPING,'add');
			$addSql = "   	INSERT INTO `".TBL_HOTEL_MAPPING."` SET 
							`booking_engine_id` = '".addslashes($_POST['booking_engine_id'])."',
							`id_shop` = '".addslashes($_POST['shop_id'])."',
							`channel_id` = '".addslashes($_POST['channel_id'])."',
							`id_shop_group` = '1',
							`hotel_id` = '".addslashes($_POST['hotel_id'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Mapping details has been added sucessfully.';
				header("location:manageHotelMapping.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Mapping has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_MAPPING,'update');
			$editSql = "   	UPDATE `".TBL_HOTEL_MAPPING."` SET 
							`booking_engine_id` = '".addslashes($_POST['booking_engine_id'])."',
							`id_shop` = '".addslashes($_POST['shop_id'])."',
							`channel_id` = '".addslashes($_POST['channel_id'])."',
							`id_shop_group` = '1',
							`hotel_id` = '".addslashes($_POST['hotel_id'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Mapping  details has been updated sucessfully.';
				header("location:manageHotelMapping.php?&page=".$_REQUEST['page']);
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
	$sql = "  SELECT * FROM `".TBL_HOTEL_MAPPING."`
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
        <small>Manage Hotel Mapping</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Hotel Mapping</li>
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
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Hotel Mappping</a></li>   
			  <li><a href="manageRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Mapping</a></li>  
			  </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Hotel Mapping	</h3>
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
                  <label for="shop_id">Shop<font color="#FF0000">*</font></label>
                   <?php $shopDropDown = '<select class="form-control select2" name="shop_id" onchange="getHotelMapping(this.value,'.$row->hotel_id.');">
											    <option value="">Select Shop</option>';
											  $resCat = selectSql(TBL_SHOP," where status='1' AND `id` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
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
                  <label for="hotel_id">Hotel<font color="#FF0000">*</font></label>
                   <?php $categoryDropDown = '<select class="form-control select2" name="hotel_id" id="hotel_id">
											    <option value="">Select Shop First</option>';											 
											 	echo $categoryDropDown .= '</select>';
											  ?>
				<?php echo $err_hotel_id;?>
                </div>
				
				<div class="form-group">
                  <label for="booking_engine_id">Mapping Id<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter mapping Id" id="booking_engine_id" name="booking_engine_id" value="<?php if($_POST) echo $_POST['booking_engine_id'];else echo stripslashes($row->booking_engine_id);?>" data-parsley-required>
				<?php echo $err_booking_engine_id;?>
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
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageHotelMapping.php"); '>
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

				getHotelMapping('<?php echo $row->id_shop; ?>','<?php echo $row->hotel_id; ?>');
				
				
				 };
							
</script>

