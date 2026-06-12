<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////
$dataValue = explode('|',$_REQUEST['dataValue']);
$uniqueCode = $_REQUEST['uniqueCode'];
$tarrif =  $_REQUEST['tarrif'];
$meal =  $_REQUEST['meal'];
$extra =  $_REQUEST['extra'];
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($dataValue['4'])."'");
$rowPlan = $db->fetch_object2($ratePlan);
if(num_rows($resRoom) >0){

$rowRoom = $db->fetch_object2($resRoom);
$priceValue = $tarrif+$meal+$extra;
$inclusionFood =$meal;
$pkg_extra = $extra ;
/////////////////////////////////////making calculation////////////////////////////////////////////////

//////////////////////////////removing modified session////////////////////////////////////////////////////////
$_SESSION['editCart']['room_price'][$uniqueCode] = $priceValue;
$_SESSION['editCart']['inclusion_food'][$uniqueCode] = $inclusionFood;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $pkg_extra;
$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $tarrif;

if($rowPlan->tax_detail=='2'){
///////////////////////////tax configuration////////////////////////////////////////////////
if(($_SESSION['editCart']['tarrif_price'][$uniqueCode]) >7500){
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('>7500')."'");
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['editCart']['room_price'][$uniqueCode]+$_SESSION['editCart']['inclusion_food'][$uniqueCode])*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
	
		
		
	}else{
	
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('<=7500')."'");
	
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['editCart']['room_price'][$uniqueCode]+$_SESSION['editCart']['inclusion_food'][$uniqueCode])*$_SESSION['editCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
		
	}
///////////////////////////tax configuration////////////////////////////////////////////////
}else{
$_SESSION['editCart']['room_tax_price'][$uniqueCode] = '0';

}



/////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '||| |||<strong><i class="fa fa-inr"></i> '.$priceValue*$_SESSION['editCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';	  
}else {
echo '||| |||Error.';

}


foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){			
		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice +=  $_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceFood +=  $_SESSION['editCart']['inclusion_food'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceExtra +=  $_SESSION['editCart']['pkg_extra'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalRoom += $_SESSION['editCart']['room_quantity'][$uniqueCode];				
		$taxablePrice += $_SESSION['editCart']['room_tax_price'][$uniqueCode];
}
$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $taxablePrice;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'] = 0;
$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart']['totalPrice'].'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart']['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.$_SESSION['editCart']['taxablePrice'].'</td>
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