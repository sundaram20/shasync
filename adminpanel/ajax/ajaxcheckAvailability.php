<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$hotel_id = $_POST['hotel_id'];
$room_id = $_POST['room_id'];
$reservation_date = explode(' to ',$_POST['reservation_date']);
$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));




/*-------------------Update Room Availability START----------------------------*/
$checkoutDate_upadate = date ("Y-m-d", strtotime($reservation_date['1']));
$startDate = date ("Y-m-d", strtotime($reservation_date['0']));
while (strtotime($checkinDate) <= strtotime($checkoutDate_upadate)) {	
					  
					  
					  
$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
					  
 if($room_id == 0){
$resRoom1 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."'");
	}else{
	$resRoom1 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."' and ahr.room_id='".addslashes($room_id)."'");
	}
		while($rowRoom_update = $db->fetch_object2($resRoom1)){
			$totalRoom 							= GetAssignTotalRoom($hotel_id,$rowRoom_update->room_id);			
			$blocked_hotel 						= GetTotalRoomBlocked_Hotel($startDate,$hotel_id,$rowRoom_update->room_id);			
			$GetTotalRoomoffline_block_hotel 	= GetTotalRoomoffline_block_hotel($startDate,$hotel_id,$rowRoom_update->room_id);			
			$roomAvailable 						= GetTotalRoomAlloted($startDate,$hotel_id,$rowRoom_update->room_id);						
			$orderTableAvailableRooms 			= GetTotalRoomAllotedOld($startDate,$hotel_id,$rowRoom_update->room_id);			
			
			
			//$Total_blocked_hotel	=	$orderTableAvailableRooms+$GetTotalRoomoffline_block_hotel;
			
			$Total_blocked_hotel	=	$orderTableAvailableRooms;
			
			$crs_available			=	$totalRoom-$Total_blocked_hotel;
			$availableData1 = "UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = '".addslashes($crs_available)."',								
								blocked_hotel = '".addslashes(isset($orderTableAvailableRooms)?$orderTableAvailableRooms:0)."' 								
								where  `hotel_id`='".addslashes($hotel_id)."' and 
						  		`room_id`='".addslashes($rowRoom_update->room_id)."' and 
								allocation_date = '".date('Y-m-d',strtotime($startDate))."'";
			
			$updateInventory = executeSql($availableData1);
			
		} 
		 $startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));	
			  			
  }

/*-------------------Update Room Availability START----------------------------*/




$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));

	
if($room_id == 0){
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."'");
}else{
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."' and ahr.room_id='".addslashes($room_id)."'");
}

$totalRoom = GetTotalRoom($hotel_id);
$availableData = '<table class="table table-hover">
					<tr>
					  <th>Room Type</th>';					 
					  while (strtotime($checkinDate) <= strtotime($checkoutDate)) {					  
					  	$availableData .= '<th>'.dateformat_date($checkinDate).'<a href="javascript:void(0);" onclick="getEvents('.strtotime($checkinDate).');" class="text-red"><i class="fa fa-caret-up"></i></a></th>';
					  	$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));		
					  }
					  				  
$availableData .= '</tr>';

if(mysqli_num_rows($resRoom) >0 ){
$counterDate=0;
$counterRoom=0;
while($rowRoom = $db->fetch_object2($resRoom)){
$startDate = date ("Y-m-d", strtotime($reservation_date['0']));
$startDate2 = date ("Y-m-d", strtotime($reservation_date['0']));

$availableData .= '<tr>
                  <td>'.$rowRoom->name.'</td>';							
					while (strtotime($startDate) <= strtotime($checkoutDate)) {							
						$roomAlloted = GetTotalRoomAlloted($startDate,$hotel_id,$rowRoom->room_id);
						$roomAvailable = $roomAlloted;
						$availableData .= '<td>'.$roomAvailable.' AVL</td>';
						$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
						$counterDate++;	
						
						if($counterDate==7){
						$availableData .= '</tr>';
						}			
					}
}
				 
	
$availableData .= '<tr>
                  <td>Total Rooms Available</td>';				  
				  
while (strtotime($startDate2) <= strtotime($checkoutDate)) {
	$roomAllotedAll= GetTotalRoomAllotedTwo($startDate2,$hotel_id,$room_id);
	
	$inventory = $roomAllotedAll;
	if($inventory > 2){
		$availableClass = 'label-success';
	}else {
		$availableClass = 'label-danger';
	}
		$availableData .= '<td><span class="label '.$availableClass.'">'.$inventory.' AVL</span></td>';
		$startDate2 = date ("Y-m-d", strtotime("+1 day", strtotime($startDate2)));
}	
	  							  
$availableData .= '</tr>  
              </table>';
}else {
$availableData .= '<tr align="center">
                  <td colspan="8" >No Data Available. Please try different Search.</td>
                </tr>';
}
			  
echo $availableData;
?>