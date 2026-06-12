<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
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
echo '|||';
/////////////////////////////executing query///////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($_REQUEST['rate_assign_id'])."'  and rd.rate_plan_id='".addslashes($_REQUEST['rate_plan_id'])."' and rd.rate_id='".addslashes($_REQUEST['rate_id'])."' and room_id='".addslashes($_REQUEST['room_id'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($_REQUEST['rate_plan_id'])."'");
$rowPlan = $db->fetch_object2($ratePlan);


if(num_rows($resRoom) >0){
$uniqueCode = 'CODE'.rand(0000,9999);
$rowRoom = $db->fetch_object2($resRoom);

////////////////////////setting the value in session /////////////////////////////
if($_REQUEST['type'] == 1){
$_SESSION['editCart']['room_price'][$uniqueCode] = $rowRoom->pkg_price;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $rowRoom->pkg_extra_price;
$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $rowRoom->pkg_tarrif_price;
$type='1';
}else{
$_SESSION['editCart']['room_price'][$uniqueCode] = $rowRoom->double_pax_price;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = 0;
$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $rowRoom->room_price;
$type='0';
}
$_SESSION['editCart']['inclusion_food'][$uniqueCode] = $rowRoom->inclusion_food+$rowRoom->inclusion_food;







/*
if($rowPlan->tax_detail=='2'){
///////////////////////////tax configuration////////////////////////////////////////////////
if(($_SESSION['editCart']['tarrif_price'][$uniqueCode]) >7500){
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('>7500')."'");
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['editCart']['room_price'][$uniqueCode]*$noOfDays*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['editCart']['room_price'][$uniqueCode]+$_SESSION['editCart']['inclusion_food'][$uniqueCode])*$noOfDays*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
	
		
		
	}else{
	
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('<=7500')."'");
	
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['editCart']['room_price'][$uniqueCode]*$noOfDays*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['editCart']['room_price'][$uniqueCode]+$_SESSION['editCart']['inclusion_food'][$uniqueCode])*$noOfDays*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
		
	}
///////////////////////////tax configuration////////////////////////////////////////////////
}else{
$_SESSION['bookCart']['room_tax_price'][$uniqueCode] = '0';

}*/



$_SESSION['editCart']['noOfDays'] = $noOfDays;
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['editCart']['rate_id'] = $_REQUEST['rate_id'];
$_SESSION['editCart']['dataValue'][$uniqueCode] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['editCart']['room_quantity'][$uniqueCode] = '1';
$_SESSION['editCart']['adult_no'][$uniqueCode] = '2';
$_SESSION['editCart']['infant_no'][$uniqueCode] = '0';
$_SESSION['editCart']['child_no'][$uniqueCode] = '0';
$_SESSION['editCart']['type'] = $_REQUEST['book_type'];
$_SESSION['editCart']['series'] = $_REQUEST['series'];
$_SESSION['editCart']['operator'] = $_REQUEST['operator'];





////////////////////////setting the value in session end/////////////////////////////
$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td ><strong>'.$rowRoom->room_name.'</strong></td>';	
$availableData .=' <td ><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id"  data-parsley-required   >
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
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));" >
                   <option value="1" >1</option>
				  <option value="2" >2</option>
				  <option value="3" >3</option>
				  <option value="4" >4</option>
				  <option value="5" >5</option>
				   <option value="6" >6</option>
				  <option value="7" >7</option>
				  <option value="8" >8</option>
				  <option value="9" >9</option>
				  <option value="10" >10</option>
				  <option value="11" >11</option>
				  <option value="12" >12</option>
				  <option value="13" >13</option>
				  <option value="14" >14</option>
				  <option value="15" >15</option>
				   <option value="16" >16</option>
				  <option value="17" >17</option>
				  <option value="18" >18</option>
				  <option value="19" >19</option>
				  <option value="20" >20</option>
				   <option value="21" >21</option>
				  <option value="22" >22</option>
				  <option value="23" >23</option>
				  <option value="24" >24</option>
				  <option value="25" >25</option>
				   
                </select></td>
				<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode|'.$uniqueCode.'">
				<input type="hidden" name="dataValue[]" value="dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type.'" id="dataValue|'.$uniqueCode.'">
				  <td>  <select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));">
                  <option value="1" >1</option>
				  <option value="2" selected="selected">2</option>
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
                </select></td>
				  <td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.$_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span></td>  
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';

}else{
echo 'There is some error. Please Try again.|||';

}
echo $availableData.'|||';


foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){		

		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice +=  $_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceFood +=  $_SESSION['editCart']['inclusion_food'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceExtra +=  $_SESSION['editCart']['pkg_extra'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalRoom += $_SESSION['editCart']['room_quantity'][$uniqueCode];				
		//$taxablePrice += $_SESSION['editCart']['room_tax_price'][$uniqueCode];
		
$dataValue = explode('|',$_SESSION['editCart']['dataValue'][$uniqueCode]);
		$Newcheckin = $_SESSION['editCart']['Newcheckin'];
$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysql_num_rows($result14);	

$query14data = mysql_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($hotel_id)."' and  `room_id` = '".addslashes($dataValue['2'])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
  $rowTax->tax_room;

		//echo "Tax %". $rowTax->tax_room."totalPrice= >".$_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
	
$taxablePrice	+= round($_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
		
		
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




$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+($_SESSION['editCart']['taxablePrice'])),0,PHP_ROUND_HALF_UP);





echo '<table class="table" >
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
?>