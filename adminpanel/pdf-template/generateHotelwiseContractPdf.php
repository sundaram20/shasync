<?php include_once("../../config/auto_loader.php"); 
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = $db->fetch_object2($resShop);
$logo	=	$rowShop->image;
$NewHotel_id	= addslashes($_REQUEST['id']);
	
	
$content = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
tr#footer { text-align: right; border-top: 1px solid black; page-break-before: always; }
.fitwidth{
	
	}

</style>';

	$cond .= " AND `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."'";
	//print_r($_REQUEST);
	if($_REQUEST['company_id'] != ''){
	$cond .= " AND `".TBL_RATE."`.`company_id` = '".addslashes($_REQUEST['company_id'])."'";
	}
	if($_REQUEST['session'] != '' && $_REQUEST['session'] !='null' ){	
	$session = $_REQUEST['session'];		
		$cond .= " AND `".TBL_RATE."`.`seasonId` IN  (".addslashes($session).")";
				
	}
	if($NewHotel_id != ''){		
			$cond .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` in (".$NewHotel_id.")";
			$condHotel = " AND `".TBL_HOTELS."`.`id` in (".$NewHotel_id.")";
	}
	

$content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;">
						<tr>					
						  <th>
						  <img src="../../uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />
						  ';
$content .= '			 </th>
				</tr>
			</table>
	     <table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%">';
		 
	$FindSearonWord	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$_REQUEST['season']."'");
if (strpos($FindSearonWord, 'WINTER') !== false) {
	$BackgroundColor	='background-color:#540320;';
   
}else{
	$BackgroundColor	='background-color:#254061;';
	
	}


$content .= '<tr style="'.$BackgroundColor.'color:#fff !important;font-size:16px !important;">
	<th colspan="11" ><b>HOTELWISE CONTRACTED RATES</b></th></tr>';

	
	
	
$query ="SELECT `".TBL_RATE_DETAILS."`.*,`".TBL_RATE."`.*, `".TBL_RATE."`.id  as detail_id FROM `".TBL_RATE_DETAILS."` join `".TBL_RATE."` on fs_rate.id=fs_rate_details.rate_id  LEFT JOIN `fs_company` AS cmp ON cmp.id_company=".TBL_RATE.".company_id  WHERE 1=1 ".$cond." group by ".TBL_RATE.".id  order by ".TBL_RATE.".seasonId ,cmp.name asc";	

$RatecheckCountSql =  executeSQl($query);
$HoteNumValue	=	$db->num_rows2($RatecheckCountSql);

if($HoteNumValue>0){
	
	
	$content .= '<tr style="'.$BackgroundColor.'color:#fff !important;font-size:16px !important;">
	<th colspan="11" ><b>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$NewHotel_id."'").'</b></th></tr>';
	
	

$InCount=1;
$InCount2=0;

while($RatecheckCountRecords=	$db->fetch_object2($RatecheckCountSql)){

		
	
		
		
	
$Company1	=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");
if($Company1!=''){
	$Company=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");
	$id_default_group=selectColumn(TBL_COMPANY,'id_default_group'," WHERE `id_company` = '".$RatecheckCountRecords->company_id."'");
	$CompanyDefaultGroupType=selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$id_default_group."'");

}if($Company1==''){

$Company= selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$RatecheckCountRecords->rate_level_id."'");


$CompanyDefaultGroupType	='-';

}		
$Market	=	selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RatecheckCountRecords->market."'");
$seasonName	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RatecheckCountRecords->seasonId."'");
$RateName	=	$RatecheckCountRecords->rate_name;
$Date	=	dateformat_date($RatecheckCountRecords->date_created);	
		

$RateSql 	=  executeSQl("SELECT `".TBL_RATE_DETAILS."`.* FROM `".TBL_RATE_DETAILS."` WHERE  fs_rate_details.rate_id=".$RatecheckCountRecords->id."   and `".TBL_RATE_DETAILS."`.`hotel_id` in (".$NewHotel_id.") order by ".TBL_RATE_DETAILS.".id asc");

$LoadRateID	=	$RatecheckCountRecords->id;	
$CountRateDetails	=	$db->num_rows2($RateSql);



	

$content .='
		<tr>
		<td colspan="11" style="border:0px solid #000;">
			<table  width="100%" style="border:1px solid red; width:100%;  text-align:center; vertical-align:text-top;">
			
			
		
		
		<tr>
			<td colspan="4" style="border:1px solid #000; text-align:center; vertical-align:text-top;width:350px;">
						<table  width="100%" style="border:1px solid green;width:100%;  text-align:center; vertical-align:text-top;">
							<tr border="0px ;" style="color:#000 !important;font-size:14px !important; font-weight:bold;text-align:center;">
								<td style="width:30px;">S:No.</td>
								<td>Agent Name</td>'; 	
								
								//$content .= '<td>Type</td>';	
								$content .= '<td>Market</td>	
								
								<td style="width:120px;">Season</td>
							</tr>
							';
								$content .='<tr style="height:100%;"><td>'.$InCount++.'</td>
								<td style="text-align:left;">'.$Company.'</td> ';	
								//$content .='<td >'.$CompanyDefaultGroupType.'</td>';
								$content .='<td>'.$Market.'</td>				
								<td>'.$seasonName.'</td></tr>';
						$content .='</table>
					</td>';
		
		
		$content .='<td colspan="7" style="border:1px solid #000;">	<table width="100%" style="border:1px solid #000; width:100%;  text-align:center; vertical-align:text-top;">';
		$content .= '<tr style="color:#000 !important;font-size:14px !important; font-weight:bold;text-align:center;">
			
		<td>Room Category</td>
			<td style="width:70px;">Plan</td>
		<td style="width:60px;">Single</td>	
		<td style="width:60px;">Double</td>	
		<td style="width:65px;">Extra Bed</td>	
			
		<td style="width:60px;">Lunch</td>	
		<td style="width:60px;">Dinner</td>
		</tr>';	

 while($RateDetailsRecords=	$db->fetch_object2($RateSql)){
	
	 
		
		$content .='<tr>
			<td  style="text-align:left;" >'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$RateDetailsRecords->room_id."'").'</td>';	
			$content .='<td>'.selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".$RateDetailsRecords->rate_plan_id."'").'</td>';
			$content .='<td>'.$RateDetailsRecords->single_pax_price.'</td>';	
			$content .='<td>'.$RateDetailsRecords->double_pax_price.'</td>';	
			$content .='<td>'.$RateDetailsRecords->extra_bed_price.'</td>	
			
			<td>'.$RateDetailsRecords->lunch_price.'</td>	
			<td>'.$RateDetailsRecords->dinner_price.'</td>
		</tr>';
		
	
	}


	$content .='</table></td>';


		
		$content .='</tr></table></td></tr>';
		
	
	//$InCount	=$InCount2;	
}


}//num

//} //Hotel Sql End
		
		



$content .='
</table>';


 echo $content;	
//die;

/*================================CONVEYANCE START==================================================================================*/   



 
/*if($_REQUEST['hotelwisetype']=='emailToHotel'){
		$CurrentDate	= date('Y-m-d');
		$HOtelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['id']."'");
 		$dompdf = new DOMPDF();
		$dompdf->set_paper('letter', 'landscape');
		$dompdf->load_html($content);
		$dompdf->render();
		
		$font = Font_Metrics::get_font("helvetica", "bold");
		$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
		
		$dompdf->output();
		$output = $dompdf->output();
		$SaleReport	=	$HOtelName.'_'.$CurrentDate;
		$pj = $_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/cron-pdf/".$SaleReport.".pdf";
		file_put_contents($pj,$output);
		
		
			$HOtelName2	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['id']."'");
			$HotelEmail	=	selectColumn(TBL_HOTELS,'email'," WHERE `id` = '".$_REQUEST['id']."'");
		    $SaleReport2	=	$HOtelName2.'_'.$CurrentDate;
			$EmailID	=	$HotelEmail;	
			$corpse 	= $HOtelName2.' Revise Rate Letter';
			$mail 		= new PHPMailer;
			$mail->isMail();
			$mail->IsHTML(true);
			$mail->From=$EmailID;
			$mail->FromName='Roomstatushub';
			$mail->AddAddress($HotelEmail);
			
		
			
			$pj = $_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/cron-pdf/".$HOtelName2."_".$CurrentDate.".pdf";
			//echo $pj;
			//echo "<br>".is_readable($pj) ? 'The file is readable' : 'The file is NOT readable';
			$mail->Subject = $HOtelName2.' Revise Rate Letter';
			$mail->AddAttachment($pj);
			$mail->AddReplyTo($HotelEmail);
	
			$mail->Body=$corpse;
			if (!$mail->Send())
					//echo "Error Sending: ".$mail->ErrorInfo;
			unset($mail);
		
	echo addslashes(encryptor('encrypt',$LoadRateID)).'###'.addslashes(encryptor('encrypt',$_REQUEST['id']));
		
			

            
	}else{*/
 

$dompdf = new DOMPDF();
//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');
$dompdf->load_html($content);
$dompdf->render();


$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));

$dompdf->output();
//$dompdf->stream();
$dompdf->stream('HotelWiseContract.pdf', array("Attachment" => true));
exit;

//}

 
?>

