<?php include_once("../config/fron_autoload.php"); ?>

<?php
/////////////////////////////////////////////////////////////

$postData = file_get_contents('php://input');
//or below one//
//$postData = file_get_contents('WhrRequestBooking.xml');

/////////////////////////////////////////////////////////
$xml = simplexml_load_string($postData);



if($xml){
$xmlarray = json_decode(json_encode($xml), true);


//Get API Details//

$EchoToken = $xmlarray['@attributes']['EchoToken'];
$TimeStamp = $xmlarray['@attributes']['TimeStamp'];
$ResStatus = $xmlarray['@attributes']['ResStatus'];

$POSID = $xmlarray['POS']['Source']['RequestorID']['@attributes']['ID'];
$CompanyName = $xmlarray['POS']['Source']['BookingChannel']['CompanyName'];

$HotelReservations_CreateDateTime =  $xmlarray['HotelReservations']['HotelReservation']['@attributes']['CreateDateTime'];
$HotelReservations_ID =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];
$HotelReservations_Type =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['Type'];
$HotelReservations_ID_Context =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID_Context'];

$HotelReservations_HotelCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelCode'];
$HotelReservations_HotelName =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelName'];

$HotelReservations_RatePlanCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];
$HotelReservations_RatePlanName =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RatePlans']['RatePlan']['@attributes']['RatePlanName'];

$RoomType_NumberOfUnits =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];
$RoomType_RoomTypeCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];

$RoomType_RoomTypeName =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomTypes']['RoomType']['RoomDescription']['@attributes']['Name'];

$RoomType_RoomTypeName =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomTypes']['RoomType']['RoomDescription']['@attributes']['Name'];

$RoomType_AgeQualifyingCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['GuestCounts']['GuestCount']['@attributes']['AgeQualifyingCode'];
$RoomType_AgeQualifyingCount =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['GuestCounts']['GuestCount']['@attributes']['Count'];


$TimeSpan_Start =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Start'];
$TimeSpan_End =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['TimeSpan']['@attributes']['End'];


$EffectiveDate =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['@attributes']['EffectiveDate'];

$Base_AmountBeforeTax =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];

$Base_CurrencyCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['Base']['@attributes']['CurrencyCode'];

$Total_AmountBeforeTax =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['Total']['@attributes']['AmountBeforeTax'];
$Total_CurrencyCode =  $xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']['Total']['@attributes']['CurrencyCode'];

$ProfileType = $xmlarray['HotelReservations']['HotelReservation']['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['@attributes']['ProfileType'];

$Customer_GivenName = $xmlarray['HotelReservations']['HotelReservation']['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];

$Customer_Surname = $xmlarray['HotelReservations']['HotelReservation']['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];

$Total_AmountBeforeTax = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['AmountBeforeTax'];
$Total_AmountIncludingMarkup = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['AmountIncludingMarkup'];
$Total_TotalBookingAmount = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['TotalBookingAmount'];
$Total_CurrencyCode = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['CurrencyCode'];

$Tax_Amount = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['Taxes']['Tax']['@attributes']['Amount'];
$Tax_CurrencyCode = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['Taxes']['Tax']['@attributes']['CurrencyCode'];

$PayAtHotel = $xmlarray['HotelReservations']['HotelReservation']['PayAtHotel'];
$referenceNo = $HotelReservations_ID;



mysql_query("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."'");
//echo $RoomType_AgeQualifyingCount;


//Get Hotel info from db//

	

	/* grab the posts from the db */
	//$query1 = "SELECT * FROM fs_hotels WHERE id ='1'";
	
	$queryChannel = executeSql("SELECT * FROM fs_hotel_mapping WHERE booking_engine_id ='".$HotelReservations_HotelCode."' and channel_id='1' ");
	$resChannel = mysql_fetch_object($queryChannel);
	
	$queryRoomId = executeSql("SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='".$resChannel->id."' and booking_engine_id='".$RoomType_RoomTypeCode."'");
	$resRoomId = mysql_fetch_object($queryRoomId);
	
	$queryCompanyId = executeSql("SELECT * FROM fs_company_mapping WHERE booking_engine_name like '%".$CompanyName."%' and channel_id='1' ");
	$resCompanyId = mysql_fetch_object($queryCompanyId);	
	
	$queryRateId = executeSql("SELECT * FROM fs_rate_mapping WHERE booking_engine_id ='".$HotelReservations_RatePlanCode."' and channel_id='1'");
	$resRateId = mysql_fetch_object($queryRateId);	
	
	$rateId = selectColumn(TBL_RATE,'id'," WHERE (`company_id` = '".$resCompanyId->company_id."' || company_id=0) and ((start_date <=  '".date('Y-m-d',strtotime($TimeSpan_Start))."' and end_date >= '".date('Y-m-d',strtotime($TimeSpan_Start))."') OR ( start_date between '".date('Y-m-d',strtotime($TimeSpan_Start))."' and '".date('Y-m-d',strtotime($TimeSpan_Start))."') OR ( end_date between '".date('Y-m-d',strtotime($TimeSpan_Start))."' and '".date('Y-m-d',strtotime($TimeSpan_Start))."')) and id_shop='".$resChannel->id_shop."'"); 
	
	
	
	$rateAssignId = selectColumn(TBL_RATE_ASSIGN_DETAILS,'id'," WHERE `rate_id` = '".$rateId ."' and hotel_id='".$resChannel->hotel_id."' "); 
	
	
	$HotelReservations_ID =$resChannel->hotel_id;
	$query1 = "SELECT * FROM fs_hotels WHERE id ='".$resChannel->hotel_id."'";
	
	$result1 = executeSql($query1);
	$query1count = mysql_num_rows($result1);
	
	if($query1count>0) {
		while($query1data = mysql_fetch_array($result1)){
			$id_shop = trim(stripslashes($query1data['id_shop']));
			$id_shop_group = trim(stripslashes($query1data['id_shop_group']));
			$hotel_category = trim(stripslashes($query1data['hotel_category']));
			$hotel_code = trim(stripslashes($query1data['hotel_code']));
		}
		
		$id_lang=1;
		$id_hotel = $HotelReservations_ID;
		$id_rate = $rateId;
		$id_company = $resCompanyId->company_id;
		$id_company_person = 0;
		$id_cart = 0;
		$id_currency = 1;
		$payment_status = 2;
		
		
		//Get booking status info//
		
		if($ResStatus=="Commit") { 
		$ResStatusNew = "Confirmed";
		}else if($ResStatus=="Cancel") {
		$ResStatusNew = "Cancelled";	
		}else {
		$ResStatusNew = "Waitlisted";
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
		
		
		$tarrif_price = $Total_AmountBeforeTax;
		$food_price = 0;
		$extra_price = 0;
		
		$subtotal = $Total_AmountBeforeTax;
		$total_tax = $Total_TotalBookingAmount - $Total_AmountBeforeTax;
		$total_price = $Total_TotalBookingAmount;
		$amount_received = $Total_TotalBookingAmount;
		$balance = 0;
		
		
		$total_products = $RoomType_NumberOfUnits;
		
		//Qualifying code allocated for age. Values: 8-child, 10-adult
		
		if($RoomType_AgeQualifyingCode=="10"){
		$total_adults =	$RoomType_AgeQualifyingCount;
		}else {
		$total_adults = 0;	
		}
		
		
		if($RoomType_AgeQualifyingCode=="8"){
		$total_child =	$RoomType_AgeQualifyingCount;
		}else {
		$total_child = 0;	
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
		
		//get last id of orders table//
		
		$query4 = "SELECT * FROM fs_orders order by id_order desc limit 1";
		$result4 = executeSql($query4,$link);
		$query4count = mysql_num_rows($result4);
		if($query4count>0) {
		while($query4data = mysql_fetch_array($result4)){
			$fs_orders_id = trim(stripslashes($query4data['id_order']));
			$fs_orders_idnew = $fs_orders_id+1;
		}
		}else{
		$fs_orders_idnew = 0;	
		}
		
		$invID = str_pad($fs_orders_idnew, 3, '0', STR_PAD_LEFT);
		$reference = "RES/".$hotel_code."/".$invID;
		$code = 0;
		
		//get customer id from name//
		
		
		
		/*$query6 = "SELECT * FROM fs_customer WHERE first_name='".$Customer_GivenName."'";
		$result6 = mysql_query($query6,$link) or die('Errant query:  '.$query6);
		$query6count = mysql_num_rows($result6);
		if($query6count>0) {
		while($query6data = mysql_fetch_array($result6)){
			$id_customer = trim(stripslashes($query6data['id_customer']));
		}
		}else {
		$id_customer = 0;	
		}*/
		
		$query7 = mysql_query("Insert into fs_customer set first_name = '".$Customer_GivenName."' , last_name='".$Customer_Surname."',id_shop='".$id_shop."',status='1'");
		$id_customer = mysql_insert_id();			
		$Insert_into_Order_Table = "Insert into fs_orders(reference,code,id_shop_group,id_shop,id_lang,id_hotel,id_rate,id_customer,id_company,id_company_person,id_cart,id_currency,payment_status,booking_status,conversion_rate,tarrif_price,food_price,extra_price,discount_type,discount_var,total_discounts,subtotal,total_tax,total_price,amount_received,balance,total_products,total_adults,total_child,invoice_number,invoice_date,payment_date,checkin,checkout,no_of_days,valid,segment_id,series_id,operator_id,type,reminder,date_created,last_modified,last_modified_by,other_reference) Values('$reference','$code','$id_shop_group','$id_shop','$id_lang','$id_hotel','$id_rate','$id_customer','$id_company','$id_company_person','$id_cart','$id_currency','$payment_status','$booking_status','$conversion_rate','$tarrif_price','$food_price','$extra_price','$discount_type','$discount_var','$total_discounts','$subtotal','$total_tax','$total_price','$amount_received','$balance','$total_products','$total_adults','$total_child','$invoice_number','$invoice_date','$payment_date','$checkin','$checkout','$no_of_days','$valid','$segment_id','$series_id','$operator_id','$type','$reminder','$date_created','$last_modified','$last_modified_by','$referenceNo')";
					
					
		$Insert_into_Order_Table_Run = executeSql($Insert_into_Order_Table);
		
		$in_last_id = mysql_insert_id();
		
		$room_id=$resRoomId->room_id;
		$rate_id=$rateId;
		$rate_plan_id=$resRateId->rate_id;
		$rate_assign_id =$rateAssignId;
		$dated = $HotelReservations_CreateDateTime;
		$type=0;
		$room_quantity = $RoomType_NumberOfUnits;
		$adults = $total_adults;
		$child = $total_child;
		
		$unique_code = "CODE".rand(0000,9999);
		
		
		
		
	do{
		$Insert_into_Order_Details = "Insert into fs_order_detail(id_order,id_shop,hotel_id,room_id,rate_id,rate_plan_id,rate_assign_id,dated,type,room_quantity,adults,child,tarrif_price,food_price,extra_price,total_price,original_product_price,unique_code)Values('$in_last_id','$id_shop','$HotelReservations_ID','$room_id','$rate_id','$rate_plan_id','$rate_assign_id','$checkin','$type','$room_quantity','$adults','$child','$tarrif_price','$food_price','$extra_price','$total_price','$Base_AmountBeforeTax','$unique_code')";
		$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);
		
		if($booking_status ==1 || $booking_status ==2){	
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$room_quantity."',
								blocked_hotel = blocked_hotel+'".$room_quantity."',
								online_allocation=online_allocation-'".$room_quantity."' 
								where `hotel_id`='".addslashes($resChannel->hotel_id)."' and 
						  		`room_id`='".addslashes($room_id)."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($checkin)))."'");
		}
		
	$checkin = date ("Y-m-d", strtotime("+1 day", strtotime($checkin)));	
		}
	while (strtotime($checkin) < strtotime($checkout));
		
		
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