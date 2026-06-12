<?php include_once("../config/auto_loader.php");
$image_path = $UPLOAD_FILES.'/hotel_room/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_room/";
checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'view');
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }
/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_ASSIGN_HOTEL_ROOM."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_ASSIGN_HOTEL_ROOM."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}
		$assignHotelId = selectColumn(TBL_ASSIGN_HOTEL_ROOM,'hotel_id'," WHERE `id` = '".$statusId."'");
		$assignRoomId = selectColumn(TBL_ASSIGN_HOTEL_ROOM,'room_id'," WHERE `id` = '".$statusId."'");
	if(executeSql($statusSql)){
		$err = 0;		
		$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$assignHotelId."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$assignRoomId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$assignHotelId."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$assignRoomId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'delete');
	$delSql = "DELETE FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$sqlDelUsers = selectRow(TBL_ASSIGN_HOTEL_ROOM," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");
	if(executeSql($delSql)){		
		$err = 0;
		if(file_exists($image_path.$sqlDelUsers['image'])){
			@unlink($image_path.$sqlDelUsers['image']);
			@unlink($image_path.'small-'.$sqlDelUsers['image']);
			@unlink($image_path.'medium-'.$sqlDelUsers['image']);
		}
		$_SESSION['successMsg'] = 'One Hotel Room assigned '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'").' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete hotel Room assign '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$sqlDelUsers['hotel_id']."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$sqlDelUsers['room_id']."'");
	}
}

///////////////
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_ASSIGN_HOTEL_ROOM."`
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_ASSIGN_HOTEL_ROOM."`
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `id` IN (".addslashes($deleteIds).")";
	$delSqlImage = selectSql(TBL_ASSIGN_HOTEL_ROOM,"where `id` in (".addslashes($deleteIds).") ",'');	
	if(executeSql($delSql)){		
		$err = 0;
		while($delResultImage = mysqli_fetch_array($delSqlImage)){
			if(file_exists($image_path.$delResultImage['image'])){
				@unlink($image_path.$delResultImage['image']);
				@unlink($image_path.'small-'.$delResultImage['image']);
				@unlink($image_path.'medium-'.$delResultImage['image']);
			}
		}
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}	

// ----------cate---------
$sql = executeSql(" SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `hotel_id` = '".encryptor('decrypt',$_REQUEST['eId'])."' ");

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hotel Manager
        <small>Assign Room To Hotel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assign Room To Hotel</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">	
      <div class="row">
        <div class="col-xs-12">	
		 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li  ><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li>   
              <li ><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
			  <li class="active"><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Room Types</a></li>   
			   <li><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Inventory</a></li>  
			   <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>     
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"> Room : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>
			  
			   <a href="editAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add New Room</a>
            
			</div>   
			
			 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>  
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Room List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
				  <th>Room Name</th>
				  <th>Inventory</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if(num_rows($sql) > 0){$counter = 1;
				
				  while($row = $db->fetch_object2($sql)){ 
				  
				 ?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>--> <?php echo $counter++;?>.&nbsp;</td>
				  <td><?php echo selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$row->room_id."'");   ?></td>
				  <td><?php echo $row->inventory;   ?></td>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageAssignHotelRoom.php?inactiveId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageAssignHotelRoom.php?activeId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editAssignHotelRoom.php?eId=<?=$_GET['eId']?>&id=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageAssignHotelRoom.php?delId=<?=encryptor('encrypt',$row->id)?>&eId=<?=$_GET['eId']?>&action=delete&page=<?=$_REQUEST['page']?>';}"/>--></td>
                </tr>
               <?php }?> 
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
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