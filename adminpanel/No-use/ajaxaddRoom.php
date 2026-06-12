<?php include_once("includes/autoloader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
/////////////////////////////////reservation date and no of days in session ////////////////////////////
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}
$_SESSION['bookingHotelId'] = $_REQUEST['hotel_id'];
$_SESSION['noOfDays'] = $noOfDays;
$_SESSION['reservation_date'] = $_REQUEST['reservation_date'];
/////////////////////////////////reservation date and no of days in session end ////////////////////////////
$totalRoom = GetTotalRoom($hotelId);
$roomAllotedArray = array();
do{
	$roomAllotedArray[] = GetTotalRoomAlloted($checkinDate,$hotelId,'');
	$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
}
while (strtotime($checkinDate) < strtotime($checkoutDate));
$roomAlloted = max($roomAllotedArray);
$totalRoomAvailable = $totalRoom-$roomAlloted;
$_SESSION['totalRoomAvailable']=$totalRoomAvailable;

$totalRoomTypeBooked=0;
foreach($_SESSION['bookingRoomId'] as $uniqueCode =>$roomType){	
	$totalRoomTypeBooked += $_SESSION['bookingRoomNo'][$uniqueCode];
}

if($totalRoomAvailable <= $totalRoomTypeBooked){
	echo '<h4><i class="icon fa fa-warning"></i> Room Limit Exceeded.</h4>|||';
	$_SESSION['roomLimitMsg']='<h4><i class="icon fa fa-warning"></i> Room Limit Exceeded.</h4>';
}
else if($totalRoomAvailable <= count($_SESSION['bookingRoomId']) ){
	echo '<h4><i class="icon fa fa-warning"></i> Room Limit Exceeded.</h4>|||';
	$_SESSION['roomLimitMsg']='<h4><i class="icon fa fa-warning"></i> Room Limit Exceeded.</h4>';
}else {
	echo '|||';
}
/////////////////////////////executing query///////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($_REQUEST['rate_assign_id'])."' and rd.rate_id='".addslashes($_REQUEST['rate_id'])."' and room_id='".addslashes($_REQUEST['room_id'])."' order by rd.room_id");	

if(num_rows($resRoom) >0){
$rowRoom = $db->fetch_object2($resRoom);

$uniqueCode = 'CODE'.rand(0000,9999);
$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td>'.$rowRoom->room_name.'</td>';				
$availableData .=' <td> <select class="form-control" name="bookingRoomNo[]" id="bookingRoomNo_'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));" >
                  <option value="1" >1</option>
				  <option value="2" >2</option>
				  <option value="3" >3</option>
				  <option value="4" >4</option>
				  <option value="5" >5</option>
                </select></td>
				<input type="hidden" name="bookingHotelId[]" value="'.$hotelId.'" id="bookingHotelId_'.$uniqueCode.'">
				<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode_'.$uniqueCode.'">
				  <td>  <select class="form-control" name="bookingAdultNo[]" id="bookingAdultNo_'.$uniqueCode.'" data-parsley-required  onchange="getRate($(this).attr(\'id\'));">
                  <option value="1" >1</option>
				  <option value="2" >2</option>
				  <option value="3" >3</option>
                </select></td>
				 <td> <select class="form-control" name="bookingInfantNo[]" id="bookingInfantNo_'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td> <select class="form-control" name="bookingChildNo[]" id="bookingChildNo_'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td id="price_'.$uniqueCode.'" valign="bottom"><strong><i class="fa fa-inr"></i>  00.00</strong></td>  
				  <td> <a class="btn btn-danger" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';


}



				
$sessionRoomId = $_SESSION['bookingRoomId'];
$sessionRoomId[$uniqueCode] = '';
$_SESSION['bookingRoomId'] = $sessionRoomId;

$sessionRoomNo = $_SESSION['bookingRoomNo'];
$sessionRoomNo[$uniqueCode] = '1';
$_SESSION['bookingRoomNo'] = $sessionRoomNo;

echo $availableData;
?>