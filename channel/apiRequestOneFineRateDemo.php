<?php 
//error_reporting(1);
//error_reporting(E_ALL);
include_once("../config/fron_autoload_OneFineRate.php"); 
//die;
/////////////////////////////////////////////////////////////
$postData = file_get_contents('php://input');
//or below one//
//$postData = file_get_contents('testbookingstaah.js');
/////////////////////////////////////////////////////////
//mail('support1@roomstatushub.com','Api Request ',"'".$postData."'");
//$jsondeocde = json_decode($postData, true);
//echo '<pre>';print_r($jsondeocde);echo '</pre>';
//die;
//$postData = str_replace("'", ' ', $postData);

$id_shop=2;
// mysqli_query($connNew,"Insert into api_test set  request='".$postData."',date_created='".date('Y-m-d H:i:s')."'");


//$xml = simplexml_load_string($postData);

if($postData){
$xmlarray = json_decode($postData, true);
//echo '1111';
//echo '<pre>';print_r($xmlarray);
	
  $xmlarray['reservations']['reservation']['0']['booking_id'];
	//die;
$channelId='9';
/*** only for cancelled booking ***/
	
if(@$xmlarray['reservations']['reservation']['0']['booking_status']=='cancel'){
	
	
	
	//$otherRefrenceId = @$xmlarray['UniqueID']['@attributes']['ID'];
	$otherRefrenceId 			  =  @$xmlarray['reservations']['reservation']['0']['booking_id'];
	//executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='0',booking_referance_id='0',booking_type='cancel'");
	$id_order= selectColumn(TBL_ORDERS,'id_order'," WHERE other_reference='".trim($otherRefrenceId)."' ");
	
	if($id_order > 0 && $id_order!=''){

		$id_shop=selectColumn(TBL_ORDERS,'id_shop'," WHERE other_reference='".trim($otherRefrenceId)."' ");

		if($id_shop==2)
			$reason_id=4;
		else
			$reason_id=17;

		$updateSql = "UPDATE ".TBL_ORDERS." SET booking_status='4',cancellation_reason_id='".$reason_id."',last_modified='".date('Y-m-d H:i:s')."'  WHERE id_order='".$id_order."' AND other_reference='".$otherRefrenceId."' ";

		if(executeSql($updateSql)){
			
			//executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='0', channel_id = '9' ,booking_referance_id='0',response_status='0',id_pms_response='0',failed_at='".date('Y-m-d H:i:s')."',booking_type='cancel'");
			
//executeSql("Insert into api_request set channel_id = '9' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',response_status='0',id_pms_response='0',failed_at='".date('Y-m-d H:i:s')."',count='1',booking_type='cancel' ,booking_referance_id='0");
			/*echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$reference.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>Success</Success>
				</OTA_HotelResNotifRS>';*/
			
			$demoData = array("reservations"=> array("status"=>"Success","booking_id"=>$reference,"TimeStamp"=>date('Y-m-d H:i:s')));
				echo json_encode($demoData);
		}
		
	}
	
	exit;








}
/*** cancelled booking end ***/


//Get API Details//
$hotel_name 			  =  $xmlarray['reservations']['reservation']['0']['hotel_name'];
$currencycode 			  =  $xmlarray['reservations']['reservation']['0']['currencycode'];
$booking_date 			  =  $xmlarray['reservations']['reservation']['0']['booking_date'];
//$channel_ref 			  =  $xmlarray['reservations']['reservation']['0']['channel_ref'];
$payment_type 			  =  $xmlarray['reservations']['reservation']['0']['payment_type'];
$company 			  	  =  $xmlarray['reservations']['reservation']['0']['company'];
$hotel_idMapping 			      =  $xmlarray['reservations']['reservation']['0']['hotel_id'];
$deposit 			 		=  $xmlarray['reservations']['reservation']['0']['deposit'];
$booking_id 			  	=  $xmlarray['reservations']['reservation']['0']['booking_id'];
$totalprice 			  	=  $xmlarray['reservations']['reservation']['0']['totalprice'];
//$total_tax 			  		=  $xmlarray['reservations']['reservation']['0']['total_tax'];
$commissionamount 			=  $xmlarray['reservations']['reservation']['0']['commissionamount'];
$booking_status 			=  $xmlarray['reservations']['reservation']['0']['booking_status'];
$pos 			  			=  $xmlarray['reservations']['reservation']['0']['pos'];


$first_name	=	$xmlarray['reservations']['reservation']['0']['customer']['first_name'];
$last_name	=	$xmlarray['reservations']['reservation']['0']['customer']['last_name'];
$email	=	$xmlarray['reservations']['reservation']['0']['customer']['email'];
$telephone	=	$xmlarray['reservations']['reservation']['0']['customer']['telephone'];
$city	=	$xmlarray['reservations']['reservation']['0']['customer']['city'];
$first_name	=	$xmlarray['reservations']['reservation']['0']['customer']['first_name'];
$first_name	=	$xmlarray['reservations']['reservation']['0']['customer']['first_name'];

  $RoomCount = count($xmlarray['reservations']['reservation']['0']['room']);
 
 
 if($RoomCount>0){

//print_r($xmlarray['reservations']['reservation']['0']['room']);

foreach($xmlarray['reservations']['reservation']['0']['room'] as $countstart=>$datarooms){
	//print_r($datarooms);
	//echo '=='.$countstart;
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['arrival_time'];
	
	 $totalpriceRoom	=	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['totalprice'];
	$roomCodeRoom	=$xmlarray['reservations']['reservation']['0']['room'][$countstart]['id'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['child_age'];
	$checkout=$xmlarray['reservations']['reservation']['0']['room'][$countstart]['departure_date'];
	$checkin=$xmlarray['reservations']['reservation']['0']['room'][$countstart]['arrival_date'];
	$roomTypeRoom	=$xmlarray['reservations']['reservation']['0']['room'][$countstart]['name'];
	
	
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['numberofextraadult'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['guest_name'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['remarks'];
	$total_adults += $xmlarray['reservations']['reservation']['0']['room'][$countstart]['numberofadult'];
	$total_child += $xmlarray['reservations']['reservation']['0']['room'][$countstart]['numberofchild'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['numberofextrachild'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['extrachildrate'];
	
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['currencycode'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['extraadultrate'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['numberofguests'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['arrival_time'];
	
	
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['price']['date'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['price']['amount'];
	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['price']['rate_id'];
	
	
	$NewpriceValue	=	$xmlarray['reservations']['reservation']['0']['room'][$countstart]['totalprice'];
	
		$SelectTaxDateSQL		=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($id_shop)."'  order by start_date desc");
		$SelectTaxDateRow 		= mysqli_fetch_object($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
			$resNewTaxInclution=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($id_shop)."' AND ((tax_inc_slabs_from <=  '".$NewpriceValue."' and tax_inc_slabs_to  >= '".$NewpriceValue."') OR ( tax_inc_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_inc_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");

			if(num_rows($resNewTaxInclution) >0 ){
				$rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
				$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
				$subtotal +=round($NewpriceValue/$tax_new_percent);
				$total_tax  += $NewpriceValue-(round($NewpriceValue/$tax_new_percent));
			}
	
} // echo '=======subtotal'.$subtotal;$total_tax ;
//die;
$EchoToken = $xmlarray['@attributes']['EchoToken'];
$TimeStamp = $xmlarray['@attributes']['TimeStamp'];

$POSID 		 = $xmlarray['POS']['Source']['RequestorID']['@attributes']['ID'];
$CompanyName = trim($xmlarray['POS']['Source']['BookingChannel']['CompanyName']);



$HotelReservations_CreateDateTime =  $xmlarray['HotelReservations']['HotelReservation']['@attributes']['CreateDateTime'];
$HotelOtherReferanceReservations_ID 			  =  $xmlarray['reservations']['reservation']['0']['booking_id'];
//$HotelReservations_new_ID 		  =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID'];
$HotelReservations_Type 		  =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['Type'];
$HotelReservations_ID_Context     =  $xmlarray['HotelReservations']['HotelReservation']['UniqueID']['@attributes']['ID_Context'];

/*---------------------ResGlobalInfo----START-----------------------------------------*/
$Total_AmountBeforeTax 			= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['AmountBeforeTax'];
$Total_AmountIncludingMarkup 	= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['AmountIncludingMarkup'];
$Total_TotalBookingAmount 		= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['TotalBookingAmount'];
$Total_CurrencyCode 			= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['@attributes']['CurrencyCode'];
//$totaltaxamount 		  = $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['Taxes'][0]['@attributes']['Amount'];
$Tax_CurrencyCode 				= $xmlarray['HotelReservations']['HotelReservation']['ResGlobalInfo']['Total']['Taxes']['Tax']['@attributes']['CurrencyCode'];





		
		
/*---------------------ResGlobalInfo----END-----------------------------------------*/

/*====================Addition Guest Start-----------------------------------------------*/
	$guestCount=1;
foreach($xmlarray['reservations']['reservation']['0']['additional_guests']['additional_guest'] as $gueststart=>$dataGuest){


 	 $first_name	=	$xmlarray['reservations']['reservation']['0']['additional_guests']['additional_guest'][$gueststart]['first_name'];
	 $last_name	   =	$xmlarray['reservations']['reservation']['0']['additional_guests']['additional_guest'][$gueststart]['last_name'];
	$AdditionGuest .= ' Guest '.$guestCount.' '.$first_name.' '.$last_name.' ';
		
		$guestCount++;
}


 
/*====================Addition Guest End-----------------------------------------------*/
	
	          executeSql("Insert into api_request set channel_id = '9' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',response_status='0',id_pms_response='0',failed_at='".date('Y-m-d H:i:s')."',count='1',booking_type='Commit' ,booking_referance_id='".$HotelOtherReferanceReservations_ID."'");
//echo '111 step 1';die;
$PayAtHotel = $xmlarray['HotelReservations']['HotelReservation']['PayAtHotel'];


//$RoomStayCount = count($xmlarray['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']);


/*Common */

		if($booking_status=="new" || $booking_status=="modify") { 
			$ResStatusNew = "Confirmed";
		}else if($booking_status=="Cancel") {
			$ResStatusNew = "Cancelled";	
		}else {
			$ResStatusNew = "Waitlisted";
		}


$HotelReservations_ID!=='';

$CheckRecordExitSql 	= "SELECT * FROM fs_orders WHERE other_reference='".$HotelOtherReferanceReservations_ID."'"; 
$ResultCheckRecordExit 	= executeSql($CheckRecordExitSql);
$CheckRecordExitCount 	= mysqli_num_rows($ResultCheckRecordExit);


//========================================> CHECK Other Reference ID IS Exit Or Not
$BookingStatus =	$xmlarray['reservations']['reservation']['0']['booking_status'];
		
if(($CheckRecordExitCount == 0 && $BookingStatus=='new') || ($CheckRecordExitCount > 1 && $BookingStatus=='modify')) {  


if(($CheckRecordExitCount == 1 && $BookingStatus=='modify')){
	
	//echo 'modify';
		$otherRefrenceId 			  =  @$xmlarray['reservations']['reservation']['0']['booking_id'];	
	$id_order= selectColumn(TBL_ORDERS,'id_order'," WHERE other_reference='".trim($otherRefrenceId)."' ");
	
	if($id_order > 0 && $id_order!=''){

		$id_shop=selectColumn(TBL_ORDERS,'id_shop'," WHERE other_reference='".trim($otherRefrenceId)."' ");

		if($id_shop==2)
			$reason_id=4;
		else
			$reason_id=17;

		$updateSql = "UPDATE ".TBL_ORDERS." SET booking_status='4',cancellation_reason_id='".$reason_id."',last_modified='".date('Y-m-d H:i:s')."'  WHERE id_order='".$id_order."' AND other_reference='".$otherRefrenceId."' ";

		if(executeSql($updateSql)){
			
			executeSql("Insert into api_request set request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='0', channel_id = '9' ,booking_referance_id='0',response_status='0',id_pms_response='0',failed_at='".date('Y-m-d H:i:s')."',booking_type='cancel'");

			//$demoData = array("reservations"=> array("status"=>"Success","booking_id"=>$reference,"TimeStamp"=>date('Y-m-d H:i:s')));
				//echo json_encode($demoData);
		}
		
	}
	
	}


$queryChannel = executeSql("SELECT * FROM fs_hotel_mapping WHERE booking_engine_id ='".$hotel_idMapping."' and channel_id='".$channelId."' ");
$resChannel = mysqli_fetch_object($queryChannel);



$queryCompanyId = executeSql("SELECT * FROM fs_company_mapping WHERE booking_engine_name like '%".$company."%' and channel_id='".$channelId."' ");
$resCompanyId = mysqli_fetch_object($queryCompanyId);	



	
		
$rateId = selectColumn(TBL_RATE,'id'," WHERE (`company_id` = '".$resCompanyId->company_id."' || company_id=0) and ((start_date <=  '".date('Y-m-d',strtotime($TimeSpan_Start))."' and end_date >= '".date('Y-m-d',strtotime($TimeSpan_Start))."') OR ( start_date between '".date('Y-m-d',strtotime($TimeSpan_Start))."' and '".date('Y-m-d',strtotime($TimeSpan_Start))."') OR ( end_date between '".date('Y-m-d',strtotime($TimeSpan_Start))."' and '".date('Y-m-d',strtotime($TimeSpan_Start))."')) and id_shop='".$resChannel->id_shop."'"); 
	
	

	
	$apiHotelID	=	$resChannel->hotel_id;
	$HotelReservations_ID =$resChannel->hotel_id;
	$query1 = "SELECT * FROM fs_hotels WHERE id ='".$resChannel->hotel_id."'";
	
	$result1 = executeSql($query1);
	$query1count = mysqli_num_rows($result1);
//========================================> Check Hotel Id Is Mismatched
	if($query1count>0) { 
		
		while($query1data = mysqli_fetch_array($result1)){
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

	if($payment_type =='Hotel Collect'){
			$payment_status = 23;	//Direct / At Hotel 
								
			}else{				
				
			 $payment_status = 36;//online / Awaited

			}
				
					
			
		$query2 = "SELECT * FROM fs_htl_booking_status WHERE name='".$ResStatusNew."'";
		
		$result2 = executeSql($query2);
		$query2count = mysqli_num_rows($result2);
		
		if($query2count>0) {
		while($query2data = mysqli_fetch_array($result2)){
			$booking_status = trim(stripslashes($query2data['id']));
		}
		}else{
		$booking_status="0";	
		}
		$booking_status="1";

		$booking_confirm_date	=date('Y-m-d');
		
		$conversion_rate = "1.00";
		$discount_type = 0;
		$discount_var = 0;
		$total_discounts = 0;
		
		$invoice_number = 0;
		$invoice_date = date("Y-m-d",strtotime($booking_date));
		$payment_date = $HotelReservations_CreateDateTime;
		//$checkin = $TimeSpan_Start;
		//$checkout = $TimeSpan_End;
		
		
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
		$segment_id = 39; 
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

$query4 				= "SELECT * FROM fs_orders where id_hotel ='".$resChannel->hotel_id."' order by id_order desc ";
$result4 				= executeSql($query4,$link);
$query4count 			= mysqli_num_rows($result4);


if($query4count>0) {	   
	$query4data 				= mysqli_fetch_array($result4);
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
		

		
		$TotalBeforTax		=	$Total_TotalBookingAmount-$totaltaxamount;
		$food_price = 0;
		$extra_price = 0;
		
		//$subtotal 		= $TotalBeforTax;
		
		
		$tarrif_price 	= ($subtotal/$no_of_days);
		$total_price 	= $subtotal;
		
		$total_price_orderTable = 	($totalprice);
		
		$Base_AmountBeforeTax	=	($subtotal/$no_of_days);
		
		$amount_received = 0;
		
		$balance = ($totalprice);	
		
		$total_products = count($xmlarray['reservations']['reservation']['0']['room']);
		

		
/*INSERT QUERY*/			
		$query7 		= executeSql("Insert into fs_customer set first_name = '".$first_name."' , last_name='".$last_name."', email='".$email."', mobile='".$telephone."',id_shop='".$id_shop."',status='1'");
		$id_customer = $db->insert_id();
		//$id_customer = mysqli_insert_id();	
		
/*INSERT QUERY*/					
		
			$Insert_into_Order_Table = "INSERT INTO fs_orders SET 
								`reference`	='".$reference."',
								`code`	='".$code."',
								`booking_confirm_date` ='".$booking_confirm_date."',
								`id_shop_group`	='".$id_shop_group."',
								`id_shop`	='".$id_shop."',
								`id_lang`	='".$id_lang."',
								`id_hotel`	='".$resChannel->hotel_id."',
								`id_rate`	='".$id_rate."',
								`id_customer`	='".$id_customer."',
								`id_company`	='".$id_company."',
								`id_company_person`	='".$id_company_person."',
								`id_cart`	='".$id_cart."',
								`id_currency`	='".$id_currency."',
								`payment_status`	='".$payment_status."',
								`payment_remarks`	='".$AdditionGuest."',
								`booking_status`	='".$booking_status."',
								`conversion_rate`	='".$conversion_rate."',
								`tarrif_price`	='".$totalprice."',
								`food_price`	='".$food_price."',
								`extra_price`	='".$extra_price."',
								`discount_type`	='".$discount_type."',
								`discount_var`	='".$discount_var."',
								`total_discounts`	='".$total_discounts."',
								`subtotal`		=	'".$subtotal."',
								`total_tax`		=	'".$total_tax."',
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
								 `id_booking_source`='4',
								 `booking_hrough`='25',
								`series_id`	='".$series_id."',
								`operator_id`	='".$operator_id."',
								`type`	='".$type."',
								`reminder`	='".$reminder."',
								`date_created`	='".$date_created."',
								`last_modified`	='".$last_modified."',
								`last_modified_by`	='".$last_modified_by."',
								`created_by`='".$last_modified_by."',
								`other_reference`	='".$booking_id."'";
	echo $Insert_into_Order_Table;
		$Insert_into_Order_Table_Run = executeSql($Insert_into_Order_Table);		
		$in_last_id = $db->insert_id();
		//$in_last_id = mysqli_insert_id();
					
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
	 
	 $SetTypeCode					=	 $TotalRoomS['RoomTypeCode'];	 
	 $SetTypeCodeNew[$SetTypeCode]['NumberOfUnits']	+=$RoomStayCount[$stay]['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];
}
	
//New StAAH INtegration Start====================================================>


foreach($xmlarray['reservations']['reservation']['0']['room'] as $counts=>$datarooms){
	//print_r($datarooms);
	$counts;
	$checkout='';$checkin='';
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['arrival_time'];
	
	$totalpriceRoom	=	$xmlarray['reservations']['reservation']['0']['room'][$counts]['totalprice'];
	$roomCodeRoom	  =    $xmlarray['reservations']['reservation']['0']['room'][$counts]['id'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['child_age'];
	$checkout=$xmlarray['reservations']['reservation']['0']['room'][$counts]['departure_date'];
	$checkin=$xmlarray['reservations']['reservation']['0']['room'][$counts]['arrival_date'];
	$roomTypeRoom	=$xmlarray['reservations']['reservation']['0']['room'][$counts]['name'];
	
	
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['numberofextraadult'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['guest_name'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['remarks'];
	$adults= $xmlarray['reservations']['reservation']['0']['room'][$counts]['numberofadult'];
	$child = $xmlarray['reservations']['reservation']['0']['room'][$counts]['numberofchild'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['numberofextrachild'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['extrachildrate'];
	
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['currencycode'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['extraadultrate'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['numberofguests'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['arrival_time'];
	
	
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['price']['0']['date'];
	$xmlarray['reservations']['reservation']['0']['room'][$counts]['price']['0']['amount'];
	$RatePlansMappingID	=$xmlarray['reservations']['reservation']['0']['room'][$counts]['price']['0']['rate_id'];
	
	$NewpriceValue	=	$xmlarray['reservations']['reservation']['0']['room'][$counts]['totalprice'];
	$RoomType_NumberOfUnits=1;
 $dated = $checkin;
	 $checkout;
	
	 foreach($xmlarray['reservations']['reservation']['0']['room'][$counts]['price'] as $priceCounts=>$daywisePrice){
		 
	//$NewpriceValue	=	$xmlarray['reservations']['reservation']['0']['room'][$counts]['price'][$priceCounts]['amount'] ;
	//$dated	=	$xmlarray['reservations']['reservation']['0']['room'][$counts]['price'][$priceCounts]['date'] ;
		//print_r($daywisePrice);	 
	 }
	$noDaysin=  count($xmlarray['reservations']['reservation']['0']['room'][$counts]['price']);
	$NewpriceValue=round($NewpriceValue/$noDaysin);
while(strtotime($dated)!=strtotime($checkout)){
				
		//echo '<br>'.date('Y-m-d',strtotime($dated));
		$SQLRateId 			= mysqli_query($connNew,"SELECT * FROM fs_rate_mapping WHERE booking_engine_id ='".addslashes($RatePlansMappingID)."' and channel_id='".$channelId."'");
		$FetchArrayRateId 	= mysqli_fetch_object($SQLRateId);
		$Room_rate_plan_id	= addslashes($FetchArrayRateId->rate_id);
		
		
		
				
		$SelectTaxDateSQL		=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($id_shop)."'  order by start_date desc");
		$SelectTaxDateRow 		= mysqli_fetch_object($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
			$resNewTaxInclution=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($id_shop)."' AND ((tax_inc_slabs_from <=  '".$NewpriceValue."' and tax_inc_slabs_to  >= '".$NewpriceValue."') OR ( tax_inc_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_inc_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");

			if(num_rows($resNewTaxInclution) >0 ){
				$rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
				//$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
				//$totalpriceRoom=round($NewpriceValue/$tax_new_percent);
				//$tax_perday_perroom= $NewpriceValue-$totalpriceRoom;
				
				$resRatePlanDetail 		= selectSql('fs_rate_plan',"where id='".addslashes($Room_rate_plan_id)."' ",' ORDER BY `name`'); 
				$resRateDetail = mysqli_fetch_object($resRatePlanDetail);
				$tax_detail=$resRateDetail->tax_detail;
				if($tax_detail=='2'){ 
					$tax_new_percent	=$rowNewTaxInclution->tax_percent;
					 $totalpriceRoom=round($NewpriceValue);
					$taxparsentamount=round(($NewpriceValue*$tax_new_percent)/100);
					$tax_perday_perroom= $taxparsentamount;
				}else{
				
					$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
					$totalpriceRoom=round($NewpriceValue/$tax_new_percent);
					$tax_perday_perroom= $NewpriceValue-$totalpriceRoom;
				}
				
				$SubTotalAssignDetail	+=($totalpriceRoom);
			    $SubTotalTax	+=($tax_perday_perroom);
			}
			
						
	//echo '<br>'."SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='".$resChannel->id."' and booking_engine_id='".$roomCodeRoom."'";
$queryRoomId = executeSql("SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='".$resChannel->id."' and booking_engine_id='".$roomCodeRoom."'");
$resRoomId = mysqli_fetch_object($queryRoomId);
		$room_id=$resRoomId->room_id;
		$room_quantity = 1;
		
		
		
		$rate_id=0;
		
	
		$SqlCheckOrderDetails = mysqli_query($connNew,"SELECT * FROM fs_order_detail WHERE `id_order`='".$in_last_id."' AND `room_id`='".$room_id."' AND `rate_plan_id`='".$Room_rate_plan_id."' AND  `dated`='".addslashes(date("Y-m-d",strtotime($dated)))."' AND `adults`='".$adults."'");
		$NumRow	=	mysqli_num_rows($SqlCheckOrderDetails); 
		$resRoomOrderDetails= mysqli_fetch_object($SqlCheckOrderDetails);
		
		
		if($NumRow	==0){
		
		
		
		  '<br>'.$Insert_into_Order_Details = "Insert into fs_order_detail SET 
			`id_order`			='".$in_last_id."',
			`id_shop`			='".$id_shop."',
			`hotel_id`			='".$resChannel->hotel_id."',
			`room_id`			='".$room_id."',
			`rate_id`			='".$rate_id."',
			`rate_plan_id`		='".$Room_rate_plan_id."',
			`rate_assign_id`	='".$rate_assign_id."',
			`dated`				='".date("Y-m-d",strtotime($dated))."',
			`type`				='".$type."',
			`room_quantity`		='".$RoomType_NumberOfUnits."',
			`adults`			='".$adults."',
			`child`				='".$child."',
			`tarrif_price`		='".$totalpriceRoom."',
			`food_price`		='".$food_price."',
			`extra_price`		='".$extra_price."',
			`total_price`		='".$totalpriceRoom."',
			`original_product_price`='".$totalpriceRoom."',
			`unique_code`		='".$unique_code."',
			`tax_perday_perroom`		='".$tax_perday_perroom."',
			`tarrif_price_per_day`	='".$tarrif_price."'";



			
	$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);
		
		if($booking_status ==1 || $booking_status ==2){	
		
//INSERT QUERY -UPDATE INVENTORY		
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$room_quantity."',
								blocked_hotel = blocked_hotel+'".$room_quantity."',
								online_allocation=online_allocation-'".$room_quantity."' 
								where `hotel_id`='".addslashes($resChannel->hotel_id)."' and 
						  		`room_id`='".addslashes($room_id)."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($dated)))."'");
		}
		
	
		}else{ //SAME ROOM TYPE UPDATE QTY
			//`tax_perday_perroom`		=`tax_perday_perroom`+'".$tax_perday_perroom."',
			$updateInventory = executeSql("UPDATE  fs_order_detail  SET 								
								`room_quantity`		=`room_quantity`+'".$RoomType_NumberOfUnits."',								
								`tarrif_price`		=`tarrif_price`+'".$totalpriceRoom."',
								`food_price`		=`food_price`+'".$food_price."',
								`extra_price`		=`extra_price`+'".$extra_price."',
								`total_price`		=`total_price`+'".$totalpriceRoom."',
								`original_product_price`=`original_product_price`+'".$totalpriceRoom."',								
								
								`tarrif_price_per_day`	=`tarrif_price_per_day`+'".$tarrif_price."'
								where 
								`id_order`			='".$in_last_id."' and `id_order_detail`			='".$resRoomOrderDetails->id_order_detail."'");
			
			}
		
		/*INSERT QUERY -UPDATE INVENTORY*/	
	$updateInventory = executeSql("UPDATE  `".TBL_ORDERS."`  SET 
								`total_price`	='".($SubTotalAssignDetail+$SubTotalTax)."',
								
								`balance`	='".($SubTotalAssignDetail+$SubTotalTax)."',
								`subtotal`		=	'".$SubTotalAssignDetail."',																
								`total_tax`='".$SubTotalTax."'
								where 
								`id_order`			='".$in_last_id."'");
		
		 $dated = date('Y-m-d',strtotime('+1 day',strtotime($dated)));
		
		
		
		} 
	InventoryUpdateDayWise($id_hotel,date('Y-m-d',strtotime($checkin)),date('Y-m-d',strtotime($checkout)));
	
	$AmendmentSQL = "INSERT INTO  `".TBL_AMENDMENT_COUNT."` SET 				 
								`id_order`			='".addslashes($in_last_id)."',								
								`id_shop`			='".$id_shop."',
								`id_hotel`			='".$id_hotel."',
								`id_rate`			='".$id_rate."',
								`id_customer`		='".$id_customer."',
								`id_company`		='".$id_company."',
								`id_company_person`	='".$id_company_person."',
								`payment_status`	='".$payment_status."',
								`booking_status`	='".$booking_status."',				  				  
								`tarrif_price`		='".$tarrif_price."',
								`food_price`		='".$food_price."',
								`extra_price`		='".$extra_price."',
								`subtotal`			='".$subtotal."',
								`total_tax`			='".$totaltaxamount."',
								`total_price`		='".$total_price_orderTable."',
								`amount_received`	='".$amount_received."',
								`balance`			='".$balance."',
								`total_products`	='".$RoomType_NumberOfUnits."',				
								`total_adults`		='".$total_adults."',
								`total_child`		='".$total_child."',
								`payment_date`		='".$payment_date."',																
								`checkin`			='".$checkin."',
								`checkout`			='".$checkout."',
								`no_of_days`		='".$no_of_days."',
								`segment_id`		='".$segment_id."',		
								`type`				='".$type."',
								`date_created`		='".$date_created."',
								`last_modified`		='".$last_modified."',
								`last_modified_by`	='".$last_modified_by."'
								
				 ";
				  				  
executeSql($AmendmentSQL);
}

//New StAAH INtegration End=====================================>









	/*/*while (strtotime($checkin) < strtotime($checkout));*/
		
		$idres=	encryptor(encrypt,$in_last_id);
	
		/* "<script type='text/javascript'>window.location.href='../adminpanel/mail-template/apiRequestSendOrderMail.php?id=".$idres."';</script>";*/

		/*echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$reference.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>Success</Success>
				</OTA_HotelResNotifRS>';*/
		
		$demoData = array("reservations"=> array("status"=>"Success","booking_id"=>$HotelOtherReferanceReservations_ID,"TimeStamp"=>date('Y-m-d H:i:s')));
				echo json_encode($demoData);
		
				$UserBookingType	    =	'OTA';
				$OrderLastInsertedID	=	encryptor(encrypt,$in_last_id);
				//if($apiHotelID=='173' || $apiHotelID=='221' || $apiHotelID == '227'){
				$idsActiveSql = "SELECT * FROM `".TBL_HOTEL_MAPPING."` WHERE `id_shop` = '2'   AND `hotel_id` = '".addslashes($apiHotelID)."' and channel_id='".$channelId."' and status=1 ";
				$idsActiveResult = executeSql($idsActiveSql);
				$idsActiveResultcount = mysqli_num_rows($idsActiveResult);
				$idsActiveResultdata = mysqli_fetch_object($idsActiveResult);
				if($apiHotelID == $idsActiveResultdata->hotel_id){	
				
				//MustOPEN - include($_SERVER["DOCUMENT_ROOT"]."/crs/adminpanel/idsApiRequest.php");
				}
				//MustOPEN - include_once($_SERVER["DOCUMENT_ROOT"]."/crs/adminpanel/adiApiBookingRequest.php");
				//MustOPEN - adiBookingRequest(4,$OrderLastInsertedID,$apiHotelID);	
		 		//MustOPEN - crmconnectivity($id_customer,$id_company,$OrderLastInsertedID);
					
		}else{
		/*echo '<?xml version="1.0" encoding="UTF8"?>
					<OTA_HotelResNotifRS EchoToken="'.rand(0000,9999).'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
					<Errors>
					<Error Code="" Type="" Status="" ShortText="Hotel Id Mismatch"/>					
					</Errors>
					<Success>Hotel Id Mismatch</Success>
					</OTA_HotelResNotifRS>';*/
			$demoData = array("reservations"=> array("status"=>"Hotel Id Mismatch","booking_id"=>$HotelOtherReferanceReservations_ID,"TimeStamp"=>date('Y-m-d H:i:s')));
				echo json_encode($demoData);
						
						}

}else{
					$demoData = array("reservations"=> array("status"=>"Duplicate","booking_id"=>$HotelOtherReferanceReservations_ID,"TimeStamp"=>date('Y-m-d H:i:s')));
				echo json_encode($demoData);
	/*echo '<?xml version="1.0" encoding="UTF8"?>
					<OTA_HotelResNotifRS EchoToken="'.rand(0000,9999).'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
					<Errors>
					<Error Code="" Type="" Status="" ShortText="Duplicate"/>
					</Errors>
					<Success>Duplicate </Success>
					</OTA_HotelResNotifRS>';*/
	
	}
		

	}else{
				$demoData = array("reservations"=> array("status"=>"Room Information is required","booking_id"=>$HotelOtherReferanceReservations_ID,"TimeStamp"=>date('Y-m-d H:i:s')));
				echo json_encode($demoData);
	
	
	}
	
}else{
$demoData = array("reservations"=> array("status"=>"Invalid JSON in request","booking_id"=>$reference,"TimeStamp"=>date('Y-m-d H:i:s')));
				echo json_encode($demoData);	
	
/*echo '<?xml version="1.0" encoding="UTF8"?>
<OTA_HotelResNotifRS EchoToken="'.rand(0000,9999).'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
<Errors>
<Error Code="" Type="" Status="" ShortText="Invalid JSON in request"/>
</Errors><Success>Getting Invalid JSON in request</Success>
</OTA_HotelResNotifRS>';*/
}



?>