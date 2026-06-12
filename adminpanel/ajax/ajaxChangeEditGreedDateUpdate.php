<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

//$OrderUniqueID	=$_SESSION['OrderUniqueID'];
 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];
 
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);

$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date 	= explode(' to ',$_REQUEST['reservation_date']);
$checkin_date 		= $reservation_date[0];
$checkout_date 		= $reservation_date[1];

$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}
 $StartDateList	=strtotime($checkin_date);



									
$id_company = $_REQUEST['id_company'];
$rate_id = 	$_REQUEST['rate_id'];	
if($rate_id!='0'){ // USING RATE LETTER=================================================================

//echo "USING RATE LETTER ===============================";



$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}
$dataValue = explode('|',$_REQUEST['dataValue']);

////////////////////////////////////////////////////////////////////////////////////////////////////
//echo '|||';
/////////////////////////////executing query///////////////////////////////////////////////////////////

$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($_REQUEST['rate_plan_id'])."'");
$rowPlan = $db->fetch_object2($ratePlan);


//if(num_rows($resRoom) >0){
//$uniqueCode = 'CODE'.rand(0000,9999);
$rowRoom = $db->fetch_object2($resRoom);

if($dataValue['6'] == '1'){	
		if($_REQUEST['adult_no'] == '1'){
			$priceValue += $rowRoom->pkg_price;	
			$extra_bed_price	=	0;
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$priceValue += $rowRoom->pkg_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			$priceValue += $rowRoom->pkg_price+$rowRoom->extra_bed_price;	
			$extra_bed_price	=	$rowRoom->extra_bed_price;	
		}
		if($_REQUEST['child_no'] == '0'){
			$priceValue += $rowRoom->extra_bed_price;
			$extra_bed_price_child	=	0;
			
		}elseif($_REQUEST['child_no'] == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
		}	
	
}else{
	
		if($_REQUEST['adult_no'] == '1'){
			$priceValue += $rowRoom->single_pax_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$priceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			$priceValue += $rowRoom->double_pax_price+$rowRoom->extra_bed_price;
			$extra_bed_price	=	$rowRoom->extra_bed_price;	
		}
		if($_REQUEST['child_no'] == '0'){
			$extra_bed_price_child	=	0;
			$priceValue += $rowRoom->extra_bed_price;
			
		}elseif($_REQUEST['child_no'] == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
			
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
			
		}					
	

}

////////////////////////setting the value in session /////////////////////////////


$_SESSION['editCart'][$OrderUniqueID]['hotel_id'] = $_REQUEST['hotel_id'];


$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $noOfDays;
$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['editCart'][$OrderUniqueID]['rate_id'] = $_REQUEST['rate_id'];
//$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['editCart'][$OrderUniqueID]['type'] = $_REQUEST['book_type'];
$_SESSION['editCart'][$OrderUniqueID]['series'] = $_REQUEST['series'];
$_SESSION['editCart'][$OrderUniqueID]['operator'] = $_REQUEST['operator'];




function array_flatten($array) { 
  if (!is_array($array)) { 
    return FALSE; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
} 


$tarrif						=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
$adult_no					=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
$room_type_id				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['room_type_id']);
$room_quantity				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);
$rate_plan_id				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id']);
$infant_no					=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
$child_no					=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['child_no']);
$TaxPerdayPerroom			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);
$extra_bed_price_child		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);
$extra_bed_price			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price']);
$dataValue_new				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['dataValue']);


unset($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
unset($_SESSION['editCart'][$OrderUniqueID]['room_price']);
unset($_SESSION['editCart'][$OrderUniqueID]['room_type_id']);
unset($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
unset($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);
unset($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id']);
unset($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
unset($_SESSION['editCart'][$OrderUniqueID]['child_no']);
unset($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);
unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price']);
unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);
unset($_SESSION['editCart'][$OrderUniqueID]['dataValue']);




$reservation_date 	= explode(' to ',$_REQUEST['reservation_date']);
$checkinDate 		= $reservation_date[0];
$checkoutDate 		= $reservation_date[1];
$UniqueDate 		= date ("d-m-Y", $checkinDate);
$days 				= abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}

if(!empty($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'] as $datas =>$uniqueCode22){
//foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode22 =>$dataCode){
		
	$StartDateListFor22	=strtotime($checkinDate);
	
	for($i=0;$i<1;$i++){
		$UniqueDateFor22 = date ("d-m-Y", $StartDateListFor22); 
	

		//$totalAdult		+=	$adult_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		//$totalInfant	+=	$infant_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		//$totalChild		+=	($child_no[$uniqueCode22]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22]);
		$totalRoom				+= $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		
		//$totalRoom 		+= 	$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		
	}
	
	
	}
}
	
$StartDateListRateLetter	=strtotime($checkinDate);

 //if (is_array($_SESSION['editCart'][$OrderUniqueID]['dataValue']) || is_object($_SESSION['editCart'][$OrderUniqueID]['dataValue'])){
// foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode =>$dataCode){
	if(!empty($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'] as $dataCode =>$uniqueCode){ 
 	for($i=0;$i<$noOfDays;$i++){	

		$UniqueDate = date ("d-m-Y", $StartDateListRateLetter); 
	
		$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]=$tarrif[$uniqueCode];

		$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDate][$uniqueCode]	 			= 	$room_type_id[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$uniqueCode]					=	$adult_no[$uniqueCode];			
		$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode]			=	$room_quantity[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDate][$uniqueCode]				=	$rate_plan_id[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDate][$uniqueCode]				=	$infant_no[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDate][$uniqueCode]					=	$child_no[$uniqueCode];	
		$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDate][$uniqueCode]			=	$TaxPerdayPerroom[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDate][$uniqueCode]			=	$extra_bed_price[$uniqueCode];	
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDate][$uniqueCode]	=	$extra_bed_price_child[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDate][$uniqueCode]		=	$dataValue_new[$uniqueCode];//+$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDate][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDate][$uniqueCode]
		
		$totalPrice += ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode];
		
		$AlltotalPrice += ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode];
		
		
	$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDate][$uniqueCode]);
	$checkin_date = explode('to',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']); 
	$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
	$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";
	
	$result14 = executeSql($query14,$link);
	$query14count = mysqli_num_rows($result14);	
	
	$query14data 	= mysqli_fetch_array($result14);
	$seasonIdnew	= $query14data['id'];	
	
	
	
	$CheckTaxStatusSql	=	selectColumn(TBL_ORDERS,'tax_group_id'," WHERE `id_order` = '".addslashes(encryptor('decrypt',$_SESSION['eId']))."'");
	
	if($CheckTaxStatusSql	== 1){
	$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($_SESSION['editCart'][$OrderUniqueID]['hotel_id'])."' and  `room_id` = '".addslashes($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDate][$uniqueCode])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");
	
	$rowTax = $db->fetch_object2($resTax);
	$rowTax->tax_room;
	//echo "<br>Room price". $_SESSION['editCart'][$OrderUniqueID]['room_price'][$uniqueCode];//*$_SESSION['editCart'][$OrderUniqueID]['noOfDays']*($rowTax->tax_room/100);
	
	
	//$roomTax	+=	$rowOrderDetail->total_price*$row->no_of_days*($rowTax->tax_room/100);
	
	
	$TaxInclusiveStatus	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDate][$uniqueCode]."'");
	
	if($TaxInclusiveStatus	==2){
	
	$roomTax	+=	round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$uniqueCode]*($rowTax->tax_room/100));
	
	}	
	
	}else{ /*New Tax Rules Start */
	
	$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
	$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
	$SlectedDateNewTax_id	= $SelectTaxDateRow->id;
	
	
	$price 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode]);
	
	$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
	
	if(num_rows($resNewTax) >0 ){
	$rowNewTax = $db->fetch_object2($resNewTax);
	
	//echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent."totalPrice= >".round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode]*($rowNewTax->tax_percent/100));
	
	
	$taxablePrice	+= round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode]*($rowNewTax->tax_percent/100));
	
	
	
	
	}
	
	
	/*New Tax Rules END */
	
	}
 	
	 $StartDateListRateLetter	=	strtotime("+1 day", strtotime($UniqueDate));
	}

	
		echo '<strong><i class="fa fa-inr"></i> '.$totalPrice.'</strong>&nbsp;&nbsp;';
	
		echo "###".$uniqueCode."###";
	
		$totalPrice	=0;

		$StartDateListRateLetter	=strtotime($checkinDate);
	}
}
//}


$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];

$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];



foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue ){
	
	//$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}



//print_r($_SESSION['editCart'][$OrderUniqueID]);

//$_SESSION['editCart'][$OrderUniqueID]['totalRoom']= $totalRoom;
//$_SESSION['editCart'][$OrderUniqueID]['totalAdult']= $totalAdult;
//$_SESSION['editCart'][$OrderUniqueID]['totalChild']= $totalChild;
//$_SESSION['editCart'][$OrderUniqueID]['totalInfant']= $totalInfant;
echo "AlltotalPrice".$_SESSION['editCart'][$OrderUniqueID]['totalPrice'] = $AlltotalPrice;
$_SESSION['editCart'][$OrderUniqueID]['taxablePrice'] = $taxablePrice;//+$TotalAdditionalChargesTaxValue;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart'][$OrderUniqueID]['discountPrice'];




$_SESSION['editCart'][$OrderUniqueID]['finalPrice']  = round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+($_SESSION['editCart'][$OrderUniqueID]['taxablePrice'])),0,PHP_ROUND_HALF_UP);



				
echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart'][$OrderUniqueID]['totalPrice'].'</td>
              </tr>
			   <tr>
                <th>Additional Charges:</th>
                <td id="addchargesvalue"><i class="fa fa-inr"></i> '. round($TotalAdditionalChargesPrice,2).'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart'][$OrderUniqueID]['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.round($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$TotalAdditionalChargesTaxValue,2).'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i>  '.$_SESSION['editCart'][$OrderUniqueID]['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i>  '.round($_SESSION['editCart'][$OrderUniqueID]['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart'][$OrderUniqueID]['finalPrice']-$_SESSION['editCart'][$OrderUniqueID]['amountReceived']).'</td>
              </tr>
            </table>';
			
			
}else{// USING ADHOL ===============================
	
	
//echo "USING ADHOL ===============================";

//exit;
$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$UniqueDate = date ("d-m-Y", $checkinDate);
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
//echo '|||';
/////////////////////////////executing query///////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($_REQUEST['rate_assign_id'])."'  and rd.rate_plan_id='".addslashes($_REQUEST['rate_plan_id'])."' and rd.rate_id='".addslashes($_REQUEST['rate_id'])."' and room_id='".addslashes($_REQUEST['room_id'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($_REQUEST['rate_plan_id'])."'");
$rowPlan = $db->fetch_object2($ratePlan);


//if(num_rows($resRoom) >0){
//$uniqueCode = 'CODE'.rand(0000,9999);
$rowRoom = $db->fetch_object2($resRoom);

////////////////////////setting the value in session /////////////////////////////


$_SESSION['editCart'][$OrderUniqueID]['hotel_id'] = $_REQUEST['hotel_id'];


$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $noOfDays;
$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['editCart'][$OrderUniqueID]['rate_id'] = $_REQUEST['rate_id'];
$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['editCart'][$OrderUniqueID]['type'] = $_REQUEST['book_type'];
$_SESSION['editCart'][$OrderUniqueID]['series'] = $_REQUEST['series'];
$_SESSION['editCart'][$OrderUniqueID]['operator'] = $_REQUEST['operator'];



function array_flatten($array) { 
  if (!is_array($array)) { 
    return FALSE; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
} 



//print_r($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);
$tarrif				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
$adult_no			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
$room_type_id		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['room_type_id']);
$room_quantity		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);
$rate_plan_id		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id']);
$infant_no			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
$child_no			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['child_no']);
$TaxPerdayPerroom	=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);
$dataValue			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['dataValue']);
$extra_bed_price_child		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);
$extra_bed_price			=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price']);

unset($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
unset($_SESSION['editCart'][$OrderUniqueID]['room_price']);
unset($_SESSION['editCart'][$OrderUniqueID]['room_type_id']);
unset($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
unset($_SESSION['editCart'][$OrderUniqueID]['tax']);
unset($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);
unset($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id']);
unset($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
unset($_SESSION['editCart'][$OrderUniqueID]['child_no']);
unset($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);
unset($_SESSION['editCart'][$OrderUniqueID]['dataValue']);
unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price']);
unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);


$DateNew 	= explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);


$checkin_date = $reservation_date[0];
foreach($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'] as $dataCode =>$uniqueCode22){
		
	$StartDateListFor22	=strtotime($checkin_date);
	
	for($i=0;$i<1;$i++){
		$UniqueDateFor22 = date ("d-m-Y", $StartDateListFor22); 
	
		
		$totalAdult		+=	$adult_no[$uniqueCode22]* $room_quantity[$uniqueCode22];
		$totalInfant	+=	$infant_no[$uniqueCode22]* $room_quantity[$uniqueCode22];
		$totalChild	+=	($child_no[$uniqueCode22]*$room_quantity[$uniqueCode22]);
		$totalRoom +=($room_quantity[$uniqueCode22]);
		
		//$totalRoom 		+= 	$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
	}
	
	
	}
$checkinDate = $DateNew[0];
$StartDateList	=strtotime($checkin_date);
//print_r($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$checkinDate]);
/*foreach($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'] as $uniqueCode =>$dataCode){
	echo $uniqueCode;
}die;*/
foreach($_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'] as $uniqueCode =>$dataCode){
		
	
	$StartDateListFor	=strtotime($checkinDate);
		
		
	for($i=0;$i<$noOfDays;$i++){	

		$UniqueDate = date ("d-m-Y", $StartDateListFor); 
		
		$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]			=	$tarrif[$dataCode];
		$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDate][$dataCode]	 	= 	$room_type_id[$dataCode];
		$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$dataCode]			=	$adult_no[$dataCode];			
		$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode]	=	$room_quantity[$dataCode];
		$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDate][$dataCode]		=	$rate_plan_id[$dataCode];
		$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDate][$dataCode]		=	$infant_no[$dataCode];
		$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDate][$dataCode]			=	$child_no[$dataCode];		
		$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDate][$dataCode]		=	$TaxPerdayPerroom[$dataCode];
		$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDate][$dataCode]		=	$dataValue[$dataCode];
		
 		$Price += $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];
		$totalPrice += $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];	
		
		
		
		//$totalAdult 			+= $_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];
		//$totalChild 			+= $_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];
		//$totalInfant 			+= $_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];
		
		$totalPriceTarrif 		+=  $_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];

		$totalPriceExtra 		+=  $_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDate][$dataCode];
		//$totalRoom				+= $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode];				
	
	
	//$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDate][$dataCode]);
	$checkin_date = explode('to',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']); 
	$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
	$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";
	
	$result14 = executeSql($query14,$link);
	$query14count = mysqli_num_rows($result14);	
	
	$query14data 	= mysqli_fetch_array($result14);
	$seasonIdnew	= $query14data['id'];	
	
	
	
	
	$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
	$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
	$SlectedDateNewTax_id	= $SelectTaxDateRow->id;
	
	
	$price 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]);
	
	$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
	
	if(num_rows($resNewTax) >0 ){
	$rowNewTax = $db->fetch_object2($resNewTax);
	
	//echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent."totalPrice= >".round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode]*($rowNewTax->tax_percent/100));
	
	
	//$taxablePrice	+= round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode]*($rowNewTax->tax_percent/100));
	
		$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDate][$dataCode]."'");
		
			if($TaxInclusiveStatus1	== '2'  && $TaxInclusiveStatus1	!= '1'   &&  $TaxInclusiveStatus1	!= '3' ){
				
					
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDate][$dataCode]  = $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]*($rowNewTax->tax_percent/100);
				
				$taxablePrice	+= round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$dataCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$dataCode]*($rowNewTax->tax_percent/100));
				
					
			}if($TaxInclusiveStatus1	== '1'  && $TaxInclusiveStatus1	!= '2'   &&  $TaxInclusiveStatus1	!= '3' ){	
			
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDate][$dataCode]	= 0;
				$SingleRowTaxValue	=0;
				}
				
		}
	
	
	/*New Tax Rules END */
	
	//}
	
		
		
		
		$StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDate));
		
		
		
		}
		
echo '<strong><i class="fa fa-inr"></i> '.$Price.'</strong>';

	echo "###".$dataCode."###";	
$StartDateListFor	=strtotime($checkinDate);
 	
	$Price=0;
 	}


foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue){
	
	//$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}
	
	


$otherChargeTypeNoOfDays=$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$otherchagersid];
//$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price']*$otherChargeTypeNoOfDays;

$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price']*$otherChargeTypeNoOfDays;

$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];


//$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
//$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_total']);


$_SESSION['editCart'][$OrderUniqueID]['totalRoom']= $totalRoom;
$_SESSION['editCart'][$OrderUniqueID]['totalAdult']= $totalAdult;
$_SESSION['editCart'][$OrderUniqueID]['totalChild']= $totalChild;
$_SESSION['editCart'][$OrderUniqueID]['totalInfant']= $totalInfant;
$_SESSION['editCart'][$OrderUniqueID]['totalPrice'] = $totalPrice;
$_SESSION['editCart'][$OrderUniqueID]['taxablePrice'] = $taxablePrice;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart'][$OrderUniqueID]['discountPrice'];




$_SESSION['editCart'][$OrderUniqueID]['finalPrice']  = round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+($_SESSION['editCart'][$OrderUniqueID]['taxablePrice'])),0,PHP_ROUND_HALF_UP);
echo $TotalAdditionalChargesPrice;
/*echo "<pre>";
					//print_r($_REQUEST['uniqueCode']);
					print_r($_SESSION['editCart']);
					 echo "</pre>";*/
	
	echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart'][$OrderUniqueID]['totalPrice'].'</td>
              </tr>
			   <tr>
                <th>Additional Charges:</th>
                <td id="addchargesvalue"><i class="fa fa-inr"></i> '. round($TotalAdditionalChargesPrice,2).'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart'][$OrderUniqueID]['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.round($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$TotalAdditionalChargesTaxValue,2).'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i>  '.$_SESSION['editCart'][$OrderUniqueID]['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i>  '.round($_SESSION['editCart'][$OrderUniqueID]['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart'][$OrderUniqueID]['finalPrice']-$_SESSION['editCart'][$OrderUniqueID]['amountReceived']).'</td>
              </tr>
            </table>';		

	}
	
	


?>