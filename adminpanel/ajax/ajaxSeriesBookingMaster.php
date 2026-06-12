<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$seriesId=$_REQUEST['seriesId'];
$operatorId=$_REQUEST['operatorId'];
$seriestype	= $_REQUEST['book_type'];
$serieshotel_id	= $_REQUEST['hotel_id'];
$id_company	= $_REQUEST['id_company'];



//if($seriesId!='' && $operatorId !=''){
//	AND id_hotel	= '".addslashes($serieshotel_id)."'


$resOrder = executeSql("SELECT * from `".TBL_ORDERS."` where series_id='".addslashes($seriesId)."' and operator_id='".addslashes($operatorId)."' and id_company='".addslashes($id_company)."'  and type='S' order by id_order desc limit 0,1");
$NumberOfRow	=	num_rows($resOrder);
$row 		= $db->fetch_object2($resOrder);
if($NumberOfRow > 0){
	
	  //$_SESSION['OrderUniqueID'] = $row->id_order;
 	  //$OrderUniqueID			 = $_SESSION['OrderUniqueID'];
	
	$_SESSION['OrderUniqueID'] = rand(0000,9999);
 	 $OrderUniqueID				= $_SESSION['OrderUniqueID'];
	 $_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = date('d-m-Y',strtotime($row->checkin)).' to '. date('d-m-Y',strtotime($row->checkout));
	 $days =  abs((strtotime($row->checkin) - strtotime($row->checkout))/ 86400 );
	if($days == '0'){
	$noOfDays = '1';
	}else {
	$noOfDays = $days;
	}
	 $_SESSION['editCart'][$OrderUniqueID]['orderReference']		 = $row->reference;
	$_SESSION['editCart'][$OrderUniqueID]['amountReceived']		 = round($row->amount_received,2);						
	$_SESSION['editCart'][$OrderUniqueID]['discountPrice'] 		 = round($row->total_discounts,2);
	$_SESSION['editCart'][$OrderUniqueID]['AdditionalChargesPrice'] = round($row->total_addcharges,2);
	$_SESSION['editCart'][$OrderUniqueID]['addcharges_var']		 = round($row->addcharges_var,2);
	$_SESSION['editCart'][$OrderUniqueID]['addcharges_type']	 = round($row->addcharges_type,2);
	$_SESSION['editCart'][$OrderUniqueID]['noOfDays']			 = $row->no_of_days;
	$_SESSION['editCart'][$OrderUniqueID]['id_company']			 = $row->id_company;
	$_SESSION['editCart'][$OrderUniqueID]['id_guest']			 = $row->id_customer;
	$_SESSION['editCart'][$OrderUniqueID]['id_contacts']		 = $row->id_company_person;
	$_SESSION['editCart'][$OrderUniqueID]['hotel_id']			 = $row->id_hotel;
	$_SESSION['editCart'][$OrderUniqueID]['rate_id']			 = $row->id_rate;
	 
}
if($seriesId!='' && $operatorId!='' && $id_company!='' && $NumberOfRow=='0'){
	 $_SESSION['OrderUniqueID'] = rand(0000,9999);
 	 $OrderUniqueID				= $_SESSION['OrderUniqueID'];
	}
	
		
		//echo "<pre>"; print_r($row);			echo "</pre>";
		 $companyId 	= $row->id_company.'|||';
		 $companyperson = $row->id_company_person.'|||';
		 $guestId 		= $row->id_customer.'|||';
		 $id_hotel 		= $row->id_hotel.'|||';
		 
		 
 $_SESSION['editCart'][$OrderUniqueID]['series']['series']		=	$seriesId;				  
 $_SESSION['editCart'][$OrderUniqueID]['series']['operator']		=	$operatorId;

$_SESSION['hotel_id']			=	$serieshotel_id;
$_SESSION['id_company']			=	$id_company;
	
	?>

                       <input type="hidden" value="<?php echo $OrderUniqueID;?>" name="OrderUniqueID" id="OrderUniqueID" />     
                       <div class="form-group col-sm-4">
                           <label for="hotel_id" >Hotel</label>
                           <?php 
				$categoryDropDown = '<select name="hotel_id" id="hotel_id" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError"  onChange="getRoom(this.value,0); ajaxAddRoommsgUpdate(); getRateLetter(this.value); " '.$disabled.'>
									 					  <option value="">Select Hotel</option>';
	  $resCat = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->id_hotel == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
                           <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
                           <input type="hidden" name="hotel_id" id="hotel_id" value="<?php  echo $row->id_hotel; ?>">
                         
                           <?PHP } ?>
                           <span id="hotelError"></span> </div>
                        <div class="form-group col-sm-2">
                           <label for="room_id">Rooms<br />
                          </label>
                           <select class="form-control select2" name="room_id" id="room_id" data-parsley-required data-parsley-errors-container="#roomError">
                            <?php if($id_hotel){
					$resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($id_hotel)."'");
					$roomType ='<option value="0">All Rooms</option>';
					 while($rowRoom = mysqli_fetch_object($resRoom)){	
						$roomType .= '<option value="'.$rowRoom->room_id.'">'.$rowRoom->name.'</option>';
						}
						echo $roomType;
				}else { ?>
                            <option value="" selected="">Please select hotel</option>
                            <?php } ?>
                          </select>
                           <span id="roomError"></span> </div>
                           
                           
                           
                           
                           <div class="container">
                           
                            <div class="form-group col-sm-1">
                               <button id="search_availabilty" name="search_availabilty" type="button" class="btn btn-primary" style="margin-top:25px;" onClick="ajaxCheckAvailability();"> <i class="fa fa-search"></i> Search </button>
                             </div>
                          
                         </div>
                         
                          <div class="form-group col-sm-10">
                           <label for="id_guest" >Guest Name</label>
                           <div class="input-group" id="showGuest">
              <select class="form-control guses" name="id_guest" id="id_guest" data-parsley-errors-container="#guestError" data-parsley-required>
              
                <?php 
									$resCat = selectSql(TBL_CUSTOMER,"where status='1' and type='1' and  id_customer='".$row->id_customer."'",' ORDER BY `first_name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($row->id_customer == $resultCat->id_customer){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->title).' '.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
              </select>
             <div class="input-group-addon guest_open"> <i class="fa fa-plus"></i> </div>
            </div>
                           <span id="guestError"></span> </div>
                           
                           
    
                      
                       
                        
                        
                         
                         
         <section class="content"> 
                           
                           <!-- /.row -->
                           <div class="row">
                            <div class="col-xs-12">
                               <div class="box">
                                <div class="box-header">
                                   <h3 class="box-title">Room Availability</h3>
                                   <div class="box-tools">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                       <li><a href="#">&laquo;</a></li>
                                       <li><a href="#">&raquo;</a></li>
                                     </ul>
                                  </div>
                                 </div>
                                <!-- /.box-header -->
                                <!--<div class="box-body table-responsive no-padding text-center loading" >
                                   <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request"> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
                                 </div>-->
                                <div class="box-body table-responsive no-padding" id="availabilty">
                                   <table class="table table-hover">
                                    <tr>
                                       <th>Room Type</th>
                                       <?php 
				  $checkinDate = date('Y-m-d');
				  echo $availableData = '<th>'.date('d M, Y', strtotime($checkinDate)).'</th>';
				  for($i =0; $i < 6; $i++){
						$checkinDate = date('d M, Y', strtotime('+1 day', strtotime($checkinDate)));
						echo $availableData = '<th>'.$checkinDate.'</th>';
					} ?>
                                     </tr>
                                    <tr align="center">
                                       <td colspan="8" >No Data Available. Please try different Search.</td>
                                     </tr>
                                  </table>
                                 </div>
                                <!-- /.box-body --> 
                              </div>
                               <!-- /.box --> 
                             </div>
                          </div>
                         </section>
                         
                           
           <div class="nav-tabs-custom" style="margin-right: 16px;">
           
     
               
               <div class="box-body">
                <ul class="timeline" style="margin-left: -56px;/*! margin-right: 19px; */">
                   
                   <!-- END timeline item -->
                    <li class="time-label"> <span class="bg-blue"> Room Information </span>
                    <div class="box-tools pull-right">
                    <div id="view"  <?php if($row->id_rate=='0' ){echo 'style="display:none;"';} ?> >
                       <button class="btn btn-danger" type="button" id="view" > <i class="fa fa-eye fa-lg"></i> View</button>
                       </div>
                       <div id="adhol" <?php if($row->id_rate !='0'){echo 'style="display:none;"';} ?>>
                       <button class="pull-left btn btn-success btn-xs" id="adhoc" type="button" onClick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" style="width: 50px;height: 36px;margin-right: 8px"><i class="fa fa-plus-circle" > Add </i></button></div>
                     </div>
                    <div class="form-group col-sm-3 pull-right" >
                       <select class="form-control" name="rate_id" id="rate_id" onChange="ajaxCheckAdogoRateletter();  showRateLetterView(this);" data-parsley-required data-parsley-errors-container="#rate_idError">
                        <?php 
						//`fs_rate_details`.hotel_id='".addslashes($row->id_hotel)."' AND;
			  $rate_level_assgin = selectColumn(TBL_COMPANY,'id_rate_level'," WHERE `id_company` = '".addslashes($row->id_company)."'");
			  
$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id  LEFT JOIN `fs_rate_details` ON `fs_rate`.id=`fs_rate_details`.rate_id  where   `fs_rate_details`.hotel_id='".addslashes($row->id_hotel)."' AND  `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."' and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($row->checkin))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($row->checkout))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($row->checkin))."' and '".date('Y-m-d',strtotime($row->checkout))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($row->checkin))."' and '".date('Y-m-d',strtotime($row->checkout))."')) group by `".TBL_RATE."`.rate_name");
							  if($db->num_rows2($resCat)==0 and $row->id_rate!=''){
									  $planData .= '<option '.$selected.' value="0">ADHOC</option>';
									  }
							  
							  
							  if($db->num_rows2($resCat)){
								  
								  
                               $planData .= '<option '.$selected.' value="0">ADHOC</option>';
                            	
							
							
							
								while($resultCat = $db->fetch_object2($resCat)){
									if($row->id_rate == $resultCat->id){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									$planData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).'</option>';
								}
							  //}elseif(){
								  
								  }else{
							  $planData .= '<option value="" >Rate Letter</option>';
							  
							  }
							 echo $planData; ?>
                      </select>
                       <span id="rate_idError"></span> </div>
                  </li>
                  
                   <li id="DateAdd"> <i class="fa fa-home bg-blue"></i>
                    <div class="timeline-item">
                       <div class="row table-responsive">
                        <table class="table table-hover" id="showRoom">
                           <tr>
                            <th>Room Type</th>
                            <th>Plan</th>
                            <th>Room Quantity</th>
                            <th>Adults/Room</th>
                            <th>Child/Room<br>
                               (0 - 5 yrs)</th>
                            <th>Child/Room<br>
                               (5 - 12 yrs)</th>
                            <th>Tariff. Per Room <br> /Per Night</th>
                            <th>Tax</th>
                           <!-- <th>Inclusive Tax</th>-->
                            <th>Avg. Rate*Night</th>
                            <th><button class="btn btn-danger btn-sm" type="button" id="view" onClick="ajaxRoomRemoveAll();"> <i class="fa fa-close fa-lg"></i> </button></th>
                          </tr>
                           <tr id="addRoommsg" align="center" <?php if($row->id_order != ''){ echo 'style="display:none;"';}  ?>>
                            <td colspan="9"><strong>Please Add Room.</strong></td>
                          </tr>
                           <?php 	
			
			


			
			$sqlOrderDetail = executeSql("Select * from `".TBL_ORDER_DETAIL."` where id_order='".addslashes($row->id_order)."' group by unique_code,room_id,room_quantity,adults,child,rate_plan_id");
			
			
			if(num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= $db->fetch_object2($sqlOrderDetail)){
					
					
			$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where  rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($rowOrderDetail->rate_assign_id)."'  and rd.rate_plan_id='".addslashes($rowOrderDetail->rate_plan_id)."' and rd.rate_id='".addslashes($row->id_rate)."' and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id");	


	if(num_rows($resRoom) >0){

$rowRoom = $db->fetch_object2($resRoom);		
		
		if($rowOrderDetail->adults == '1'){
			$priceValue += $rowRoom->pkg_price;	
			
		}elseif($rowOrderDetail->adults == '2'){
			$priceValue += $rowRoom->pkg_price;	
			$extra_bed_price	=	0;	
		}elseif($rowOrderDetail->adults == '3'){
			$priceValue += $rowRoom->pkg_price+$rowRoom->extra_bed_price;	
			$extra_bed_price	=	$rowRoom->extra_bed_price*1;	
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
			
	}
				$uniqueCode = 'CODE'.rand(0000,9999);				
								
				$start_date		=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$rowOrderDetail->rate_id."'");
				$Newcheckin = $row->checkin;
				$Newcheckout	= $row->checkout;
				$_SESSION['editCart'][$OrderUniqueID]['Newcheckin']	=	$Newcheckin;
				 $query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".addslashes($_SESSION['shop'])."'";
	
	$result14 = executeSql($query14,$link);
	$query14count = mysqli_num_rows($result14);	
	
	$query14data = mysqli_fetch_array($result14);
	$seasonIdnew	= $query14data['id'];	
	 
	 		
$CheckTaxStatusSql	=	selectColumn(TBL_ORDERS,'tax_group_id'," WHERE `id_order` = '".addslashes(encryptor('decrypt',$_SESSION['eId']))."'");

if($CheckTaxStatusSql	== 1){
	
	
				 $resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($rowOrderDetail->hotel_id)."' and  `room_id` = '".addslashes($rowOrderDetail->room_id)."' and  `seasonId` = '".addslashes($seasonIdnew)."'");
				
		$rowTax = $db->fetch_object2($resTax);
		$rowOrderDetail->total_price*$row->no_of_days."tax %= ".$rowTax->tax_room;
					
					
					$roomTax11	=	$rowOrderDetail->total_price*$row->no_of_days*($rowTax->tax_room/100);					
					$TaxInclusiveStatus	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$rowOrderDetail->rate_plan_id."'");
			
				if($TaxInclusiveStatus	==2){		
					$roomTax	+=	$rowOrderDetail->total_price*($rowTax->tax_room/100);
					$_SESSION['editCart'][$OrderUniqueID]['room_tax_price'][$uniqueCode] =  $roomTax;
				}
			
}else{ //New Tax Rules Start =========================

		
		$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
		$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;		
		$uniqueCodeRequest		= $_REQUEST['uniqueCode'];
		
		$price 					= ($rowOrderDetail->total_price);
		
		$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
		
		if(num_rows($resNewTax) >0 ){
				$rowNewTax = $db->fetch_object2($resNewTax);
		
		//echo "New Tax Calculation -Tax %". $rowNewTax->tax_percent.'=='.$rowOrderDetail->total_price."totalPrice= >".round($rowOrderDetail->total_price*($rowNewTax->tax_percent/100));
		
		
				
		$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$rowOrderDetail->rate_plan_id."'");
		
			if($TaxInclusiveStatus1	== '2'  && $TaxInclusiveStatus1	!= '1'   &&  $TaxInclusiveStatus1	!= '3' ){
				
					
				$roomTax	+=	($rowOrderDetail->total_price)*$rowOrderDetail->room_quantity*($rowNewTax->tax_percent/100);
				$_SESSION['editCart'][$OrderUniqueID]['room_tax_price'][$uniqueCode] =  ($rowOrderDetail->total_price)*($rowNewTax->tax_percent/100);
					
			}
			if($TaxInclusiveStatus1	== '1'  && $TaxInclusiveStatus1	!= '2'   &&  $TaxInclusiveStatus1	!= '3' ){	
			
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode]	= 0;
				$SingleRowTaxValue	=0;
				}
							
		}
	
	}//New Tax Rules END 
				
				$totalRoom += $rowOrderDetail->room_quantity;
				$totalPrice += $rowOrderDetail->total_price*$row->no_of_days;		
				$totalPriceTarrif += $rowOrderDetail->original_product_price*$row->no_of_days*$rowOrderDetail->room_quantity;
				$totalPriceFood += $rowOrderDetail->food_price*$row->no_of_days;
				$totalPriceExtra += $rowOrderDetail->extra_price*$row->no_of_days;
				
				
				if($rowOrderDetail->rate_id >'0'){									
					$disabledq = 'disabled="disabled"';					
					}
			
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_ORDER_DETAIL."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where   rd.rate_id='".addslashes($rowOrderDetail->rate_id)."'  and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id ");
		
				if(num_rows($resRoom) >0){	
				
				
				
				
				$StartDateListFor	=strtotime($row->checkin);
				
			
			$totalAdult		+=	$rowOrderDetail->room_quantity*$rowOrderDetail->adults;
			$totalChild += $rowOrderDetail->room_quantity*($rowOrderDetail->child);		
			
			
			$totalInfant += $rowOrderDetail->room_quantity*($rowOrderDetail->infants);
			
			$_SESSION['editCart'][$OrderUniqueID]['RoomUniqueCode'][]	=	$uniqueCode;
			
	for($i=0;$i<$noOfDays;$i++){	


		$UniqueDateFor = date ("d-m-Y", $StartDateListFor); 
		
		
		$_SESSION['editCart'][$OrderUniqueID]['dataValue'][$UniqueDateFor][$uniqueCode] = 	'dateValue|'.$rowOrderDetail->hotel_id.'|'.$rowOrderDetail->room_id.'|'.$rowOrderDetail->rate_id.'|'.$rowOrderDetail->rate_plan_id.'|'.$rowOrderDetail->rate_assign_id.'|'.$rowOrderDetail->type;
		
		
		$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode]=$rowOrderDetail->room_id;
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price'][$UniqueDateFor][$uniqueCode]	= $extra_bed_price;
		$_SESSION['editCart'][$OrderUniqueID]['extra_bed_price_child'][$UniqueDateFor][$uniqueCode]	= $extra_bed_price_child;	
				
				$_SESSION['editCart'][$OrderUniqueID]['room_quantity'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->room_quantity;
				
				$_SESSION['editCart'][$OrderUniqueID]['infant_no'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->infants;
				$_SESSION['editCart'][$OrderUniqueID]['child_no'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->child;
				$_SESSION['editCart'][$OrderUniqueID]['tarrif_price'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->tarrif_price_per_day;				
				$_SESSION['editCart'][$OrderUniqueID]['pkg_extra'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->extra_price;
				$_SESSION['editCart'][$OrderUniqueID]['room_price'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->total_price;
				$_SESSION['editCart'][$OrderUniqueID]['tarrif'][$UniqueDateFor][$uniqueCode] = (($rowOrderDetail->total_price)/$rowOrderDetail->room_quantity);
				$_SESSION['editCart'][$OrderUniqueID]['meal'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->food_price_per_day;
				$_SESSION['editCart'][$OrderUniqueID]['TaxPerdayPerroom'][$UniqueDateFor][$uniqueCode] = $rowOrderDetail->tax_perday_perroom;
				$_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode]	= $rowOrderDetail->rate_plan_id;
				
				$_SESSION['editCart'][$OrderUniqueID]['adult_no'][$UniqueDateFor][$uniqueCode]=$rowOrderDetail->adults;
				
				
		$StartDateListFor	=	strtotime("+1 day", strtotime($UniqueDateFor));
	}
		
			$_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode];	
					$rowRoom = $db->fetch_object2($resRoom);
					$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td><select class="form-control"  name="room_type_id[]" id="room_type_id|'.$uniqueCode.'" data-parsley-required  onchange="newTaxOnChange($(this).attr(\'id\'));  getRateEdit($(this).attr(\'id\'));" >
											  <option value="">Room Type</option>';
										
									
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where rt.status='1' and ahr.hotel_id=".addslashes($rowOrderDetail->hotel_id));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($_SESSION['editCart'][$OrderUniqueID]['room_type_id'][$UniqueDateFor][$uniqueCode] == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select></td>';
			if($rowOrderDetail->rate_id >'0'){									 
			 $availableData .='<input type="hidden" name="room_type_id[]" value="'.$rowOrderDetail->room_id.'|'.$uniqueCode.'">';
			// $availableData .='<input type="hidden" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.round($_SESSION['editCart'][$OrderUniqueID]['tarrif'][$uniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$uniqueCode.'\');" >';		
			}
$availableData .=' <td><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id|'.$uniqueCode.'"  data-parsley-required  onchange="newTaxOnChange($(this).attr(\'id\')); getRateEdit($(this).attr(\'id\'));"  >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['editCart'][$OrderUniqueID]['rate_plan_id'][$UniqueDateFor][$uniqueCode] == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td></strong></td>';
												 		
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="newTaxOnChange($(this).attr(\'id\')); getRateEdit($(this).attr(\'id\'));" >';		
				
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
				<input type="hidden"  id="dataValue" name="dataValue[]" value="dateValue|'.$rowOrderDetail->hotel_id.'|'.$rowOrderDetail->room_id.'|'.$rowOrderDetail->rate_id.'|'.$rowOrderDetail->rate_plan_id.'|'.$rowOrderDetail->rate_assign_id.'|'.$rowOrderDetail->type.'" id="dataValue|'.$uniqueCode.'">			
				  <td><select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="newTaxOnChange($(this).attr(\'id\')); getRateEdit(\'adult_no|'.$uniqueCode.'\');">';
				if($rowOrderDetail->adults == '1' ){$selectedAdultNo1 =  'selected="selected"';}else{$selectedAdultNo1 =''; }
				if($rowOrderDetail->adults == '2' ){$selectedAdultNo2 =  'selected="selected"';}else{$selectedAdultNo2 =''; }
				if($rowOrderDetail->adults == '3' ){$selectedAdultNo3 =  'selected="selected"';}else{$selectedAdultNo3 =''; }
$availableData .='<option value="1" '.$selectedAdultNo1.'>1</option>
				  <option value="2" '.$selectedAdultNo2.'>2</option>                				
				  <option value="3" '.$selectedAdultNo3.'>3</option></select></td>
				<td> <select class="form-control input-sm" name="infant_no[]" id="infant_no|'.$uniqueCode.'" data-parsley-required onchange="newTaxOnChange($(this).attr(\'id\')); getRateEdit($(this).attr(\'id\'));">';
				if($rowOrderDetail->infants == '0' ){$selectedChildNo1 =  'selected="selected"';}else{$selectedChildNo1 =''; }
				if($rowOrderDetail->infants == '1' ){$selectedChildNo2 =  'selected="selected"';}else{$selectedChildNo2 =''; }
				if($rowOrderDetail->infants == '2' ){$selectedChildNo3 =  'selected="selected"';}else{$selectedChildNo3 =''; }
$availableData .=' <option value="0" '.$selectedChildNo1.'>0</option>
				   <option value="1" '.$selectedChildNo2.'>1</option>
				   <option value="2" '.$selectedChildNo3.'>2</option>
                </select></td>				
				  <td> <select class="form-control input-sm" name="child_no[]" id="child_no|'.$uniqueCode.'" data-parsley-required onchange="newTaxOnChange($(this).attr(\'id\')); getRateEdit($(this).attr(\'id\'));">';
				if($rowOrderDetail->child == '0' ){$selectedChildNo1 =  'selected="selected"';}else{$selectedChildNo1 =''; }
				if($rowOrderDetail->child == '1' ){$selectedChildNo2 =  'selected="selected"';}else{$selectedChildNo2 =''; }
				if($rowOrderDetail->child == '2' ){$selectedChildNo3 =  'selected="selected"';}else{$selectedChildNo3 =''; }
$availableData .='<option value="0" '.$selectedChildNo1.'>0</option>
				   <option value="1" '.$selectedChildNo2.'>1</option>
				   <option value="2" '.$selectedChildNo3.'>2</option>
                </select></td>';
				
$availableData .='<td id="trafficprice_'.$uniqueCode.'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$uniqueCode.'" value="'.round((($rowOrderDetail->total_price)/$rowOrderDetail->room_quantity),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="newTaxOnChange($(this).attr(\'id\')); getRateEdit(\'tarrif|'.$uniqueCode.'\');" '.$disabledq.'></td>';


/*$availableData .='<td><input type="text" class="form-control input-sm"  name="meal[]"  id="meal|'.$uniqueCode.'"  value="'.round($_SESSION['editCart'][$OrderUniqueID]['meal'][$uniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'meal|'.$uniqueCode.'\');"></td>';
	*/			
				
		
	$availableData .='<td id="TaxPerdayPerroom_'.$uniqueCode.'"><input type="text" class="form-control input-sm"  name="TaxPerdayPerroom[]"  id="TaxPerdayPerroom|'.$uniqueCode.'"  value="'.$rowOrderDetail->tax_perday_perroom.'" style="width: 80px;" readonly ></td>';

	//$availableData .='<td id="inclusive_tax_'.$uniqueCode.'"><input type="text" class="form-control input-sm"  name="inclusive_tax[]"  id="inclusive_tax|'.$uniqueCode.'"  value="test" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'inclusive_tax|'.$uniqueCode.'\');"  ></td>';
			
							 
$availableData .='<td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.($rowOrderDetail->total_price+($_SESSION['editCart'][$OrderUniqueID]['meal'][$uniqueCode]))*$row->no_of_days.'</strong>&nbsp;&nbsp;';

/*<span class="pricePopUp_open" onclick="CheckRoomPlan(this.id);" onclick="pricePopUp(this.id);  id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>*/

$availableData .='</td> 
<input type="hidden" name="tax_group_id" id="tax_group_id|'.$uniqueCode.'" value="'.$CheckTaxStatusSql.'">
 
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';
					}
			  	$i++;
				echo $availableData;
				}
				
				
				
			
			}		
	
$_SESSION['editCart'][$OrderUniqueID]['totalRoom']= $totalRoom;
$_SESSION['editCart'][$OrderUniqueID]['totalAdult']= $totalAdult;
$_SESSION['editCart'][$OrderUniqueID]['totalChild']= $totalChild;
$_SESSION['editCart'][$OrderUniqueID]['totalInfant']= $totalInfant;
$_SESSION['editCart'][$OrderUniqueID]['totalPrice'] = $totalPrice;

$_SESSION['editCart'][$OrderUniqueID]['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart'][$OrderUniqueID]['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart'][$OrderUniqueID]['finalPrice']  = round((($_SESSION['editCart'][$OrderUniqueID]['totalPrice']-$_SESSION['editCart'][$OrderUniqueID]['discountPrice'])+$_SESSION['editCart'][$OrderUniqueID]['room_tax_price'][$uniqueCode]+$_SESSION['editCart'][$OrderUniqueID]['AdditionalCharges'][$uniqueCode]),0,PHP_ROUND_HALF_UP);
				?>
                         </table>
                      </div>
                   
                      
                       <div class="row"> 
                        <!-- accepted payments column -->
                        
                        
                        
                        
                        
                        <div class="col-sm-7 text-muted well well-sm no-shadow"  style="width:100% !important" >
                          <div style="color:#FF0000;" ><?php echo $row->rate_text; ?> </div>
                           <div style="float: left;width: 100%;">
                            <p class="lead" style="width: 200px;float: left;margin: 0px;">Charges:</p>
                            <button class="pull-left btn btn-success btn-xs" type="button" onClick="ajaxAddothercharges('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" style="margin: 0px;" ><i class="fa fa-plus-circle"></i></button>
                          </div>
                          
                          
                           <p id="addchargesMsg" align="center"></p>
                           <table class="table table-hover" style="margin-bottom:0px;" >
                            <tr>
                               <th style="width: 200px;">Charges</th>
                              
                               <th>Amount</th>
                               <th>Nos</th>
                               <th>Tax %</th>
                               <th>Tax Value</th>
                               <th>Net Value</th>
                             </tr>
                            <?php 
		
		$sqlOtherChargesDetail 		= executeSql("Select * from `".TBL_OTHERCHARGES_DETAIL."` where id_order='".addslashes($row->id_order)."'");
		
		$NUmber 				=	num_rows($sqlOtherChargesDetail);
			
				while($rowOtherChargesDetail	= $db->fetch_object2($sqlOtherChargesDetail)){
				$OtherChargesuniqueCode 		= 'OTHERCHARGE'.rand(0000,9999);
				$_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'][]	= $OtherChargesuniqueCode;
				$_SESSION['editCart'][$OrderUniqueID]['id_othercharges_detail'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->id_othercharges_detail;
				$_SESSION['editCart'][$OrderUniqueID]['charges_description'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_description_id;
				
				$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_tax_percentage;
				$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_tax_value;
				$_SESSION['editCart'][$OrderUniqueID]['charges_net'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_net;
				$_SESSION['editCart'][$OrderUniqueID]['otherChargeType'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_method;
				
				
					$LessOtherChargesTax +=	$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherChargesuniqueCode];
				if($rowOtherChargesDetail->charges_noofdays	==0){
					
					$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherChargesuniqueCode] = 1;
					}else{
					
						
						$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_noofdays;
						}
				
				
				
				
				$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherChargesuniqueCode] = $rowOtherChargesDetail->charges_price;
				
			$availableData = '<tr id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom" >';
			
$availableData .=' <td><input type="text" class="form-control"  name="charges_description|'.$OtherChargesuniqueCode.'" id="charges_description|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['charges_description'][$OtherChargesuniqueCode].'"  placeholder="Charges Description." onKeyUp="calculateOthercharge(\'charges_description|'.$OtherChargesuniqueCode.'\');" data-parsley-required></td>';	

/*$availableData .='<td style="padding: 8px 7px;"><div class="col-sm-3"><select class="form-control" name="otherChargeType|'.$OtherChargesuniqueCode.'" id="otherChargeType|'.$OtherChargesuniqueCode.'" onchange="calculateOthercharge(\'otherChargeType|'.$OtherChargesuniqueCode.'\');" style="width: 100px;" s>
                               <option value="1" '.$selectedCharge1.' >Net</option>
                               <option value="2" '.$selectedCharge2.'>Per Day</option>
                             </select></div></td>';*/
							 
$availableData .='<input type="hidden" class="form-control"  name="id_othercharges_detail|'.$OtherChargesuniqueCode.'" id="id_othercharges_detail|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['id_othercharges_detail'][$OtherChargesuniqueCode].'" >';




$availableData .=' <td ><input type="text" class="form-control"  name="charges_price|'.$OtherChargesuniqueCode.'" id="charges_price|'.$OtherChargesuniqueCode.'" value="'.$rowOtherChargesDetail->charges_price.'"  onKeyUp="calculateOthercharge(\'charges_price|'.$OtherChargesuniqueCode.'\');"></td>';	


$availableData .=' <td  ><div id="otherChargeTypeNoOfDays_'.$OtherChargesuniqueCode.'"><input type="text" class="form-control"  name="otherChargeTypeNoOfDays|'.$OtherChargesuniqueCode.'" id="otherChargeTypeNoOfDays|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherChargesuniqueCode].'"  autocomplete="off" onKeyUp="calculateOthercharge(\'otherChargeTypeNoOfDays|'.$OtherChargesuniqueCode.'\');" ></div>

<input type="hidden" class="form-control"  name="ChargeNoOfDays|'.$OtherChargesuniqueCode.'" id="ChargeNoOfDays|'.$OtherChargesuniqueCode.'" value="'.$noOfDays.'" ></td>';

		

echo $availableData .='<td>
				  <input type="text" class="form-control" id="charges_tax|'.$OtherChargesuniqueCode.'" name="charges_tax|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$OtherChargesuniqueCode].'" onKeyUp="calculateOthercharge(\'charges_tax|'.$OtherChargesuniqueCode.'\');" autocomplete="off" >
                    
                      
                    </td>
				 <td ><input type="text" class="form-control"  name="charges_total|'.$OtherChargesuniqueCode.'" id="charges_total|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherChargesuniqueCode].'"  onKeyUp="calculateOthercharge(\'charges_total|'.$OtherChargesuniqueCode.'\');"></td>
				 
				 <td ><input type="text" class="form-control"  name="charges_net|'.$OtherChargesuniqueCode.'" id="charges_net|'.$OtherChargesuniqueCode.'" value="'.$_SESSION['editCart'][$OrderUniqueID]['charges_net'][$OtherChargesuniqueCode].'"  onKeyUp="calculateOthercharge(\'charges_net|'.$OtherChargesuniqueCode.'\');"></td>
				 
				  <td ><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';?>
                            <?php } ?>
                          </table>
                           <div id="showOtherCharges"></div>
                           
                           
                       
                           
                           
                           
                          
                          
                           
                           <div class="col-sm-12">
                           
                          </div>
                         </div>
                        
                        
                        
                        
                        
                        
                        <div class="col-sm-7 text-muted well well-sm no-shadow"  >
                          
                           
                          
                        
                           
                           
                            <?php 
		$_SESSION['editCart'][$OrderUniqueID]['taxablePrice'] = $row->total_tax-$LessOtherChargesTax;
		$_SESSION['editCart'][$OrderUniqueID]['discountVar']	=	$row->discount_var;
		$_SESSION['editCart'][$OrderUniqueID]['discountType']	=	$row->discount_type;
		?>
                           <p class="lead" style="float: left;width: 100%;margin: 0px;">Discount:</p>
                           <p id="discountMsg" align="center"></p>
                           <p class="col-sm-3" style="margin-top: 10px;">Apply Discount </p>
                           <div class="col-sm-3">
                            <select class="form-control" name="discountType" id="discountType" onChange="discounttype(this.value);">
                               <option value="1" <?php if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '1' ){echo 'selected="selected"';} ?>>Flat</option>
                               <option value="2" <?php if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '2' ){echo 'selected="selected"';} ?>>Percentage</option>
                             </select>
                          </div>
                           <div class="col-sm-4" id="flat" <?php if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '1' ){echo 'style="display:block;"';}else if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '2'){echo 'style="display:none;"';} else  { echo 'style="display:block;"'; } ?>  >
                            <div class="input-group">
                               <div class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat"><i class="fa fa-inr"></i> </button>
                              </div>
                               <!-- /btn-group -->
                               <input type="text" class="form-control" id="flatDiscount" value="<?php if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '1' ){echo $_SESSION['editCart'][$OrderUniqueID]['discountVar']; }?>" autocomplete="off">
                             </div>
                          </div>
                           <div class="col-sm-4" id="percent" <?php if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '2' ){echo 'style="display:block;"';}else {echo 'style="display:none;"';} ?>>
                            <div class="input-group">
                               <input type="text" class="form-control" id="percentDiscount" value="<?php if($_SESSION['editCart'][$OrderUniqueID]['discountType'] == '2' ){echo $_SESSION['editCart'][$OrderUniqueID]['discountVar'];}?>" autocomplete="off">
                               <span class="input-group-btn">
                              <button type="button" class="btn btn-info btn-flat"><i class="fa fa-percent"></i></button>
                              </span> </div>
                          </div>
                           <div class="col-sm-2 ">
                            <button type="button" class="btn btn-primary" onClick="applyDiscount();">Apply</button>
                          </div>
                          
                          
                           
                           <div class="col-sm-12">
                            <?php $row->rate_text; ?>
                          </div>
                         </div>
                           
                      <?php 
					  
					 /* echo "<pre>";
					  print_r($_SESSION);
					  echo "</pre>";*/
					  ?>
                        <!-- /.col -->
                        <div class="col-sm-5">
                           <div class="table-responsive" id="pricingValue">
                            <table class="table" >
                               <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td id="subtotal"><i class="fa fa-inr"></i> <?php echo round($row->subtotal,2); ?></td>
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
                  </li>
                  
                   
                        
                   <!-- timeline item -->
                   <li class="time-label"> <span class="bg-yellow"> Payment Information </span> </li>
                   <li> <i class="fa fa-credit-card bg-yellow"></i>
                    <div class="timeline-item">
                       <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="booking_status" >Booking Status</label>
                           <?php 
								$bookingStatusDropDown = '<select name="booking_status" id="booking_status" class="form-control select2" data-parsley-required data-parsley-errors-container="#bookingStatusError" onchange="showPaymentDate(this.value); ViewPaymentDate(this.value);">
									 	 <option value="">Select Booking Status</option>';
											  $resCat = selectSql(TBL_HTL_BOOKING_STATUS," where status='1'",' ORDER BY `id`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->booking_status == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$bookingStatusDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $bookingStatusDropDown .= '</select>';

									 ?>
                           <span id="bookingStatusError"></span> </div>
                          
                           
                            <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit' && $row->booking_status==2){?>
  
                           <div class="form-group col-sm-4"  id="paymentDate">
                      <label for="payment_date" >Date</label>
                      <div class="input-group">
                        <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
						
                        <input type="text" class="form-control" id="payment_date" name="payment_date" automcomplete="off" value="<?php echo date('d-m-Y',strtotime($row->payment_date)); ?>" data-parsley-required>
                      </div>
                    </div>
                    <?php }else{ ?>
                     <div id="paymentStatusDate"></div>
                   <?php } ?>
                       
                         
                         
                         
                        <div class="form-group col-sm-4" <?php if($row->booking_status!=4){echo 'style="display:none;"';} ?> id="CancellationReason">
                           <label for="CancellationReason_status"  style="width:210px">Cancellation Reason</label>
                           <?php 
								$paymentStatusDropDown = '<select name="CancellationReason_status" id="CancellationReason_status" class="form-control select2" style="width:100%">
									 	 <option value="">Select Cancellation Status</option>';
											  $resCat = selectSql(TBL_CANCEL_MASTER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->cancellation_reason_id == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$paymentStatusDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $paymentStatusDropDown .= '</select>';
									
									 ?>
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="payment_status" >Payment Status</label>
                           <?php 
								$paymentStatusDropDown = '<select name="payment_status" id="payment_status" class="form-control select2" data-parsley-required data-parsley-errors-container="#paymentStatusError">
									 	 <option value="">Select Payment status</option>';
											  $resCat = selectSql(TBL_ORDER_STATE,"where id_lang='1' AND status='1' ",' ORDER BY `id_order_state`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->payment_status == $resultCat->id_order_state){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$paymentStatusDropDown .= '<option '.$selected.' value="'.$resultCat->id_order_state.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $paymentStatusDropDown .= '</select>';
									
									 ?>
                                     
                           <span id="paymentStatusError"></span> </div>
                       
                    
                      </div>
                       <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="amount_received" >Amount Received</label>
                           <div class="input-group">
                            <div class="input-group-addon"> <i class="fa fa-inr"></i> </div>
                            <input type="text" name="amount_received" id="amount_received" class="form-control" placeholder="Enter amount received" data-parsley-required data-parsley-type="digits"  automcomplete="off" value="<?php echo round($row->amount_received,2);  ?>">
                          </div>
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="payment_reference" >Payment Reference No.</label>
                           <input type="text" name="payment_reference" id="payment_reference" class="form-control" placeholder="Enter Payment Reference No."  automcomplete="off"  value="<?php echo $row->payment_reference;?>">
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="payment_remarks" >Remarks</label>
                           <input type="text" name="payment_remarks" id="payment_remarks" class="form-control" placeholder="Enter Remarks"  automcomplete="off"  value="<?php echo $row->payment_remarks;?>">
                         </div>
                      </div>
                     </div>
                  </li>
                   <!-- END timeline item -->
                   
                   <li class="time-label" id="miscInfo"> <span class="bg-green"> Miscellaneous Information </span> </li>
                   <!-- timeline item -->
                   <li id="miscInfoDisplay"> <i class="fa fa-th-large bg-green"></i>
                    <div class="timeline-item">
                       <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="arrival_time" >Arrival Time</label>
                           <div class="input-group">
                            <div class="input-group-addon"> <i class="fa fa-clock-o"></i> </div>
                            <input type="text" class="form-control pickertime" id="arrival_time" name="arrival_time" automcomplete="off" readonly >
                          </div>
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="arrival_from" >Arrival From</label>
                           <input type="text" name="arrival_from" id="arrival_from" class="form-control" placeholder="Enter Arrival Place" automcomplete="off" value="<?php echo $row->arrival_from;?>">
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="departing_to" >Departing To</label>
                           <input type="text" name="departing_to" id="departing_to" class="form-control" placeholder="Enter Departing Place" automcomplete="off" value="<?php echo $row->departing_to;?>">
                         </div>
                      </div>
                       <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="pickup" >Pickup Required</label>
                           <select name="pickup" id="pickup" class="form-control">
                            <option value="">Select</option>
                            <option value="No" <?php if($row->pickup == 'No'){ echo 'selected="selected"';} ?>>No</option>
                            <option value="Yes" <?php if($row->pickup == 'Yes'){ echo 'selected="selected"';} ?>>Yes</option>
                          </select>
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="pickup_details" >Pickup Details</label>
                           <input type="text" name="pickup_details" id="pickup_details" class="form-control" placeholder="Enter Pickup Details" automcomplete="off" value="<?php echo $row->pickup_details;?>">
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="travel" >Mode of Travel</label>
                           <select name="travel" id="travel" class="form-control">
                            <option value="">Select Mode of Travel</option>
                            <option value="Surface" <?php if($row->travel == 'Surface'){ echo 'selected="selected"';} ?>>By Surface</option>
                            <option value="Train" <?php if($row->travel == 'Train'){ echo 'selected="selected"';} ?>>By Train</option>
                            <option value="Air" <?php if($row->travel == 'Air'){ echo 'selected="selected"';} ?>>By Air</option>
                          </select>
                         </div>
                      </div>
                       <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="booking_hrough" >Booking Through</label>
                           <select name="booking_hrough" id="booking_hrough" class="form-control">
                            <option value="">Select Booking Through</option>
                            <option value="Mail" <?php if($row->booking_hrough == 'Mail'){ echo 'selected="selected"';} ?>>Mail</option>
                            <option value="Phone" <?php if($row->booking_hrough == 'Phone'){ echo 'selected="selected"';} ?>>Phone</option>
                          </select>
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="remarks" >Special Requests</label>
                           <textarea class="form-control" name="requests" id="requests" rows="1" placeholder="Enter requests (if any)" automcomplete="off"><?php echo $row->requests;?></textarea>
                         </div>
                        <div class="form-group col-sm-4">
                           <label for="other_reference" >Other Reference No.</label>
                           <input type="text" name="other_reference" id="other_reference" class="form-control" placeholder="Enter Other Refernce No." value="<?php echo $row->other_reference;?>" automcomplete="off">
                         </div>
                      </div>
                       <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="segment" >Segment</label>
                           <?php 
				
								$segmentDropDown = '<select name="segment" id="segment" class="form-control select2" data-parsley-required data-parsley-errors-container="#segmentError">
									 	 <option value="">Select Segment</option>';
											  $resCat = selectSql(TBL_SEGMENT_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $row->segment_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$segmentDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $segmentDropDown .= '</select>';
									
									 ?>
                           <span id="segmentError"></span> </div>
                        <?php                  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
                        <?php                //  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
                        <div class="form-group col-sm-4">
                           <label for="segment" >Amendment In</label>
                           <?php 
					
								$segmentDropDown = '<select name="amendment_remarks_id" id="amendment_remarks_id" class="form-control select2" data-parsley-required data-parsley-errors-container="#amendment_remarksError">
									 	 <option value="">Select Amendment</option>';
											  $resCat2 = selectSql(TBL_AMENDMENT_REMARKS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `am_id`');
											  if(num_rows($resCat2)){
											  	while($resultCat2 = $db->fetch_object2($resCat2)){
												
													if($row->amendment_remarks_id	== $resultCat2->am_id){
														$selected2 = 'selected="selected"';
													}else{
														$selected2 = '';
													}
													$segmentDropDown .= '<option   '.$selected2.' value="'.$resultCat2->am_id.'">'.ucfirst($resultCat2->name).'</option>';
												}
											  }
											 	echo $segmentDropDown .= '</select>';
									
									 ?>
                           <span id="amendment_remarksError"></span> </div>
                        <?php } ?>
                       
                      </div>
                     </div>
                  </li>
                   <!-- END timeline item --> 
                   <!-- timeline time label -->
                   <li class="time-label"> <span class="bg-gray"> <?php echo date("d M, Y"); ?> </span> </li>
                   <!-- /.timeline-label --> 
                   <!-- timeline item -->
                 </ul>
                 
                  
   
   
                <?php if($row->date_created){?>
                <div class="clearfix"></div>
                <div class="form-group col-sm-4">
                   <label for="date_created">Date Created</label>
                   <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">
                 </div>
                <div class="form-group col-sm-4">
                   <label for="last_modified">Last Updated</label>
                   <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
                 </div>
                <div class="form-group col-sm-4">
                   <label for="last_modified_by">Last Updated By</label>
                   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                   <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">
                 </div>
                <?php } ?>
              </div>
              <?php // } ?>
   
   <div class="box-body box-primary" id="SeriesBookingMasterDetail"> </div>
   
              
               <!-- /.box-body -->
               
             </form>
          </div>
           <!-- /.box --> 
       
       <!-- /.row --> 
         
						 
                           <?php 
						   
						   			   	   		
	//}else {
	//echo '<div style="text-align:center;"><br><br><br><br><br>Series Booking Is Not Available Please Select Different Series Name And Operator Name </div>';
	//}
//}else{
//echo '<div style="text-align:center;"><br><br><br><br>Please Select Operator Name </div>';

//}
?><!--
Below Code is added by Hitesh Aloney on 10-08-2018 and uncommented on 13-09-2018
This code check the credit allowed or not option on page load 
-->
<script type="text/javascript">
  var cmpId = document.getElementById("id_company").value; 
 	
  function checkCreditFirst(id_comp){
    var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			var resultArray = this.responseText.split('&&&&');			
          document.getElementById("getcredit_value").innerHTML = resultArray['1'];
        }
      };
      xhttp.open("GET", "ajax/ajaxgetcreditvalue.php?id_company="+id_comp, true);
      xhttp.send();
  }

  checkCreditFirst(cmpId);
</script>
<!-- Above Code Ends Here-->
<script>


function calculateOthercharge(id){
var tot = 0;
var data = new Array();
value= id.split('|');	
	mainId = value[0];
	dateId = value[1];
	
var otherChargeType 		= parseFloat($('#otherChargeType\\|'+dateId).val());
var charges_price 			= (isNaN(parseFloat($('#charges_price\\|'+dateId).val())) ? 0 : parseFloat($('#charges_price\\|'+dateId).val()));
var charges_tax 			= (isNaN(parseFloat($('#charges_tax\\|'+dateId).val())) ? 0 : parseFloat($('#charges_tax\\|'+dateId).val()));
var charges_total 			= (isNaN(parseFloat($('#charges_total\\|'+dateId).val())) ? 0 : parseFloat($('#charges_total\\|'+dateId).val()));
//var charges_tax 			= parseFloat($('#charges_tax\\|'+dateId).val());
//var charges_total 		= parseFloat($('#charges_total\\|'+dateId).val());
var charges_net 			= parseFloat($('#charges_net\\|'+dateId).val());
var charges_description 	= $('#charges_description\\|'+dateId).val();


 var otherChargeTypeNoOfDays			= $('#otherChargeTypeNoOfDays\\|'+dateId).val();
var totalPrice = parseFloat($('#totalPrice').val());

if(charges_tax !== null && charges_tax !== '') {

var total = parseFloat((charges_price*charges_tax)/100)*otherChargeTypeNoOfDays;
}
$('#charges_total\\|'+dateId).val(total);

var netamount = +total + +charges_price*otherChargeTypeNoOfDays;

$('#charges_net\\|'+dateId).val(netamount);
var OrderUniqueID = $("#OrderUniqueID").val();

$.ajax({
	    type: "GET",
	  	url: 'ajax/ajaxUpdateAdditionalChargesEditPage.php',
    	data: 'discount=apply'+'&charges_total='+total+'&otherchagersid='+dateId+'&charges_tax='+charges_tax+'&charges_price='+charges_price+'&charges_description='+charges_description+'&charges_net='+charges_net+'&otherChargeType='+otherChargeType+'&otherChargeTypeNoOfDays='+otherChargeTypeNoOfDays+'&OrderUniqueID='+OrderUniqueID, 
    cache: false,
	   
	   success: function (result) {
		 var  resultArray = result.split('|||');
		// $('#discount').html(resultArray['0']);		
		//alert(result);
		 $('#addchargesvalue').html(resultArray['0']);	
		 $('#totalPrice').html(resultArray['1']);	
		 $('#balance').html(resultArray['3']);			
		 $('#tax').html(resultArray['2']);	
		 var  resultArray1 = resultArray['4'].split('###');
		 
		 $('#otherChargeTypeNoOfDays_'+resultArray1['1']).html(resultArray1['0']);	
		// $('#TaxPerdayPerroom_'+uniqueId).html(resultArray['1']);	 	
	/*	// $('#balance').html(resultArray['3']);
		 var  resultArray1 = resultArray['4'].split('###');
		$('#otherChargeTypeNoOfDays|'+resultArray1['1']).html(resultArray['3']);*/	  		
		}
	});
 
}





//window.onbeforeunload = function() { return "Your work will be lost."; };
function discounttype(discountType) {
	if(discountType == '1' ){
		$("#flat").css('display', 'block');
		$("#percent").css('display', 'none');
	}else{
		$("#flat").css('display', 'none');
		$("#percent").css('display', 'block');
	}
}
function applyDiscount() {
var discountType = $('#discountType').val();
	if(discountType == '1' ){
		var discountVar = $('#flatDiscount').val();
	}else{
		var discountVar = $('#percentDiscount').val();
	}
var OrderUniqueID = $("#OrderUniqueID").val();
 $.ajax({
	   type: "GET",
	   url: 'ajax/ajaxUpdateDiscountEditPage.php',
	   data: 'discount=apply'+'&discountType='+discountType+'&discountVar='+discountVar+'&OrderUniqueID='+OrderUniqueID, 
	   success: function (result) {
		 var  resultArray = result.split('|||');	
		 $('#discount').html(resultArray['0']);	
		 $('#totalPrice').html(resultArray['1']);				
		 $('#discountMsg').html(resultArray['2']);	
		 $('#balance').html(resultArray['3']);	  
		
		}
	});

}


/*function getRateLetter(){		
	
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company, 
		   success: function (result) {			  	    
				$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();										
			}
		})
}*/
function changeEditData(){		
	
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company, 
		   success: function (result) {			  	    
				//$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();										
			}
		})
}




function changePaymentDate(startDate){
	
	var paymentDate = moment(startDate, "YYYY-MM-DD");
	paymentDate.subtract(45, 'days');
	
	var d= moment();
	$('#payment_date').datepicker('destroy');
	if(paymentDate < d){
	$('#payment_date').val(d.format('DD-MM-YYYY'));
	
	}else{
	$('#payment_date').val(paymentDate.format('DD-MM-YYYY'));
	}
	$("#payment_date").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : new Date(),
	maxDate : new Date(startDate),
	});

}


$("#view").click(function (){
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetPlanDetails.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company+'&rate_id='+rate_id+'&hotel_id='+hotel_id, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
				//$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	
})



function ajaxAddRoom(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddRoomEditPage.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type+'&OrderUniqueID='+OrderUniqueID, 
		   success: function (result) {					
				resultArray = result.split('|||');					
					$('#showRoom').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();				
			}
		})

}

function ajaxAddothercharges(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddOtherChargesEditPage.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type+'&OrderUniqueID='+OrderUniqueID, 
		   success: function (result) {					
				resultArray = result.split('|||');					
					$('#showOtherCharges').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();				
			}
		})

}

function ajaxRoomRemove(uniqueCode){	
var OrderUniqueID = $("#OrderUniqueID").val();
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateSessionBookingEditPage.php',
			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode+'&OrderUniqueID='+OrderUniqueID, 
			   success: function (result) {	
			   resultArray = result.split('|||');			
				 $('#'+uniqueCode).remove();
				 	if(resultArray['1']=='removeroomLimitMsg'){
						$('#roomLimitMsg').css('display', 'none');	
					}
					if(resultArray['2']=='roomLimitMsgRoomType'){
						$('#roomLimitMsgRoomType').css('display', 'none');	
					}
					
					$('#pricingValue').html(resultArray['2']);
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
					
					
				}
		});
}
function ajaxOtherChargesRemove(uniqueCode){
	var OrderUniqueID = $("#OrderUniqueID").val();
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateSessionOtherChargesEditPage.php',
			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode+'&OrderUniqueID='+OrderUniqueID, 
 success: function (result) {	
			   resultArray = result.split('|||');			
				 $('#'+uniqueCode).remove();
				 	if(resultArray['1']=='removeroomLimitMsg'){
						$('#roomLimitMsg').css('display', 'none');	
					}
					if(resultArray['2']=='roomLimitMsgRoomType'){
						$('#roomLimitMsgRoomType').css('display', 'none');	
					}
					$('#pricingValue').html(resultArray['0']);
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
					
					
				}
		});
}


function ajaxRoomRemoveAll(){	
	var rate_id = $("#rate_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();
	//
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxUpdateSessionBookingEditPage.php',
   data: 'remove=removeAll'+'&rate_id='+rate_id+'&OrderUniqueID='+OrderUniqueID, 
   success: function (result) {	
   resultArray = result.split('###');
   if(resultArray[1]!=0){	   			
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
   }else{
	   //ajaxAdogaRoomRemoveAll();
		resultArray2 = result.split('|||');
		
		//resultArrayKey = resultArray2.split('%^&');
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
		
		var len = resultArray2.length;
		for (var i = 1; i < len; i++) {
			resultArrayKey = resultArray2[i].split('%^&');	
			resultArrayKeyRateId = resultArrayKey['1'].split('###');   
			$('#trafficprice_'+resultArrayKeyRateId['0']).html(resultArrayKey['0']);	
		
		}
		
		
				
				
				
	   }
		
	}
	})
	
}

function ajaxCheckAdogoRateletter(){	
	var rate_id = $("#rate_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();
	

 $.ajax({
   type: "GET",
   url: 'ajax/ajaxCheckAdogoSericeRateletter.php',
   data: 'remove=removeAll'+'&rate_id='+rate_id+'&OrderUniqueID='+OrderUniqueID, 
   success: function (result) {	
    	
   resultArray = result.split('###');
   if(resultArray[1]!=0){	  
  		
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
   }else{   
	   
	   //ajaxAdogaRoomRemoveAll();
		resultArray2 = resultArray[0].split('|||');
		resultArray2 = resultArray2.filter(Boolean);	
		var len = resultArray2.length;		
		for (var i = 0; i < len; i++) {		
			resultArray3 = resultArray2[i].split('&&&&');		
			$('#trafficprice_'+resultArray3[0]).html(resultArray3[1]);	
		}
			
			
	   }
		
	}
	})
	
}
function ajaxAdogaRoomRemoveAll(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAdogoUpdateSessionBookingEditPage.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 
		   success: function (result) {					
				resultArray = result.split('|||');	
				
				$('#trafficprice_'+resultArray['2']).html(resultArray['1']);				
					/*$('#showOtherCharges').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();*/				
			}
		})

}






$("#payment_date").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : new Date(),
	maxDate: new Date('<?php echo date('Y-m-d',strtotime($row->checkin) ); ?>')
});


//////////////////////////////save price popup form common//////////////////////////////////////////////////////////


function pricePopUp(id){
	var Id = id.split('_');
	var uniqueId= Id[1];
	$('#uniqueCode').val(uniqueId);
	
}
function savepricePopUpform(){
	var uniqueCode = $("#uniqueCode").val();
	var dataValue = $('#dataValue'+'\\|'+uniqueCode).val();		
	var form=$("#pricePopUpform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSavePriceEdit.php',
	   data: form.serialize()+'&dataValue='+dataValue, 
	   success: function (result) {
	    $('#pricePopUp').popup('hide');
		$("#pricePopUpform")[0].reset();
		 alert('Price has been updated.');
		resultArray = result.split('|||');	
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
						$('#roomLimitMsgRoomType').css('display', 'block');
						$('#roomLimitMsgRoomType').html(resultArray['1']);
					
					$('#price_'+uniqueCode).html(resultArray['2']);
					$('#pricingValue').html(resultArray['3']);	
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();						
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}




function MultiPlanSelect(uniqueCode){
	
 var form1=$("#availabiltyForm");	
 var form2=$("#addRoomForm");
 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetMultiPlanDetails.php',
		   data: dataString+'&uniqueCode='+uniqueCode, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			 transition: 'all 0.3s',
           			 autoopen: true,            
        		});
				 //$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	
}

 
function getRateEdit(uniqueCode){	

	var arrayCode = uniqueCode.split("|");
	var uniqueId = arrayCode['1'];
	
	
	var eId = $('#eId').val();
	var room_quantity = $('#room_quantity\\|'+uniqueId).val();
	var dataValue = $('#dataValue\\|'+uniqueId).val();
	var adult_no = $('#adult_no\\|'+uniqueId).val();
	var infant_no = $('#infant_no\\|'+uniqueId).val();
	var child_no = $('#child_no\\|'+uniqueId).val();
	var uniqueCode = $('#uniqueCode\\|'+uniqueId).val();
	var room_type_id = $('#room_type_id\\|'+uniqueId).val();
	var rate_plan_id = $('#rate_plan_id\\|'+uniqueId).val();
	var reservation_date = $("#reservation_date").val();
	var meal = $('#meal\\|'+uniqueId).val();
	var tarrif = $('#tarrif\\|'+uniqueId).val();
	var inclusive_tax = $('#inclusive_tax\\|'+uniqueId).val();
	var OrderUniqueID = $("#OrderUniqueID").val();


	   	
	 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxupdateRoomDetailsEditPage.php',
			   data: 'dataValue='+dataValue+'&room_quantity='+room_quantity+'&adult_no='+adult_no+'&child_no='+child_no+'&infant_no='+infant_no+'&uniqueCode='+uniqueCode+'&reservation_date='+reservation_date+'&rate_plan_id='+rate_plan_id+'&room_type_id='+room_type_id+'&tarrif='+tarrif+'&meal='+meal+'&inclusive_tax='+inclusive_tax+'&eId='+eId+'&OrderUniqueID='+OrderUniqueID, 
			   success: function (result) {
			   	resultArray = result.split('|||');						
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
						$('#roomLimitMsgRoomType').css('display', 'block');

						
						if(resultArray['1']  === undefined || resultArray['1'] == 0){
							//$('#trafficprice_'+uniqueId).html(resultArray['1']);
					$('#TaxPerdayPerroom_'+uniqueId).html(resultArray['2']);					
					$('#price_'+uniqueId).html(resultArray['3']);					
					$('#pricingValue').html(resultArray['4']);	
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
							
						}else{
							
						$('#trafficprice_'+uniqueId).html(resultArray['1']);
					$('#TaxPerdayPerroom_'+uniqueId).html(resultArray['2']);					
					$('#price_'+uniqueId).html(resultArray['3']);					
					$('#pricingValue').html(resultArray['4']);	
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();	
							
							}
										
				   }
				})	
	
				
}

 </script>