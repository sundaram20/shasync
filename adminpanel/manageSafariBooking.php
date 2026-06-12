<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'view');
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_SAFARI_BOOKING."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_safari` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_SAFARI_BOOKING."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_safari` = '".addslashes($statusId)."'";
	}
		
	if(executeSql($statusSql)){
		$err = 0;		
		$_SESSION['successMsg'] = 'Safari Booking status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Safari Booking status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'delete');
	$delSql = "DELETE FROM `".TBL_SAFARI_BOOKING."` WHERE `id_safari` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$sqlDelUsers = selectRow(TBL_SAFARI_BOOKING," WHERE `id_safari` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'One Safari Booking  has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Safari Booking';
	}
}

///////////////
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_SAFARI_BOOKING."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_safari` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_SAFARI_BOOKING."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_safari` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_SAFARI_BOOKING."` WHERE `id_safari` IN (".addslashes($deleteIds).")";	
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}	

// ----------cate---------
$sql = " SELECT * FROM `".TBL_SAFARI_BOOKING."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";
if($_REQUEST['booking_status'] != ''){
	$sql .= " AND `booking_status` = '".addslashes($_REQUEST['booking_status'])."%'";
}
if($_REQUEST['from_date'] != ''){
	$sql .= " AND `safari_date` >= '".addslashes(date('Y-m-d',strtotime($_REQUEST['from_date'])))."%'";
}
if($_REQUEST['to_date'] != ''){
	$sql .= " AND `safari_date` <= '".addslashes(date('Y-m-d',strtotime($_REQUEST['to_date'])))."%'";
}
if($_REQUEST['id_guest'] != ''){
	$sql .= " AND `id_guest` = '".addslashes($_REQUEST['id_guest'])."'";
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
        Hotel Booking Manager
        <small>Manage Safari Bookings</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Safari Bookings</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">	
      <div class="row">
        <div class="col-xs-12">	
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
                  <a type="button" class="btn btn-success" href="editSafariBooking.php">Add Safari Bookings</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                   <?php /*?> <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_SAFARI_BOOKING;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_SAFARI_BOOKING;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
                  
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
                <label>Guest Details</label>				
				 <?php $guestDropDown = '<select class="form-control select2" name="id_guest">
											    <option value="">Select Guest</option>';
											  $resCat = selectSql(TBL_CUSTOMER," where type='1' and status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `first_name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_guest'] == $resultCat->id_customer){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
												}
											  }
											 	echo $guestDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div>
            <!-- /.col -->  
			
		  
		  <div class="form-group col-sm-3">
                  <label for="from_date">From Date</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pickerdate" placeholder="Enter from date" id="from_date" name="from_date" value="<?php if($_REQUEST) echo $_REQUEST['from_date'];?>">
                  </div>
                 </div>
			
			<div class="form-group col-sm-3">
                  <label for="to_date">To Date</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pickerdate" placeholder="Enter to date" id="to_date" name="to_date" value="<?php if($_REQUEST) echo $_REQUEST['to_date']; ?>">
                  </div>
                   </div>
          <!-- /.row -->
        </div>
		
		
		<div class="row">
            <!-- /.col -->  
		  <div class="form-group col-sm-6">
                  <label for="booking_status" >Booking Status</label>
                  <select class="form-control select2" name="booking_status" id="booking_status" data-parsley-errors-container="#booking_statusError" data-parsley-required>
                    <option value="">Select Booking Status</option>
                    <?php 
									$resCat = selectSql(TBL_HTL_BOOKING_STATUS,"where safari_status='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['booking_status'] == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$bookStatusDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $bookStatusDropDown;
									
									 ?>
                  </select>
                  <span id="booking_statusError"></span> </div>
          <!-- /.row -->
        </div>
		
		
		
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>		
      </div>
              
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Safari Bookings List</h3>
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
				  <th>Safari Code</th>
				  <th>Guest Name</th>
				  <th>Safari Date</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
				  
                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_safari;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
				  <td><?php echo stripslashes($row->safari_code).'-'.stripslashes($row->sub_code);   ?></td>	
				  <td><?php echo selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$row->id_guest."'").' '.selectColumn(TBL_CUSTOMER,'last_name'," WHERE `id_customer` = '".$row->id_guest."'") ;   ?></td>
				  <td><?php echo dateFormat_date(stripslashes($row->safari_date)).' '.dateFormat_time(stripslashes($row->safari_time));   ?></td>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageSafariBooking.php?inactiveId='.encryptor('encrypt',$row->id_safari).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageSafariBooking.php?activeId='.encryptor('encrypt',$row->id_safari).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editSafariBooking.php?id=<?=encryptor('encrypt',$row->id_safari)?>&action=edit&page=<?=$_REQUEST['page']?>';" />
				  &nbsp;&nbsp;&nbsp;&nbsp;				  
				  <img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageSafariBooking.php?delId=<?=encryptor('encrypt',$row->id_safari)?>&action=delete&page=<?=$_REQUEST['page']?>';}"/></td>
                </tr>
               <?php }?> 
			    <tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>
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