<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];

$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];
$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);

$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}
$StartDateList	=strtotime($checkinDate);




////////////////////////////////////////////////////////////////////////////////////////////////////
echo '|||';
/////////////////////////////executing query///////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($_REQUEST['rate_assign_id'])."'  and rd.rate_plan_id='".addslashes($_REQUEST['rate_plan_id'])."' and rd.rate_id='".addslashes($_REQUEST['rate_id'])."' and room_id='".addslashes($_REQUEST['room_id'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($_REQUEST['rate_plan_id'])."'");
$rowPlan = $db->fetch_object2($ratePlan);


//if(num_rows($resRoom) >0){
$uniqueCode = 'CODE'.rand(0000,9999);
			$_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'][]	=	$uniqueCode;
$rowRoom = $db->fetch_object2($resRoom);

//print_r($_SESSION);

if($rowOrderDetail->adults == '1'){
			$priceValue += $rowRoom->pkg_price;	
			
		}elseif($rowOrderDetail->adults == '2'){
			$priceValue += $rowRoom->pkg_price;	
			$extra_bed_price	=	0;	
		}elseif($rowOrderDetail->adults == '3'){
			$priceValue += $rowRoom->pkg_price+$rowRoom->extra_bed_price;	
			$extra_bed_price	=	$rowRoom->extra_bed_price*2;	
		}
		if($rowOrderDetail->child == '0'){
			$extra_bed_price_child	=	0;
			$priceValue += $rowRoom->extra_bed_price;
			
		}elseif($rowOrderDetail->child == '1'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price;	
			
		}elseif($rowOrderDetail->child == '2'){					
			$priceValue += $rowRoom->extra_bed_price+$rowRoom->extra_bed_price;
			$extra_bed_price_child	=	$rowRoom->extra_bed_price*2;	
		}	
if($_REQUEST['type'] == 1){
	
	$NewSelect1	=	'selected="selected"';
	}if($_REQUEST['type'] == 0){
	$NewSelect2	=	'selected="selected"';
		}
		
				


////////////////////////setting the value in session /////////////////////////////






$_SESSION['editCart'][$OrderUniqueID]['hotel_id'] = $_REQUEST['hotel_id'];


$_SESSION['editCart'][$OrderUniqueID]['noOfDays'] = $noOfDays;
$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['editCart'][$OrderUniqueID]['rate_id'] = $_REQUEST['rate_id'];
$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]][$uniqueCode] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;

$_SESSION['editCart'][$OrderUniqueID]['type'] = $_REQUEST['book_type'];
$_SESSION['editCart'][$OrderUniqueID]['series'] = $_REQUEST['series'];
$_SESSION['editCart'][$OrderUniqueID]['operator'] = $_REQUEST['operator'];

if($_SESSION['editCart'][$OrderUniqueID]['rate_id'] >'0'){
									
		$disabled	= 'disabled="disabled"';
					
		}

for($i=0;$i<$noOfDays;$i++){
	
	$UniqueDate = date ("d-m-Y", $StartDateList);
	
if($_REQUEST['type'] == 1){
$_SESSION['editCart'][$OrderUniqueID]['room_price'][$UniqueDate][$uniqueCode] 	= $rowRoom->single_pax_price;
$_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDate][$uniqueCode] 	= $rowRoom->pkg_extra_price;
$_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDate][$uniqueCode] = $rowRoom->pkg_tarrif_price;
$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]		= $rowRoom->single_pax_price;
$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$uniqueCode] = '1';

$NewSelect	=	'selected="selected"';
$type='1';
}else{
$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode]	 	= $rowRoom->double_pax_price;
$_SESSION['editCart'][$OrderUniqueID]['room_price'][$UniqueDate][$uniqueCode] 	= $rowRoom->double_pax_price;
$_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDate][$uniqueCode]  	= $rowRoom->extra_bed_price;
$_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDate][$uniqueCode] = $rowRoom->room_price;
$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$uniqueCode] = '2';
$type='0';

}		$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDate][$uniqueCode]=  $_REQUEST['rate_plan_id'];
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDate][$uniqueCode]	= 0;
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDate][$uniqueCode]	= 0;
		$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDate][$uniqueCode] = '1';
		$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDate][$uniqueCode] = $_REQUEST['room_id'];
		//$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDate][$uniqueCode] = '2';
		$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDate][$uniqueCode] = '0';
		$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDate][$uniqueCode] = '0';
		//$_SESSION['editCart'][$OrderUniqueID]['tax'][$UniqueDate][$uniqueCode] = '0';
		
	
	$StartDateList	=strtotime("+1 day", strtotime($UniqueDate));
	
}

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
$roomqty_no				=	array_flatten($_SESSION['editCart'][$OrderUniqueID]['room_quantity']);

foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode22 =>$dataCode){
		
	$StartDateListFor22	=strtotime($checkinDate);
	
	for($i=0;$i<1;$i++){
		$UniqueDateFor22 = date ("d-m-Y", $StartDateListFor22); 
	
		
		$totalAdult		+=	$adult_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		$totalInfant	+=	$infant_no[$uniqueCode22]* $_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
		$totalChild	+=	($child_no[$uniqueCode22]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22]);
		$totalRoom 		+= 	$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor22][$uniqueCode22];
	}
	
	
	}



$StartDateListFor	=strtotime($checkinDate);
	
	for($i=0;$i<$noOfDays;$i++){	

		$UniqueDateFor = date ("d-m-Y", $StartDateListFor); 
 
 		foreach($_SESSION['editCart'][$OrderUniqueID]['dataValue'][$reservation_date[0]] as $uniqueCode =>$dataCode){
			
		//$totalAdult += $_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		//$totalChild += $_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		//$totalInfant += $_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		//$totalPrice += ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['meal'][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];
		$totalPrice += ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode];
		$totalPriceTarrif +=  $_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];
		
		$totalPriceExtra +=  $_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDateFor][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'];
				
		//$taxablePrice += $_SESSION['editCart'][$OrderUniqueID]['room_tax_price'][$uniqueCode];
		
$dataValue = explode('|',$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$checkinDate][$uniqueCode]);

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

$uniqueCodeRequest		= $_REQUEST['uniqueCode'];
	$price 					= ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]);

$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");

if(num_rows($resNewTax) >0 ){
				$rowNewTax = $db->fetch_object2($resNewTax);
		
		//echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent.'=='.$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]."totalPrice= >".round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
		
		
				
		$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]."'");
		
			if($TaxInclusiveStatus1	== '2'  && $TaxInclusiveStatus1	!= '1'   &&  $TaxInclusiveStatus1	!= '3' ){
				$taxablePrice	+=	($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100);
				
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode] = ($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode])*($rowNewTax->tax_percent/100);	
			}
			if($TaxInclusiveStatus1	== '1' ){	
			//$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCodeRequest]=0;
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]  = 0;
				$SingleRowTaxValue	=0;
				}
				
			
			$TaxInclusiveStatus44	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCodeRequest]."'");
		
		
		
		$SingleRowTaxValue	=	round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode]*($rowNewTax->tax_percent/100));
			
			
		}


// New Tax Rules END 

	//}
			
			
			
		}
	$StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDateFor));	
	}
		
if($_REQUEST['hotel_id']!=''){
////////////////////////setting the value in session end/////////////////////////////
$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td ><select class="form-control"  name="room_type_id[]" id="room_type_id|'.$uniqueCode.'" data-parsley-required  '.$disabled.' onchange="getRateEdit($(this).attr(\'id\'));">
											  <option value="">Room Type</option>';
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($_REQUEST['hotel_id']));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($_REQUEST['room_id'] == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select></td>';
				if($_SESSION['editCart'][$OrderUniqueID]['rate_id'] >'0'){								 
				$availableData .='<input type="hidden" name="room_type_id[]" value="'.$_REQUEST['room_id'].'|'.$uniqueCode.'">';
				$availableData .='<input type="hidden" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$uniqueCode].'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$uniqueCode.'\');" >';
				}
				
				
$availableData .=' <td ><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id|'.$uniqueCode.'"  data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));" '.$disabled.' >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['rate_plan_id'] == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td>';			
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));" >';
for ($i=1; $i<=100; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($_SESSION['bookCart']['room_quantity'][$UniqueDate][$uniqueCode] == $i){
			 $availableData .='selected="selected"';
			 }
			 
			 $availableData .='>'.$i.'</option>';
       
    }
				 
				   
                 $availableData .='</select></td>
				<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode|'.$uniqueCode.'">
				<input type="hidden" name="dataValue[]" value="dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type.'" id="dataValue|'.$uniqueCode.'">
				
				  <td>  <select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));">
                  <option value="1" '.$NewSelect1.'>1</option>
				  <option value="2" '.$NewSelect2.'>2</option>
				  <option value="3" >3</option>
                </select></td>
				 <td> <select class="form-control input-sm" name="infant_no[]" id="infant_no|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td> <select class="form-control input-sm" name="child_no[]" id="child_no|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>';
				
				
				
			$availableData .='

<td id="trafficprice_'.$uniqueCode.'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDate][$uniqueCode].'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$uniqueCode.'\');" '.$disabled.' data-parsley-required></td>';


$availableData .='<td id="TaxPerdayPerroom_'.$uniqueCode.'"><input type="text" class="form-control input-sm"  name="TaxPerdayPerroom[]"  id="TaxPerdayPerroom|'.$uniqueCode.'"  value="'.$SingleRowTaxValue.'" style="width: 80px;" readonly ></td>';

/*<td><input type="text" class="form-control input-sm"  name="meal[]"  id="meal|'.$uniqueCode.'"  value="'.$_SESSION['editCart'][$OrderUniqueID]['meal'][$uniqueCode].'" style="width: 80px;" data-parsley-type="digits" onchange="getRateEdit($(this).attr(\'id\'));"></td>
			
				
				
			*/	
				  $availableData .='<td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.$_SESSION['editCart'][$OrderUniqueID]['room_price'][$UniqueDate][$uniqueCode]*$_SESSION['editCart'][$OrderUniqueID]['noOfDays'].'</strong>&nbsp;&nbsp;';
				  
				  /*<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>*/
				  
				  
				 $availableData .='</td>  
				  
				  
				  
				  
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';

}else{
echo 'There is some error. Please Try again.|||';

}
echo $availableData.'|||';




$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];

$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];

if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue ){
	
	
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}}




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


echo '<table class="table" >
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
                <td id="tax"><i class="fa fa-inr"></i>  '.round($_SESSION['editCart'][$OrderUniqueID]['taxablePrice']+$TotalAdditionalChargesTaxValue).'</td>
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
?>