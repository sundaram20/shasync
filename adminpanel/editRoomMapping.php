<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'view');
$image_path = $UPLOAD_FILES.'/hotel_room/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_room/";
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){ header("location:editHotelMapping.php"); }

$sqlHotelMappingDetail = $db->fetch_obj2(selectSql(TBL_HOTEL_MAPPING,"WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[eId]))."'",''));	
/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){		
	$err = 0;
	
	if(empty($_POST['room_id'])){
		$err++;
		$err_room_id = '<font style="color:red;font-weight:normal;" ><br>Please select room.</font>';
	}
	if(empty($_POST['booking_engine_id'])){
		$err++;
		$err_booking_engine_id = '<font style="color:red;font-weight:normal;" ><br>Please enter booking engine id.</font>';
	}
	
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'add');
			$addSql = "   	INSERT INTO `".TBL_ROOM_MAPPING."` SET 
							`room_id` = '".addslashes($_POST['room_id'])."',
							`hotel_mapping_id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`booking_engine_id` = '".addslashes($_POST['booking_engine_id'])."'";			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Room Mapping details has been added sucessfully.';
				header("location:manageRoomMapping.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'New Room Mapping details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'update');
			$editSql = "   	UPDATE `".TBL_ROOM_MAPPING."` SET 
							`room_id` = '".addslashes($_POST['room_id'])."',
							`hotel_mapping_id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`booking_engine_id` = '".addslashes($_POST['booking_engine_id'])."'";			
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[id]))."'";								
			if(executeSql($editSql)){
				
				$_SESSION['successMsg'] = 'Mapping details has been updated sucessfully.';
				header("location:manageRoomMapping.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Mapping details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Mapping details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_ROOM_MAPPING."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[id]))."'";
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
        <small>Room Mapping</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Room Mapping</li>
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
			   <li  ><a href="editHotelMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Hotel Mapping</a></li> 
			  <li class="active"><a href="manageRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Room Mapping</a></li>        
			 
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Room Mapping: <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($sqlHotelMappingDetail->hotel_id	)."'"); ?> </a></h3>     
			   <a href="manageRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  
			</div> 
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form">
                <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">			  
			   <div class="form-group">
                  <label for="room_id">Room Type<font color="#FF0000">*</font></label>
                 <?php $categoryDropDown = '<select class="form-control select2" name="room_id" id="room_id">
											  <option value="">Select Room Type</option>';
											  $resCat = executeSql("Select rt.name, ahr.* from  `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join  `".TBL_ROOM_TYPE."` rt on  ahr.room_id = rt.id where rt.status='1' and ahr.status='1' and ahr.hotel_id='".$sqlHotelMappingDetail->hotel_id."'");
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['room_id'] == $resultCat->room_id){
														$selected = 'selected="selected"';
													}elseif($row->room_id == $resultCat->room_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->room_id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
				<?php echo $err_room_id;?>
                </div>
				
				<div class="form-group">
                  <label for="booking_engine_id">Mapping Id<font color="#FF0000">*</font></label>
                 
				  <input type="text" class="form-control" placeholder="Enter mapping id" id="booking_engine_id" name="booking_engine_id" value="<?php if($_POST) echo $_POST['booking_engine_id'];else echo stripslashes($row->booking_engine_id);?>">
				<?php echo $err_booking_engine_id;?>
                </div>
								
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio"  class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
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
				<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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


