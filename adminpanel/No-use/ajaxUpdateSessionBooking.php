<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$remove = $_REQUEST['remove'];
$uniqueCode = $_REQUEST['uniqueCode'];
if($remove == 'removeAll'){

	unset($_SESSION['bookCart']);
	
 echo '<td colspan="8"><strong>Please add room again with new details.</strong></td>' ;
}else if(($remove == 'removeOne') && ($uniqueCode!='')){

	unset($_SESSION['bookCart']['dataValue'][$uniqueCode]);
	unset($_SESSION['bookCart']['room_quantity'][$uniqueCode]);
	unset($_SESSION['bookCart']['adult_no'][$uniqueCode]);
	unset($_SESSION['bookCart']['infant_no'][$uniqueCode]);
	unset($_SESSION['bookCart']['child_no'][$uniqueCode]);
	unset($_SESSION['bookCart']['room_price'][$uniqueCode]);
	unset($_SESSION['bookCart']['taxablePrice']);
	unset($_SESSION['bookCart']['discountType']);
	unset($_SESSION['bookCart']['discountVar']);
	unset($_SESSION['bookCart']['discountPrice']);
	unset($_SESSION['bookCart']['totalPrice']);
	unset($_SESSION['bookCart']['finalPrice']);
	echo 'success|||';
	$reservationDate = explode(' to ',$_SESSION['reservation_date']);
	$checkinDate = $reservationDate[0];
	$checkoutDate = $reservationDate[1];
	$roomAllotedArray = array();
	do{
		$roomAllotedArray[] = GetTotalRoomAlloted($checkinDate,$_SESSION['bookingHotelId'],$_SESSION['bookingRoomId'][$uniqueCode]);
		$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
	}
	while (strtotime($checkinDate) < strtotime($checkoutDate));
	$totalRoom = GetTotalRoom($_SESSION['bookingHotelId'],$_SESSION['bookingRoomId'][$uniqueCode]);
	$roomAlloted = max($roomAllotedArray);
	$totalRoomAvailable = $totalRoom-$roomAlloted;
	foreach($_SESSION['bookingRoomId'] as $uniqueCodes =>$roomType){
		if($uniqueCode == $uniqueCodes){
			$totalRoomBooked += $_SESSION['bookingRoomNo'][$uniqueCodes];
		}
		$totalRoomTypeBooked += $_SESSION['bookingRoomNo'][$uniqueCodes];
	}
	
	
	if($_SESSION['totalRoomAvailable'] >= $totalRoomTypeBooked){
	echo 'removeroomLimitMsg|||';
	unset($_SESSION['roomLimitMsg']);
	}else {
	echo '|||';
	}
	if($totalRoomAvailable >= $totalRoomBooked){
	echo 'roomLimitMsgRoomType|||';
	unset($_SESSION['roomLimitMsgRoomType']);
	}else {
	echo '|||';
	}
}


?>