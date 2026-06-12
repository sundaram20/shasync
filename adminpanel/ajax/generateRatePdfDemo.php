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

$content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="../../uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;</th>';
//if($rowShop->image_logo2!=''){		  
$content .= '		  <th><img src="../../uploaded_files/shop/'.$rowShop->image_logo2.'" class="img-responsive" alt="logo" title="logo" /> &nbsp;</th>';
//} 
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
							   $content .= " eee";//strtoupper(selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RateTitle->company_id."'")); 
							   
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
		   

			 
?>
<?php 
$i=0;	
				$previous = '';	
				
//$resHotelDetail =  executeSQl("SELECT a.`id`,a.`state`,a.`zonal`,a.`excel_display_weekday`,a.`display_order`,a.`city`,a.`name`,a.`address`,a.`special_notes`, b.`id` As `Zone ID`,b.`order_list_number`,b.`name` As zone_name FROM `fs_hotels` as a Left JOIN `fs_zonal` as b on b.`id`=a.`zonal` where a.`status`='1' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY a.`zonal`,  a.`display_order`");

	$resHotelDetail =  executeSQl("SELECT a.*, b.`id` As `Zone ID`,b.`order_list_number`,b.`name` As zone_name FROM `fs_hotels` as a Left JOIN `fs_zonal` as b on b.`id`=a.`zonal` where a.`status`='1' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY a.display_order,`zonal`, state,city, b.`order_list_number` asc ");





?>
<?php 



$resCat_rooms = executeSql("SELECT * FROM `".TBL_RATE."` as a  where   a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id='".$Newrate_id."'") ;
		$RateHotelDetail = $db->fetch_object2($resCat_rooms);

$additional_points	=	$RateHotelDetail->additional_points;
//$y	=	strip_tags($Desc, "<table><tr><td><b><i><p><span><strong><br>");





//echo $content;	
//exit;
//die;



$Companyname	=	'111';//selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$RateTitle->company_id."'");
$Seassionname	=	 selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$RateTitle->seasonId."'");
//$Filename	=$Companyname.'-'.$Seassionname;
$Filename =str_replace(array( '\'', '_', ' / ' , ';', '<', '>',' ' ), '-', urldecode($Companyname)).'-'.$Seassionname;
//$Filename='rateletter';

 

$dompdf = new DOMPDF();


//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');


$dompdf->load_html($content);
//debugData($dompdf);

$dompdf->render();


//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));



//echo $Filename;die;
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

