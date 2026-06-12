<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');

//---------------------------------------------------------------------------------------------------------
//$OrderUniqueID	=$_SESSION['OrderUniqueID'];
 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];
$array 	=	$_SESSION['editCart'];
foreach( $array as $key => $value ){	
	    if( $key == "" ){ 
		unset($_SESSION['editCart'][$key]);
		}
}


if($_SESSION['editCart'][$OrderUniqueID]['dataValue'] == ''){
	header("location:manageOrders.php");
	exit;	
}
$resLogo  =  selectColumn(TBL_SHOP,'image'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");


/*echo "totalAdult=".$_SESSION['editCart'][$OrderUniqueID]['totalAdult'];
echo "totalRoom=".$_SESSION['editCart'][$OrderUniqueID]['totalRoom'];
echo "totalChild=".$_SESSION['editCart'][$OrderUniqueID]['totalChild'];
echo "totalInfant=".$_SESSION['editCart'][$OrderUniqueID]['totalInfant'];*/
/*echo "<pre>";
print_r($_REQUEST);
print_r($_SESSION);
echo "</pre>";
die;*/
?>
<?php  include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php 


 $sizeofArray	=	sizeof($_REQUEST['dataValue']);


?>
<!--getting all the details of hotel, guest and customer-->
<?php 

if($_POST['eId']!=''){  //EDIT--Starts---------------------------->

		//echo "EDIT--Starts"; die;
if($sizeofArray	==1){
	
	 $sqlOrderDetailExisting = executeSQl("SELECT `".TBL_ORDERS."`.*, `".TBL_ORDER_DETAIL."`.hotel_id , `".TBL_ORDER_DETAIL."`.room_id , `".TBL_ORDER_DETAIL."`.rate_plan_id, `".TBL_ORDER_DETAIL."`.room_quantity ,`".TBL_ORDER_DETAIL."`.dated ,`".TBL_ORDER_DETAIL."`.rate_id FROM `".TBL_ORDERS."` LEFT JOIN `".TBL_ORDER_DETAIL."` ON  `".TBL_ORDERS."`.id_order=`".TBL_ORDER_DETAIL."`.id_order WHERE `".TBL_ORDER_DETAIL."`.`id_order` = '".addslashes(encryptor('decrypt',$_POST['eId']))."' group by `".TBL_ORDER_DETAIL."`.unique_code");
	
}else{


	
$sqlOrderDetailExisting = executeSQl("SELECT `".TBL_ORDERS."`.*, `".TBL_ORDER_DETAIL."`.hotel_id , `".TBL_ORDER_DETAIL."`.room_id , `".TBL_ORDER_DETAIL."`.rate_plan_id, `".TBL_ORDER_DETAIL."`.room_quantity ,`".TBL_ORDER_DETAIL."`.dated ,`".TBL_ORDER_DETAIL."`.rate_id FROM `".TBL_ORDERS."` LEFT JOIN `".TBL_ORDER_DETAIL."` ON  `".TBL_ORDERS."`.id_order=`".TBL_ORDER_DETAIL."`.id_order WHERE `".TBL_ORDER_DETAIL."`.`id_order` = '".addslashes(encryptor('decrypt',$_POST['eId']))."'");

}
						
							
		 while($rowOrderDetailExisting = $db->fetch_object2($sqlOrderDetailExisting)){
			 
			//print_r($rowOrderDetailExisting);
		 if(($rowOrderDetailExisting->booking_status =='1') || ($rowOrderDetailExisting->booking_status =='2')){			 
		 	
$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available+'".addslashes($rowOrderDetailExisting->room_quantity)."',
								blocked_hotel = blocked_hotel-'".addslashes($rowOrderDetailExisting->room_quantity)."',
								online_allocation=online_allocation+'".addslashes($rowOrderDetailExisting->room_quantity)."' 
								where  `hotel_id`='".addslashes($rowOrderDetailExisting->hotel_id)."' and 
						  		`room_id`='".addslashes($rowOrderDetailExisting->room_id)."' and 
								allocation_date between '".addslashes(date("Y-m-d", strtotime($rowOrderDetailExisting->checkin)))."' and '".addslashes(date("Y-m-d", strtotime("-1 day",strtotime($rowOrderDetailExisting->checkout))))."'");
		 }
		}
		
		 
		 ?>
		 
<?php $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($_REQUEST['id_guest'])."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS,"where id='".$_SESSION['editCart'][$OrderUniqueID]['hotel_id']."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".$_REQUEST['id_contacts']."'",''); 
		  $resultContact = $db->fetch_object2($resContact); 
	  $resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($_REQUEST['id_company'])."'",''); 
		  $resultCompany = fetch_object($resCompany); 
		  ?>
<!--getting all the details of hotel, guest and customer end-->
<?php 
if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue){
	
	$charges_total_order			+=	 $_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
	$NoDaysInOtherCharges	=	$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$charges_price			+=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$charges_net			+=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]+$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$OtherUniceValue];
}}

$order_reference = $_SESSION['editCart'][$OrderUniqueID]['orderReference'];
$reservationDate = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);
$startDate = $reservationDate['0'];
if($_REQUEST['rate_id'] !=''){
$RateID		=	$_REQUEST['rate_id'];
}else{
$RateID		=	$_SESSION['editCart'][$OrderUniqueID]['rate_id'];	
	}
			
 $insertOrder = "UPDATE `".TBL_ORDERS."` SET 
				  `code`=code+01,
				  `id_shop_group`='1',
				  `id_shop` = '".addslashes($_SESSION['shop'])."',
				  `id_lang`='1',
				  `id_currency`='1',		
				  `id_rate`='".addslashes($RateID)."',  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',
				  
				  `id_company_person`='".addslashes($_REQUEST['id_contacts'])."',
				  `id_customer`='".addslashes($_REQUEST['id_guest'])."',
				  
				  `id_company`	='".addslashes($_REQUEST['id_company'])."',
				  `conversion_rate`='1.000000',
				  `discount_type`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountVar'])."',
  				  `total_discounts`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountPrice'])."',
				  `total_addcharges`='".addslashes($charges_price)."',

				  `tarrif_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'])."',

				  `subtotal`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order)."',
				  `total_price`='".addslashes(round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_price+$charges_total_order-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])),0,PHP_ROUND_HALF_UP))."',
				  
				  `balance`='".addslashes((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order+$charges_price-$_SESSION['editCart'][$OrderUniqueID]['discountPrice']))-$_REQUEST['amount_received'])."',
				  
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalChild'])."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($reservationDate[0])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($reservationDate[1])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['noOfDays'])."',
				  `payment_reference`='".addslashes($_REQUEST['payment_reference'])."',
				  `other_reference`='".addslashes($_POST['other_reference'])."',
				  `payment_remarks`='".addslashes($_POST['payment_remarks'])."',
				  `arrival_time`='".addslashes($_POST['arrival_time'])."',
				  `arrival_from`='".addslashes($_POST['arrival_from'])."',
				  `departing_to`='".addslashes($_POST['departing_to'])."',
				  `pickup`='".addslashes($_POST['pickup'])."',
				  `pickup_details`='".addslashes($_POST['pickup_details'])."',
				  `travel`='".addslashes($_POST['travel'])."',
				  `booking_hrough`='".addslashes($_POST['booking_hrough'])."',
				  `requests`='".addslashes($_POST['requests'])."',
				  `valid`='1',
				  `vaoucher_pass_code`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['EvoucherPassCode'])."',
				  `vaoucher_value`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['EvoucherValue'])."',
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `series_id`='".addslashes($_POST['series'])."',
				  `operator_id`='".addslashes($_POST['operator'])."',				  
				  `cancellation_reason_id`='".addslashes($_POST['CancellationReason_status'])."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."' 
				  where id_order='".addslashes(encryptor('decrypt',$_POST['eId']))."'";

//print_r($_SESSION['editCart'][$OrderUniqueID]['charges_price']);



if(executeSql($insertOrder)){
	$orderId = mysqli_insert_id();
	
	executeSql("DELETE from `".TBL_ORDER_DETAIL."` where id_order='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
	executeSql("DELETE from `".TBL_OTHERCHARGES_DETAIL."` where id_order='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
}

 $Promo_code_status = "	UPDATE `fs_promo_code_details` 
						SET `promo_code_status` =4 
						WHERE `promo_code` = '".addslashes($_SESSION['editCart'][$OrderUniqueID]['EvoucherPassCode'])."' ";	
									
	executeSql($Promo_code_status);

/*====================================Amendment count Query Insert Start=======================================*/

$AmendmentSQL = "INSERT INTO  `".TBL_AMENDMENT_COUNT."` SET 				 
 				  `id_order`='".addslashes(encryptor('decrypt',$_POST['eId']))."',
				  `id_shop` = '".addslashes($_SESSION['shop'])."',
				  `id_hotel`='".addslashes($_REQUEST['hotel_id'])."',
				  `id_rate`='".addslashes($RateID)."',  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',				  
				  `id_company_person`='".addslashes($_REQUEST['id_contacts'])."',
				  `id_customer`='".addslashes($_REQUEST['id_guest'])."',				  
				  `id_company`	='".addslashes($_REQUEST['id_company'])."',
				  `discount_type`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountVar'])."',
  				  `total_discounts`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountPrice'])."',
				  `total_addcharges`='".addslashes($charges_price)."',

				  `tarrif_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'])."',

				  `subtotal`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order)."',
				  `total_price`='".addslashes(round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_price+$charges_total_order-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])),0,PHP_ROUND_HALF_UP))."',
				  
				  `balance`='".addslashes((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order+$charges_price-$_SESSION['editCart'][$OrderUniqueID]['discountPrice']))-$_REQUEST['amount_received'])."',
				  
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalChild'])."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($reservationDate[0])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($reservationDate[1])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['noOfDays'])."',				 
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `series_id`='".addslashes($_POST['series'])."',
				  `operator_id`='".addslashes($_POST['operator'])."',				  
				  `cancellation_reason_id`='".addslashes($_POST['CancellationReason_status'])."',
				   `date_created`='".currenDateTime()."',
				   `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."' 
				 ";
				  				  
executeSql($AmendmentSQL);

/*====================================Amendment count Query Insert End=======================================*/






if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue){
	
	//$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$TotalAdditionalChargesPrice	=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue =$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
	
	
$id_othercharges_detail			=	 $_SESSION['editCart'][$OrderUniqueID]['id_othercharges_detail'][$OtherUniceValue];
$charges_price					=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue];
$charges_total					=	 $_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
$charges_description			=	 $_SESSION['editCart'][$OrderUniqueID]['charges_description'][$OtherUniceValue];
$charges_tax					=	 $_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$OtherUniceValue];
$charges_net					=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]+$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
$otherChargeTypeNoOfDays		=	 $_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
$otherChargeType				=	 $_SESSION['editCart'][$OrderUniqueID]['otherChargeType'][$OtherUniceValue];

		
$sqlOtherChargesDetail 		= executeSql("Select * from `".TBL_OTHERCHARGES_DETAIL."` where id_othercharges_detail='".addslashes($id_othercharges_detail)."'");
		$OtherChargesNumRow 				=	num_rows($sqlOtherChargesDetail);
		if($OtherChargesNumRow!=''){//INSERT
		
		$updateInventory = executeSql("UPDATE  `".TBL_OTHERCHARGES_DETAIL."`  SET 
						 `hotel_id`='".addslashes($_REQUEST['hotel_id'])."',
						  `charges_description_id`='".$charges_description."',
						  `charges_price`='".addslashes($charges_price)."',
						  `charges_method`='".addslashes($otherChargeType)."',
						  `charges_noofdays`='".addslashes($otherChargeTypeNoOfDays)."',
						  `charges_tax_percentage`='".addslashes($charges_tax)."',
						  `charges_tax_value`='".addslashes($charges_total)."',
						`charges_net`='".addslashes($TotalAdditionalChargesPrice+$charges_total)."'
								where  `id_othercharges_detail`='".addslashes($id_othercharges_detail)."'");
		}else{
			
		 $insertOthereChargesDetail3 = "INSERT INTO fs_othercharges_detail SET 
						  `id_order`='".addslashes(encryptor('decrypt',$_POST['eId']))."',						  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_REQUEST['hotel_id'])."',
						  `charges_method`='".addslashes($otherChargeType)."',
						  `charges_noofdays`='".addslashes($otherChargeTypeNoOfDays)."',
						  `charges_description_id`='".$charges_description."',
						  `charges_price`='".addslashes($charges_price)."',
						  `charges_tax_percentage`='".addslashes($charges_tax)."',
						  `charges_tax_value`='".addslashes($charges_total)."',
						  						`charges_net`='".addslashes($TotalAdditionalChargesPrice+$charges_total)."'";
		executeSql($insertOthereChargesDetail3);
		
		}
		
		
	
	}
}





$checkinDate =  $startDate;

foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$checkinDate] as $uniqueCode =>$dataCode){
		$StartDateListFor	=strtotime($checkinDate);
		for($i=0;$i<$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];$i++){
			
			$UniqueDateFor = date ("d-m-Y", $StartDateListFor); 
		

		$dataValue = explode('|',$dataCode);		
		
		  $insertOrderDetail = "INSERT INTO `".TBL_ORDER_DETAIL."` SET 
						  `id_order`='".addslashes(encryptor('decrypt',$_POST['eId']))."',
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($dataValue['1'])."',
						  `room_id`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode])."',
						  `rate_id`='".addslashes($RateID)."',
						  `rate_plan_id`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode])."',
						  `rate_assign_id`='".addslashes($dataValue['5'])."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($UniqueDateFor)))."',
						  `type`='".addslashes($dataValue['6'])."',
						  `room_quantity`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode])."',						 
						  `adults`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode])."',
						  `infants`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode])."',
						  `child`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode])."',
						  `tarrif_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode])."',
						  `food_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDateFor][$uniqueCode])."',
						  `extra_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDateFor][$uniqueCode])."',
`total_price`='".addslashes(($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode])."',
						  `original_product_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])."',
						  `tarrif_price_per_day`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])."',
						  `food_price_per_day`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDateFor][$uniqueCode])."',
						  `tax_perday_perroom`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode])."',
						  `unique_code`='".addslashes($uniqueCode)."'";
		
		executeSql($insertOrderDetail);
		
				
	if(($_POST['booking_status'] =='1') || ($_POST['booking_status'] =='2')){	
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]."',
								blocked_hotel = blocked_hotel+'".$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]."',
								online_allocation=online_allocation-'".$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]."' 
								where  `hotel_id`='".addslashes($dataValue['1'])."' and 
						  		`room_id`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode])."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($UniqueDateFor)))."'");
		}
	$ii++;
	
	
 $StartDateListFor = strtotime("+1 day", strtotime($UniqueDateFor));		
	}
	$StartDateListFor	=strtotime($checkinDate); 

}
/*}
	
$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));

}
while (strtotime($startDate) < strtotime($reservationDate[1]));*/



	 //EDIT--Starts---------------------------->  
}else{
	//echo "New Booking";
		//exit;
		//echo "================================================>New";
			  /*==============================Start New Booking======================*/
		$_REQUEST['id_contacts'];  
	 
		  	
 $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($_SESSION['bookCart']['id_guest'])."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS," where id='".addslashes($_REQUEST['hotel_id'])."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER," where type='2' and id_customer='".addslashes($_REQUEST['id_contacts'])."'",''); 
		  $resultContact = $db->fetch_object2($resContact);
		
	 $resCompany = selectSql(TBL_COMPANY," where id_company='".addslashes($_REQUEST['id_company'])."'",''); 
		  $resultCompany = fetch_object($resCompany);
		  
		$resCompanyArea = selectSql(TBL_AREAS," where id='".addslashes($resultCompany->area)."'",''); 
			  $resultCompanyArea = fetch_object($resCompanyArea);

		 ?>
<!--getting all the details of hotel, guest and customer end-->
<?php 
$reservationDate = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);
$startDate = $reservationDate['0'];


$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_ORDERS."' and TABLE_SCHEMA='".$DB_NAME."'");
$lastRecordRow = $db->fetch_object2($lastRecordRes);
$newId = sprintf("%'03d",$lastRecordRow->maxId);

			
			
$query4 = "SELECT * FROM fs_orders where id_hotel ='".$_REQUEST['hotel_id']."' order by id_order desc ";
$result4 = executeSql($query4,$link);
$query4count = mysqli_num_rows($result4);


if($query4count>0) {
	   
$query4data = mysqli_fetch_array($result4);
$fs_orders_id = explode('/',$query4data['reference']);
$fs_orders_id		=	$fs_orders_id[1];

$reference_increment_start  =  selectColumn(TBL_HOTELS,'reference_increment_start'," WHERE `id` = '".addslashes($_REQUEST['hotel_id'])."'");

		if($fs_orders_id>=$reference_increment_start){
		
			$fs_orders_idnew 	= $fs_orders_id+1;	
			}else{
		
			$fs_orders_idnew 	= $reference_increment_start;
				}


}else{

$fs_orders_idnew = 1;	
}

$newId = str_pad($fs_orders_idnew, 3, '0', STR_PAD_LEFT);	
			
$order_reference = $resultHotelDetail->hotel_code.'/'.$newId;




if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue){
	
	$charges_total_order	+=	 $_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
	$NoDaysInOtherCharges	=	$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$charges_price			+=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$charges_net			+=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]+$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$OtherUniceValue];
}}



if(!empty($_SESSION['editCart'][$OrderUniqueID]['meal'])){
	
	$Meals	=	$_SESSION['editCart'][$OrderUniqueID]['meal'];
}else{
	
	
	$Meals='';
	}


 		 $insertOrder = "INSERT INTO `".TBL_ORDERS."` SET 
				  `reference`='".addslashes($order_reference)."',
				  `code`='00',
				  `id_shop_group`='1',
				  `id_shop` = '".addslashes($_SESSION['shop'])."',
				  `id_lang`='1',
				  `id_hotel`='".addslashes($_REQUEST['hotel_id'])."',
				  `id_rate`='".addslashes($_REQUEST['rate_id'])."',
				  `id_customer`='".addslashes($_REQUEST['id_guest'])."',
				  `id_company`='".addslashes($_REQUEST['id_company'])."',
				  `id_company_person`='".addslashes($_REQUEST['id_contacts'])."',
				  `id_cart`='0',
				  `id_currency`='1',
				  `id_area`='".addslashes($resultCompany->deals_in)."',
				  `id_executive`='".addslashes($resultCompanyArea->user_id)."',				  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',
				  `conversion_rate`='1.000000',
				  `discount_type`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountVar'])."',
				  `total_discounts`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountPrice'])."',
				  
				  `tarrif_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'])."',
				  
				  `total_addcharges`='".addslashes($charges_price)."',
				  `subtotal`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order)."',
 `total_price`='".addslashes(round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$charges_price+$charges_total_order-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']),0,PHP_ROUND_HALF_UP))."',
				  
				  `balance`='".addslashes(round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$charges_price+$charges_total_order-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']-$_REQUEST['amount_received']),0,PHP_ROUND_HALF_UP))."',
				  
				  
				
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalChild'])."',
				  `invoice_number`='',
				  `invoice_date`='".addslashes(currenDateTime())."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($reservationDate[0])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($reservationDate[1])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['noOfDays'])."',
				  `payment_reference`='".addslashes($_REQUEST['payment_reference'])."',
				  `other_reference`='".addslashes($_POST['other_reference'])."',
				  `payment_remarks`='".addslashes($_POST['payment_remarks'])."',
				  `arrival_time`='".addslashes($_POST['arrival_time'])."',
				  `arrival_from`='".addslashes($_POST['arrival_from'])."',
				  `departing_to`='".addslashes($_POST['departing_to'])."',
				  `pickup`='".addslashes($_POST['pickup'])."',
				  `pickup_details`='".addslashes($_POST['pickup_details'])."',
				  `travel`='".addslashes($_POST['travel'])."',
				  `booking_hrough`='".addslashes($_POST['booking_hrough'])."',
				  `requests`='".addslashes($_POST['requests'])."',
				  `valid`='1',
				  `vaoucher_pass_code`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['EvoucherPassCode'])."',
				  `vaoucher_value`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['EvoucherValue'])."',
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `series_id`='".addslashes($_SESSION['series']['series'])."',
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `operator_id`='".addslashes($_SESSION['series']['operator'])."',
				  `type`='".addslashes($_REQUEST['book_type'])."',
				  `date_created`='".currenDateTime()."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."'";
	
if(executeSql($insertOrder)){
	$orderId = mysqli_insert_id();
	$OrderLastInsertedID	=	encryptor('encrypt',$orderId);
}

 $Promo_code_status = "	UPDATE `fs_promo_code_details` 
						SET `promo_code_status` =4 
						WHERE `promo_code` = '".addslashes($_SESSION['editCart'][$OrderUniqueID]['EvoucherPassCode'])."' ";	
									
	executeSql($Promo_code_status);
/*====================================Amendment count Query Insert Start=======================================*/

$AmendmentSQL = "INSERT INTO  `".TBL_AMENDMENT_COUNT."` SET 				 
 				  `id_order`='".addslashes($orderId)."',
				  `id_hotel`='".addslashes($_REQUEST['hotel_id'])."',
				  `id_shop` = '".addslashes($_SESSION['shop'])."',
				  `id_rate`='".addslashes($RateID)."',  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',				  
				  `id_company_person`='".addslashes($_REQUEST['id_contacts'])."',
				  `id_customer`='".addslashes($_REQUEST['id_guest'])."',				  
				  `id_company`	='".addslashes($_REQUEST['id_company'])."',
				  `discount_type`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountVar'])."',
  				  `total_discounts`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['discountPrice'])."',
				  `total_addcharges`='".addslashes($charges_price)."',
				  `tarrif_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'])."',
				  `subtotal`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order)."',
				  `total_price`='".addslashes(round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_price+$charges_total_order-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])),0,PHP_ROUND_HALF_UP))."',				  
				  `balance`='".addslashes((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$charges_total_order+$charges_price-$_SESSION['editCart'][$OrderUniqueID]['discountPrice']))-$_REQUEST['amount_received'])."',				  
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['totalChild'])."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($reservationDate[0])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($reservationDate[1])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['noOfDays'])."',				 
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `series_id`='".addslashes($_POST['series'])."',
				  `operator_id`='".addslashes($_POST['operator'])."',				  
				  `cancellation_reason_id`='".addslashes($_POST['CancellationReason_status'])."',
				  `date_created`='".currenDateTime()."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."' 
				 ";
				  				  
executeSql($AmendmentSQL);

/*====================================Amendment count Query Insert End=======================================*/


if(!empty($_SESSION['editCart'][$OrderUniqueID]['charges_price'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['charges_price'] as $uniqueCode =>$dataCode){
	if($dataCode!=''){
		$id_othercharges_detail			=	 $_SESSION['editCart'][$OrderUniqueID]['id_othercharges_detail'][$uniqueCode];
		
		$charges_price				=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$uniqueCode];
		$charges_total				=	 $_SESSION['editCart'][$OrderUniqueID]['charges_total'][$uniqueCode];
		$charges_description		=	 $_SESSION['editCart'][$OrderUniqueID]['charges_description'][$uniqueCode];
		$charges_tax				=	 $_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$uniqueCode];
		$charges_net				=	 $_SESSION['editCart'][$OrderUniqueID]['charges_price'][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$uniqueCode];
		$otherChargeTypeNoOfDays	=	 $_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$uniqueCode];
		$otherChargeType			=	 $_SESSION['editCart'][$OrderUniqueID]['otherChargeType'][$uniqueCode];
		
/*$sqlOtherChargesDetail 			= 	executeSql("Select * from `".TBL_OTHERCHARGES_DETAIL."` where id_othercharges_detail='".addslashes($id_othercharges_detail)."'");
		$OtherChargesNumRow 				=	num_rows($sqlOtherChargesDetail);
		if($OtherChargesNumRow!=''){//INSERT
		
	
		$updateInventory = executeSql("UPDATE  `".TBL_OTHERCHARGES_DETAIL."`  SET 
								`charges_description_id`='".$charges_description."',
								`hotel_id`='".addslashes($_REQUEST['hotel_id'])."',
								`charges_method`='".addslashes($otherChargeType)."',
						  		`charges_noofdays`='".addslashes($otherChargeTypeNoOfDays)."',
						  		 `charges_price`='".addslashes($charges_price)."',
						  		`charges_tax_percentage`='".addslashes($charges_tax)."',
						  		`charges_tax_value`='".addslashes($charges_total)."',
								`charges_net`='".addslashes(($charges_price*$otherChargeTypeNoOfDays)+$charges_total)."'
								where  `id_othercharges_detail`='".addslashes($id_othercharges_detail)."'");
		}else{
			*/
		
		
			$insertOthereChargesDetail3 = "INSERT INTO fs_othercharges_detail SET 
						  `id_order`='".addslashes($orderId)."',
						  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_REQUEST['hotel_id'])."',
						  `charges_description_id`='".$charges_description."',
						  `charges_method`='".addslashes($otherChargeType)."',
						  `charges_noofdays`='".addslashes($otherChargeTypeNoOfDays)."',
						   `charges_price`='".addslashes($charges_price)."',
						  `charges_tax_percentage`='".addslashes($charges_tax)."',
						  `charges_tax_value`='".addslashes($charges_total)."',
						  `charges_net`='".addslashes(($charges_price*$otherChargeTypeNoOfDays)+$charges_total)."'";
		executeSql($insertOthereChargesDetail3);
		
		//}
	
	}
	}

}
//do{ $ii=0;



$checkinDate =  $startDate;
foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$checkinDate] as $uniqueCode =>$dataCode){
		$StartDateListFor	=strtotime($checkinDate);
		for($i=0;$i<$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];$i++){
			
			$UniqueDateFor = date ("d-m-Y", $StartDateListFor); 
		
		$dataValue = explode('|',$dataCode);
		  		$insertOrderDetail = "INSERT INTO `".TBL_ORDER_DETAIL."` SET 
				  `id_order`='".addslashes($orderId)."',
				  `id_shop` = '".addslashes($_SESSION['shop'])."',
				  `hotel_id`='".addslashes($dataValue['1'])."',
				   `room_id`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode])."',
				  `rate_id`='".addslashes($dataValue['3'])."',
				  `rate_plan_id`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode])."',
				  `rate_assign_id`='".addslashes($dataValue['5'])."',
				  `dated`='".addslashes(date('Y-m-d',strtotime($UniqueDateFor)))."',
				  `type`='".addslashes($dataValue['6'])."',
				  `room_quantity`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode])."',						 
				  `adults`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode])."',
				  `infants`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode])."',
				  `child`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode])."',
				  `tarrif_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode])."',
				  `food_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDateFor][$uniqueCode])."',
				  `extra_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDateFor][$uniqueCode])."',
				  `total_price`='".addslashes(($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*($_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]))."',
				  `original_product_price`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])."',
				  `tarrif_price_per_day`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])."',
				  `tax_perday_perroom`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode])."',
				  `food_price_per_day`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDateFor][$uniqueCode])."',
				  `unique_code`='".addslashes($uniqueCode)."'";
		 executeSql($insertOrderDetail);
		

	if($_POST['booking_status'] ==1 || $_POST['booking_status'] ==2){	
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]."',
								blocked_hotel = blocked_hotel+'".$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]."',
								online_allocation=online_allocation-'".$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]."' 
								where  `hotel_id`='".addslashes($dataValue['1'])."' and 
						  		`room_id`='".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode])."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($UniqueDateFor)))."'");
		}
	$ii++;
	
		
	
 $StartDateListFor = strtotime("+1 day", strtotime($UniqueDateFor));		
	}
	$StartDateListFor	=strtotime($checkinDate); 

}
//}
//while (strtotime($startDate) < strtotime($reservationDate[1]));
			  /*==============================END Book Now INSERT======================*/
	
	
	}

?>

   
				
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Booking Manager <small>Book Now</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Booking  Process</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-default">
      <div class="form-group has-error" align="center">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <div class="box-header with-border">
          <h3 class="box-title">  <i class="fa fa-hotel"></i> Booking Process Updated Successfully </h3>
        </div>
      <!-- /.box-header -->
	   <!-- /.first section -->
	  
		<!-- /.eighth section end-->
		
		
		<?php 
		$_SESSION['editCart'][$OrderUniqueID]['content'] = $content;
		if($_POST['eId']!=''){
			$PdfMailUrl	=	$_POST['eId'];			
				
		}else{
		 $PdfMailUrl	=		$OrderLastInsertedID;
			}
			
			
			
		 ?>
		<br><br><br>
        <!-- this row will not appear when printing -->
        <div class="row no-print">		
		
		<div class="col-sm-10">
            <a href="mail-template/sendOrderMail.php?id=<?=$PdfMailUrl?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Send Mail
          </a>
          </div>	
          <div class="col-sm-2">
            <a href="pdf-template/generateOrderPdf.php?id=<?=$PdfMailUrl?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </a>
          </div>		  
		<br><br><br><br>
        </div>
      </section> 
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<?php 
include_once("includes/footer.php");
unset($_SESSION['editCart']);
unset($_SESSION['editCart'][$OrderUniqueID]);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_description']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
?>
