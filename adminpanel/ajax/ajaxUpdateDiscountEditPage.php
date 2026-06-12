<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$OrderUniqueID	=$_REQUEST['OrderUniqueID'];
 
$reservation_date = explode(' to ',$_SESSION['editCart'][$OrderUniqueID][$OrderUniqueID]['reservation_date']);


$discount = $_REQUEST['discount'];
$discountType = $_REQUEST['discountType'];
$discountVar = $_REQUEST['discountVar'];
$addcharges_var = $_REQUEST['addcharges_var'];

$totalPrice = $_SESSION['editCart'][$OrderUniqueID]['totalPrice'];
$taxablePrice = $_SESSION['editCart'][$OrderUniqueID]['taxablePrice'];
$amountReceived = $_SESSION['editCart'][$OrderUniqueID]['amountReceived'];

$charges_total 			= 	$_REQUEST['charges_total'];
$otherchagersid			=	$_REQUEST['otherchagersid'];

$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];
$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];
$_SESSION['editCart'][$OrderUniqueID]['charges_description'][$otherchagersid]	=	$_REQUEST['charges_description'];
$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$otherchagersid]			=	$_REQUEST['charges_tax'];
$_SESSION['editCart'][$OrderUniqueID]['charges_net'][$otherchagersid]			=	$_REQUEST['charges_net'];

if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue){
	
	//$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}
}
if(($discount == 'apply') && ($discountType!='') && ($discountVar!='') && (is_numeric($discountVar)) && (is_numeric($discountType))){
	if($discountType == '1'){			
		if($discountVar <= $totalPrice){
		    $discount_price = $discountVar;			
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';			
			$msg .= '|||<i class="fa fa-inr"></i> '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);				
		}else {
			$discount_price = 0;
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';			
			$msg .= '<font color="#d73925" >Please enter discount less than '.$totalPrice.'.</font>';
			$msg .= '|||<i class="fa fa-inr"></i> '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);	
		}		
	}
	else if($discountType == '2'){	
	  if($discountVar <= '100'){		
		$discount_price = round($totalPrice*($discountVar/100),0,PHP_ROUND_HALF_UP);
		$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
		$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
		$msg .= '|||';
		$msg .= '|||<i class="fa fa-inr"></i> '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);	
		}else {
			$discount_price = 0;			
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<font color="#d73925" >Please enter percent less than 100.</font>';
			$msg .= '|||<i class="fa fa-inr"></i> '.round((($totalPrice-$discount_price)+$TotalAdditionalChargesPrice+$TotalAdditionalChargesTaxValue+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);	
		}
	}else {
			$discount_price = 0;			
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$TotalAdditionalCharges+$TotalAdditionalChargesTaxValue+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<font color="#d73925" >Please enter proper value.</font>';
			$msg .= '|||<i class="fa fa-inr"></i> '.round((($totalPrice-$discount_price)+$TotalAdditionalCharges+$TotalAdditionalChargesTaxValue+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);	
	}	
}else{
$discount_price = 0;			
$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$TotalAdditionalCharges+$TotalAdditionalChargesTaxValue+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
$msg .= '<font color="#d73925" >Please enter proper value.</font>';
$msg .= '|||<i class="fa fa-inr"></i> '.round((($totalPrice-$discount_price)+$TotalAdditionalCharges+$TotalAdditionalChargesTaxValue+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);	

}
$_SESSION['editCart'][$OrderUniqueID]['discountType'] = $discountType;
$_SESSION['editCart'][$OrderUniqueID]['discountVar'] = $discountVar;
$_SESSION['editCart'][$OrderUniqueID]['discountPrice'] = $discount_price;

$_SESSION['editCart'][$OrderUniqueID]['addcharges_var'] = $addcharges_var;
$_SESSION['editCart'][$OrderUniqueID]['AdditionalChargesPrice'] = $TotalAdditionalCharges;
			




echo $msg;
?>