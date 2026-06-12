<?php include_once("../../config/auto_loader.php");

$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = $db->fetch_object2($resShop);
$logo	=	$rowShop->image;
$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));



$type="xls"; 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".time()."data.".$type."");



/*$objReader = PHPExcel_IOFactory::createReader('HTML');
$objPHPExcel = $objReader->load($table);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');*/

?>
<?php 

$content = '<style>

body { 
margin:0px; padding:0px;
 font-size:14px !important; 
 }
.table-bordered {
    border: 1px solid #000;
	font-size:14px !important; 
}
.table {
	font-size:14px !important; 
    margin-bottom: 20px;
	   
    width:100%;
} 
table {
	font-size:14px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000;
    
    
}
.fitwidth{
	
	}

</style>';

$content .= '<table class="table" >
						<tr>
						   <th ></th>
						    <th ></th>
							<th ></th>
												
						  <th colspan="5" width="60%"  style="margin-top:3px">
						  <img src="http://crs.roomstatushub.com/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo" width="40%" height="90" />
						  ';?>
<?php 
						 	$content .= '</th>
						   
						</tr><tr>
						   <th ></th></tr><tr>
						   <th ></th></tr><tr>
						   <th ></th></tr><tr>
						   <th ></th></tr>
					</table>
       <table class="table" border="1px solid red;">';
	    
		//  $resHotelDetail = selectSql(TBL_HOTELS,'  ORDER BY `state`'); 
		
$stateCountSql =  executeSQl("SELECT *  FROM `".TBL_HOTELS."` WHERE status='1'  group by state");
while($resstateCountSql=	$db->fetch_object2($stateCountSql)){
$CountrowRoomCount[] = $resstateCountSql->id;
}
		
$resHotelDetail =  executeSQl("SELECT * FROM `".TBL_HOTELS."` where status='1'  ORDER BY `state`");
  
  
  
$resTitleSQL = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id!='0' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id  AND b.detail_status=1 AND b.rate_id='".$Newrate_id."'") ;
		$RateTitle = $db->fetch_object2($resTitleSQL);
		
		



$StartDate	=	selectColumn(TBL_RATE,'start_date'," WHERE `rate_name` = '".$RateTitle->rate_name."'");
$EndDate	=	selectColumn(TBL_RATE,'end_date'," WHERE `rate_name` = '".$RateTitle->rate_name."'");
//$content .=$RateTitle->last_modified_by;

$content .= '


						   
			
			<tr style="background-color:#254061;color:#fff;">
			<th colspan="9" ><b>'.$rowShop->name.'-'.selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$RateTitle->market."'").'-'.selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RateTitle->seasonId."'").'</b></th>
			</tr>
						<tr  style="background-color:#4f6228;color:#fff;">
						   <th colspan="3" align="left"><b>TRAVEL AGENCY NAME:';
						   
						   if($RateTitle->company_id==0){
						   
						   $content .= 'Template Rate';}else {$content .= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RateTitle->company_id."'"); }
						   
						   
						  $content .= '</b></th>
						   <th colspan="4" align="left"><b>RATES VALIDITY:';
						    $content .=  dateformat_date($StartDate).'-'.dateformat_date($EndDate); 
						   
			     $content .= '</b></th>
				 <th colspan="2" align="left"><b>Ref:';
						    $content .=  $RateTitle->rate_name.' Date:'.date("d-m-Y"); 
						   
			     $content .= '</b></th>
						   </tr>
						<tr style="background-color:#4f6228;color:#fff;">
						   <th colspan="3" align="left"><b>RATES ISSUED TO: < Name, Designation of T/A Contracting Party>:</b></th>
						   <th colspan="6" align="left"><b>RATES ISSUED BY:';
						   
						   $content .= selectColumn(TBL_USERS,'name'," WHERE `id` = '".$RateTitle->last_modified_by."'");
						   
						   
						   $content .= '</b></th>
						   </tr>   
						<tr align="middle" style="background-color:#4f6228;color:#fff">
						   <th class="fitwidth" style="width:80px !important;"><b>CITY</b></th>
						   	<th class="fitwidth" style="width:80px;"><b>HOTEL/RESORT</b></th>
						   <th class="fitwidth" style="width:80px;"><b>ROOMS</b></th>
						   <th ><b></b></th>
						   <th ><b></b></th>
						   <th ><b></b></th>
						   <th ><b></b></th>
						   <th ><b></b></th>
						   <th ><b></b></th>

						   
						</tr>';
				$i=0;		
while($resultHotelDetail = $db->fetch_object2($resHotelDetail)){ 


		 $resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'") ;
		while($RateHotelDetail = $db->fetch_object2($resCat_rooms)){
		 
		
		 $CountroomSql =  executeSQl("SELECT sum(inventory) as roomcount FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE  hotel_id= '".$resultHotelDetail->id."'");
		 
 		 $rowRoomCount = $db->fetch_object2($CountroomSql);
		 
		 if (in_array($resultHotelDetail->id, $CountrowRoomCount)){		
				$content	.='<tr align="middle" style="background-color:#75923c;color:#fff">
				   <th width="15%" colspan="9"><b>'.selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$resultHotelDetail->state."'").'</b></th>
				</tr>';
		  }
						
$content	.='<tr style="text-align:left;vertical-align:top;">
				<th class="fitwidth" style="text-align:left;vertical-align:top;width:80px !important;"><b>'.$resultHotelDetail->city."<br>".$resultHotelDetail->address.'</b></th>
				<th class="fitwidth" style="text-align:left;vertical-align:top;width:80px !important;"><b>'.$resultHotelDetail->name."\n ".$resultHotelDetail->special_notes.'</b></th>
				<th class="fitwidth" style="text-align:left;vertical-align:top; width:80px !important;"><b>'.$rowRoomCount->roomcount.' Rooms</b></th>
				
				<th width="25%" colspan="6">				
					<table class="table" border="1px solid red;">
						<tr align="middle" style="background-color:#c2d69a;">   
						<th width="15%"><b>ROOM  CATEGORY</b></th>
						<th width="15%"><b>PLAN</b></th>				
						<th width="25%"><b>SINGLE</b></th>
						<th width="25%"><b>DOUBLE</b></th>
						<th><b>EXTRA BED</b></th>	   
						<th><b>INCLUSIONS</b></th>
						<th><b>MEALS/TAXES</b></th>
			</tr>';
			   
$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  hotel_id='".addslashes($resultHotelDetail->id)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND b.detail_status=1 AND a.id=b.rate_id AND b.rate_id='".$Newrate_id."'");

											  
  //$resCat_rooms = executeSql("SELECT *  from `fs_rate_details` where status='1' and hotel_id=".addslashes($resultHotelDetail->id));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													
													
													
													
													
			$content .='<tr>
<th width="22%"><b>'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = ".$rowInclusion->room_id).'</b></th>
			<th width="15%" ><b>'.selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = ".$rowInclusion->rate_plan_id).'</b></th>
			';
			
			 $taxExtra	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = ".$rowInclusion->rate_plan_id);
			 //$content .= $taxExtra;
			 if($taxExtra	=='2'){
			 $t	='+Taxes';			 
			 }else{
			 $t	='';

			 }
			 
	if($rowInclusion->single_pax_price>0){
	
	$single_pax_price	=	'INR '.$rowInclusion->single_pax_price.' '.$t;
	}else{
	$single_pax_price	=	"Rates available on request";
		}
if($rowInclusion->double_pax_price>0){
	
	$double_pax_price	=	'INR '.$rowInclusion->double_pax_price.' '.$t;
	}else{
	$double_pax_price	=	"Rates available on request";
		}
if($rowInclusion->extra_bed_price>0){
	
	$extra_bed_price	=	'INR '.$rowInclusion->extra_bed_price.' '.$t;
	}else{
	$extra_bed_price	=	"Rates available on request";
		}	
		
				
$content .='<th ><b>'.$single_pax_price.'</b></th>
<th ><b>'.$double_pax_price.'</b></th>

 

<th><b>'.$extra_bed_price.'</b></th>
<th><b>'.selectColumn(TBL_RATE_PLAN,'remarks'," WHERE `id` = ".$rowInclusion->rate_plan_id).'</b></th>';

if($rowInclusion->breakfast_price>0){
	$BreakFast	=	' B : INR '.$rowInclusion->breakfast_price.'|';
	}
if($rowInclusion->lunch_price>0){
	$lunch	=	' L : INR '.$rowInclusion->lunch_price;
	}
	
if($rowInclusion->dinner_price>0){
	$Dinner	=	' D : INR '.$rowInclusion->dinner_price;
	}

$content .='<th width="25%" ><b>'.$BreakFast.$lunch.$Dinner.'  GST will be levied extra, as applicable</b></th>
			</tr>';
												
	  }
						     $content .='</table></th>
						   
						  
						  
						   
						</tr>
						
					
      	 '; 
	$i++;	  
//echo "<pre>";		  print_r($resultHotelDetail->name);
}
}



$content .='</tr>';
?>
<?php 


$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a  where   a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id='".$Newrate_id."'") ;
		$RateHotelDetail = $db->fetch_object2($resCat_rooms);

$additional_points	=	$RateHotelDetail->additional_points;
//$y	=	strip_tags($Desc, "<table><tr><td><b><i><p><span><strong><br>");
$content .=	'
   		 <tr><td colspan="9">'.$additional_points;
$content .='</td></tr>';



$GeneralTermSql = executeSql("SELECT * FROM `".TBL_GENERAL_TERMS."` where  `id_shop` = '".addslashes($_SESSION['shop'])."' ");
$RowGeneralTermSql = $db->fetch_object2($GeneralTermSql);

$Desc	=	$RowGeneralTermSql->description;
$y	=	strip_tags($Desc, "<table><tr><td><b><i><p><span><strong><br>");
$content .=	'
   		<tr><td colspan="9">'.$y;
$content .='</td></tr></table>';

 echo $content;	
//$dompdf->load_html($content);
//$dompdf->render();
//$dompdf->stream('abcd.pdf', array("Attachment" => false	));
	