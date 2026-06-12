<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];
 
$reservation_date = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);

$remove = $_REQUEST['remove'];
$uniqueCode = $_REQUEST['uniqueCode'];

$rate_id = $_REQUEST['rate_id'];
unset($_SESSION['editCart'][$OrderUniqueID]['totalPrice']);
if($remove == 'removeAll'){ //&& $rate_id!='0'
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
	
	
 echo '<td colspan="8"><strong>Please add room again with new details.</strong></td>' ;
 echo "###".$rate_id;
 
 
 
}else if(($remove == 'removeOne') && ($uniqueCode!='')){
	
$reservation_date = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];

$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}
 $StartDateList	=strtotime($checkin_date);
 
 for($i=0;$i<$noOfDays;$i++){	

		$UniqueDateFor = date ("d-m-Y", $StartDateList); 
		
	unset($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$uniqueCode]);		
	unset($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_price'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['pkg_description'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_tax_price'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode]);
	unset($_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDateFor][$uniqueCode]);	
	unset($_SESSION['editCart'][$OrderUniqueID]['totalPrice']);
	$StartDateList	=	strtotime("+1 day", strtotime($UniqueDateFor));	
 	}
 
 
 
	echo 'success|||';
	
	
$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $_SESSION['editCart'][$OrderUniqueID]['noOfDays'];



$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($dataValue['4'])."'");
$rowPlan = $db->fetch_object2($ratePlan);

	if(num_rows($resRoom) >0){

$rowRoom = $db->fetch_object2($resRoom);
$priceValue = 0;
$inclusionFood =0;
$pkg_extra = 0;
/////////////////////////////////////making calculation////////////////////////////////////////////////
if($dataValue['6'] == '1'){	
		if($_REQUEST['adult_no'] == '1'){
			$priceValue += $rowRoom->pkg_price;	
			$extra_bed_price	=	0;
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$priceValue += $rowRoom->pkg_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			$priceValue += $rowRoom->pkg_price+$rowRoom->extra_bed_price;	
			$extra_bed_price	=	$rowRoom->extra_bed_price*2;	
		}
		if($_REQUEST['child_no'] == '0'){
			$priceValue += $rowRoom->extra_bed_price;
			$extra_bed_price_child	=	0;
			
		}elseif($_REQUEST['child_no'] == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price*2;	
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
			$extra_bed_price	=	$rowRoom->extra_bed_price*2;	
		}
		if($_REQUEST['child_no'] == '0'){
			$extra_bed_price_child	=	0;
			$priceValue += $rowRoom->extra_bed_price;
			
		}elseif($_REQUEST['child_no'] == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
			
		}elseif($_REQUEST['child_no'] == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;	
			$extra_bed_price_child	=	$rowRoom->extra_bed_price*2;	
			
		}					
	}
}	

$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$uniqueCode]		= $extra_bed_price;
$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$uniqueCode]	= $extra_bed_price_child;

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


$adult_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['adult_no']);
$infant_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['infant_no']);
$child_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['child_no']);
if (is_array($_SESSION['editCart'][$OrderUniqueID]['dataValue']) || is_object($_SESSION['editCart'][$OrderUniqueID]['dataValue'])){
foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode22 =>$dataCode){
		
	$StartDateListFor22	=strtotime($checkin_date);
	
	for($i=0;$i<1;$i++){
		$UniqueDateFor22 = date ("d-m-Y", $StartDateListFor22); 
	
		
		echo "totoal=".$totalAdult		+=	$adult_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		$totalInfant	+=	$infant_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		$totalChild	+=	($child_no[$uniqueCode22]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22]);
		$totalRoom 		+= 	$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
	}
	
	}
}
	 $StartDateListFor	=strtotime($checkin_date);
for($i=0;$i<$noOfDays;$i++){	

	$UniqueDateFor = date ("d-m-Y", $StartDateListFor);
if (is_array($_SESSION['editCart'][$OrderUniqueID]['dataValue']) || is_object($_SESSION['editCart'][$OrderUniqueID]['dataValue'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode =>$dataCode){	

	
	$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode]	=	$child_no[$uniqueCode];
	$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode]	=	$infant_no[$uniqueCode];	
	$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode]	=	$adult_no[$uniqueCode];
	
	
		$totalPrice  += ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		$totalPriceTarrif +=  $_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		
		$totalPriceExtra +=  $_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDateFor][$uniqueCode];
		//$totalRoom += $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];				
		$taxablePrice = $_SESSION['editCart'][$OrderUniqueID]['room_tax_price'][$UniqueDateFor][$uniqueCode];
		
		
		
		$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$uniqueCode]);
		$checkin_date = explode('to',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']); 
$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
		$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 		= executeSql($query14,$link);
$query14count 	= mysqli_num_rows($result14);	

$query14data 	= mysqli_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	



	$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
	$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
	$SlectedDateNewTax_id	= $SelectTaxDateRow->id;


	$price 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]);

$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");

if(num_rows($resNewTax) >0 ){
	$rowNewTax = $db->fetch_object2($resNewTax);
	
	echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent."totalPrice= >".round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
	
	
	//$roomTax	+= round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
	
	$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]."'");
		
			if($TaxInclusiveStatus1	== '2'  && $TaxInclusiveStatus1	!= '1'   &&  $TaxInclusiveStatus1	!= '3' ){
				
					
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]  = $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100);
				
				$roomTax	+=	$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100);
				
					
			}if($TaxInclusiveStatus1	== '1'  && $TaxInclusiveStatus1	!= '2'   &&  $TaxInclusiveStatus1	!= '3' ){	
			
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]	= 0;
				$SingleRowTaxValue	=0;
				}
	
}



}
}

 $StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDateFor));
}


$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];

$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];


/*$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_total']);*/

foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue ){
	
	
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}

$_SESSION['editCart'][$OrderUniqueID]['totalRoom']= $totalRoom;
$_SESSION['editCart'][$OrderUniqueID]['totalAdult']= $totalAdult;
$_SESSION['editCart'][$OrderUniqueID]['totalChild']= $totalChild;
$_SESSION['editCart'][$OrderUniqueID]['totalInfant']= $totalInfant;
$_SESSION['editCart'][$OrderUniqueID]['totalPrice'] = $totalPrice;
$_SESSION['editCart'][$OrderUniqueID]['taxablePrice'] = $roomTax;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart'][$OrderUniqueID]['discountPrice'];
$_SESSION['editCart'][$OrderUniqueID]['finalPrice']  = round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.round($_SESSION['editCart'][$OrderUniqueID]['totalPrice'],2).'</td>
              </tr>
			   <tr>
                <th>Additional Charges:</th>
                <td id="addchargesvalue"><i class="fa fa-inr"></i> '.round($TotalAdditionalChargesPrice,2).'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart'][$OrderUniqueID]['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$TotalAdditionalChargesTaxValue).'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i> '.$_SESSION['editCart'][$OrderUniqueID]['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i> '.round($_SESSION['editCart'][$OrderUniqueID]['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart'][$OrderUniqueID]['finalPrice']-$_SESSION['editCart'][$OrderUniqueID]['amountReceived']).'</td>
              </tr>
            </table>';
			
			
			echo "###".$rate_id;
	
}
?>