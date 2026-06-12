<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'view');
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){ header("location:editHotelMapping.php"); }

$sqlHotelMappingDetail = $db->fetch_obj2(selectSql(TBL_HOTEL_MAPPING,"WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[eId]))."'",''));	
/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_ROOM_MAPPING."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_ROOM_MAPPING."` 
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'delete');
	$delSql = "DELETE FROM `".TBL_ROOM_MAPPING."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$sqlDelUsers = selectRow(TBL_ROOM_MAPPING," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");
	if(executeSql($delSql)){		
		$err = 0;
		
		$_SESSION['successMsg'] = 'One Mapping has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Mapping';
	}
}

///////////////
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_ROOM_MAPPING."`
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_ROOM_MAPPING."`
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_MAPPING,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_ROOM_MAPPING."` WHERE `id` IN (".addslashes($deleteIds).")";
	$delSqlImage = selectSql(TBL_ROOM_MAPPING,"where `id` in (".addslashes($deleteIds).") ",'');	
	if(executeSql($delSql)){		
		$err = 0;
		
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}	

// ----------cate---------
$sql = executeSql(" SELECT * FROM `".TBL_ROOM_MAPPING."` WHERE `hotel_mapping_id` = '".encryptor('decrypt',$_REQUEST['eId'])."' ");

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
        <div class="col-xs-12">	
		 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li  ><a href="editHotelMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Hotel Mapping</a></li> 
			  <li class="active"><a href="manageRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Room Mapping</a></li>   
			     
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Room Mapping: <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($sqlHotelMappingDetail->hotel_id)."'"); ?> </a></h3>     	
			   <a href="editRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add New Room Mapping</a>
            
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
                  <th width="10%">S.No.</th>
				  <th>Room Name</th>
				  <th>Mapping Id</th>
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
                  <td> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
				  <td><?php echo selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$row->room_id."'");   ?></td>
				  <td><?php echo $row->booking_engine_id;   ?></td>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageRoomMapping.php?inactiveId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageRoomMapping.php?activeId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editRoomMapping.php?eId=<?=$_GET['eId']?>&id=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageRoomMapping.php?delId=<?=encryptor('encrypt',$row->id)?>&eId=<?=$_GET['eId']?>&action=delete&page=<?=$_REQUEST['page']?>';}"/></td>
                </tr>
               <?php }?> 
			
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
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
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                   
<?php include_once("includes/footer.php")?>  