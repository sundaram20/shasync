<?php 
include("../../config/data.config.php");

include("../../phplib/imageprocess.php");
include("../../phplib/functions.library.php");
include("../../phplib/roomstatus.library.php");
include("../../phplib/msgs.inc.php");
include("../../phplib/class.database.php");
include("../../phplib/data.constant.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include("../../phplib/admin.pagingClass.php");
include("../../phplib/dompdf/dompdf_config.inc.php");
include("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include("../../phplib/class.mailer.php");
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());



//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');

$_REQUEST['id']	=	encryptor('encrypt','378');
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '2'");
 $rowShop = $db->fetch_object2($resShop);
 
$sqlOrder = executeSQl("SELECT `".TBL_ORDERS."`.* FROM `".TBL_ORDERS."`  WHERE `".TBL_ORDERS."`.`id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'"); 
  $rowOrder = $db->fetch_object2($sqlOrder);
	

  print_r($rowOrder);
  
?>
<?php $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($rowOrder->id_customer)."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS,"where id='".$rowOrder->id_hotel."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($rowOrder->id_company_person)."'",''); 
		  $resultContact = $db->fetch_object2($resContact); ?>
<?php $resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($rowOrder->id_company)."'",''); 
		  $resultCompany = fetch_object($resCompany); 
		  
		  print_r($resultCompany);
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

$content .= '<div class="container"><table class="table" width="800">
						<tr>
						  <td width="50%">
							<address>
						  <img src="'.$SITE_URL.'/uploaded_files/shop/'.$rowShop->image.'" class="img-responsive" alt="logo" title="logo" />
							</address>
						 	</td>
						   <td align="right">
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
       '; 
	?>
<?php 

$Booking_Status =selectColumn(TBL_HTL_BOOKING_STATUS,'id'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");

if($Booking_Status	!=	'2'){

$BookingStatus	=	selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");
$BookingStatus  = '<b>Booking Status: '.$BookingStatus.' </b>';
}else{

$BookingStatus	=	selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".addslashes($rowOrder->booking_status)."'");
$BookingStatus 	= '<b>Booking Status: '.$BookingStatus.' </b><br>Time Till: '.dateformat_date($rowOrder->payment_date);
}


$content .= '<br/><table class="table" width="800">
						<tr>
						  <td >
							<b>Booking Status: '.$BookingStatus.' </b>
							
						 	</td>
						   <td style="text-align:center">
							<b>Reservation ID# '.$rowOrder->reference.'</b>
						 </td>
						  <td style="text-align:right">
							<b>Booking Date: '.dateformat_date($rowOrder->invoice_date).'</b>
						 </td>
						</tr><tr><td colspan="3">Dear ';
						if($rowOrder->id_company_person != 0){
						
							if($resultContact->first_name!='self'){
								$content .= $resultContact->first_name.' '.$resultContact->last_name; 
							}else{
								
								$content .= ' Sir/Madam'; 
								}
						
						}else{
						$content .= ' Sir/Madam'; 
						}
						
						
			
			
			
		$content .=	'</td></tr>
					<tr>
						<td colspan="3">  Greetings !!!<br><br>       
							We are delighted to provide you with the room reservation status and summary of your booking mentioned below:<br><br>	  </td>
					</tr>'; 
	?>
<?php 
$content .= '<tr>
			   <td width="100%" colspan="3"><b>Guest Details</b><br>';
		if($rowOrder->other_reference ==''){
		
				$content .= '<address>
					 <strong>'.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>';
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
			 <strong>'.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>
			 			 
			<strong>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes($rowOrder->id_company)."'").'</strong> (Ref '.$rowOrder->other_reference.')<br>
					
				</address>';
			
			}
				
			 $content .= '</td>
			</tr>
		</table>
      	 '; 
	?>
<?php 

if($rowOrder->type=='L'){

$content .= '<table class="table" width="800">
						<tr align="middle" style="background-color:#ddd;">
						   <th ><b>Booking Date</b></th>
						   <th ><b>Total Pax</b></th>
						   <th ><b>Price/Pax</b></th>
						   <th><b>Currency</b></th>
						   <th ><b>Payment Status</b></th>							
						</tr>
						<tr align="middle">
						   <td>'.dateformat_date($rowOrder->checkin).'</td>
						   <td>Adults-'.$rowOrder->total_adults.' | Child-'.$rowOrder->total_child.' | Infants-'.$rowOrder->total_infants.'</td>
						  
						   <td>INR '.$rowOrder->tarrif_price.' </td>
						    <td>INR</td>
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
$content .= '<table class="table" width="800">
						<tr align="middle" style="background-color:#ddd;">
						   <th ><b>Checkin Date</b></th>
						   <th ><b>Checkout Date</b></th>
						   <th><b>Total Rooms</b></th>
						   <th ><b>Total Pax</b></th>
						  
						   <th><b>Total Nights</b></th>
						   <th ><b>Mode of Payment / Status</b></th>							
						</tr>';
						
						
					
						
						
						
						$content .= '<tr align="middle">
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
						   $content .= '| Infants-'.$rowOrder->total_infants.'</td>
						   
						   <td>'.$rowOrder->no_of_days.'</td>
						   <td>'.selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".addslashes($rowOrder->payment_status)."'").'</td>						
						<tr>
					</table><br><br> ';
					
$content .= '<table class="table" style="margin:0px !important;" width="800">
						<tr>
						  <td>
							<b>Booking Details :</b>
						 	</td>
						   
						<tr>
					</table>
       '; 
 $content .='
            <table class="table table-striped" width="800">
              <thead>
                <tr  align="middle" style="background-color:#ddd;">
                  <th >S. No.</th>             
				  <th>Room Type</th>
				  <th >Total Room </th>
				  <th >Pax / Room</th>
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
			 $resRatePlan = selectSql(TBL_RATE_PLAN,"where id='".addslashes($rowOrderDetail->rate_plan_id)."'",''); 
		  $resultRatePlan = fetch_object($resRatePlan);
		  
		   $resRatePlan = selectSql(TBL_RATE_PLAN,"where id='".addslashes($rowOrderDetail->rate_plan_id)."'",''); 
		  $resultRatePlan = fetch_object($resRatePlan);
		 
			if($resultRatePlan->name =='EP'){
		   $remarks	=	'Room Only';
		  }else{
		 
		  $remarks	=	$resultRatePlan->remarks;
		  }
				$priceValue = 0;
				$content .=' <tr align="middle">
				  <td>'.$counter.'</td>
				  <td>'.$rowRoom->room_name.'<br>(Inclusions: '.$remarks.')</td>
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
            </table><br><br>';
			
}			
			
	$content .='<table class="table" width="800" style="text-align:right">
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
              </table>';
		?>
<?php $content .= '<table class="table" width="800">
					
						<tr>
						  <td style="text-align:justify;">						   
							 <p><strong>SPECIAL INSTRUCTIONS:</strong><br>'.strtoupper($rowOrder->requests).'<br><br>'.$resultHotelDetail->special_notes.'</p>
							
			  <p>'.$rowShop->note.'</p>
						 	</td>
						 </td>
						</tr>
					</table>'; 
	?>
<?php $content .='<table class="table" width="800">
					<tr><td><div class="row">
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
	 '.$rowShop->cancellation_policy.'
  </div>
</div>';
}
if($rowShop->other_policy !=''){
 $content .='<div class="row">
  <div class="col-sm-12 text-muted  no-shadow" >
	<p><strong><u>Become A Member</u></strong> <br></p>
	 '.$rowShop->other_policy.'
  </div>
</div>';
}
$content .='<div class="row">
  <div class="col-sm-12 text-muted  no-shadow" >
	<p>Kindly contact us for any further assistance. <br></p>
	 With Kind Regards.
	 <br>Reservation Team<br>'.$rowShop->social_links.'<br>
	 Please forward us your valuable feedback on '.$rowShop->feedback_url.'
  </div>
</div></td></tr></table>';



if($resultCompany->id_default_group != '0'){
	
			$emailId= $resultContact->email;
	}else{
			$emailId= $rowGuestDetail->email;
	}
?>
<?php 



//$sendMail->sendMail('noreply@roomstatushub.com', $_POST['mailId'], $_POST['subject'], $_POST['sendcontent']);

$subject="Booking Details Reservation ID# ".$rowOrder->reference;

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';


$headers[] = 'To:'.$rowShop->email;
$headers[] = 'From: '.$rowShop->email;
$headers[] = 'Cc:'.$resultHotelDetail->email.','.$rowShop->email,'sundaram@globalcomputersolutions.in';

mail($rowShop->email,$subject, $content, implode("\r\n", $headers));


/*
$headers[] = 'To: shashafeer@gmail.com';
$headers[] = 'From: sundaram@globalcomputersolutions.in';
$headers[] = 'Cc: sundaram@globalcomputersolutions.in';

mail('shashafeer@gmail.com', $subject, $content, implode("\r\n", $headers));

*/

//echo $message = "Mail Sent Successfully";



?>
