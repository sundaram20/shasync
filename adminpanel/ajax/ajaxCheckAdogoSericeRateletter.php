<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$OrderUniqueID	=$_REQUEST['OrderUniqueID'];

$eId	=$_REQUEST['eId'];
 
$reservation_date = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);

$remove = $_REQUEST['remove'];
$uniqueCode = $_REQUEST['uniqueCode'];

$rate_id = $_REQUEST['rate_id'];
$checkinDate 		= $reservation_date[0];
$checkoutDate 		= $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}


if($rate_id	== '0'){
	$_SESSION['editCart'][$OrderUniqueID]['rate_id']	=	0;
	
	foreach($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'] as $RoomUniqueCode){
		
		$StartDateListFor	=strtotime($checkinDate);
	
	for($i=0;$i<$noOfDays;$i++){	

		$UniqueDateFor = date ("d-m-Y", $StartDateListFor);
		
		$hotel_id	=	$_SESSION['editCart'][$OrderUniqueID]['hotel_id'];
		$room_type_id	=	$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$RoomUniqueCode];
		$rate_id =	0;
		$rate_plan_id	=	$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$RoomUniqueCode];
		$rate_assign_id=0;
		$type=0;
		
		
		
		$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$RoomUniqueCode] ='dateValue|'.$hotel_id.'|'.$room_type_id.'|'.$rate_id.'|'.$rate_plan_id.'|'.$rate_assign_id.'|'.$type;
		
		
		//$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$RoomUniqueCode]);
		//print_r($dataValue);
		
		
		
		//echo "===".$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$RoomUniqueCode];
		
		$StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDateFor));
		
		
		}

$StartDateListFor	=strtotime($checkinDate);
		
		
		$hotel_id	=	$_SESSION['editCart'][$OrderUniqueID]['hotel_id'];
		$room_type_id	=	$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$checkinDate][$RoomUniqueCode];
		if($room_type_id==''){
			$room_type_id	=0;
			}
		$rate_id =	0;
		$rate_plan_id	=	$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$checkinDate][$RoomUniqueCode];
		$rate_assign_id=0;
		$type=0;
		
		
		
		$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$RoomUniqueCode] ='dateValue|'.$hotel_id.'|'.$room_type_id.'|'.$rate_id.'|'.$rate_plan_id.'|'.$rate_assign_id.'|'.$type;	
		
		
		
	
		
		
	echo $RoomUniqueCode;
	echo "&&&&";	
	echo '<td id="trafficprice_"'.$RoomUniqueCode.'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$RoomUniqueCode.'" value="'.round((($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$checkinDate][$RoomUniqueCode])),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$RoomUniqueCode.'\');" ></td>';
	
	echo '@@@@@<input id="dataValue" type="text" name="dataValue" value="'.$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$RoomUniqueCode].'" id="dataValue|'.$RoomUniqueCode.'">';
	echo '|||';
	
	
	
	}echo "###".$rate_id;
}
	if(($remove == 'removeAll')  && ($rate_id!='0')){ //$remove == 'removeAll'

	unset($_SESSION['editCart'][$OrderUniqueID]['dataValue']);
	unset($_SESSION['editCart'][$OrderUniqueID]['dataValue']);
	unset($_SESSION['editCart'][$OrderUniqueID]['tarrif_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);
	unset($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
	unset($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
	unset($_SESSION['editCart'][$OrderUniqueID]['child_no']);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['pkg_extra']);
	unset($_SESSION['editCart'][$OrderUniqueID]['pkg_description']);
	
	unset($_SESSION['editCart'][$OrderUniqueID]['room_tax_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['rate_id']);
	unset($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
	unset($_SESSION['editCart'][$OrderUniqueID]['meal']);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_type_id']);
	unset($_SESSION['editCart'][$OrderUniqueID]['totalPrice']);
	unset($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);
	
	unset($_SESSION['editCart'][$OrderUniqueID]['id_othercharges_detail']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_description']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_tax']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_net']);
	
	unset($_SESSION['editCart'][$OrderUniqueID]['discountVar']);
	unset($_SESSION['editCart'][$OrderUniqueID]['discountType']);
	unset($_SESSION['editCart'][$OrderUniqueID]['discountPrice']);

	unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);
	unset($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id']);
	
	unset($_SESSION['editCart'][$OrderUniqueID]['totalRoom']);
	unset($_SESSION['editCart'][$OrderUniqueID]['totalAdult']);
	unset($_SESSION['editCart'][$OrderUniqueID]['totalInfant']);
	
	unset($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']);
	unset($_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif']);
	unset($_SESSION['editCart'][$OrderUniqueID]['finalPrice']);
	
	unset($_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['id_othercharges_detail']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_description']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_tax']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_net']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
	unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
	unset($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID']);
 echo '<td colspan="8"><strong>Please add room again with new details.</strong></td>' ;
 echo "###".$rate_id;
		}

?>