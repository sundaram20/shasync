<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'view');

if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){
		
		checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_EVENT_CALENDER_DETAILS."`
						SET `status` = '1'
						WHERE `event_id` = '".addslashes($statusId)."'AND id_hotel='".$_REQUEST['HotelId']."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		
		checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		 $statusSql = "	UPDATE `".TBL_EVENT_CALENDER_DETAILS."` 
						SET `status` = '0' 
						
						WHERE `event_id` = '".addslashes($statusId)."' AND id_hotel='".$_REQUEST['HotelId']."'";
					
	
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Events '.selectColumn(TBL_EVENT_CALENDER,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Events '.selectColumn(TBL_EVENT_CALENDER,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'delete');
	$delSql_details = "DELETE FROM `".TBL_EVENT_CALENDER_DETAILS."` WHERE `event_id` = '".$_REQUEST['delId']."'";
	executeSql($delSql_details);
	$delSql = "DELETE FROM `".TBL_EVENT_CALENDER."` WHERE `id` = '".$_REQUEST['delId']."'";
	
	
	$sqlDelUserLevel = selectRow(TBL_EVENT_CALENDER," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One Events '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Events '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	foreach($_REQUEST['ids'] as $HotelListId){
		 $idHotelId	=	explode('|',$HotelListId);
		$statusSql = "	UPDATE `".TBL_EVENT_CALENDER_DETAILS."`
						SET `status` = '1'
						WHERE `event_id` = '".addslashes($idHotelId[0])."'AND id_hotel='".$idHotelId[1]."'";	
	executeSql($statusSql);
		}
	
	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'deactivate');	
	
	foreach($_REQUEST['ids'] as $HotelListId){
		 $idHotelId	=	explode('|',$HotelListId);
		$statusSql = "	UPDATE `".TBL_EVENT_CALENDER_DETAILS."`
						SET `status` = '0'
						WHERE `event_id` = '".addslashes($idHotelId[0])."'AND id_hotel='".$idHotelId[1]."'";	
	executeSql($statusSql);
		}
		
	
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != '' && $_REQUEST["act"] != "delete"){
	$delId = addslashes(encryptor('decrypt',$_REQUEST['delId']));
	checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'delete');
	$delSql = "DELETE FROM `".TBL_EVENT_CALENDER_DETAILS."` WHERE `event_id` = '".$delId."' AND id_hotel='".$_REQUEST['HotelId']."'";
	
	$sqlDelUserLevel = selectRow(TBL_EVENT_CALENDER," WHERE `id` = '".$delId."'");
	
	if(executeSql($delSql)){
		 $resCat = selectSql(TBL_EVENT_CALENDER_DETAILS," where `event_id` = '".$delId."'");
		if($db->num_rows2($resCat)=='0'){
		$delEventSql = "DELETE FROM `".TBL_EVENT_CALENDER."` WHERE `id` = '".$delId."' ";
		executeSql($delEventSql);	
		}		
		$err = 0;
		$_SESSION['successMsg'] = 'One Event '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Event '.$sqlDelUserLevel["name"];
	}
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	
	checkUserLevelPermission($_SESSION['userLevel'],TBL_EVENT_CALENDER,'delete');	
	
	foreach($_REQUEST['ids'] as $HotelListId){
		 $idHotelId	=	explode('|',$HotelListId);
	$delSql = "DELETE FROM `".TBL_EVENT_CALENDER_DETAILS."` WHERE `event_id` = '".$idHotelId[0]."' AND id_hotel='".$idHotelId[1]."'";
	
	if(executeSql($delSql)){
		 $resCat = selectSql(TBL_EVENT_CALENDER_DETAILS," where `event_id` = '".$idHotelId[0]."'");
		if($db->num_rows2($resCat)=='0'){
		$delEventSql = "DELETE FROM `".TBL_EVENT_CALENDER."` WHERE `id` = '".$idHotelId[0]."' ";
		executeSql($delEventSql);	
		}		
	}
	
	
	
	
		}
	
	
}
// ----------cate---------
$sql = " SELECT `".TBL_EVENT_CALENDER."`.*,`a`.*,a.status as detailstatus FROM `".TBL_EVENT_CALENDER."`  ";

$sql .= " LEFT JOIN `".TBL_EVENT_CALENDER_DETAILS."` AS a ON a.event_id=`".TBL_EVENT_CALENDER."`.id 
WHERE `".TBL_EVENT_CALENDER."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";

if($_REQUEST['hotelId'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

	$sql .= " AND FIND_IN_SET (".addslashes($_REQUEST['hotelId']).",`a`.`id_hotel`)";

}
if($_REQUEST['event_date'] != ''){

//list($from_event,$to_event) = split(" to ",$_REQUEST['event_date']);	

$event_date= explode(" to ",$_REQUEST['event_date']);
	$from_event = $event_date['0'];
	$to_event = $event_date['1'];
/*
function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
 $k= date_range($from_event, $to_event, $step = '+1 day', $output_format = 'd/m/Y' );
print_r($k);*/
//$sql .= " AND DATE(`".TBL_EVENT_CALENDER."`.`start`) >='".date('Y-m-d',strtotime($from_event))."' AND DATE(`".TBL_EVENT_CALENDER."`.`start`) <= '".date('Y-m-d',strtotime($to_event))."'  ";
$sql .= "and (( `".TBL_EVENT_CALENDER."`.start <=  '".date('Y-m-d',strtotime($from_event))."' and  `".TBL_EVENT_CALENDER."`.end >= '".date('Y-m-d',strtotime($to_event))."') OR (  `".TBL_EVENT_CALENDER."`.start between '".date('Y-m-d',strtotime($from_event))."' and '".date('Y-m-d',strtotime($to_event))."') OR (  `".TBL_EVENT_CALENDER."`.start between '".date('Y-m-d',strtotime($from_event))."' and '".date('Y-m-d',strtotime($to_event))."'))";
}

if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

	 $sql .= " AND `a`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

}

if($_REQUEST['search_name'] != ''){
	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."'";
}
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
}
//echo $sql;die;
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
        Attributes Manager
        <small>Events Master</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Events Master</li>
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
							  <a type="button" class="btn btn-success" href="calendar.php" >Add Events</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php ?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_EVENT_CALENDER;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_EVENT_CALENDER;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>Events Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>			  
              <!-- /.form-group -->
            </div>
     <div class="form-group col-sm-4">



                                <label for="booking_date"> Date : From </label>



                                <div class="input-group">



                                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                                 <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Enter event date" id="event_date" name="event_date" value="<?php if($_REQUEST) echo $_REQUEST['event_date'];?>">



                                </div>
	

                                <!-- /.input group -->



                                </div>  




<div class="col-md-5">

              <div class="form-group">

                <label>Hotel</label>				

				<?php

				

				$hotelDropDown = '<select class="form-control select2" name="hotelId">

											    <option value="">Select Hotel</option>';

  $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['hotelId'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $hotelDropDown .= '</select>';

											  ?>

              </div>

              <!-- /.form-group -->

            </div>

			
            <!-- /.col -->  
			<div class="col-md-4">
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
          <a type="button" class="btn btn-success" href="manageEvents.php" >Clear</a>
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Events List</h3>
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
                  <th>Events Name</th>
                  <th>Hotel Name</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id.'|'.$row->id_hotel;?>"/>
                  <?php
				  echo (($_REQUEST['page']-1)*$setpage)+$counter++;
				  
				  ?>.&nbsp;</td>
                  <td><?=ucfirst($row->name);?></td>
                   <td><?php echo  ucfirst(selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_hotel."'"));?></td>
                   <td><?=date('d M Y',strtotime($row->start));?></td>
                   <td><?=date('d M Y',strtotime($row->end));?></td>
                  <td><?=$row->detailstatus=='1'?'<span onclick="location.href=\'manageEvents.php?inactiveId='.encryptor('encrypt',$row->id).'&HotelId='.$row->id_hotel.'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageEvents.php?activeId='.encryptor('encrypt',$row->id).'&HotelId='.$row->id_hotel.'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td>
                 <img src="images/view_edit.gif"
                  style="cursor:pointer;" title=" View / Edit "  />
                  
                  <!--<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editEvents.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />-->&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageEvents.php?delId=<?=encryptor('encrypt',$row->id)?>&HotelId=<?php echo $row->id_hotel; ?>&action=delete&page=<?=$_REQUEST['page']?>';}"/></td>
                </tr>
               <?php }?> 
			   <tr>
                     <td align="left" colspan="7">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>
				<tr>	 
					  <td align="right" colspan="8"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
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

  <script type="text/javascript">
  	function deleteMe(id,name){
		alert(name);
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    //if (this.readyState == 4 && this.status == 200) {
				alert("checjk");
  		    	//console.log(this.responseText);
  		    			
						if(confirm('Are you sure that you want to delete this record '+name+'?')){
							window.location.href='manageEvents.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
						}
  		      
  		    //}
  		  };
  		  //xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_segment="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  