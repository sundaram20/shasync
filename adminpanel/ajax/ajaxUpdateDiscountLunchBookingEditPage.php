<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////

$discount = $_REQUEST['discount'];
$discountType = $_REQUEST['discountType'];
$discountVar = $_REQUEST['discountVar'];
$addcharges_var = $_REQUEST['addcharges_var'];

$totalPrice = $_SESSION['editCart']['totalPrice'];
$taxablePrice = $_SESSION['editCart']['taxablePrice'];
$amountReceived = $_SESSION['editCart']['amountReceived'];

$charges_total 			= 	$_REQUEST['charges_total'];
$otherchagersid			=	$_REQUEST['otherchagersid'];

$_SESSION['editCart']['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];
$_SESSION['editCart']['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];
$_SESSION['editCart']['charges_description'][$otherchagersid]	=	$_REQUEST['charges_description'];
$_SESSION['editCart']['charges_tax'][$otherchagersid]			=	$_REQUEST['charges_tax'];
$_SESSION['editCart']['charges_net'][$otherchagersid]			=	$_REQUEST['charges_net'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart']['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart']['charges_total']);

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
$_SESSION['editCart']['discountType'] = $discountType;
$_SESSION['editCart']['discountVar'] = $discountVar;
$_SESSION['editCart']['discountPrice'] = $discount_price;

$_SESSION['editCart']['addcharges_var'] = $addcharges_var;
$_SESSION['editCart']['AdditionalChargesPrice'] = $TotalAdditionalCharges;
			




echo $msg;
?>