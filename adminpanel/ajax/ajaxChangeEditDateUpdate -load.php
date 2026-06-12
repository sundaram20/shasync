<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////


/*print_r($_SESSION);
echo "====================";
print_r($_REQUEST);*/

//$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}


if(!empty($_SESSION['eId'])){
	$sql = "SELECT * FROM `".TBL_ORDERS."` where `id_order` = '".addslashes(encryptor('decrypt',$_SESSION['eId']))."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}	
	
	$disabled = 'disabled="disabled"';
			
}else{

	}	
	$_SESSION['editCart']['orderReference']		 = $row->reference;
	$_SESSION['editCart']['amountReceived']		 = round($row->amount_received,2);						
	$_SESSION['editCart']['discountPrice'] 		 = round($row->total_discounts,2);
	$_SESSION['editCart']['AdditionalChargesPrice'] = round($row->total_addcharges,2);
	$_SESSION['editCart']['addcharges_var']		 = round($row->addcharges_var,2);
	$_SESSION['editCart']['addcharges_type']	 = round($row->addcharges_type,2);
	$_SESSION['editCart']['noOfDays']			 = $noOfDays;
	$_SESSION['editCart']['id_company']			 = $row->id_company;
	$_SESSION['editCart']['id_guest']			 = $row->id_customer;
	$_SESSION['editCart']['id_contacts']		 = $row->id_company_person;
	$_SESSION['editCart']['hotel_id']			 = $row->id_hotel;
	$_SESSION['editCart']['rate_id']			 = $row->id_rate;
	
	$id_shop = $row->id_shop;
	
	$_SESSION['series']['series']		=	$row->series_id;				  
	$_SESSION['series']['operator']		=	$row->operator_id;
	$_SESSION['series']['type']			=	$row->type;
				  
				  
	//$_SESSION['editCart']['reservation_date'] = date('d-m-Y',strtotime($checkin_date)).' to '. date('d-m-Y',strtotime($checkout_date));
	
	
	$id_company = $_REQUEST['id_company'];
$rate_id = 	$_REQUEST['rate_id'];

	?>
<i class="fa fa-home bg-blue"></i>
                    <div class="timeline-item">
                       <div class="row table-responsive">
                        <table class="table table-hover" id="showRoom">
                           <tr>
                            <th>shafeerRoom Type</th>
                            <th>Plan</th>
                            <th>Room Quantity</th>
                            <th>Adults/Room</th>
                            <th>Child/Room<br>
                               (0 - 5 yrs)</th>
                            <th>Child/Room<br>
                               (5 - 12 yrs)</th>
                            <th>Tariff/Night</th>
                            <th>Meal/day</th>
                            <th>Avg. Rate*Night</th>
                            <th><button class="btn btn-danger btn-sm" type="button" id="view" onClick="ajaxRoomRemoveAll();"> <i class="fa fa-close fa-lg"></i> </button></th>
                          </tr>
                           <tr id="addRoommsg" align="center" <?php if($row->id_order != ''){ echo 'style="display:none;"';}  ?>>
                            <td colspan="7"><strong>Please Add Room.</strong></td>
                          </tr>
                           <?php 	
			
			

			
			$sqlOrderDetail = executeSql("Select * from `".TBL_ORDER_DETAIL."` where id_order='".addslashes($row->id_order)."' group by unique_code,room_id,room_quantity,adults,child,rate_plan_id");
			
			if(num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= $db->fetch_object2($sqlOrderDetail)){
									
				$uniqueCode = 'CODE'.rand(0000,9999);		
				
				$_SESSION['editCart']['room_quantity'][$uniqueCode] = $rowOrderDetail->room_quantity;
				$_SESSION['editCart']['adult_no'][$uniqueCode] = $rowOrderDetail->adults;
				$_SESSION['editCart']['infant_no'][$uniqueCode] = $rowOrderDetail->infants;
				$_SESSION['editCart']['child_no'][$uniqueCode] = $rowOrderDetail->child;
				$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $rowOrderDetail->original_product_price;				
				$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $rowOrderDetail->extra_price;
				$_SESSION['editCart']['room_price'][$uniqueCode] = $rowOrderDetail->total_price;
				$_SESSION['editCart']['tarrif'][$uniqueCode] = $rowOrderDetail->tarrif_price_per_day;
				$_SESSION['editCart']['meal'][$uniqueCode] = $rowOrderDetail->food_price_per_day;
				
			
				
				$start_date		=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$rowOrderDetail->rate_id."'");
				$Newcheckin = $row->checkin;
				$Newcheckout	= $row->checkout;
				$_SESSION['editCart']['Newcheckin']	=	$Newcheckin;
				 $query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".addslashes($_SESSION['shop'])."'";
	
	$result14 = executeSql($query14,$link);
	$query14count = mysqli_num_rows($result14);	
	
	$query14data = mysqli_fetch_array($result14);
	$seasonIdnew	= $query14data['id'];	
	 
	 
				 $resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($rowOrderDetail->hotel_id)."' and  `room_id` = '".addslashes($rowOrderDetail->room_id)."' and  `seasonId` = '".addslashes($seasonIdnew)."'");
				
		$rowTax = $db->fetch_object2($resTax);

		$rowOrderDetail->total_price*$noOfDays."tax %= ".$rowTax->tax_room;
					
					
					$roomTax	+=	$rowOrderDetail->total_price*$noOfDays*($rowTax->tax_room/100);
					
					
					$_SESSION['editCart']['room_tax_price'][$uniqueCode] =  $roomTax;
				
				
				$totalRoom += $rowOrderDetail->room_quantity;
				$totalAdult += $rowOrderDetail->adults;
				$totalChild += $rowOrderDetail->child;
				$totalInfant += $rowOrderDetail->infants;
				
				$totalPrice += $rowOrderDetail->total_price*$noOfDays;
				$taxablePrice += $_SESSION['editCart']['room_tax_price'][$uniqueCode];
				$totalPriceTarrif += $rowOrderDetail->original_product_price*$noOfDays*$rowOrderDetail->room_quantity;
				$totalPriceFood += $rowOrderDetail->food_price*$noOfDays;
				$totalPriceExtra += $rowOrderDetail->extra_price*$noOfDays;
				
				$_SESSION['editCart']['dataValue'][$uniqueCode] = 	'dateValue|'.$rowOrderDetail->hotel_id.'|'.$rowOrderDetail->room_id.'|'.$rowOrderDetail->rate_id.'|'.$rowOrderDetail->rate_plan_id.'|'.$rowOrderDetail->rate_assign_id.'|'.$rowOrderDetail->type;
				$_SESSION['editCart']['room_type_id'][$uniqueCode]=$rowOrderDetail->room_id;
			
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_ORDER_DETAIL."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where   rd.rate_id='".addslashes($rowOrderDetail->rate_id)."'  and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id ");
		
				if(num_rows($resRoom) >0){				
					$rowRoom = $db->fetch_object2($resRoom);
					$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td><select class="form-control"  name="room_type_id[]" id="room_type_id|'.$uniqueCode.'" data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));" >
											  <option value="">Room Type</option>';
										
									
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($rowOrderDetail->hotel_id));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($rowOrderDetail->room_id == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select></td>';		
										
$availableData .=' <td><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id|'.$uniqueCode.'"  data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));" >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($rowOrderDetail->rate_plan_id == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td></strong></td>';
												 		
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));" >';		
				
				for ($i=1; $i<=100; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($rowOrderDetail->room_quantity ==$i){
			 $availableData .='selected="selected"';
			 }
			 
			 $availableData .='>'.$i.'</option>';
       
    }

                $availableData .='</select></td>	
				<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode|'.$uniqueCode.'">
				<input type="hidden" name="dataValue[]" value="dateValue|'.$rowOrderDetail->hotel_id.'|'.$rowOrderDetail->room_id.'|'.$rowOrderDetail->rate_id.'|'.$rowOrderDetail->rate_plan_id.'|'.$rowOrderDetail->rate_assign_id.'|'.$rowOrderDetail->type.'" id="dataValue|'.$uniqueCode.'">			
				  <td><select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="getRateEdit(\'adult_no|'.$uniqueCode.'\');">';
				if($rowOrderDetail->adults == '1' ){$selectedAdultNo1 =  'selected="selected"';}else{$selectedAdultNo1 =''; }
				if($rowOrderDetail->adults == '2' ){$selectedAdultNo2 =  'selected="selected"';}else{$selectedAdultNo2 =''; }
				if($rowOrderDetail->adults == '3' ){$selectedAdultNo3 =  'selected="selected"';}else{$selectedAdultNo3 =''; }
$availableData .='<option value="1" '.$selectedAdultNo1.'>1</option>
				  <option value="2" '.$selectedAdultNo2.'>2</option>
                </select></td>				
				  <option value="3" '.$selectedAdultNo3.'>3</option>
				<td> <select class="form-control input-sm" name="infant_no[]" id="infant_no|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));">';
				if($rowOrderDetail->infants == '0' ){$selectedChildNo1 =  'selected="selected"';}else{$selectedChildNo1 =''; }
				if($rowOrderDetail->infants == '1' ){$selectedChildNo2 =  'selected="selected"';}else{$selectedChildNo2 =''; }
				if($rowOrderDetail->infants == '2' ){$selectedChildNo3 =  'selected="selected"';}else{$selectedChildNo3 =''; }
$availableData .=' <option value="0" '.$selectedChildNo1.'>0</option>
				   <option value="1" '.$selectedChildNo2.'>1</option>
				   <option value="2" '.$selectedChildNo3.'>2</option>
                </select></td>				
				  <td> <select class="form-control input-sm" name="child_no[]" id="child_no|'.$uniqueCode.'" data-parsley-required onchange="getRateEdit($(this).attr(\'id\'));">';
				if($rowOrderDetail->child == '0' ){$selectedChildNo1 =  'selected="selected"';}else{$selectedChildNo1 =''; }
				if($rowOrderDetail->child == '1' ){$selectedChildNo2 =  'selected="selected"';}else{$selectedChildNo2 =''; }
				if($rowOrderDetail->child == '2' ){$selectedChildNo3 =  'selected="selected"';}else{$selectedChildNo3 =''; }
$availableData .='<option value="0" '.$selectedChildNo1.'>0</option>
				   <option value="1" '.$selectedChildNo2.'>1</option>
				   <option value="2" '.$selectedChildNo3.'>2</option>
                </select></td>';
				
$availableData .='<td><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.round($_SESSION['editCart']['tarrif'][$uniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$uniqueCode.'\');"></td>';


$availableData .='<td><input type="text" class="form-control input-sm"  name="meal[]"  id="meal|'.$uniqueCode.'"  value="'.round($_SESSION['editCart']['meal'][$uniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'meal|'.$uniqueCode.'\');"></td>';
				
				
				
							 
$availableData .='<td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.($rowOrderDetail->total_price+($_SESSION['editCart']['meal'][$uniqueCode]))*$noOfDays.'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="CheckRoomPlan(this.id);" onclick="pricePopUp(this.id);  id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span></td> 

 
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';
					}
			  	$i++;
				echo $availableData;
				}
				
				
				
			
			}		
				
				
				
$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
//$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $roomTax;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['room_tax_price'][$uniqueCode]+$_SESSION['editCart']['AdditionalCharges'][$uniqueCode]),0,PHP_ROUND_HALF_UP);
				?>
                         </table>
                      </div>
                       <div class="row"> 
                        <!-- accepted payments column -->
                        
                        <div class="col-sm-7 text-muted well well-sm no-shadow" >
                           <?php 
		
		$_SESSION['editCart']['discountVar']	=	$row->discount_var;
		$_SESSION['editCart']['discountType']	=	$row->discount_type;
		?>
                           <p class="lead">Discount:</p>
                           <p id="discountMsg" align="center"></p>
                           <p class="col-sm-3" style="margin-top: 10px;">Apply Discount </p>
                           <div class="col-sm-3">
                            <select class="form-control" name="discountType" id="discountType" onChange="discounttype(this.value);">
                               <option value="1" <?php if($_SESSION['editCart']['discountType'] == '1' ){echo 'selected="selected"';} ?>>Flat</option>
                               <option value="2" <?php if($_SESSION['editCart']['discountType'] == '2' ){echo 'selected="selected"';} ?>>Percentage</option>
                             </select>
                          </div>
                           <div class="col-sm-4" id="flat" <?php if($_SESSION['editCart']['discountType'] == '1' ){echo 'style="display:block;"';}else if($_SESSION['editCart']['discountType'] == '2'){echo 'style="display:none;"';} else  { echo 'style="display:block;"'; } ?>  >
                            <div class="input-group">
                               <div class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat"><i class="fa fa-inr"></i> </button>
                              </div>
                               <!-- /btn-group -->
                               <input type="text" class="form-control" id="flatDiscount" value="<?php if($_SESSION['editCart']['discountType'] == '1' ){echo $_SESSION['editCart']['discountVar']; }?>" autocomplete="off">
                             </div>
                          </div>
                           <div class="col-sm-4" id="percent" <?php if($_SESSION['editCart']['discountType'] == '2' ){echo 'style="display:block;"';}else {echo 'style="display:none;"';} ?>>
                            <div class="input-group">
                               <input type="text" class="form-control" id="percentDiscount" value="<?php if($_SESSION['editCart']['discountType'] == '2' ){echo $_SESSION['editCart']['discountVar'];}?>" autocomplete="off">
                               <span class="input-group-btn">
                              <button type="button" class="btn btn-info btn-flat"><i class="fa fa-percent"></i></button>
                              </span> </div>
                          </div>
                           <div class="col-sm-2 ">
                            <button type="button" class="btn btn-primary" onClick="applyDiscount();">Apply</button>
                          </div>
                           <div style="float: left;width: 100%;margin-top: 28px;">
                            <p class="lead" style="width: 200px;float: left;margin-top: 10px;">Charges:</p>
                            <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddothercharges('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" style="margin-top: 14px;" ><i class="fa fa-plus-circle"></i></button>
                          </div>
                           <p id="addchargesMsg" align="center"></p>
                           <table class="table table-hover" style="margin-bottom:0px;" >
                            <tr>
                               <th>Charges</th>
                               <th>Amount</th>
                               <th>Tax %</th>
                               <th>Tax Value</th>
                               <th>Net Value</th>
                             </tr>
                            <?php 
		
		$sqlOtherChargesDetail 		= executeSql("Select * from `".TBL_OTHERCHARGES_DETAIL."` where id_order='".addslashes($row->id_order)."'");
		
		$NUmber 				=	num_rows($sqlOtherChargesDetail);
			
				while($rowOtherChargesDetail	= $db->fetch_object2($sqlOtherChargesDetail)){
				$OtherChargesuniqueCode 		= 'OTHERCHARGE'.rand(0000,9999);
				
				$_SESSION['editCart']['id_othercharges_detail'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->id_othercharges_detail;
				$_SESSION['editCart']['charges_description'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_description_id;
				$_SESSION['editCart']['charges_price'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_price;
				$_SESSION['editCart']['charges_tax'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_tax_percentage;
				$_SESSION['editCart']['charges_total'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_tax_value;
				$_SESSION['editCart']['charges_net'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_net;		
				
			$availableData = '<tr id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom" >';
			
$availableData .=' <td ><input type="text" class="form-control"  name="charges_description|'.$OtherChargesuniqueCode.'" id="charges_description|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart']['charges_description'][$OtherChargesuniqueCode].'"  placeholder="Charges Description." style="width: 160px;"  onKeyUp="calculateOthercharge(\'charges_description|'.$OtherChargesuniqueCode.'\');"></td>';	


$availableData .='<input type="hidden" class="form-control"  name="id_othercharges_detail|'.$OtherChargesuniqueCode.'" id="id_othercharges_detail|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart']['id_othercharges_detail'][$OtherChargesuniqueCode].'" >';

$availableData .=' <td ><input type="text" class="form-control"  name="charges_price|'.$OtherChargesuniqueCode.'" id="charges_price|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart']['charges_price'][$OtherChargesuniqueCode].'"  onKeyUp="calculateOthercharge(\'charges_price|'.$OtherChargesuniqueCode.'\');"></td>';			

echo $availableData .='<td style="width: 19%;" >
				  <input type="text" class="form-control" id="charges_tax|'.$OtherChargesuniqueCode.'" name="charges_tax|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart']['charges_tax'][$OtherChargesuniqueCode].'" onKeyUp="calculateOthercharge(\'charges_tax|'.$OtherChargesuniqueCode.'\');" autocomplete="off" style="float: left;width: 50%;">
                    
                      <button type="button" class="btn btn-info btn-flat" ><i class="fa fa-percent"></i></button>
                    </td>
				 <td ><input type="text" class="form-control"  name="charges_total|'.$OtherChargesuniqueCode.'" id="charges_total|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart']['charges_total'][$OtherChargesuniqueCode].'"  onKeyUp="calculateOthercharge(\'charges_total|'.$OtherChargesuniqueCode.'\');"></td>
				 
				 <td ><input type="text" class="form-control"  name="charges_net|'.$OtherChargesuniqueCode.'" id="charges_net|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart']['charges_net'][$OtherChargesuniqueCode].'"  onKeyUp="calculateOthercharge(\'charges_net|'.$OtherChargesuniqueCode.'\');"></td>
				 
				  <td ><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';?>
                            <?php } ?>
                          </table>
                           <div id="showOtherCharges"></div>
                           <div class="col-sm-12">
                            <?php $row->rate_text; ?>
                          </div>
                         </div>
                        
                        <!-- /.col -->
                        <div class="col-sm-5">
                           <div class="table-responsive" id="pricingValue">
                            <table class="table" >
                               <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td id="subtotal"><i class="fa fa-inr"></i><?php echo round($totalPrice,2); ?></td>
                              </tr>
                               <tr>
                                <th>Additional Charges:</th>
                                <td id="addchargesvalue"><i class="fa fa-inr"></i> <?php echo round($row->total_addcharges,2); ?></td>
                              </tr>
                               <tr>
                                <th>Discount:</th>
                                <td id="discount"><i class="fa fa-inr"></i> <?php echo round($row->total_discounts,2); ?></td>
                              </tr>
                               <tr>
                                <th>Tax </th>
                                <td id="tax"><i class="fa fa-inr"></i> <?php echo round($row->total_tax); ?></td>
                              </tr>
                               <tr>
                                <th>Total:</th>
                                <td id="totalPrice"><i class="fa fa-inr"></i> <?php echo round($row->total_price,2); ?></td>
                              </tr>
                               <tr>
                                <th>Amount Received:</th>
                                <td id="amountReceived" ><i class="fa fa-inr"></i> <?php echo round($row->amount_received,2); ?></td>
                              </tr>
                               <tr>
                                <th>Balance:</th>
                                <td id="balance"><i class="fa fa-inr"></i> <?php echo round($row->balance,2); ?></td>
                              </tr>
                             </table>
                            <b></b> </div>
                         </div>
                        <!-- /.col --> 
                      </div>
                     </div>
    