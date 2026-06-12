<?php include_once("../../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'update');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$hotelId = $_REQUEST['hotelId'];
$roomId = $_REQUEST['roomId'];
$blocked_hotel = $_REQUEST['blocked_hotel'];
$crs_available = $_REQUEST['crs_available'];
$online_allocation = $_REQUEST['online_allocation'];
$allocation_date = $_REQUEST['allocation_date'];
$type=$_REQUEST['type'];
$start_date=$_REQUEST['start_date'];
$end_date=$_REQUEST['end_date'];

if($type==1){
$res = "UPDATE `".TBL_INVENTORY."` set 
						offline_block_hotel='".addslashes($blocked_hotel)."',
						crs_available='".addslashes($crs_available)."',
						online_allocation='".addslashes($online_allocation)."',
						color='#f39c12',
						`last_modified` = '".currenDateTime()."'
 						where  allocation_date = '".addslashes($allocation_date)."' and
						 room_id ='".addslashes($roomId)."' and
						 hotel_id ='".addslashes($hotelId)."'";
						 if(executeSql($res)){
						  echo 'Success';
						 }else{
						   echo 'error';
						 }
						 
						 
			
}elseif($type==2){
$date = new DateTime($end_date); 
$date->modify("-1 day");




$availableInventory = selectSql(TBL_ASSIGN_HOTEL_ROOM,'inventory'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and status='1'"); 

while($row=$db->fetch_object2($availableInventory)){
$res = "UPDATE `".TBL_INVENTORY."` set 
						blocked_hotel='".addslashes($row->inventory)."',
						crs_available='0',
						color='#dd4b39',
						`last_modified` = '".currenDateTime()."'
 						where  allocation_date between '".addslashes($start_date)."' and '".addslashes($date->format("Y-m-d"))."' and
						 hotel_id ='".addslashes($hotelId)."' and 
						 room_id ='".addslashes($row->room_id)."'";
						 if(executeSql($res)){
						  echo 'success';
						 }else{
						   echo 'error';
						 }
}

}else{

echo 'Error, Please Try again.';
}
?>