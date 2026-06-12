<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];


$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];
$_SESSION['editCart'][$OrderUniqueID]['hotel_id']	=$hotel_id;
$charges_total	=	$_REQUEST['charges_total'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}



$totalPrice = $_SESSION['editCart'][$OrderUniqueID]['totalPrice'];

$BookingType	=	$_REQUEST['type'];
if($BookingType == 'C'){
	
	$AddAditionalChanrge	= '==';
	
	}else{
			$AddAditionalChanrge	= '>';
		
		}


if($totalPrice.$AddAditionalChanrge. 0){
////////////////////////////////////////////////////////////////////////////////////////////////////
echo '|||';
/////////////////////////////executing query///////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($_REQUEST['rate_assign_id'])."'  and rd.rate_plan_id='".addslashes($_REQUEST['rate_plan_id'])."' and rd.rate_id='".addslashes($_REQUEST['rate_id'])."' and room_id='".addslashes($_REQUEST['room_id'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($_REQUEST['rate_plan_id'])."'");
$rowPlan = $db->fetch_object2($ratePlan);


//if(num_rows($resRoom) >0){
$OtherChargesuniqueCode = 'OTHERCHARGE'.rand(0000,9999);
$_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'][]=$OtherChargesuniqueCode;
				
$rowRoom = $db->fetch_object2($resRoom);

////////////////////////setting the value in session /////////////////////////////


////////////////////////setting the value in session end/////////////////////////////
$availableData = '<table class="table table-hover" style="margin-bottom:0px;">
<tr id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom">';

$availableData .=' <td ><input type="text" class="form-control"  name="charges_description|'.$OtherChargesuniqueCode.'" id="charges_description|'.$OtherChargesuniqueCode.'" value=""  placeholder="Charges Description."  data-parsley-required style="width: 182px;" ></td>';	




$availableData .=' <td ><input type="text" class="form-control"  name="charges_price|'.$OtherChargesuniqueCode.'" id="charges_price|'.$OtherChargesuniqueCode.'" value="0"  onKeyUp="calculateOthercharge(\'charges_price|'.$OtherChargesuniqueCode.'\');"></td>';	


$availableData .=' <td  ><div id="otherChargeTypeNoOfDays_'.$OtherChargesuniqueCode.'"><input type="text" class="form-control"  name="otherChargeTypeNoOfDays|'.$OtherChargesuniqueCode.'" id="otherChargeTypeNoOfDays|'.$OtherChargesuniqueCode.'" value="1"  autocomplete="off" onKeyUp="calculateOthercharge(\'otherChargeTypeNoOfDays|'.$OtherChargesuniqueCode.'\');" ></div>

<input type="hidden" class="form-control"  name="ChargeNoOfDays|'.$OtherChargesuniqueCode.'" id="ChargeNoOfDays|'.$OtherChargesuniqueCode.'" value="'.$noOfDays.'" ></td>';
		


$availableData .='<td>
				  <input type="text" class="form-control" id="charges_tax|'.$OtherChargesuniqueCode.'" name="charges_tax|'.$OtherChargesuniqueCode.'" value="0" onKeyUp="calculateOthercharge(\'charges_tax|'.$OtherChargesuniqueCode.'\');" autocomplete="off" >
                    
                 
                    </td>
				 <td><input type="text" class="form-control"  name="charges_total|'.$OtherChargesuniqueCode.'" id="charges_total|'.$OtherChargesuniqueCode.'" value="0" onKeyUp="calculateOthercharge(\'charges_total|'.$OtherChargesuniqueCode.'\');"></td>
				 
				 <td><input type="text" class="form-control"  name="charges_net|'.$OtherChargesuniqueCode.'" id="charges_net|'.$OtherChargesuniqueCode.'" value="0"  onKeyUp="calculateOthercharge(\'charges_net|'.$OtherChargesuniqueCode.'\');" ></td>
				 
				 
				 
				  <td ><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr><table>';
//}else{
//echo 'There is some error. Please Try again.|||';

//}


	
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


$reservation_date = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}



$k						=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['tarrif']);
$extra_bed_price_child	=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child']);

$TaxPerdayPerroom		=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom']);

	foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode =>$dataCode){
		
	$StartDateListFor	=strtotime($checkinDate);
	
	for($i=0;$i<$noOfDays;$i++){	

		$UniqueDateFor = date ("d-m-Y", $StartDateListFor); 
 
 		
	 
	    		
	 	$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]=	$k[$uniqueCode];
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDateFor][$uniqueCode]=	$extra_bed_price_child[$uniqueCode];
		
		
		$TotalTraffic=	($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]);
		$totalAdult += $_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		$totalChild += $_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		$totalInfant += $_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		$totalPrice +=  $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		
		$AlltotalPrice +=  $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		
		$totalPriceTarrif +=  $_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];
		
		
		
		
		
		$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$uniqueCode]);
				
$checkin_date = explode('to',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']); 
$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
		
		
$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysqli_num_rows($result14);	

$query14data = mysqli_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	


		
		$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
		$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;		
		//$uniqueCodeRequest		= $_REQUEST['uniqueCode'];
		
		$price 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]);
		
		
		
		
		$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
		
		if(num_rows($resNewTax) >0 ){
				$rowNewTax = $db->fetch_object2($resNewTax);
		
		//echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent.'=='.$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]."totalPrice= >";
		
		//$_SESSION['editCart'][$OrderUniqueID]['tax'][$UniqueDateFor][$uniqueCode]	= round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
		
		
		
		//echo "===".$taxablePrice22	+=	round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
		
		
		$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]."'");
		
			if($TaxInclusiveStatus1	== '2' ){
				
					
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]  = $_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100);
				
				$taxablePrice	+=	$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100);
				
					
			}if($TaxInclusiveStatus1	== '1'  && $TaxInclusiveStatus1	!= '2'   &&  $TaxInclusiveStatus1	!= '3' ){	
			
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]	= 0;
				$SingleRowTaxValue	=0;
				}
				
		}

		
		$StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDateFor));
		}
	$StartDateListFor	=strtotime($checkinDate);
 	}

echo $availableData.'|||';

$_SESSION['editCart'][$OrderUniqueID]['taxablePrice']	=	$taxablePrice;
$otherchagersid							=	$_REQUEST['otherchagersid'];

//$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]			=	$_REQUEST['charges_price'];
//$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]			=	$_REQUEST['charges_total'];
//$_SESSION['editCart'][$OrderUniqueID]['charges_description'][$otherchagersid]	=	$_REQUEST['charges_description'];
//$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$otherchagersid]			=	$_REQUEST['charges_tax'];
//$_SESSION['editCart'][$OrderUniqueID]['charges_net'][$otherchagersid]			=	$_REQUEST['charges_net'];


//$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
//$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_total']);

if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue){
	
	//$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}
}

$_SESSION['editCart'][$OrderUniqueID]['finalPrice']  = round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+($_SESSION['editCart'][$OrderUniqueID]['taxablePrice'])),0,PHP_ROUND_HALF_UP);


echo '<table class="table" >
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
                <th>Tax</th>
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
			
}else{	
	echo $availableData.'|||<font color="#d73925" >Please enter Charges less than Price.</font>';
	}
			
?>