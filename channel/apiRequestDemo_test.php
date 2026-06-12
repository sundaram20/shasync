<?php include_once("../config/fron_autoload.php"); 
/////////////////////////////////////////////////////////////

$postData = file_get_contents('php://input');
//or below one//
//$postData = file_get_contents('testbookingbookingdotcom.xml');
/////////////////////////////////////////////////////////


$xml = simplexml_load_string($postData);

if($xml){
$xmlarray = json_decode(json_encode($xml), true);


/*echo "<pre>";
print_r($xmlarray);
echo "</pre>";
*/


//Get API Details//




$EchoToken = $xmlarray['@attributes']['EchoToken'];
$TimeStamp = $xmlarray['@attributes']['TimeStamp'];
$ResStatus = $xmlarray['@attributes']['ResStatus'];

$POSID 		 = $xmlarray['POS']['Source']['RequestorID']['@attributes']['ID'];
$CompanyName = trim($xmlarray['POS']['Source']['BookingChannel']['CompanyName']);



$HotelReservations_CreateDateTime =  $xmlarray['HotelReservations']['HotelReservation']['@attributes']['CreateDateTime'];
$HotelReservations_ID 			  =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];
$HotelReservations_new_ID 		  =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];
$HotelReservations_Type 		  =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['Type'];
$HotelReservations_ID_Context     =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID_Context'];

/*---------------------ResGlobalInfo----START-----------------------------------------*/
$Total_AmountBeforeTax 			= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['AmountBeforeTax'];
$Total_AmountIncludingMarkup 	= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['AmountIncludingMarkup'];
$Total_TotalBookingAmount 		= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['TotalBookingAmount'];
$Total_CurrencyCode 			= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['CurrencyCode'];
$Total_Tax_Amount 				= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['Taxes']['Tax']['@attributes']['Amount'];
$Tax_CurrencyCode 				= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['Taxes']['Tax']['@attributes']['CurrencyCode'];

/*---------------------ResGlobalInfo----END-----------------------------------------*/

/*INSERT QUERY*/
mysql_query("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='".$HotelReservations_ID."'");


$PayAtHotel = $xmlarray['HotelReservations']['HotelReservation']['PayAtHotel'];


$RoomStayCount = count($xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']);


/*Common */

		if($ResStatus=="Commit") { 
			$ResStatusNew = "Confirmed";
		}else if($ResStatus=="Cancel") {
			$ResStatusNew = "Cancelled";	
		}else {
			$ResStatusNew = "Waitlisted";
		}


$HotelReservations_ID!=='';

$CheckRecordExitSql 	= "SELECT * FROM fs_orders WHERE other_reference='".$HotelReservations_ID."'";
$ResultCheckRecordExit 	= executeSql($CheckRecordExitSql);
$CheckRecordExitCount 	= mysql_num_rows($ResultCheckRecordExit);
		
if($CheckRecordExitCount == 0) {  // CHECK Other Reference ID IS Exit Or Not

$CheckIsArrayRoomStays	=	is_array($xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'][0]);
if($CheckIsArrayRoomStays ==1){
	
	$HotelReservations_HotelCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'][0]['BasicPropertyInfo']['@attributes']['HotelCode'];
	$TimeSpan_Start =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'][0]['TimeSpan']['@attributes']['Start'];
$TimeSpan_End 	=  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'][0]['TimeSpan']['@attributes']['End'];

}else{
	
	$HotelReservations_HotelCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelCode'];
	
	$TimeSpan_Start =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Start'];
$TimeSpan_End 	=  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['TimeSpan']['@attributes']['End'];
}



$queryChannel = executeSql("SELECT * FROM fs_hotel_mapping WHERE booking_engine_id ='".$HotelReservations_HotelCode."' and channel_id='1' ");
$resChannel = mysql_fetch_object($queryChannel);



$queryCompanyId = executeSql("SELECT * FROM fs_company_mapping WHERE booking_engine_name like '%".$CompanyName."%' and channel_id='1' ");
$resCompanyId = mysql_fetch_object($queryCompanyId);	



	
		
$rateId = selectColumn(TBL_RATE,'id'," WHERE (`company_id` = '".$resCompanyId->company_id."' || company_id=0) and ((start_date <=  '".date('Y-m-d',strtotime($TimeSpan_Start))."' and end_date >= '".date('Y-m-d',strtotime($TimeSpan_Start))."') OR ( start_date between '".date('Y-m-d',strtotime($TimeSpan_Start))."' and '".date('Y-m-d',strtotime($TimeSpan_Start))."') OR ( end_date between '".date('Y-m-d',strtotime($TimeSpan_Start))."' and '".date('Y-m-d',strtotime($TimeSpan_Start))."')) and id_shop='".$resChannel->id_shop."'"); 
	
	

	
	
	$HotelReservations_ID =$resChannel->hotel_id;
	$query1 = "SELECT * FROM fs_hotels WHERE id ='".$resChannel->hotel_id."'";
	
	$result1 = executeSql($query1);
	$query1count = mysql_num_rows($result1);
	
	if($query1count>0) { //Check Hotel Id Is Mismatched 
		
		while($query1data = mysql_fetch_array($result1)){
			$id_shop = trim(stripslashes($query1data['id_shop']));
			$id_shop_group = trim(stripslashes($query1data['id_shop_group']));
			$hotel_category = trim(stripslashes($query1data['hotel_category']));
			$hotel_code = trim(stripslashes($query1data['hotel_code']));
		}
		
		$id_lang=1;
		$id_hotel = $HotelReservations_ID;
		$id_rate = 0;
		$id_company = $resCompanyId->company_id;
		$id_company_person = 0;
		$id_cart = 0;
		$id_currency = 1;

	if($PayAtHotel =='Y'){
			$payment_status = 23;	//Direct / At Hotel 
								
			}else{				
				
			 $payment_status = 36;//online / Awaited

			}
				
					
			
		$query2 = "SELECT * FROM fs_htl_booking_status WHERE name='".$ResStatusNew."'";
		
		$result2 = executeSql($query2);
		$query2count = mysql_num_rows($result2);
		
		if($query2count>0) {
		while($query2data = mysql_fetch_array($result2)){
			$booking_status = trim(stripslashes($query2data['id']));
		}
		}else{
		$booking_status="0";	
		}
		
	
		
		
		
		$conversion_rate = "1.00";
		$discount_type = 0;
		$discount_var = 0;
		$total_discounts = 0;
		
		$invoice_number = 0;
		$invoice_date = date("Y-m-d",strtotime($HotelReservations_CreateDateTime));
		$payment_date = $HotelReservations_CreateDateTime;
		$checkin = $TimeSpan_Start;
		$checkout = $TimeSpan_End;
		
		
		$now = $checkin; // or your date as well
		$your_date = strtotime($checkout);
		$datediff = $checkin - $checkout;
		$daysNew =  abs((strtotime($checkin) - strtotime($checkout))/ 86400 );
		if($daysNew == '0'){
			$no_of_days = '1';
		}else {
			$no_of_days = $daysNew;
		}
		
		
		//$no_of_days =  round($datediff / (60 * 60 * 24));
		
		$valid = 1;
		$segment_id = 1; 
		$series_id = 0;
		$operator_id = 0;
		$type = "N";
		$reminder = 0;
		
		$date_created = date("Y-m-d H:i:s");
		$last_modified = date("Y-m-d H:i:s");
		$last_modified_by = 9;
		
		
		
		/******************Hotel Code Auto Increment**START***************************/
$resHotelDetail 		= selectSql(TBL_HOTELS,"where id='".addslashes($resChannel->hotel_id)."' ",' ORDER BY `name`'); 
$resultHotelDetail 		= $db->fetch_object2($resHotelDetail);

$query4 				= "SELECT * FROM fs_orders where id_hotel ='".$resChannel->hotel_id."' order by reference desc ";
$result4 				= executeSql($query4,$link);
$query4count 			= mysql_num_rows($result4);


if($query4count>0) {	   
	$query4data 				= mysql_fetch_array($result4);
	$fs_orders_id 				= explode('/',$query4data['reference']);
	$fs_orders_id				=	$fs_orders_id[1];	
	$reference_increment_start  =  selectColumn(TBL_HOTELS,'reference_increment_start'," WHERE `id` = '".addslashes($resChannel->hotel_id)."'");

	if($fs_orders_id>=$reference_increment_start){
		$fs_orders_idnew 	= $fs_orders_id+1;	
	}else{
		$fs_orders_idnew 	= $reference_increment_start;
	}


}else{
	$fs_orders_idnew = 1;	
}

	$newId 		= str_pad($fs_orders_idnew, 3, '0', STR_PAD_LEFT);	

	$reference 	= $resultHotelDetail->hotel_code.'/'.$newId;

	/**************Hotel Code Auto Increment****END****************************/


		$code = 0;
		
		//get customer id from name//
		
		
$Newcheckin = addslashes(date("Y-m-d", strtotime($checkin)));
$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$id_shop."'";

$result14 = executeSql($query14,$link);
$query14count = mysql_num_rows($result14);	

$query14data = mysql_fetch_array($result14);
$seasonIdnew	= $query14data['id'];

		
				
	


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($id_shop)."' and `id_hotel` = '".addslashes($resChannel->hotel_id)."' and  `room_id` = '".$resRoomId->room_id."' and  `seasonId` = '".addslashes($seasonIdnew)."'");
$rowTax = $db->fetch_object2($resTax);
$rowTax->tax_room;

 $valid=	'1.'.$rowTax->tax_room;

		$TotalBeforTax		=	($Total_TotalBookingAmount - $Total_Tax_Amount);
			
		//$tarrif_price = $Total_AmountBeforeTax;
		//$total_tax = $Total_TotalBookingAmount - $Total_AmountBeforeTax;
		$total_tax	=	$Total_Tax_Amount;
		//$tarrif_price = ($Total_TotalBookingAmount/$no_of_days)-$Tax_Amount;
		$food_price = 0;
		$extra_price = 0;
		
		$subtotal 		= $TotalBeforTax;
		
		
		$tarrif_price 	= ($subtotal/$no_of_days);
		$total_price 	= $subtotal;
		
		$total_price_orderTable = 	($subtotal+$total_tax);
		
		$Base_AmountBeforeTax	=	($subtotal/$no_of_days);
		
		$amount_received = 0;
		
		$balance = ($subtotal+$total_tax);	
		
		$total_products = $RoomType_NumberOfUnits;
		

$ChechGuestArray	=	array_key_exists('0',$xmlarray['HotelReservations']['HotelReservation']['ResGuests']['ResGuest']);
if($ChechGuestArray	==1){

				$ChangeGuestArray	=	$xmlarray['HotelReservations']['HotelReservation']['ResGuests']['ResGuest'];
}else{
				$ChangeGuestArray	=	array();
				$ChangeGuestArray[]	=	$xmlarray['HotelReservations']['HotelReservation']['ResGuests']['ResGuest'];
	}	
		
		$Customer_GivenName =$ChangeGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
		$Customer_Surname = $ChangeGuestArray[0]['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];
/*INSERT QUERY*/			
		$query7 		= mysql_query("Insert into fs_customer set first_name = '".$Customer_GivenName."' , last_name='".$Customer_Surname."',id_shop='".$id_shop."',status='1'");
		$id_customer = mysql_insert_id();	
		
/*INSERT QUERY*/					
		
					$Insert_into_Order_Table = "INSERT INTO fs_orders SET 
								`reference`	='".$reference."',
								`code`	='".$code."',
								`id_shop_group`	='".$id_shop_group."',
								`id_shop`	='".$id_shop."',
								`id_lang`	='".$id_lang."',
								`id_hotel`	='".$id_hotel."',
								`id_rate`	='".$id_rate."',
								`id_customer`	='".$id_customer."',
								`id_company`	='".$id_company."',
								`id_company_person`	='".$id_company_person."',
								`id_cart`	='".$id_cart."',
								`id_currency`	='".$id_currency."',
								`payment_status`	='".$payment_status."',
								`booking_status`	='".$booking_status."',
								`conversion_rate`	='".$conversion_rate."',
								`tarrif_price`	='".$tarrif_price."',
								`food_price`	='".$food_price."',
								`extra_price`	='".$extra_price."',
								`discount_type`	='".$discount_type."',
								`discount_var`	='".$discount_var."',
								`total_discounts`	='".$total_discounts."',
								`subtotal`		=	'".$subtotal."',
								`total_tax`		=	'".$Total_Tax_Amount."',
								`total_price`	='".$total_price_orderTable."',
								`amount_received`	='".$amount_received."',
								`balance`	='".$balance."',
								`total_products`	='".$total_products."',
								`total_adults`	='".$total_adults."',
								`total_child`	='".$total_child."',
								`invoice_number`	='".$invoice_number."',
								`invoice_date`	='".$invoice_date."',
								`payment_date`	='".$payment_date."',
								`checkin`	='".$checkin."',
								`checkout`	='".$checkout."',
								`no_of_days`	='".$no_of_days."',
								`valid`	='".$valid."',
								`segment_id`	='".$segment_id."',
								`series_id`	='".$series_id."',
								`operator_id`	='".$operator_id."',
								`type`	='".$type."',
								`reminder`	='".$reminder."',
								`date_created`	='".$date_created."',
								`last_modified`	='".$last_modified."',
								`last_modified_by`	='".$last_modified_by."',
								`other_reference`	='".$HotelReservations_new_ID."'";
	
		$Insert_into_Order_Table_Run = executeSql($Insert_into_Order_Table);
		
		$in_last_id = mysql_insert_id();
					
		$kkk	=	array_key_exists('0',$xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']);

	if($kkk	==1){
		
		$RoomStayCount=$xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		$RoomStayCount1=$xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		}else{
				$RoomStayCount	=	array();
				$RoomStayCount[]	=	$xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
				$RoomStayCount1[]	=	$xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
			}

foreach($RoomStayCount as $stay=>$k){
	
	 $TotalRoomS['NumberOfUnits']	+=	$RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];
	 $TotalRoomS['RatePlanCode']	=	$RoomStayCount[$stay]['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];
	 $TotalRoomS['RoomTypeCode'] 	=   $RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];
}
	
	
		
foreach($RoomStayCount as $stay=>$k){
	
$RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];
	
	
//BasicPropertyInfo------------------------------------------	
$HotelReservations_HotelCode =  $RoomStayCount[$stay]['BasicPropertyInfo']['@attributes']['HotelCode'];	
$HotelReservations_HotelName =  $RoomStayCount[$stay]['BasicPropertyInfo']['@attributes']['HotelName'];




//RatePlans------------------------------------------
$HotelReservations_RatePlanName =  $RoomStayCount[$stay]['RatePlans']['RatePlan']['@attributes']['RatePlanName'];
$RatePlansArray = $RoomStayCount[$stay]['RatePlans'];




$RatePlansArrayCount = count($RatePlansArray);

if($RatePlansArrayCount!=0) {
foreach ($RatePlansArray as $rowplanvalue) {
	if($RatePlansArrayCount==0) {
	$HotelReservations_RatePlanCode[] = $rowplanvalue['RatePlanCode'];	
	}else {
    $HotelReservations_RatePlanCode[] = $rowplanvalue['@attributes']['RatePlanCode'];
	}
}
}else {
	$HotelReservations_RatePlanCode=array();
		
}





//RoomTypes--------------------------------------------------------
$RoomType_RoomTypeCode =  $RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];
$RoomType_NumberOfUnits =  $RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];

$TotalRoom_NumberOfUnits +=  $RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];

$RoomType_RoomTypeName =  $RoomStayCount[$stay]['RoomTypes']['RoomType']['RoomDescription']['@attributes']['Name'];





$queryRoomId = executeSql("SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='".$resChannel->id."' and booking_engine_id='".$RoomType_RoomTypeCode."'");
$resRoomId = mysql_fetch_object($queryRoomId);



//GuestCounts-------------------------------------------------------

$GuestAgeQualifyingCode 	= $RoomStayCount[$stay]['GuestCounts']['GuestCount']['@attributes']['AgeQualifyingCode'];		
$GuestCountsArray 		 	= $RoomStayCount[$stay]['GuestCounts']['GuestCount']['@attributes']['Count']; 


		if($GuestAgeQualifyingCode	==10){		
			$total_adults  +=	$GuestCountsArray;
			$adults 		= $GuestCountsArray;			
		}
		if($GuestAgeQualifyingCode ==8){
			$total_child   +=	$GuestCountsArray;			
			$child 			= $GuestCountsArray;
		}
		
		

//ResGuestRPHs--------------------------------------------------------
//TimeSpan--------------------------------------------------------
$TimeSpan_Start =  $RoomStayCount['TimeSpan']['@attributes']['Start'];
$TimeSpan_End 	=  $RoomStayCount['TimeSpan']['@attributes']['End'];

//RoomRates--------------------------------------------------------

$EffectiveDate 			=  $RoomStayCount[$stay]['RoomRates']['RoomRate']['Rates']['Rate']['@attributes']['EffectiveDate'];
$Base_AmountBeforeTax 	=  $RoomStayCount[$stay]['RoomRates']['RoomRate']['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];
$Base_CurrencyCode 		=  $RoomStayCount[$stay]['RoomRates']['RoomRate']['Rates']['Rate']['Base']['@attributes']['CurrencyCode'];


		$dated 		= 	date('Y-m-d',strtotime($TimeSpan_Start));
		$type		=	0;
		
		
		$unique_code = "CODE";
		
		
					
		$kkk1	=	array_key_exists('0',$RoomStayCount[$stay]['RoomRates']['RoomRate']['Rates']['Rate']);

	if($kkk1	==1){
		
		$RoomStayRatesRate=$RoomStayCount[$stay]['RoomRates']['RoomRate']['Rates']['Rate'];
		
		
		}else{
				$RoomStayRatesRate	=	array();
				$RoomStayRatesRate[]	=	$RoomStayCount[$stay]['RoomRates']['RoomRate']['Rates']['Rate'];	
					
				
			}
			
		$RatePlansMappingID = $RoomStayCount[$stay]['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];		
		$queryRateId 		= mysql_query("SELECT * FROM fs_rate_mapping WHERE booking_engine_id ='".$RatePlansMappingID."' and channel_id='1'");
		$resRateId 			= mysql_fetch_object($queryRateId);
		$Room_rate_plan_id	= $resRateId->rate_id;
		
			
		foreach($RoomStayRatesRate as $stay2=>$k2){
	

		
			
		$dated	= $k2['@attributes']['EffectiveDate'];
		
		$AmountBeforeTax	= $k2['Base']['@attributes']['AmountBeforeTax'];
		
			
		
		
		
		$room_id=$resRoomId->room_id;
		$room_quantity = $TotalRoomS['NumberOfUnits'];
		
		
		
		$rate_id=0;
		
	
		$SqlCheckOrderDetails = mysql_query("SELECT * FROM fs_order_detail WHERE `id_order`='".$in_last_id."' AND `room_id`='".$room_id."' AND `rate_plan_id`='".$Room_rate_plan_id."' AND  `dated`='".addslashes(date("Y-m-d",strtotime($dated)))."'");
		$NumRow	=	mysql_num_rows($SqlCheckOrderDetails); 
		if($NumRow	==0){
		
		
/*INSERT QUERY*/		
		$Insert_into_Order_Details = "Insert into fs_order_detail SET 
			`id_order`			='".$in_last_id."',
			`id_shop`			='".$id_shop."',
			`hotel_id`			='".$HotelReservations_ID."',
			`room_id`			='".$room_id."',
			`rate_id`			='".$rate_id."',
			`rate_plan_id`		='".$Room_rate_plan_id."',
			`rate_assign_id`	='".$rate_assign_id."',
			`dated`				='".addslashes(date("Y-m-d",strtotime($dated)))."',
			`type`				='".$type."',
			`room_quantity`		='".$room_quantity."',
			`adults`			='".$adults."',
			`child`				='".$child."',
			`tarrif_price`		='".$tarrif_price."',
			`food_price`		='".$food_price."',
			`extra_price`		='".$extra_price."',
			`total_price`		='".$tarrif_price."',
			`original_product_price`='".$tarrif_price."',
			`unique_code`		='".$unique_code."',
			`tarrif_price_per_day`	='".$tarrif_price."'";



			
	$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);
		
		if($booking_status ==1 || $booking_status ==2){	
		
/*INSERT QUERY -UPDATE INVENTORY*/		
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$room_quantity."',
								blocked_hotel = blocked_hotel+'".$room_quantity."',
								online_allocation=online_allocation-'".$room_quantity."' 
								where `hotel_id`='".addslashes($resChannel->hotel_id)."' and 
						  		`room_id`='".addslashes($room_id)."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($dated)))."'");
		}
		
	
		}
/*INSERT QUERY -UPDATE INVENTORY*/	
	$updateInventory = executeSql("UPDATE  `".TBL_ORDERS."`  SET 
								`total_products`='".$TotalRoom_NumberOfUnits."',`total_adults`	='".$total_adults."',
								`total_child`	='".$total_child."'
								where 
								`id_order`			='".$in_last_id."'");
				
		}
		//}	

		//}
} //RoomStay--//////////////////////////////////////////////////////////////////////////////////////////////-End Foreach





		
	
		
		
	/*/*while (strtotime($checkin) < strtotime($checkout));*/
		
		$idres=	encryptor(encrypt,$in_last_id);
	
		 "<script type='text/javascript'>window.location.href='../adminpanel/mail-template/apiRequestSendOrderMail.php?id=".$idres."';</script>";

		echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$reference.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>Success</Success>
				</OTA_HotelResNotifRS>';


					
		}else{
						echo '<?xml version="1.0" encoding="UTF8"?>
					<OTA_HotelResNotifRS EchoToken="'.rand(0000,9999).'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
					<Errors>
					<Error Code="" Type="" Status="" ShortText="Hotel Id Mismatch"/>					
					</Errors>
					<Success>Hotel Id Mismatch</Success>
					</OTA_HotelResNotifRS>';
						
						}

}else{
	
	echo '<?xml version="1.0" encoding="UTF8"?>
					<OTA_HotelResNotifRS EchoToken="'.rand(0000,9999).'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
					<Errors>
					<Error Code="" Type="" Status="" ShortText="Duplicate"/>
					</Errors>
					<Success>Duplicate </Success>
					</OTA_HotelResNotifRS>';
	
	}
		

	
	
}else{
echo '<?xml version="1.0" encoding="UTF8"?>
<OTA_HotelResNotifRS EchoToken="'.rand(0000,9999).'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
<Errors>
<Error Code="" Type="" Status="" ShortText="Not an XML input"/>
</Errors>
</OTA_HotelResNotifRS>';
}



?>