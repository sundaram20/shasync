<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////



$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];
$id_company = $_SESSION['editCart']['id_company'];
$rate_id = 	$_REQUEST['rate_id'];	

$dataValue = explode('|',$_REQUEST['dataValue']);

$_SESSION['editCart']['noOfDays'] = $_SESSION['editCart']['noOfDays'];

/*echo "<pre>";
print_r($_REQUEST);
print_r($_SESSION);
echo "</pre>";*/



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
			$NewpriceValue += $rowRoom->single_pax_price;
			$priceValue += $rowRoom->pkg_price;	
			$extra_bed_price	=	0;
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$NewpriceValue += $rowRoom->double_pax_price;
			$priceValue += $rowRoom->pkg_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			
			$NewpriceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	$rowRoom->extra_bed_price*1;
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
			$NewpriceValue += $rowRoom->single_pax_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '2'){
			$NewpriceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	0;	
			
		}elseif($_REQUEST['adult_no'] == '3'){
			$NewpriceValue += $rowRoom->double_pax_price;
			$extra_bed_price	=	$rowRoom->extra_bed_price*1;	
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
	}else{
		
		$NewpriceValue=$_REQUEST['tarrif'];
		}
											
											
											
									
											
$hotel_id = $_REQUEST['hotel_id'];
$dataValue = explode('|',$_REQUEST['dataValue']);
$uniqueCode = $_REQUEST['uniqueCode'];



$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];

$rate_plan_id	=	$_REQUEST['rate_plan_id'];


$_SESSION['editCart']['tarrif'][$uniqueCode] = $NewpriceValue;
$_SESSION['editCart']['rate_plan_id'][$uniqueCode] =	$_REQUEST['rate_plan_id'];
$_SESSION['editCart']['room_type_id'][$uniqueCode] =	$_REQUEST['room_type_id'];
$_SESSION['editCart']['meal'][$uniqueCode] =	$_REQUEST['meal'];




$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$_SESSION['editCart']['noOfDays'] = '1';
}else {
	$_SESSION['editCart']['noOfDays'] = $days;
}
$_SESSION['editCart']['noOfDays'] = $_SESSION['editCart']['noOfDays'];

	
	
	

$_SESSION['editCart']['room_quantity'][$uniqueCode] = $_REQUEST['room_quantity'];
$_SESSION['editCart']['adult_no'][$uniqueCode] = $_REQUEST['adult_no'];
$_SESSION['editCart']['infant_no'][$uniqueCode] = $_REQUEST['infant_no'];
$_SESSION['editCart']['child_no'][$uniqueCode] = $_REQUEST['child_no'];

$_SESSION['editCart']['inclusion_food'][$uniqueCode] = $inclusionFood;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $pkg_extra;
$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $priceK;

$_SESSION['editCart']['room_price'][$uniqueCode]	= $NewpriceValue;

$_SESSION['editCart']['extra_bed_price'][$uniqueCode]	= $extra_bed_price;
$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode]	= $extra_bed_price_child;

$tarrif = $NewpriceValue+$extra_bed_price_child+$extra_bed_price;


echo '||| ||||';



foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){	

 $TotalTraffic=	($_SESSION['editCart']['tarrif'][$_REQUEST['uniqueCode']]);
		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice +=  ($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['meal'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceFood +=  $_SESSION['editCart']['inclusion_food'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceExtra +=  $_SESSION['editCart']['pkg_extra'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalRoom += $_SESSION['editCart']['room_quantity'][$uniqueCode];				
		//$taxablePrice += $_SESSION['editCart']['room_tax_price'][$uniqueCode];
		
		
		
	$dataValue = explode('|',$_SESSION['editCart']['dataValue'][$uniqueCode]);
				
$checkin_date = explode('to',$_SESSION['editCart']['reservation_date']); 
$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
		
		
$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysqli_num_rows($result14);	

$query14data = mysqli_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($dataValue['1'])."' and  `room_id` = '".addslashes($_SESSION['editCart']['room_type_id'][$uniqueCode])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

if(num_rows($resTax) >0 ){
	$rowTax = $db->fetch_object2($resTax);
    $rowTax->tax_room;

		echo "Tax %". $rowTax->tax_room."totalPrice= >".round($_SESSION['editCart']['tarrif'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
	
	$TaxInclusiveStatus	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart']['rate_plan_id'][$uniqueCode]."'");
	
if($TaxInclusiveStatus	==2){

	$taxablePrice	+= round(($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price_per_day'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
}
	if($TaxInclusiveStatus	==1){
		$taxablePrice_Inclusive	= 0;
	}
//}
}
	
//echo '||| ||||<td id="trafficprice_'.$uniqueCode.'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.round(($TotalTraffic),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$uniqueCode.'\');" '.$disabledq.'></td>';

//echo '<td id="trafficprice_"'.$_REQUEST['uniqueCode'].'">dfg<input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.round(($TotalTraffic),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$uniqueCode.'\');" '.$disabledq.'><td>';
}
			

	

//echo '<td id="trafficprice_"'.$_REQUEST['uniqueCode'].'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$_REQUEST['uniqueCode'].'" value="'.round(($tarrif),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits"  ><td>';



echo '||| ||||<td id="price_"'.$_REQUEST['uniqueCode'].'"><strong><i class="fa fa-inr"></i> '.(($tarrif+$_REQUEST['meal'])*$_SESSION['editCart']['noOfDays'])*$_REQUEST['room_quantity'].'</strong>&nbsp;&nbsp;<td>';



//echo '||| |||Error.';

/*/-------------------------*/







$_SESSION['editCart']['charges_price'][$uniqueCode]	=$_REQUEST['charges_price'];

$_SESSION['editCart']['charges_total'][$uniqueCode]	=$_REQUEST['charges_total'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart']['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart']['charges_total']);


$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $taxablePrice+$taxablePrice_Inclusive;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'];
$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart']['totalPrice'].'</td>
              </tr>
			  <tr>
                <th>Additional Charges:</th>
                <td id="addchargesvalue"><i class="fa fa-inr"></i> '. round($TotalAdditionalChargesPrice,2).'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart']['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.($_SESSION['editCart']['taxablePrice']+$TotalAdditionalChargesTaxValue).'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i>  '.$_SESSION['editCart']['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i>  '.round($_SESSION['editCart']['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart']['finalPrice']-$_SESSION['editCart']['amountReceived']).'</td>
              </tr>
            </table>';
?>