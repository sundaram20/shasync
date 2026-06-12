<?php include_once("../../config/auto_loader.php"); 
//error_reporting(E_ALL);

$image = selectColumn(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');

$id_rate	= addslashes(encryptor('decrypt',$_REQUEST['id']));

$sql = "SELECT * FROM ".TBL_RATE_UNIT."  
		WHERE id= '".$id_rate."' AND id_shop='".$_SESSION['shop']."' ";
		
$resSql = mysqli_query($connNew,$sql);
$rowData = mysqli_fetch_object($resSql);
$season=selectColumn(TBL_RATE_SEASON,'name','WHERE id="'.$rowData->seasonId.'" ');
//customer details/////////////////////////
$customer = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$rowData->id_contacts.'" ');
$customerLastName = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",last_name)','WHERE id_customer="'.$rowData->id_contacts.'" ');
$id_desgination=selectColumn(TBL_CUSTOMER,'designation','WHERE id_customer="'.$rowData->id_contacts.'" ');
$designation=selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_desgination.'" ');
////customer company details/////////////////
$id_company = selectColumn(TBL_CUSTOMER,'id_company','WHERE id_customer="'.$rowData->id_contacts.'" ');
$custCompany = selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$id_company.'" ');
$addressComp = selectColumn(TBL_COMPANY,'address','WHERE id_company="'.$id_company.'" ');

$phoneComp = selectColumn(TBL_COMPANY,'mobile','WHERE id_company="'.$id_company.'" ');
$emailComp = selectColumn(TBL_COMPANY,'email','WHERE id_company="'.$id_company.'" ');

$customerEmail = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$rowData->id_contacts.'" ');
$customerMobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$rowData->id_contacts.'" ');

$cityComp = selectColumn(TBL_COMPANY,'CONCAT(city,"-",postcode)','WHERE id_company="'.$id_company.'" ');
////////////////////Hotel Details///////////////////
$sqlRtl = "SELECT  hotel.id,hotel.display_order,rtd.*,rt.* FROM ".TBL_RATE_UNIT." rt RIGHT JOIN 
			".TBL_RATE_DETAILS_UNIT." rtd ON rt.id=rtd.rate_id INNER JOIN   ".TBL_HOTELS." as hotel ON  rtd.hotel_id = hotel.id 
		WHERE rt.id= '".$id_rate."' AND rt.id_shop='".$_SESSION['shop']."' order by  hotel.display_order ASC";
//echo $sqlRtl;
//die();
$resRtl = mysqli_query($connNew,$sqlRtl);
$objRtl = mysqli_fetch_object($resRtl);	

////FOR MULTI HOTELS///
$ids_hotels = array();
mysqli_data_seek($resRtl,0);
while($rowIdsHotel = mysqli_fetch_assoc($resRtl)){
	array_push($ids_hotels,$rowIdsHotel['hotel_id']);
}
$ids_hotels=array_unique($ids_hotels);



//FOR MULTI HOTELS END//


$hotelWithCity=selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)','WHERE id="'.$objRtl->hotel_id.'" ');
//echo $hotelWithCity;
$hotelBrief=selectColumn(TBL_HOTELS,'brief_description','WHERE id="'.$objRtl->hotel_id.'" ');

$crsPhone=selectColumn(TBL_HOTELS,'phone1','WHERE id="'.$objRtl->hotel_id.'" ');
$crsEmail=selectColumn(TBL_HOTELS,'email','WHERE id="'.$objRtl->hotel_id.'" ');
///////////Handeled by /////////////////////
$id_area=selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$rowData->company_id.'" ');
$id_handeled_by = selectColumn(TBL_AREAS,'user_id','WHERE id="'.$id_area.'" ');
$handeledByName = selectColumn(TBL_USERS,'name','WHERE id="'.$id_handeled_by.'" ');
$id_designation = selectColumn(TBL_USERS,'designation','WHERE id="'.$id_handeled_by.'" ');
$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');
$handeledByMobile =selectColumn(TBL_USERS,'mobile','WHERE id="'.$id_handeled_by.'" ');
$handeledByEmail =selectColumn(TBL_USERS,'email','WHERE id="'.$id_handeled_by.'" ');
$companyNameUser=selectColumn(TBL_USERS,'company','WHERE id="'.$id_handeled_by.'" ');
$address1 = selectColumn(TBL_USERS,'address','WHERE id="'.$id_handeled_by.'" AND id_shop="'.$_SESSION['shop'].'"');

$address2 = selectColumn(TBL_USERS,'address2','WHERE id="'.$id_handeled_by.'" AND id_shop="'.$_SESSION['shop'].'"');
$city = selectColumn(TBL_USERS,'city','WHERE id="'.$id_handeled_by.'" AND id_shop="'.$_SESSION['shop'].'"');
$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$id_handeled_by.'" AND id_shop="'.$_SESSION['shop'].'"');

//////////shop details///////////////////////

$shopName = selectColumn(TBL_SHOP,'name','WHERE id="'.$_SESSION['shop'].'" ');
$aboutBrand = selectColumn(TBL_SHOP,'about_brand','WHERE id="'.$_SESSION['shop'].'" ');
$addressBrand = selectColumn(TBL_SHOP,'address','WHERE id="'.$_SESSION['shop'].'" ');
$phoneBrand = selectColumn(TBL_SHOP,'phone','WHERE id="'.$_SESSION['shop'].'" ');
$faxBrand = selectColumn(TBL_SHOP,'fax','WHERE id="'.$_SESSION['shop'].'" ');
$webBrand = selectColumn(TBL_SHOP,'website_url','WHERE id="'.$_SESSION['shop'].'" ');
$crsBrandPhone = selectColumn(TBL_SHOP,'crs_phone','WHERE id="'.$_SESSION['shop'].'" ');
$emailBrand = selectColumn(TBL_SHOP,'email','WHERE id="'.$_SESSION['shop'].'" ');
//////////////////// Room Data/////////////////////


/////////////////////End////////////////////////////
$content = "<html><head>
			<style>
			@page {
               margin: 100px 50px;
            }

            .header {
               	position: fixed;
                top: -63px;
                left: 0px;
                right: 0px;
                height: 65px;
                width:100%;
                text-align: center;
                line-height: 100px;
            }

            footer {
               position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 
                font-size:8pt;
                text-align: center;
                line-height: 35px;
                border-top:2px solid black;

            }
            body{
            	font-family:Cambria;
            	font-size:11pt;
            }
            body,head{
            	display:block;
            }
        </style>	

			</head><body>";
			
if($_SESSION['shop']==6){
$image_logo2    = selectColumn(TBL_SHOP,'image_logo2','WHERE id="'.$_SESSION['shop'].'" ');
$image_logo3    = selectColumn(TBL_SHOP,'image_logo3','WHERE id="'.$_SESSION['shop'].'" ');
    $content .= "<div class='header' >";
    //$content .= "<img style='padding:10px 10px !important;height:50px;' src='../../uploaded_files/shop/".$image."'>";
   $content .='<div style="border: 1px solid #000;float:left;height:60px; ">';
   $content .='<table  style="border: 0px solid #000;float:left; " width="100%" height="70px;" >
						<tr>					
						 <td style="width:150px;">
						  <img src="../../uploaded_files/shop/'.$image.'" style="padding:10px 10px !important;height:40px; " class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </td>';
if($image_logo2!=''){		  
$content .= '		  <td style="width:130px;" ><img src="../../uploaded_files/shop/'.$image_logo2.'" style="padding:10px 10px !important;height:40px;" class="img-responsive" alt="logo" title="logo" /> &nbsp;&nbsp;&nbsp;</td>';
}
if($image_logo3!=''){		  
$content .= '		<td style="width:0px;margin-left:50px; float:right;"> <img src="../../uploaded_files/shop/'.$image_logo3.'" style="padding:10px 10px !important;height:40px;" class="img-responsive" alt="logo" title="logo" /> &nbsp;</td>';
}
						  
$content .= '</tr></table>
	    ';
    
    $content .=" </div></div>";
}else{			
$content .= "<div class='header'><img style='padding:10px 10px !important;height:50px;' src='../../uploaded_files/shop/".$image."'></div>";
}
			
$content .= "<table style='margin-top:30px;'>
				<tr><td>Date ".date('d/m/Y')."<br/><br/></td></tr></table>";	
//echo $content;
//die;
$content .= "<table ><tr><td><b>".$customer."</b></td></tr><tr><td>".$designation."<br/><br/></td></tr><tr><td><b>".$custCompany."</b></td></tr>
				".($addressComp!=''?'<tr><td>'.wordwrap($addressComp,40,"<br>\n").'</td></tr>':'')."".($cityComp!=''?'<tr><td>'.$cityComp.'</td></tr>':'')."".($phoneComp!=''?'<tr><td>Contact Number: '.$customerMobile.'</td></tr>':'')."".($customerEmail!=''?'<tr><td>Email: '.$customerEmail.'</td></tr>':'')."</table>";			

$content .= "<table style='margin-top:30px;text-align:justify;'><tr><td>
						<b> Dear ".$customerLastName.",</b><br><br>Greetings from <b>".$hotelWithCity."</b>, a member of ".ucwords(strtolower(str_replace(':','',$shopName))).", India’s leading environmentally sensitive hotel chain.<br><br>
						We look forward to welcoming your clients and guests for their business, leisure and corporate MICE stays at our hotel.<br><br></td></tr></table>";
		
$content .='<span style="text-align:justify;">'.$aboutBrand.'</span>';

$content .="<span style='margin-top:5px !important;text-align:justify !important;'>".$hotelBrief."</span>";

$content .= "<table style='margin-bottom:-50px;text-align:justify;'><tr><td>
						With reference to our discussions, we are pleased to offer your company <b>Special Rates</b> at our hotel for ".ucfirst(strtolower($season)).", as per the annexures attached. Kindly acknowledge your acceptance within 7 days for us to extend these special rates to your company.<br><br>You may make your room reservations by calling or emailing me or our Reservations team directly, by contacting our Central Reservations on ".$crsBrandPhone." / ".$emailBrand."<br><br>We thank you for your support and value your patronage, and assure you of our best services and personalized attention at all times.<br><br>Yours sincerely,<br><br><br><br><br><br></td></tr></table>";
$sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$objRtl->last_modified_by."'",''));
$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$sqlUserDetail->designation.'" ');
$address1   = $sqlUserDetail->address;
$address2   =   $sqlUserDetail->address2;
$city = selectColumn(TBL_USERS,'city','WHERE id="'.$objRtl->last_modified_by.'" AND id_shop="'.$_SESSION['shop'].'"');
$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$objRtl->last_modified_by.'" AND id_shop="'.$_SESSION['shop'].'"');
$content .= "<table style='width:100%;margin-top:30px;'><tr><td style='width:50%;'><b>".$sqlUserDetail->name."</b><br/><i>".$handeledByDesignation."</i><br/><b>".$companyNameUser."</b><br/>".($address1!=''?$address1.', ':'').($address2!=''?$address2.' ':'').'<br/>'.$city.'-'.$zip."<br/>M: ".$sqlUserDetail->mobile." <br/ >Email : ".$sqlUserDetail->email."<br><br></td></tr></table>";

$content .="<table style='page-break-before:always;margin-top:50px;'><tr><td>YOUR PREFERRED CORPORATE RATES:</td></tr></table>";

foreach ($ids_hotels as $key => $id_hotel) {
	

	$sqlRoom = "SELECT A.hotel_remarks,A.extra_bed_price,A.breakfast_price,A.lunch_price,A.dinner_price,A.extra_bed,A.room_id,A.single_pax_price,A.rate_plan_id,A.double_pax_price,A.weekend_single_pax_price,A.weekend_double_pax_price,A.detail_status FROM ".TBL_RATE_DETAILS_UNIT." A LEFT JOIN ".TBL_ASSIGN_HOTEL_ROOM." B ON A.room_id=B.room_id AND B.hotel_id = '".$id_hotel."' WHERE A.rate_id='".$rowData->id."' AND A.hotel_id = '".$id_hotel."' GROUP BY A.hotel_id,A.room_id,A.rate_plan_id  ORDER BY B.display_order ASC ";

	$weekdayAllowed=selectColumn(TBL_HOTELS,'excel_display_weekday','WHERE id="'.$id_hotel.'" ');

	$roomRes=mysqli_query($connNew,$sqlRoom);

	if($weekdayAllowed==1){
		$singleTxt = "WEEKDAYS (MON-THU)";
		$doubleTxt = "WEEKENDS (FRI-SUN)";
	}elseif($weekdayAllowed==2){
		$singleTxt = "WEEKDAYS (SUN-THU)";
		$doubleTxt = "WEEKENDS (FRI-SAT)";
	}else{
		$singleTxt = "Single";
		$doubleTxt = "Double";
	}

	$hotelWithCity=selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)','WHERE id="'.$id_hotel.'" ');


$content .= "<table cellspacing='0' cellpadding='0' border='1px' style='text-align:center;width:100%;margin-top:30px;font-size:14px;'><tr style='border:1px solid black;background-color:#272727;color:white;'><td colspan='5'  >".$hotelWithCity."<br>Rates validity: ".date('d-F-Y',strtotime($rowData->start_date))." - ".date('d-F-Y',strtotime($rowData->end_date))."</td></tr><tr style='border:1px solid black;background-color:#595959;color:white;'><td rowspan='2' width='26%'><b>Room Type</b></td><td colspan='4' rowspan='1'>Preferred Corporate Rates</td></tr><tr style='border:1px solid black;background-color:#595959;color:white;'><td >Plan</td><td >".$singleTxt."</td><td >".$doubleTxt."</td><td width='25%' style='word-warp:break-word;'>Meals/Taxes</td></tr>";
				
while($room = mysqli_fetch_object($roomRes)){
	if($room->detail_status !='0'){
		if($weekdayAllowed == '1'  ){
			$singlePriceTxt = ($room->single_pax_price==0?'Rate on request':$room->single_pax_price)."/".("INR ".$room->double_pax_price==0?'Rate on request':"INR ".$room->double_pax_price);
			$doublePriceTxt = ($room->weekend_single_pax_price==0?'Rate on request':"INR ".$room->weekend_single_pax_price)."/".($room->weekend_double_pax_price==0?'Rate on request':"INR ".$room->weekend_double_pax_price);
			
			
		}elseif($weekdayAllowed=='2'){
			$singlePriceTxt = "INR ".($room->single_pax_price==0?'Rate on request':$room->single_pax_price)." / ".($room->double_pax_price==0?'Rate on request':" ".$room->double_pax_price);
			$doublePriceTxt = ($room->weekend_single_pax_price==0?'Rate on request':"INR ".$room->weekend_single_pax_price)." / ".($room->weekend_double_pax_price==0?'Rate on request':" ".$room->weekend_double_pax_price);
			
		}else{
			$singlePriceTxt = ($room->single_pax_price==0?'Rate on request':"INR ".$room->single_pax_price);
			$doublePriceTxt = ($room->double_pax_price==0?'Rate on request':"INR ".$room->double_pax_price);
		}
		
		$empty='';
		if($room->lunch_price==0){
		    $empty='-';
		}
		elseif($room->dinner_price==0){
		    $empty='-';
		}

		$content .= "<tr style='border:0.5px solid black;background-color:#F9F9F9;color:black; font-size:12px;'><td style='text-align:left'>".selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$room->room_id.'" ')."</td><td>".selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$room->rate_plan_id.'" ')."</td><td >".$singlePriceTxt."</td><td >".$doublePriceTxt."</td><td style='word-warp:break-word;'>".($room->lunch_price!=0?"L: INR ".$room->lunch_price.' + TAXES':'').($room->dinner_price!=0?" | D : INR ".$room->dinner_price.' + TAXES':'').$empty." </td></tr>";
	}
	$hotel_remarks=$room->hotel_remarks;
	$extra_bed_price=$room->extra_bed_price;
}	
$content .= "<tr style='border:0.5px solid black;background-color:#F9F9F9;color:black;'><td style='text-align:left;'>Extra Person/Extra Bed</td><td colspan='4' style='text-align:left;'>".($extra_bed_price==0?'Rate on request':'INR '.$extra_bed_price)."</td></tr>";

$content .= "<tr style='border:0.5px solid black;background-color:#F9F9F9;color:black;'><td style='text-align:left;'>Note: </td><td style='text-align:left;' colspan='4'>".$hotel_remarks."</td></tr></table> ";

}
$content.="<br>";

if($rowData->generalterms!=''){
	$content.="<div style='page-break-before:always;'>";

$genTerm=selectColumn(TBL_GENERAL_TERMS,'description','WHERE id="'.$rowData->generalterms.'"');
$content.="</div>";
}

if(count($ids_hotels)>1){
	$content.="<div style='page-break-before:always;'></div>";
}	

$content .="<span style='margin-top:10px !important;'>".strip_tags($genTerm,"<table><p><tr><br><b><strong><td><span><i><ul><li><ol>")."</span>";
//$content .="<span style='margin-top:10px !important;'".strip_tags($genTerm,"<table><p><tr><br><b><strong><td><span><style><i><ul><li><ol>")."</span>";
$content .= "</body></html>";

$content=strip_tags($content,"<style><header><br><b><div><table><strong><tr><td><p><span><img><style><head><html><body><ul><li><br><ol><link><strong><font>");


//echo $content;die;

$domPdfNew = new DOMPDF();
//$domPdfNew->set_option("isPhpEnabled", true);
$domPdfNew->set_paper('potarit', 'potarit');

$domPdfNew->load_html($content);
$domPdfNew->render();
$font = Font_Metrics::get_font("helvetica", "bold");
$domPdfNew->get_canvas()->page_text(550, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));

$market = selectColumn(TBL_RATE_MARKET,'name','WHERE id="'.$rowData->market.'" ');
$Filename=$custCompany."-".$market."-".$season."-".$hotelWithCity;
if($_REQUEST['location']=='set'){
	$gen = $domPdfNew->output();
	$domPdfNew->stream($Filename.'.pdf', array("Attachment" => true));
	file_put_contents('../mailattachunit/'.$Filename.'.pdf', $gen);
	echo "ok";
}
else{
	$domPdfNew->output();
	$domPdfNew->stream($Filename.'.pdf', array("Attachment" => true));
}


