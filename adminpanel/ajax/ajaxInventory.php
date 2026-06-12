<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$hotelId = $_REQUEST['hotelId'];

$events = array();
$resState = executeSql("SELECT * from `".TBL_INVENTORY."` where status='1' and  allocation_date between '".addslashes($start)."' and '".addslashes($end)."' and hotel_id='".addslashes($hotelId)."'");

if(num_rows($resState) > 0){

		while($row = $db->fetch_assoc2($resState)){	
			$e = array();
			$roomName = selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$row['room_id']."'");
			$availableInventory = selectColumn(TBL_ASSIGN_HOTEL_ROOM,'inventory'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and `room_id` = '".$row['room_id']."'");
			$e['id'] = $row['id'];
			$e['title'] = $roomName.' | '.$row['crs_available'];
			$e['roomName'] = $roomName; 
			$e['room_id'] = $row['room_id'];
			$e['availableInventory'] = $availableInventory-$row['blocked_hotel'];
		//	$e['availableInventory'] = $row['crs_available'];
			//$e['blocked_hotel'] = $row['blocked_hotel'];
			$e['blocked_hotel'] = $row['offline_block_hotel'];
			
			$e['crs_available'] = $row['crs_available'];
			$e['online_allocation'] = $row['online_allocation'];
			$e['hotelId'] = $row['hotel_id'];
			$e['start'] = $row['allocation_date'];
			$e['end'] = $row['allocation_date'];
			$e['allocation_date'] = $row['allocation_date'];
			
			if( $row['color']!=''){
			$e['backgroundColor'] = $row['color'];
			$e['borderColor'] = $row['color'];
			}else{
			$e['backgroundColor'] = '#00a65a';
			$e['borderColor'] = '#00a65a';
			}
			$e['allDay'] = true;	
			array_push($events, $e);
		}
	}
echo json_encode($events);
?>