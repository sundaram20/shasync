<?php include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$checkinDate = '2017-12-01';
$checkoutDate ='2018-12-31';
$hotelId = $_REQUEST['hotelId'];
 while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	

$res = "Insert into `".TBL_INVENTORY."` set hotel_id='3', room_id='12', blocked_hotel='0', crs_available='5', online_allocation='2',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',status='1',last_modified_by='9',allocation_date='".$checkinDate."' " ;

if(executeSql($res)){
echo 'success';
}
$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));	
}


?>