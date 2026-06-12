<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$exe_user_id = $_SESSION['userId'];//$_REQUEST['exe_user_id'];
?>

<?php 
//InventoryUpdateDayWise($hotelId,date('Y-m-d',strtotime($start)),date('Y-m-d',strtotime($end)));

$events = array();
$resState = executeSql("SELECT * from `fs_weeklyplanner` where status='1' and  allocation_date between '".addslashes($start)."' and '".addslashes($end)."' and user_id='".addslashes($exe_user_id)."'");

if(num_rows($resState) > 0){

		while($row = $db->fetch_assoc2($resState)){	
		
		if($row['type']=='1'){
			$WeeklyPlanType	=	'Visit';
			if($row['id_account']=='1'){
			$DataName = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row['id_company']."' AND status='1' ");
			}else{
			$DataName = 'New Account';
			}
			}else{
				$WeeklyPlanType	=	'Activity';
				$DataName = selectColumn(TBL_OTHER_ACTIVITY,'name'," WHERE `id` = '".$row['id_other_activity']."' AND status='1' ");
				}
			$e = array();
			
				$e['id'] = $row['id'];
				$e['title'] = $WeeklyPlanType.' | '.$DataName;
				$e['roomName'] = $roomName; 
				$e['room_id'] = $row['room_id'];
				$e['availableInventory'] = $availableInventory-$row['blocked_hotel'];
			
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