<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];


$id_company = $_REQUEST['id_company'];
$rate_id = 	$_REQUEST['rate_id'];	
if($rate_id!='0'){ 
// USING RATE LETTER===============================
$rate_level_assgin = selectColumn(TBL_COMPANY,'id_rate_level'," WHERE `id_company` = '".addslashes($id_company)."'");



	
	
	$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id where     `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1'  and (`".TBL_RATE."`.company_id='".$id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))" );
	  $planData .= '<option '.$selected.' value="">Select Rate Latter</option>';
							  if($db->num_rows2($resCat)){
								  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
								  $k=0;
								while($resultCat = $db->fetch_object2($resCat)){
									
									if($rate_id == $resultCat->id){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									if($k==0){
									$planData .= '<option '.$selected.' value="0">uu'.$rate_id.'ADHOC</option>';
									}
									$planData .= '<option '.$selected.' value="'.$resultCat->id.'">'.$rate_id.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).'</option>';
								$k++;
								}
							  }else{
							  $planData .= '<option value="" >No Result</option>';
							  
							  }
							 echo $planData;
						$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate 	= $reservation_date[0];
$checkoutDate 	= $reservation_date[1];
$days 			=  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
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

////////////////////////setting the value in session /////////////////////////////




$_SESSION['editCart']['hotel_id'] = $_REQUEST['hotel_id'];


$_SESSION['editCart']['noOfDays'] = $noOfDays;
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['editCart']['rate_id'] = $_REQUEST['rate_id'];
//$_SESSION['editCart']['dataValue'][$uniqueCode] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['editCart']['type'] = $_REQUEST['book_type'];
$_SESSION['editCart']['series'] = $_REQUEST['series'];
$_SESSION['editCart']['operator'] = $_REQUEST['operator'];


$_SESSION['editCart']['extra_bed_price'][$uniqueCode]	= $extra_bed_price;
$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode]	= $extra_bed_price_child;



//echo $availableData.'|||';

if (is_array($_SESSION['editCart']['dataValue']) || is_object($_SESSION['editCart']['dataValue'])){
foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){		

		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice += ($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['meal'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		
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


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($hotel_id)."' and  `room_id` = '".addslashes($_SESSION['editCart']['room_type_id'][$uniqueCode])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
  $rowTax->tax_room;

	//	echo "<br>Tax %=". $rowTax->tax_room." Room Price=".$_SESSION['editCart']['tarrif'][$uniqueCode]." Room QTY=".$_SESSION['editCart']['room_quantity'][$uniqueCode]."  Room No Days =".$_SESSION['editCart']['noOfDays']."   totalPrice= >".round($_SESSION['editCart']['tarrif'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
	
$taxablePrice	+= round(($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
		
	echo '<strong><i class="fa fa-inr"></i> '.$_SESSION['editCart']['tarrif'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="MultiPlanSelect_open" onclick="MultiPlanSelect(this.id);" id="MultiPlanSelect_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';
	
	
	//echo "###".$uniqueCode."###";	
}
}

$_SESSION['editCart']['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];

$_SESSION['editCart']['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart']['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart']['charges_total']);


$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $taxablePrice+$TotalAdditionalChargesTaxValue;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'];




$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']+$TotalAdditionalChargesPrice-$_SESSION['editCart']['discountPrice'])+($_SESSION['editCart']['taxablePrice'])),0,PHP_ROUND_HALF_UP);



echo '||| |||<table class="table" >
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
                <td id="tax"><i class="fa fa-inr"></i>  '.round($_SESSION['editCart']['taxablePrice'],2).'</td>
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

}else{// USING ADHOL ===============================
	
echo "<pre>";
print_r($_REQUEST);
print_r($_SESSION);
echo "</pre>";
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




$_SESSION['editCart']['hotel_id'] = $_REQUEST['hotel_id'];


$_SESSION['editCart']['noOfDays'] = $noOfDays;
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['editCart']['rate_id'] = $_REQUEST['rate_id'];
//$_SESSION['editCart']['dataValue'][$uniqueCode] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['editCart']['type'] = $_REQUEST['book_type'];
$_SESSION['editCart']['series'] = $_REQUEST['series'];
$_SESSION['editCart']['operator'] = $_REQUEST['operator'];






//echo $availableData.'|||';


foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){		

		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice += ($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['meal'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		
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


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($hotel_id)."' and  `room_id` = '".addslashes($_SESSION['editCart']['room_type_id'][$uniqueCode])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
  $rowTax->tax_room;

	//	echo "<br>Tax %=". $rowTax->tax_room." Room Price=".$_SESSION['editCart']['tarrif'][$uniqueCode]." Room QTY=".$_SESSION['editCart']['room_quantity'][$uniqueCode]."  Room No Days =".$_SESSION['editCart']['noOfDays']."   totalPrice= >".round($_SESSION['editCart']['tarrif'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
	
$taxablePrice	+= round(($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price'][$uniqueCode]+$_SESSION['editCart']['extra_bed_price_child'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
		
	echo '<strong><i class="fa fa-inr"></i> '.$_SESSION['editCart']['tarrif'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';
	
	
	//echo "###".$uniqueCode."###";	
}


$_SESSION['editCart']['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];

$_SESSION['editCart']['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart']['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart']['charges_total']);


$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $taxablePrice+$TotalAdditionalChargesTaxValue;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'];




$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']+$TotalAdditionalChargesPrice-$_SESSION['editCart']['discountPrice'])+($_SESSION['editCart']['taxablePrice'])),0,PHP_ROUND_HALF_UP);



echo '||| |||<table class="table" >
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
                <td id="tax"><i class="fa fa-inr"></i>  '.round($_SESSION['editCart']['taxablePrice'],2).'</td>
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
			
			

	}
	
	


?>