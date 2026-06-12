<?php include_once("../../config/auto_loader.php");

 $resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
 $rowShop = $db->fetch_object2($resShop);
 
 $sqlOrder = executeSQl("SELECT `".TBL_ORDERS."`.* FROM `".TBL_ORDERS."`  WHERE `".TBL_ORDERS."`.`id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'"); 
 $rowOrder = $db->fetch_object2($sqlOrder);
 //test;
 
?> 
<?php $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($rowOrder->id_customer)."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS,"where status='1' AND id='".$rowOrder->id_hotel."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($rowOrder->id_company_person)."'",''); 
		  $resultContact = $db->fetch_object2($resContact); ?>
<?php $resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($rowOrder->id_company)."'",''); 
		  $resultCompany = fetch_object($resCompany); 
		  $CompanyName	=	$resultCompany->name
?>


<?php 

$content = '<style>



.table-bordered {
    border: 1px solid #000;
}
.table {
    margin-bottom: 18px;
    max-width: 80%;
    width:100%;
} 
table {
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border: 1px solid #000;
	float:left;
	margin-right:20px;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000;
    font-size: 0.90em;
    padding: 7px !important;
	float:left;
	margin-right:20px;
}
.tdrightalign{
float: left;
text-align:right;
margin-right:49px !important;

}</style>';
if(addslashes($_SESSION['shop'])==1){
	
	$ShopShortCode	=	'WH';
	}
$content .= '<table class="table">
						<tr>
						  <td width="60%">
							<address>
						 <img src="../../uploaded_files/shop/'.$rowShop->image.'" class="img-responsive" alt="logo" title="logo" />
							</address>
						 	</td>
						   <td width="40%" >
							<address><strong>  '.$ShopShortCode.' '.$resultHotelDetail->name.'</strong><br>'.
								$resultHotelDetail->address.'<br>'.
								$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.'<br>'.
								selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->state)."'").', India <br>
								<strong>Phone :</strong> '.$resultHotelDetail->phone1.'<br>
								<strong>Email :</strong> '.$resultHotelDetail->email.'<br>
								<strong>Website: </strong>'.$rowShop->website_url.'
							</address>
						 </td>
						<tr>
					</table>
       <hr>'; 
	?>
<?php 


$Booking_Status =selectColumn(TBL_HTL_BOOKING_STATUS,'id'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");

if($Booking_Status	!=	'2'){

$BookingStatus	=	selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");
$BookingStatus  = '<b>Booking Status: '.$BookingStatus.' </b>';
}else{

$BookingStatus	=	selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");
$BookingStatus 	= '<b>Booking Status : '.$BookingStatus.'<br>Time Limit Till: '.dateformat_date($rowOrder->payment_date).'</b>';
}


$AmendmentCount	= $rowOrder->code;
			
			if($AmendmentCount >0){					
				$AmendmentTotalCount	= '-'.$rowOrder->code;
				}	
				
$content .= '<table class="table" border="0">
						<tr style="background-color: #4f81bc;color:#fff;">
						  <td width="40%" style="color:#fff;">'.$BookingStatus.'</td>
						 	
						   <td width="40%" style="color:#fff;">
							<b>Reservation ID# '.$rowOrder->reference.$AmendmentTotalCount.'</b>
						 </td>
						 		  <td style="text-align:right;color:#fff;">
							<b>Booking Date: '.dateformat_date($rowOrder->invoice_date).'</b>
						 </td>
						</tr></tr>';
						
						if($rowOrder->amendment_remarks_id>0){
		$content .='<tr style="text-align:center;">
						<td colspan="3">  Amendment In : '.selectColumn(TBL_AMENDMENT_REMARKS,'name'," WHERE `am_id` = '".addslashes($rowOrder->amendment_remarks_id)."'").'       
								  </td>

		
		</tr>'; }
						$content .=	'<tr><td colspan="3">Dear ';
						if($rowOrder->id_company_person != 0){
						
							if($resultContact->first_name!='self'){
								$content .= $resultContact->title.' '.$resultContact->first_name.' '.$resultContact->last_name.",<br/>"; 
								$content .=	$CompanyName;
							}else{
								
								$content .= ' Sir/Madam,'; 
								}
						
						}else{
						$content .= ' Sir/Madam,'; 
						}
		$content .=	'</td>';
		if($rowOrder->type=='L'){
		
					$content .=	'<tr>
					
						<td colspan="3">  Greetings !!!<br><br>       
							We are delighted to provide you with the Booking status and summary of your booking mentioned below:<br><br>	  </td>
					</tr>'; 
		
			
		}else{
		
		
					$content .=	'<tr>
						<td colspan="3">  Greetings !!!<br><br>       
							We are delighted to provide you with the room reservation status and summary of your booking mentioned below:<br>	  </td>
					</tr>'; 
					
		}
	?>	
<?php 
$content .= '<tr>
			   <td width="100%" colspan="3" style=" font-size:15px;"><b>Guest Details</b><br>';
		if($rowOrder->other_reference ==''){
		
				$content .= '<address>
					 <strong>'.$rowGuestDetail->title.' '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>';
					if($rowGuestDetail->address!=''){ 
					$content .=$rowGuestDetail->address;
					}
					if($rowGuestDetail->city!=''){
					$content .=	$rowGuestDetail->city;
					}
					selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($rowGuestDetail->id_state)."'").', '.selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".addslashes($rowGuestDetail->id_country)."'").' '.$rowGuestDetail->postcode.'<br>';
					if($rowGuestDetail->mobile!=''){
					
					$content .='<strong>Phone</strong> : '.$rowGuestDetail->mobile.'<br>';
					}
					if($rowGuestDetail->email!=''){
					
					$content .='<strong>Email</strong> : '.$rowGuestDetail->email.'<br><br>';
					}
					
				$content .='</address>';
			}else{
			$content .= '<address>
			 <strong>'.$rowGuestDetail->title.' '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>
			 			 
			<strong>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes($rowOrder->id_company)."'").'</strong> (Ref '.$rowOrder->other_reference.')<br>
					
				</address>';
			
			}
				
			 $content .= '</td>
			</tr>
		</table>
      	 <hr>'; 
	?>

<?php 
if($rowOrder->type=='L'){

$content .= '<table class="table">
						<tr align="middle" style="background-color: #4f81bc;color:#fff; font-color:#fff; font-color:#fff;">
						   <th width="15%" style="color:#fff;"><b>Booking Date</b></th>
						   <th width="25%" style="color:#fff;"><b>Pax Details</b></th>
						    <th width="25%" style="color:#fff;"><b>Item Description</b></th>
						   <th width="25%" style="color:#fff;"><b>Price/Pax</b></th>
						   
						   <th width="20%" style="color:#fff;"><b>Mode of Payment / Status</b></th>							
						</tr>
						<tr>
						   <td>'.dateformat_date($rowOrder->checkin).'</td>
						   <td>Adults-'.$rowOrder->total_adults.' | Child-'.$rowOrder->total_child.' | Infants-'.$rowOrder->total_infants.'</td>
						  <td>'.selectColumn(TBL_MEALS_MASTER,'name'," WHERE `id` = '".addslashes($rowOrder->lunch_type)."'").'</td>
						   <td>INR '.$rowOrder->tarrif_price.' </td>
						   
						   <td>'.selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".addslashes($rowOrder->payment_status)."'").'</td>						
						<tr>
					</table>
      	 <hr>'; 
}else{
$content .= '<table class="table" style="margin:0px !important;text-align:center;" width="800">
						<tr>
						  <td>
							<b>Booking Summary </b>
						 	</td>
						   
						</tr>
					</table>
       ';
$content .= '<table class="table">
						<tr align="middle" style="background-color:#4f81bc;color:#fff;font-color:#fff;">
						   <th width="15%" style="color:#fff;"><b>Checkin Date</b></th>
						   <th width="15%" style="color:#fff;"><b>Checkout Date</b></th>
						   <th style="color:#fff;text-align:center;"><b>Total Rooms</b></th>
						   <th width="25%" style="color:#fff;text-align:center;"><b>Total Pax</b></th>
						   
						   <th style="color:#fff;text-align:center;"><b>Total Nights</b></th>
						   <th width="20%" style="color:#fff;text-align:center;"><b>Mode of Payment / Status</b></th>							
						</tr>
						<tr>
						   <td>'.dateformat_date($rowOrder->checkin).'</td>
						   <td>'.dateformat_date($rowOrder->checkout).'</td>
						   <td style="text-align:center;">'.round($rowOrder->total_products).'</td>
						   <td>Adults-'.$rowOrder->total_adults.' | Child-'.$rowOrder->total_child.' | Infants-'.$rowOrder->total_infants.'</td>
						   
						   <td style="text-align:center;">'.$rowOrder->no_of_days.'</td>
						   <td>'.selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".addslashes($rowOrder->payment_status)."'").'</td>						
						<tr>
					</table>';
      	  
		 
		 
		 if($rowOrder->payment_reference !=''){					
 $content .= "<b>Payment Details:</b> ".$rowOrder->payment_reference.'<br> ';
					}
					
				$content .='<hr>';	
	$content .= '<table class="table" style="margin:0px !important;text-align:center;" width="800">
						<tr>
						  <td>
							<b>Booking Details </b>
						 	</td>
						   
						<tr>
					</table>
       '; 
	   ?>
	
 <?php $content .='
            <table class="table table-striped">
              <thead>
                <tr  align="middle" style="background-color: #4f81bc;color:#fff;">
                  <th width="8%" style="color:#fff;">S. No.</th>             
				  <th style="color:#fff;text-align:center;">Room Type</th>
				  <th width="10%" style="color:#fff;text-align:center;">Room </th>
				  <th width="25%" style="color:#fff;text-align:center;">Pax / Room</th>
				  <th style="color:#fff;text-align:center;">Rate / Night	</th>				  
				  <th style="color:#fff;text-align:center;">Room Total</th>
                </tr>
              </thead>
              <tbody>';
			$counter = '1';
			$i=0;
			$sqlOrderDetail = executeSQl("SELECT * FROM `".TBL_ORDER_DETAIL."`  WHERE `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' group by unique_code,room_id,room_quantity,adults,child,rate_plan_id ");
			
			while($rowOrderDetail=$db->fetch_object2($sqlOrderDetail)){
				//$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($rowOrderDetail->rate_assign_id)."'   and rd.rate_id='".addslashes($rowOrderDetail->rate_id)."' and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id");	
				
				$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_ORDER_DETAIL."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where   rd.rate_id='".addslashes($rowOrderDetail->rate_id)."'  and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id ");
				
				
			if(num_rows($resRoom) >0){
					$rowRoom = $db->fetch_object2($resRoom);
					
		 
		  
		 if($rowOrderDetail->rate_plan_id >0){
		$resRatePlan = selectSql(TBL_RATE_PLAN,"where id='".addslashes($rowOrderDetail->rate_plan_id)."'",''); 
		  $resultRatePlan = fetch_object($resRatePlan);
		  
		   
		 
			if($resultRatePlan->name =='EP'){
		   $remarks	=	'Room Only';
		  }else{
		 
		  $remarks	=	$resultRatePlan->remarks;
		  }
			}
				$priceValue = 0;
				$content .=' <tr>
				  <td>'.$counter.'</td>
				   <td>'.$rowRoom->room_name.'<br>(Inclusions: '.$remarks.')</td>
				   <td style="text-align:center;">'.$rowOrderDetail->room_quantity.'</td>
				  <td> Adults-'.$rowOrderDetail->adults.' |  Child-'.$rowOrderDetail->child.' | Infants-' .$rowOrderDetail->infants.'</td>
				  <td style="text-align:center;">INR '.round($rowOrderDetail->total_price/$rowOrderDetail->room_quantity).'</td>
				  <td>INR '.round($rowOrderDetail->total_price)*$rowOrder->no_of_days.'</td>
				</tr>';
				$counter++;
				$i++;
				}			
			}
      $content .='</tbody>
            </table><hr>';
            
            
            
            $sqlOtherChargesCheckRecord = executeSQl("SELECT * FROM `".TBL_OTHERCHARGES_DETAIL."`  WHERE `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  ");
		if(num_rows($sqlOtherChargesCheckRecord) >0){
		
		
		$content .= '<table class="table" style="margin:0px !important;text-align:center;" width="800">
						<tr>
						  <td>
							<b>Other Charges</b>
						 	</td>
						   
						</tr>
					</table>';
		
		
		$content .= '<table class="table">
						<tr align="middle" style="background-color:#4f81bc;color:#fff;font-color:#fff;">
						   <th width="15%" style="color:#fff;"><b>Charges Detail</b></th>
						   
						   <th style="color:#fff;text-align:center;"><b>Rate</b></th>
						   <th width="15%" style="color:#fff;"><b>Nos</b></th>
						   
						   <th style="color:#fff;text-align:center;"><b>Tax %</b></th>
						   <th width="20%" style="color:#fff;text-align:center;"><b>Tax Value</b></th>	
						   <th width="20%" style="color:#fff;text-align:center;"><b>Amount</b></th>							
						</tr>';
						
						
						$sqlOtherChargesRecords = executeSQl("SELECT * FROM `".TBL_OTHERCHARGES_DETAIL."`  WHERE `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  ");
		while($rowOtherChargesRecords = $db->fetch_object2($sqlOtherChargesRecords)){
			
			if($rowOtherChargesRecords->charges_method==1){
				$OtherChargesMethods	=	'Net';
			}
			if($rowOtherChargesRecords->charges_method==2){
				$OtherChargesMethods	=	'Per Day - '.$rowOtherChargesRecords->charges_noofdays;
			}
			
						$content .= '<tr>
						   <td>'.$rowOtherChargesRecords->charges_description_id.'</td>
						   
						   <td style="text-align:center;">'.$rowOtherChargesRecords->charges_price.'</td>
						   <td style="text-align:center;">'.$rowOtherChargesRecords->charges_noofdays.'</td>
						   
						   
						    
						   <td style="text-align:center;">'.$rowOtherChargesRecords->charges_tax_percentage.'</td>
						    <td style="text-align:center;">'.$rowOtherChargesRecords->charges_tax_value.'</td>
							 
						   <td style="text-align:center;">'.$rowOtherChargesRecords->charges_net.'</td>						
						</tr>';
						
							}
					$content .= '</table><hr>';
					
		
		
		}
		
			
	}		
		$sqlOtherCharges = executeSQl("SELECT sum(charges_price) as charges_price_sum FROM `".TBL_OTHERCHARGES_DETAIL."`  WHERE `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  ");
			
			$RowOtherCharges=$db->fetch_object2($sqlOtherCharges);		
	$content .='<table class="table" width="800">
                <tr >
				  <td width="60%"></td>
                  <th style="text-align:right">Subtotal:</th>
                  <td class="tdrightalign">INR '.round($rowOrder->subtotal).'</td>
                </tr>';				
			
			if(round($RowOtherCharges->charges_price_sum)!='0'){ 
			$content .= '<tr >
						 <td width="60%"></td>
                 		 <th style="text-align:right">Other Charges:</th>
                  		   <td class="tdrightalign">INR '.round($rowOrder->total_addcharges).' </td>
						</tr>';
				}
				if(round($rowOrder->total_discounts)!='0'){ 
			$content .= '<tr >
						 <td width="60%"></td>
                 		 <th style="text-align:right">Discount:</th>
                  		  <td class="tdrightalign">INR '.round($rowOrder->total_discounts).' </td>
						</tr>';
				}
				
       $content .= '<tr>
	   				<td width="60%"></td>
                  <th style="text-align:right">Tax: </th>
                  <td class="tdrightalign">INR '.round($rowOrder->total_tax).'</td>
                </tr>
                <tr >
					<td width="60%"></td>
                  <th style="text-align:right">Total:</th>
                  <td class="tdrightalign">INR '.round($rowOrder->total_price).'</td>
                </tr>
				 <tr >
				 	<td width="60%"></td>
                  <th style="text-align:right">Amount Paid:</th>
                  <td class="tdrightalign">INR '.round($rowOrder->amount_received).'</td>
                </tr>
				 <tr>
				 <td width="61%"></td>
                  <th style="text-align:right">Balance:</th>
                  <td class="tdrightalign">INR '.round($rowOrder->balance).'</td>
                </tr>
              </table>';
		?>	
<?php $content .= '<table class="table">
					
						<tr>
						  <td width="100%" style="text-align:justify;">
						   
							 <p><strong>SPECIAL INSTRUCTIONS:</strong><br> <br>	'.strtoupper($rowOrder->requests).'<br><br>'.$resultHotelDetail->special_notes.'</p>
							
			  <p>'.$rowShop->note.'</p>
						 	</td>
						 </td>
						</tr>
					</table>'; 
	?>
	
	
	
	<?php 
if($rowOrder->pickup =='Yes'){
	
	$content .='<div class="row">
          <div class="col-sm-12 text-muted  no-shadow" >
            <p><strong>Pickup Details:</strong> <br> '.$rowOrder->pickup_details.'</p>
          </div>
		</div>';
}

?>
	
	
	
	
	<?php /*$content .='<div class="row">
          <div class="col-sm-12 text-muted  no-shadow" >
            <p><strong>Getting There:</strong> <br> Get Directions to the Hotel by clicking <a target="_blank" href="https://www.google.co.in/maps/place/'.urlencode($resultHotelDetail->name).'/@'.$resultHotelDetail->latitude.','.$resultHotelDetail->longitude.'
" >here</a>.</p>
          </div>
		</div>';
		*/?>
<?php 
if($resultHotelDetail->bank_detail !=''){
 $content .='<div class="row">
  <div class="col-sm-12 text-muted  no-shadow" >
	<p><strong><u>Bank Details</u></strong> <br></p>
	 '.$resultHotelDetail->bank_detail.'
  </div>
</div>';
}


if($rowShop->cancellation_policy !=''){
 $content .='<div class="row">
  <div class="col-sm-12 text-muted  no-shadow" >
	<p><strong><u>Cancellation Policy</u></strong> <br></p>
	 '.str_replace($SITE_URL,"../../",$rowShop->cancellation_policy).'
  </div>
</div>';
}



if($rowShop->other_policy !=''){
 $content .='<div class="row">
  <div class="col-sm-12 text-muted  no-shadow" >
	<p><strong><u>Become A Member</u></strong> <br></p>
	 '.str_replace($SITE_URL,"../../",$rowShop->other_policy).'
  </div>
</div>';
}

$content .='<div class="row">
  <div class="col-sm-12 text-muted  no-shadow" >
	<p>Kindly contact us for any further assistance. <br></p>
	 With Kind Regards.
	
	<br>Reservations - '.$resultHotelDetail->name.'<br>';
	 
	 
	 //selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").' <br/> '.selectColumn(TBL_USERS,'company'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").'
	$content .=	$rowShop->social_links.'<br>
	 Please forward us your valuable feedback on '.$rowShop->feedback_url.'
  </div>
</div>';

 	?> <?php 
 	
$dompdf->load_html($content);
$dompdf->render();
$dompdf->stream('abcd.pdf', array("Attachment" => false	));
