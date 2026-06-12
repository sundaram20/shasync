<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$hotelId	=	$_REQUEST['hotelId'];
$roomdefaultvalue = $_REQUEST['roomdefaultvalue'];
$roomdefaultvalue;
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' ORDER BY ahr.display_order");
	$hotelRoomType  =	' <select class="form-control select2" name="room_id" id="room_id">';
if($roomdefaultvalue != 0){
	//$hotelRoomType  .=    '<option value="" selected>Select Room</option>';									 
}else{
	$hotelRoomType  .=    '<option value="0" selected>All Rooms</option>';	
}
while($rowRoom = mysqli_fetch_object($resRoom)){	
	$hotelRoomType .= '<option value="'.$rowRoom->room_id.'">'.$rowRoom->name.'</option>';
}								 
	$hotelRoomType  .=	'</select>';	
echo $hotelRoomType ;
?>