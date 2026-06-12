<?php include_once("../../config/auto_loader.php");

 $resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
 $rowShop = $db->fetch_object2($resShop);
 
 $sqlOrder = executeSQl("SELECT `".TBL_ORDERS."`.* FROM `".TBL_ORDERS."`  WHERE `".TBL_ORDERS."`.`id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'"); 
 $rowOrder = $db->fetch_object2($sqlOrder);
 
 
?> 
<?php $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($rowOrder->id_customer)."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS,"where id='".$rowOrder->id_hotel."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($rowOrder->id_company_person)."'",''); 
		  $resultContact = $db->fetch_object2($resContact); ?>
<?php $resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($rowOrder->id_company)."'",''); 
		  $resultCompany = fetch_object($resCompany); 
?>


<?php 

$content = '<style>



.table-bordered {
    border: 1px solid #000;
}
.table {
    margin-bottom: 20px;
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
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000;
    font-size: 0.90em;
    padding: 7px !important;
}</style>';

$content .= '<table class="table">
						<tr>
						  <td width="60%">
							<address>
						 <img src="../../uploaded_files/shop/'.$rowShop->image.'" class="img-responsive" alt="logo" title="logo" />
							</address>
						 	</td>
						   <td width="40%" >
							<address><strong>WH '.$resultHotelDetail->name.'</strong><br>'.
								$resultHotelDetail->address.'<br>'.
								$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.'<br>'.
								selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->state)."'").', India <br>
								<strong>Phone :</strong> '.$resultHotelDetail->phone1.'<br>
								<strong>Email :</strong> '.$resultHotelDetail->email.'<br>
								<strong>Website :</strong> https://welcomheritagehotels.in
							</address>
						 </td>
						<tr>
					</table>
       <hr>'; 
	?>
<?php 
$content .= '<table class="table" border="0">
						<tr>
						  <td width="50%">
							<b>Booking Status: '.selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'").'</b>
						 	</td>
						   <td width="40%" >
							<b>Reservation ID# '.$rowOrder->reference.'</b>
						 </td>
						 		  <td style="text-align:right">
							<b>Booking Date: '.dateformat_date($rowOrder->invoice_date).'</b>
						 </td>
						</tr>
						<tr><td colspan="3">Dear ';
						if($rowOrder->id_company_person =0){
						$content .= ' Sir/Madam'; 
						}else{
						$content .= $resultContact->first_name.' '.$resultContact->last_name; 
						}
						
		$content .=	'</td></tr>
					<tr>
						<td colspan="3">  Greetings !!!<br><br>       
							We are delighted to provide you with the room reservation status and summary of your booking mentioned below:<br>	  </td>
					</tr>'; 
	?>	
<?php 
$content .= '<tr>
			   <td width="100%" colspan="3"><b>Guest Details</b><br>';
		if($rowOrder->other_reference =''){
		
				$content .= '<address>
					 <strong>'.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>'.
					$rowGuestDetail->address.', '.$rowGuestDetail->city.'<br>'.
					selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($rowGuestDetail->id_state)."'").', '.selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".addslashes($rowGuestDetail->id_country)."'").' '.$rowGuestDetail->postcode.'<br>
					<strong>Phone</strong> : '.$rowGuestDetail->mobile.'<br>
					<strong>Email</strong> : '.$rowGuestDetail->email.'<br><br>
				</address>';
			}else{
			$content .= '<address>
			 <strong>'.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>
			 
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
						<tr align="middle" style="background-color:#ddd;">
						   <th width="15%"><b>Booking Date</b></th>
						   <th width="25%"><b>Total Pax</b></th>
						   <th width="25%"><b>Price/Pax</b></th>
						   
						   <th width="20%"><b>Mode of Payment / Status</b></th>							
						</tr>
						<tr>
						   <td>'.dateformat_date($rowOrder->checkin).'</td>
						   <td>Adults-'.$rowOrder->total_adults.' | Child-'.$rowOrder->total_child.' | Infants-'.$rowOrder->total_infants.'</td>
						  
						   <td>INR '.$rowOrder->tarrif_price.' </td>
						   
						   <td>'.selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".addslashes($rowOrder->payment_status)."'").'</td>						
						<tr>
					</table>
      	 <hr>'; 
}else{
$content .= '<table class="table" style="margin:0px !important;" width="800">
						<tr>
						  <td>
							<b>Booking Summary :</b>
						 	</td>
						   
						<tr>
					</table>
       ';
$content .= '<table class="table">
						<tr align="middle" style="background-color:#ddd;">
						   <th width="15%"><b>Checkin Date</b></th>
						   <th width="15%"><b>Checkout Date</b></th>
						   <th><b>Total Rooms</b></th>
						   <th width="25%"><b>Total Pax</b></th>
						   
						   <th><b>Total Nights</b></th>
						   <th width="20%"><b>Mode of Payment / Status</b></th>							
						</tr>
						<tr>
						   <td>'.dateformat_date($rowOrder->checkin).'</td>
						   <td>'.dateformat_date($rowOrder->checkout).'</td>
						   <td>'.round($rowOrder->total_products).'</td>
						   <td>Adults-'.$rowOrder->total_adults.' | Child-';
						   
						    $sqlOrderDetail = executeSQl("SELECT * FROM `".TBL_ORDER_DETAIL."`  WHERE `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' group by unique_code");
			
			while($rowOrderDetail=$db->fetch_object2($sqlOrderDetail)){
				$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($rowOrderDetail->rate_assign_id)."'  and rd.rate_plan_id='".addslashes($rowOrderDetail->rate_plan_id)."' and rd.rate_id='".addslashes($rowOrderDetail->rate_id)."' and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id");	
			if(num_rows($resRoom) >0){
					$rowRoom = $db->fetch_object2($resRoom);
			
				
				
				  $content .=$rowOrderDetail->child;
				  
				$counter++;
				$i++;
				}			
			}	
						   
						   
						   
						   $content .=' | Infants-'.$rowOrder->total_infants.'</td>
						   
						   <td>'.$rowOrder->no_of_days.'</td>
						   <td>'.selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".addslashes($rowOrder->payment_status)."'").'</td>						
						<tr>
					</table>
      	 <hr>'; 
	$content .= '<table class="table" style="margin:0px !important;" width="800">
						<tr>
						  <td>
							<b>Booking Details :</b>
						 	</td>
						   
						<tr>
					</table>
       '; 
	   ?>
	
 <?php $content .='
            <table class="table table-striped">
              <thead>
                <tr  align="middle" style="background-color:#ddd;">
                  <th width="8%">S. No.</th>             
				  <th>Room Type</th>
				  <th width="10%">Total Room </th>
				  <th width="25%">Pax / Room</th>
				  <th>Rate / Night	</th>				  
				  <th>Room Total</th>
                </tr>
              </thead>
              <tbody>';
			$counter = '1';
			$i=0;
			$sqlOrderDetail = executeSQl("SELECT * FROM `".TBL_ORDER_DETAIL."`  WHERE `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' group by unique_code");
			
			while($rowOrderDetail=$db->fetch_object2($sqlOrderDetail)){
				$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($rowOrderDetail->rate_assign_id)."'  and rd.rate_plan_id='".addslashes($rowOrderDetail->rate_plan_id)."' and rd.rate_id='".addslashes($rowOrderDetail->rate_id)."' and room_id='".addslashes($rowOrderDetail->room_id)."' order by rd.room_id");	
			if(num_rows($resRoom) >0){
					$rowRoom = $db->fetch_object2($resRoom);
			
				$priceValue = 0;
				$content .=' <tr>
				  <td>'.$counter.'</td>
				   <td>'.$rowRoom->room_name.'<br>(Inclusions: '.selectColumn(TBL_RATE_PLAN,'remarks'," WHERE `id` = '".$rowOrderDetail->rate_plan_id."'").')</td>
				  <td>'.$rowOrderDetail->room_quantity.'</td>
				  <td> Adults-'.$rowOrderDetail->adults.' |  Child-'.$rowOrderDetail->child.' | Infants-' .$rowOrderDetail->infants.'</td>
				  <td>INR '.round($rowOrderDetail->total_price).'</td>
				  <td>INR '.round($rowOrderDetail->total_price)*$rowOrder->no_of_days.'</td>
				</tr>';
				$counter++;
				$i++;
				}			
			}
      $content .='</tbody>
            </table>';
			
	}		
			
	$content .='<table class="table" >
                <tr align="left">
				  <td width="60%"></td>
                  <th style="text-align:right">Subtotal:</th>
                  <td style="text-align:right">INR '.round($rowOrder->subtotal).'</td>
                </tr>';				
			if(round($rowOrder->total_discounts)!='0'){ 
			$content .= '<tr align="left">
						 <td width="60%"></td>
                 		 <th style="text-align:right">Discount:</th>
                  		  <td style="text-align:right">INR '.round($rowOrder->total_discounts).' </td>
						</tr>';
				}
				
       $content .= '<tr align="left">
	   				<td width="60%"></td>
                  <th style="text-align:right">Tax </th>
                  <td style="text-align:right">INR '.round($rowOrder->total_tax).'</td>
                </tr>
                <tr align="left">
					<td width="60%"></td>
                  <th style="text-align:right">Total:</th>
                  <td style="text-align:right">INR '.round($rowOrder->total_price).'</td>
                </tr>
				 <tr align="left">
				 	<td width="60%"></td>
                  <th style="text-align:right">Amount Paid:</th>
                  <td style="text-align:right">INR '.round($rowOrder->amount_received).'</td>
                </tr>
				 <tr align="left">
				 <td width="60%"></td>
                  <th style="text-align:right">Balance:</th>
                  <td style="text-align:right">INR '.round($rowOrder->balance).'</td>
                </tr>
              </table><hr>';
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
	<?php $content .='<div class="row">
          <div class="col-sm-12 text-muted  no-shadow" >
            <p><strong>Getting There:</strong> <br> Get Directions to the Hotel by clicking <a target="_blank" href="https://www.google.co.in/maps/place/'.urlencode($resultHotelDetail->name).'/@'.$resultHotelDetail->latitude.','.$resultHotelDetail->longitude.'
" >here</a>.</p>
          </div>
		</div>';
		?>
<?php 

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
	
	 <br>'.selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".addslashes($_SESSION['userLevel'])."'").' - '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").'
	 <br>'.str_replace($SITE_URL,"../../",$rowShop->social_links).'<br>
	 Please forward us your valuable feedback on '.$rowShop->feedback_url.'
  </div>
</div>';

 	?> <?php 
 	
$dompdf->load_html($content);
$dompdf->render();
$dompdf->stream('abcd.pdf', array("Attachment" => false	));
