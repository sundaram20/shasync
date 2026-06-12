<?php 

include_once("../../config/auto_loader.php"); 
if($_SESSION['userLevel'] !=1){
	restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
}
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = $db->fetch_object2($resShop);
$logo	=	$rowShop->image;
$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));



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
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style>';
//echo $rowShop->image_logo2;die;
$content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="../../uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';
if($rowShop->image_logo2!=''){		  
$content .= '		  <th><img src="../../uploaded_files/shop/zinc2.png" class="img-responsive" alt="logo" title="logo" /> &nbsp;&nbsp;&nbsp;</th>';
}
if($rowShop->image_logo3!=''){		  
$content .= '		 <th><img src="../../uploaded_files/shop/'.$rowShop->image_logo3.'" class="img-responsive" alt="logo" title="logo" /> &nbsp;</th>';
}
						  
$content .= '			 
				</tr>
			</table>
	    ';
		
 $content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%">';
		//  $resHotelDetail = selectSql(TBL_HOTELS,'  ORDER BY `state`'); 
		
$stateCountSql =  executeSQl("SELECT *  FROM `".TBL_HOTELS."` WHERE status='1'  group by zonal");
while($resstateCountSql=	$db->fetch_object2($stateCountSql)){
$CountrowRoomCount[] = $resstateCountSql->id;
}
		
  if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
	 $sql .= " AND `id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";
}

$resTitleSQL = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id!='0' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id  AND b.detail_status=1 AND b.rate_id='".$Newrate_id."'") ;
		$RateTitle = $db->fetch_object2($resTitleSQL);


$StartDate	=	selectColumn(TBL_RATE,'start_date'," WHERE `id` = '".$Newrate_id."'");
$EndDate	=	selectColumn(TBL_RATE,'end_date'," WHERE `id` = '".$Newrate_id."'");
$FindSearonWord	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RateTitle->seasonId."'");
if (strpos($FindSearonWord, 'WINTER') !== false) {
	$BackgroundColor	='background-color:#540320;';
	
	
    //echo 'true';
}elseif(strpos($FindSearonWord, 'SUMMER') !== false) {
   // echo 'true';
   $BackgroundColor	='background-color:#254061;';
   
   
	
}else{
	$BackgroundColor	='background-color:#254061;';
	
	}
$content .= '<tr style="'.$BackgroundColor.'color:#fff !important;font-size:16px !important;">
			<th colspan="16" ><b>'.$rowShop->name.' : '.selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RateTitle->market."'").'-'.selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RateTitle->seasonId."'").'</b></th>
			</tr>

						<tr  style="background-color:#4f6228;color:#fff !important;font-size:11px !important">
						   <th colspan="6" align="left"><b>COMPANY NAME:';
						   
						   if($RateTitle->company_id==0){
						   
						    $content .= "  ".strtoupper('Template Rate');}else {
							   $content .= "  ".strtoupper(selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RateTitle->company_id."'")); 
							   
						$GST	=	 strtoupper(selectColumn(TBL_COMPANY,'fax'," WHERE `id_company` = '".$RateTitle->company_id."'")); 
							   if($GST !=''){
								$content .= " ( GST: ".$GST.')'; 	   
							   }//else{
								//$content .= " ( ";
								//   }
							   
							  
							  							   

						}						   
						   
			$content .= '</b></th>
						   <th colspan="7" align="left"><b>RATES VALIDITY: &nbsp;';
			$content .=  strtoupper(date('d F Y',strtotime($StartDate)).' - '.date('d F Y',strtotime($EndDate))); 
						   
			     $content .= '</b></th>
				 <th colspan="1" align="left"><b> &nbsp;';
						    $content .=  $RateTitle->rate_name; 
						     $CreditValue =	"  ".strtoupper(selectColumn(TBL_COMPANY,'company_credibility'," WHERE `id_company` = '".$RateTitle->company_id."'"));
							   if($CreditValue == '1'){ 
							   $content .= " ( COMPANY ON CREDIT )";
							   }
							   if($CreditValue == '2'){ 
							   $content .= " ( ADVANCE/DIRECT PAYMENT )";
							   }
					
$resContact = executeSql("SELECT * from `".TBL_CUSTOMER."` where status='1' and id_customer='".addslashes($RateTitle->id_contacts)."' and type='2'");
$rowContact = $db->fetch_object2($resContact);
$BookerName	=	$rowContact->first_name." ".$rowContact->last_name;//." ".$rowContact->email;	


$DesignationTo	=	strtoupper(selectColumn(TBL_DESIGNATION_MASTER,'name'," WHERE `id` = '".$rowContact->designation."'"));
if($DesignationTo !=''){
	$DesignationToName	=	' / '.$DesignationTo;
}

$designationByid	= selectColumn(TBL_USERS,'designation'," WHERE `id` = '".$RateTitle->last_modified_by."'");
$DesignationBy	=	strtoupper(selectColumn(TBL_DESIGNATION_MASTER,'name'," WHERE `id` = '".$designationByid."'"));
if($DesignationBy !=''){
	$DesignationByName	=	' / '.$DesignationBy;
}
		   

			     $content .= '</b></th><th colspan="2" align="left"><b>DATE:&nbsp; '.date("d-m-Y",strtotime($RateTitle->date_created)).'</b></th>
						   </tr>
						<!--<tr style="background-color:#4f6228;color:#fff !important;font-size:11px !important">
						   <th colspan="10" align="left"><b>RATES ISSUED TO: &nbsp;'.strtoupper($rowContact->title).' '.strtoupper($BookerName).$DesignationToName.'</b></th>
						   <th colspan="4" align="left"><b>RATES ISSUED BY:&nbsp; '; 
						   
						   $content .= strtoupper(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$RateTitle->last_modified_by."'")).$DesignationByName;
						   
						   
						   $content .= '</b></th>
						   <th colspan="3" align="left"><b>DATE:&nbsp; '.date("d-m-Y",strtotime($RateTitle->date_created)).'</b></th>
						   </tr>--></table>';   
						/*$content .='<tr align="middle" style="background-color:#4f6228;color:#fff !important;text-align:center;">
						   <th  style="width:80px !important;text-align:center;"><b>CITY</b></th>
						   	<th  style="width:80px;text-align:center;"><b>HOTEL/RESORT</b></th>
						   <th style="width:80px;text-align:center;"><b>ROOMS</b></th>
						   <th colspan="10"><b></b></th>
						</tr>';*/
?>
<?php 
$i=0;	
				$previous = '';	
				
//$resHotelDetail =  executeSQl("SELECT a.`id`,a.`state`,a.`zonal`,a.`excel_display_weekday`,a.`display_order`,a.`city`,a.`name`,a.`address`,a.`special_notes`, b.`id` As `Zone ID`,b.`order_list_number`,b.`name` As zone_name FROM `fs_hotels` as a Left JOIN `fs_zonal` as b on b.`id`=a.`zonal` where a.`status`='1' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY a.`zonal`,  a.`display_order`");

	$resHotelDetail =  executeSQl("SELECT a.*, b.`id` As `Zone ID`,b.`order_list_number`,b.`name` As zone_name FROM `fs_hotels` as a Left JOIN `fs_zonal` as b on b.`id`=a.`zonal` where a.`status`='1' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY a.display_order,`zonal`, state,city, b.`order_list_number` asc ");




	/*$content	.='
	<tr border="0px ;" style="text-align:center;background-color:#fff !important;  margin:0px !important;margin-top:0px;">

<td colspan="13" width="100%" style="border:0px solid #000;">

<table>
			  <tr >  
			  <td colspan="13">';*/
				
				
while($resultHotelDetail = $db->fetch_object2($resHotelDetail)){ 




	 $resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'");



		while($RateHotelDetail = $db->fetch_object2($resCat_rooms)){
			
			
		
$previous = $current;

 if ($resultHotelDetail->excel_display_weekday  =='1'){		 
					 $SingleAndWeekday	='<th width="100px;"><b>WEEKDAYS</b></th>
											<th width="100px;"><b>WEEKENDS (FRI-SUN)</b></th>';
		 }else{
			  		 $SingleAndWeekday	='<th width="110px;"><b>SINGLE</b></th>
										<th width="110px;"><b>DOUBLE</b></th>';
			  }
			  
			  /*-------------------------*/
			  
			  $content	.='<table width="100%">
			 ';  
				 
 						
$content	.='<tr border="0px ;" style="text-align:center;background-color:#fff !important;  margin:0px !important;margin-top:0px;">

<td colspan="13" width="100%" style="border:0px solid #000;">';



//^^^^^^^^^^^^^^^^^^^^^^^^^State And Zone^^^^^^^^^^^^^^^^^^^^^
		 $CountroomSql =  executeSQl("SELECT sum(inventory) as roomcount FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE status=1 AND hotel_id= '".$resultHotelDetail->id."'");
 		 $rowRoomCount = $db->fetch_object2($CountroomSql);		 
		 $current = $resultHotelDetail->zonal;
		if ($current != $previous) {			
		$zonalName	=	selectColumn('fs_zonal','name'," WHERE `id` = '".$resultHotelDetail->zonal."'");
			if($zonalName	!=''){
				$zonal	=	$zonalName;
				}
//				$City	=	" - ".selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$resultHotelDetail->state."'");

/*if (strpos($FindSearonWord, 'WINTER') !== false) {
	if($zonal	=='SOUTH INDIA'){
	$pageBreaksWinSum	='page-break-before: always;';
	}else{
	$pageBreaksWinSum	='';
	}
    //echo 'true';
}


if(strpos($FindSearonWord, 'SUMMER') !== false) {
   // echo 'true';
   //if( $zonal	=='MAHARASHTRA'){
  // $pageBreaksWinSum	='page-break-before: always;';
  // }else{
 //  $pageBreaksWinSum	='';
  // }
	
}*/

  $content	.='<table   style="'.$pageBreaksWinSum.'background-color:#75923c;color:#fff;vertical-align:central;text-align:center;" width="100%">
  <tr  style="background-color:#75923c;color:#fff;vertical-align:central;text-align:center;">  
				 
 		<th colspan="13" style="vertical-align:central;text-align:center;color:#fff; font-size:14px !important"><b>'.strtoupper($zonal.$City).'</b>			</th>
	
				
				</tr></table>';			
				
			}
//^^^^^^^^^^^^^^^^^^^^^^^^^State And Zone^^^^^^^^^^^^^^^^^^^^^	



$content	.='<table width="100%" style="border:1px solid #000;">
<tr width="100%" style="border:1px solid #000;"> 


<td colspan="4" style="float: left!important;vertical-align: top !important; width:250px;border-bottom:1px; border-right:0px;">
		<table border="0px ;" width="100%">
			<tr style="background-color:#c2d69a; ">
			<th class="fitwidth" style="text-align:left;vertical-align: left !important;"><b>'.strtoupper($resultHotelDetail->city).' - </b>			
			<b>'.$resultHotelDetail->name.' </b>
			</th>
			</tr>
			<tr style="float:left!important;text-align:left !important;vertical-align:top;background-color:#fff !important; margin:0px !important;">
							<th><b>Address: </b><span style="font-weight: normal;">'.$resultHotelDetail->address.'</span><br/>	</th>
							
							
							
			</tr>
			
			<tr style="float:left!important;text-align:left !important;vertical-align:top;background-color:#fff !important; margin:0px !important;">
							<th><b>Rooms: </b><span style="font-weight: normal;">'.$rowRoomCount->roomcount.'</span><br/>	</th>
							
							
							
			</tr>';	
		if($resultHotelDetail->special_notes!=''){			
	$content	.='<tr style="float: left!important;text-align:left!important;vertical-align:top;background-color:#fff !important; margin:0px !important;">						
							
							<th>	
							<b>Remarks: </b><span style="font-weight: normal;">'.$resultHotelDetail->special_notes.'
							</span></th>
							
			</tr>';
		}
			
			$content	.='</table>	
	</td>
	<td colspan="9" style="vertical-align: top !important; margin:0px !important;"><table class="table" style="margin-bottom:0px">';
				
			//echo "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b join `".TBL_ASSIGN_HOTEL_ROOM."` where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'";	 
			
			//echo "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'";
			
			
$resCat_rooms = executeSql("SELECT a.* ,b.*,c.room_id,c.hotel_id,c.display_order,c.status FROM `fs_rate` as a join `fs_rate_details` as b left join `fs_assign_hotel_room` as c ON c.hotel_id=b.hotel_id and c.room_id=b.room_id where b.hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."' order by c.display_order asc ");			
			
/*$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'");
*/
											  
  //$resCat_rooms = executeSql("SELECT *  from `fs_rate_details` where status='1' and hotel_id=".addslashes($resultHotelDetail->id));
			$Lable	=	0;
			while($rowInclusion = $db->fetch_object2($resCat_rooms)){
				
				$Lable++;
														
				if($Lable	==1){										
				$content .='			
				<tr style="text-align:left;vertical-align:middle;background-color:#c2d69a;">
						<th width="90px;"><b>ROOM  CATEGORY</b></th>												
						<th width="40px;"><b>PLAN</b></th>';										
						$content	.=$SingleAndWeekday;						
						$content	.='
						<th width="80px;"><b>EXTRA PERSON</b></th>	';   
						//$content	.='<th><b>INCLUSIONS</b></th>';
						$content	.='<th><b>MEALS/TAXES</b></th>
				</tr>';
				}
			$content .='<tr style="background-color:#fff !important;">
			<th width="15.5%" style="font-weight: 100" align="left">'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = ".$rowInclusion->room_id).'</th>
			<th  style="font-weight: 100">'.selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = ".$rowInclusion->rate_plan_id).'</th>';
			
			 $taxExtra	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = ".$rowInclusion->rate_plan_id);
			 //$content .= $taxExtra;
			 if($taxExtra	=='2'){
			 $t	='+Taxes';			 
			 }else{
			 $t	='';

			 }
			 
	if($rowInclusion->single_pax_price>0){
	
	if($rowInclusion->single_pax_price==99){
	$single_pax_price	=	'-';
	}else{
	$single_pax_price	=	'INR '.$rowInclusion->single_pax_price.' '.$t;
	}
	
	}else{
	$single_pax_price	=	"Rates available on request";
		}
if($rowInclusion->double_pax_price>0){
	
	if($rowInclusion->double_pax_price==99){
		$double_pax_price	=	'-';
	}else{
		$double_pax_price	=	'INR '.$rowInclusion->double_pax_price.' '.$t;
		}
	}else{
	$double_pax_price	=	"Rates available on request";
		}
if($rowInclusion->extra_bed_price>0){
	
	if($rowInclusion->extra_bed_price==99){
		$extra_bed_price	=	'-';
	}else{
		$extra_bed_price	=	'INR '.$rowInclusion->extra_bed_price.' '.$t;
		}
	}else{
	//$extra_bed_price	=	"Rates available on request";
	$extra_bed_price	=	"-";
		}	
		
				
if ($resultHotelDetail->excel_display_weekday  =='1'){
	
				if($rowInclusion->single_pax_price>0){	
				$single_pax_price_week	=	'INR '.$rowInclusion->single_pax_price.' / '.$rowInclusion->double_pax_price.' '.$t;
				}elseif($rowInclusion->weekend_single_pax_price>0){
				$single_pax_price_week	=	'INR '.$rowInclusion->single_pax_price.' / '.$rowInclusion->double_pax_price.' '.$t;
				}else{
				$single_pax_price_week	=	"Rates available on request";
				}
				
				if($rowInclusion->double_pax_price>0){
				
				$double_pax_price_week	=	'INR '.$rowInclusion->weekend_single_pax_price.' / '.$rowInclusion->weekend_double_pax_price.' '.$t;
				}elseif($rowInclusion->weekend_double_pax_price>0){
				$double_pax_price_week	=	'INR '.$rowInclusion->weekend_single_pax_price.' / '.$rowInclusion->weekend_double_pax_price.' '.$t;
				}else{
				$double_pax_price_week ="Rates available on request";
				}
				
				$content .='<th style="font-weight: 100">'.$single_pax_price_week.'</th>';
				
				$content .='<th style="font-weight: 100">'.$double_pax_price_week.'</th>';
					 
					 
		 }else{
			  		 $content .='<th style="font-weight: 100">'.$single_pax_price.'</th>
								<th style="font-weight: 100">'.$double_pax_price.'</th>';
			  }
 

$content .='<th style="font-weight: 100">'.$extra_bed_price.'</th>';
//$content	.='<th style="font-weight: 100">'.selectColumn(TBL_RATE_PLAN,'remarks'," WHERE `id` = ".$rowInclusion->rate_plan_id).'</th>';


if($rowInclusion->breakfast_price!=0){
	$BreakFast	=	' B : INR '.$rowInclusion->breakfast_price.'| ';
	}
//if($rowInclusion->lunch_price>0){
	$lunch	=	' L : INR '.$rowInclusion->lunch_price.'| ';
	//}
	
//if($rowInclusion->dinner_price==0){
	$Dinner	=	' D : INR '.$rowInclusion->dinner_price;
	//}

if($rowInclusion->dinner_price>0 || $rowInclusion->lunch_price>0 || $rowInclusion->breakfast_price>0){
	

$HotelBLD	=$BreakFast.$lunch.$Dinner.' +Taxes';		
$TaxVal	='+Taxes';	

}else{
	
	$HotelBLD	=	'-';
	
	}

$content .='<th width="150px;" style="font-weight: 100">'.$HotelBLD.'</th>
			</tr>';
												
	  }
	 
	 
	 if($RateHotelDetail->hotel_remarks!=''){;
	 $content .='<tr><td colspan="6">'.$RateHotelDetail->hotel_remarks.'</td></tr>';
	 }
	 
			$content .='</table></div>	';
	        $content .='</td>
	        
	     </tr>
			</table>
			</td>
			   
	        
	        
	        </tr>'; 
	
				
				$content	.='</table>'; 


			
			
			
			
		$i++;	
			}
}
$content .='



';

?>
<?php 



$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a  where   a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id='".$Newrate_id."'") ;
		$RateHotelDetail = $db->fetch_object2($resCat_rooms);

$additional_points	=	$RateHotelDetail->additional_points;
//$y	=	strip_tags($Desc, "<table><tr><td><b><i><p><span><strong><br>");
if($additional_points!=''){
$content .=	'<table class="table"  style="margin-bottom: 0px;border: 1px; width:100%"><tr><td colspan="13">'.$additional_points;
$content .='</td></tr></table>';


}



$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a  where   a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id='".$Newrate_id."'") ;
		while($RateHotelDetail = $db->fetch_object2($resCat_rooms)){

$additional_points	=	explode(',',$RateHotelDetail->rate_points);
//print_r($additional_points);
foreach($additional_points as $Pointresult){	
	
	 $description	= selectColumn(TBL_RATE_POINTS,'description'," WHERE `id` = '".$Pointresult."'");
if($description!=''){
//$content .=	'<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%"><tr><td colspan="13" style=" color:#000;">'.$description;
//$content .='</td></tr></table>';
$content .=strip_tags($description,"<style><b><br><strong><img><table><p><tr><td><span><ul><li><ol>");

//$content .=$description;
}
	}
 } 

/*$content .=' <table class="table page_break"  style=" margin-bottom: 0px;border: 1px; width:500px!important;font-weight:normal !important;">
';*/
$id_term = selectColumn(TBL_DOCUMENT_CONFIG_DETAILS,'id_general_term','WHERE id_doc_type=1 AND id_shop="'.$_SESSION['shop'].'" AND id_rate_level="'.$RateTitle->rate_level_id.'" ');
$content .='<table class="table page_break"  style=" margin-bottom: 0px;border: 1px; width:500px!important;font-weight:normal !important;"></table>';

$GeneralTermSql = executeSql("SELECT * FROM `".TBL_GENERAL_TERMS."` where  `id_shop` = '".addslashes($_SESSION['shop'])."' AND id='".$RateTitle->generalterms."'  ");
$RowGeneralTermSql = $db->fetch_object2($GeneralTermSql);

$Desc	=	$RowGeneralTermSql->description;
//$y	=	strip_tags($Desc, "<table><tr><td><b><i><p><span><strong><br>");
//$content .=	$Desc;

$content .=strip_tags($Desc,"<style><b><br><strong><img><table><p><tr><td><span><ul><li><ol>");


//echo $content;	
//exit;
//die;



$Companyname	=	selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RateTitle->company_id."'");
$Seassionname	=	 selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RateTitle->seasonId."'");
$Filename	=$Companyname.'-'.$Seassionname;


 

$dompdf = new DOMPDF();


//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');


$dompdf->load_html($content);
//debugData($dompdf);

$dompdf->render();


//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));




if($_REQUEST['location']=='set'){
	$gen = $dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	file_put_contents('../mailattach/'.$Filename.'.pdf', $gen);
	echo "ok";
}
else{
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}
//file_put_contents('../mailattach/'.$Filename.'.pdf', array("Attachment" => true	));
//$dompdf->stream();



/*$dompdf->load_html($availableData);
//$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->output();
$dompdf->stream();
  
//$dompdf->stream('test.pdf', array("Attachment" => false	));*/

 
?>

