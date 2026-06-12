<?php include_once("../../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'update');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$hotelId = $_REQUEST['hotelId'];
$room_id = $_REQUEST['roomId']	;
$inventory_date = explode(' to ',$_POST['inventory_date']);
$start_date = date ("Y-m-d", strtotime($inventory_date['0']));
$end_date = date ("Y-m-d", strtotime($inventory_date['1']));

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-hover">
					<tr>
					  <th>Date</th>
					  <th>Room Status(CRS)</th>
					  <th>Room Blocked By Hotel </th>					  
					  <th>Room Status(Hotel)</th>
					  <th>Allocated for Online</th>
					</tr>';

 while (strtotime($start_date) <= strtotime($end_date)) {
$resInventory = executeSql("SELECT * from `".TBL_INVENTORY."` where allocation_date = '".addslashes($start_date)."' and hotel_id='".addslashes($hotelId)."' and room_id='".addslashes($room_id)."'");
$availableInventory = selectColumn(TBL_ASSIGN_HOTEL_ROOM,'inventory'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and `room_id` = '".addslashes($room_id)."'"); 

 if(num_rows($resInventory)>0){
 	$rowInventory = $db->fetch_object2($resInventory );
 	$blocked_hotel=$rowInventory->blocked_hotel;
	$crs_available=$rowInventory->crs_available;
	$online_allocation=$rowInventory->online_allocation;
	
	
	$offline_block_hotel	=	$rowInventory->offline_block_hotel;
	
	
 }else{
 	$blocked_hotel=0;
	$crs_available=$room_available;
	$online_allocation=0;
 }
 $room_available = $availableInventory-$blocked_hotel;
 
 
 $availableData .= '<tr>';				  
					  	$availableData .= '<td>'.dateformat_date($start_date).'</td>';
						$availableData .= '<td><input type="text" class="form-control input-sm" placeholder="Enter room available" id="room_available|'.$start_date.'" name="room_available|'.$start_date.'" value="'.$room_available.'" data-parsley-required readonly="true"></td>';
						$availableData .= '<td>
						<input type="hidden" name="allocation_date|'.$start_date.'" value="'.$start_date.'"/>
						<input type="hidden" name="dataId[]" value="|'.$start_date.'"/>
						
						
						<input type="text" class="form-control input-sm" placeholder="Enter room blocked" id="blocked_hotel|'.$start_date.'" name="blocked_hotel|'.$start_date.'" value="'.$offline_block_hotel.'" data-parsley-required   readonly="true">
						
						
						
						</td>';
						$availableData .= '<td><div class="input-group"> <input type="text" class="form-control input-sm" placeholder="Enter room available" id="crs_available|'.$start_date.'" name="crs_available|'.$start_date.'" value="'.$crs_available.'" data-parsley-required  onKeyUp="calculateRoomBlock(\'crs_available|'.$start_date.'\');">';
						$availableData .= '
						<a href="javascript:void(0);" onclick="updateInventoryUp(\'crs_available|'.$start_date.'\');" class="text-red input-group-addon"><i class="fa fa-chevron-up"></i></a>
						<a href="javascript:void(0);" onclick="updateInventoryDown(\'crs_available|'.$start_date.'\');" class="text-red input-group-addon"><i class="fa fa-chevron-down"></i></a>
						</div></td><td>
						
						
						
						<div class="input-group"><input type="text" class="form-control input-sm" placeholder="Enter room allocated online" id="online_allocation|'.$start_date.'" name="online_allocation|'.$start_date.'" value="'.$online_allocation.'" data-parsley-required >
						<a href="javascript:void(0);" onclick="updateInventoryUp(\'online_allocation|'.$start_date.'\');" class="text-green	 input-group-addon"><i class="fa fa-chevron-up"></i></a>
						<a href="javascript:void(0);" onclick="updateInventoryDown(\'online_allocation|'.$start_date.'\');" class="text-green input-group-addon"><i class="fa fa-chevron-down"></i></a>
						</div>
						</td>';						
					  	$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));	
						$availableData .= '<tr>';	
					  }					


 $availableData .= '  </table>
            </div>';
		
echo $availableData;
?>