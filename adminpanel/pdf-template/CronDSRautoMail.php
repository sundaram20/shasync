<?php 
include_once("../../config/data.config.php");
//include("$LIB_DIR/imageprocess.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/roomstatus.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/PHPMailer/PHPMailerAutoload.php");
include("$LIB_DIR/admin.pagingClass.php");
include("$LIB_DIR/dompdf/dompdf_config.inc.php");
include("$LIB_DIR/PHPExcel-1.8/Classes/PHPExcel.php");
include("$LIB_DIR/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

include("$LIB_DIR/class.mailer.php");



$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
//adminLoginCheck();
$setpage =10;
$dompdf = new DOMPDF();
$dompdf->stream("codexworld",array("Attachment"=>1));
$sendMail = new sendMail;
$objPHPExcel = new PHPExcel();




$AutoMailSql  		=  executeSQl("SELECT * FROM `".TBL_AUTOEMAIL_MASTER."` WHERE name='DSR' and status=1  ");
while($RowAutoMail = $db->fetch_object2($AutoMailSql)){

$resShop  		=  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE `id` = '".addslashes($RowAutoMail->id_shop)."' ");
$rowShop = $db->fetch_object2($resShop);
$ShopID			=	$rowShop->id;
$logo			= $rowShop->image;

$CurrentDate	= date('Y-m-d');
error_reporting(1);

$sql  = " SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."' ";
$sql .= " AND DATE(`".TBL_DAILYVISIT."`.`date_created`)  BETWEEN '".date('Y-m-d',strtotime($CurrentDate))."' And '".date('Y-m-d',strtotime($CurrentDate))."'";
$sql .= "order by dated desc ";
$datawisearrayFinal = array();			
$datewise_array = array();
//echo $sql; 
 $datewise_array[] = $CurrentDate;
	$db->query($sql);
	 $numRows= $db->num_rows();
	 $total = $db->num_rows();
	if($total > 0){		
		$cntr_order= 0;
		while($row = $db->fetch_object()){
			
			 $datewise_array[] = date('Y-m-d',strtotime($row->dated));
				foreach($datewise_array as $checkinDatearr){
				if(strtotime($checkinDatearr)==strtotime($row->dated)){

					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["id"]=$row->id;
					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["company"]=$row->id_company;
					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["customer"]=$row->id_contacts;
					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["id_user"]=$row->id_user;
					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["business_potential"]=$row->business_potential;
					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["discussion_summary"]=$row->discussion_summary;
					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["dated"]=$row->dated;
				}
			}

		}

	}
$availableData .= '<style>
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
    font-size: 0.85em;
    padding: 7px !important;
}</style>';


		if($total > 0){
		$counter = 1;
		foreach($datawisearrayFinal2 as $dateCheckin=>$dateData){
				$DayVisitcount=1;
				foreach($dateData as $hotelcheckarr=>$order_data1){
				$availableData .= '<div style="page-break-after: always;"><table class="table"  style="margin:0px !important;text-align:Left;" width="600">
				<tr>
				<td  width="35%">';
				$availableData .= '<table class="table" border="1">
				<tr align="middle" style="background-color:#c2d69a;color:#fff;font-color:#fff;border:1px;">
				   <th width="5%" ><b>Name of Executive </b></th>
				   <th width="15%" ><b>'.ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($hotelcheckarr)."'")).'</b></th>						   						
				</tr>
				<tr align="middle" style="color:#fff;font-color:#fff;border:1px">
				   <th width="5%" ><b>Date</b></th>
				   <th width="15%" ><b>'.dateformat_date($dateCheckin).'</b></th>
				</tr>
				
				<!--<tr align="middle" style="color:#fff;font-color:#fff;border:1px">
				   <th width="15%" ><b>Target for the Day</b></th>
				   <th width="10%" ><b>---</b></th>
				</tr>-->

				</table>';
			
			$availableData .='</td>
						<td  width="35%">
							
						</td>
						<td  width="35%">';
					 $sqlDailyvisit = executeSql(" SELECT  count(id) as total,sum(convayence_total) sumTotal,sum(entertainment) as enTotal FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' and id_user	= '".addslashes($hotelcheckarr)."'");
					$rowDailyvisite = $db->fetch_object2($sqlDailyvisit);
					
					
$dt = date('Y-m-d',strtotime($dateCheckin));
$MonthStartDate 	=	date("Y-m-01", strtotime($dt));
$MonthEndDate = date("Y-m-d", strtotime($dt)); 

if(date('m')=='01' || date('m')=='02' || date('m')=='03'){
	
	$year = date('Y') - 1;
	$Finalcialyear	=	date($year."-04-01");
	
	}else{
		$Finalcialyear	=	date("Y-04-01");
		}
		
		

$TillDate	=	date('Y-m-d');

$sqlFinalcialyear = executeSql("SELECT  count(id) as Yeartotal,sum(convayence_total) as YearsumTotal ,sum(entertainment) as enYearTotal FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."'AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($Finalcialyear))."' And '".date('Y-m-d',strtotime($TillDate))."' and id_user	= '".addslashes($hotelcheckarr)."'");
$sqlFinalcialyear = $db->fetch_object2($sqlFinalcialyear);

$sqlCurrentMonth = executeSql(" SELECT  count(id) as Monthtotal,sum(convayence_total) MonthsumTotal ,sum(entertainment) as enTotalMonth FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."'AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($MonthStartDate))."' And '".date('Y-m-d',strtotime($MonthEndDate))."' and id_user	= '".addslashes($hotelcheckarr)."'");
 
 
					$sqlCurrentMonth = $db->fetch_object2($sqlCurrentMonth); 				
						$availableData .= '<table class="table" border="1" style="float:right;">
						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px;">
						   <th width="5%" style="color:#000;"><b>Particulars</b></th>
						   <th width="15%" style="color:#000;"><b>Today</b></th>	
						    <th width="15%" style="color:#000;"><b>MTD</b></th>	
						    <th width="15%" style="color:#000;"><b>YTD</b></th>						   						
						</tr>
						<tr align="middle" style="color:#000;font-color:#000;border:1px">
						   <th width="5%" ><b>Sales Calls Done</b></th>
						   <th width="15%" ><b>'.$rowDailyvisite->total.'</b></th>
						   <th width="15%" ><b>'.$sqlCurrentMonth->Monthtotal.'</b></th>
						   <th width="15%" ><b>'.$sqlFinalcialyear->Yeartotal.'</b></th>
						</tr>
						<tr align="middle" style="color:#000;font-color:#000;border:1px">
						   <th width="5%" ><b>Conveyance</b></th>
						   <th width="15%" ><b>'.$rowDailyvisite->sumTotal.'</b></th>
						   <th width="15%" ><b>'.$sqlCurrentMonth->MonthsumTotal.'</b></th>
						   <th width="15%" ><b>'.$sqlFinalcialyear->YearsumTotal.'</b></th>
						</tr>
						<tr align="middle" style="color:#000;font-color:#000;border:1px">
						   <th width="5%" ><b>Entertainment</b></th>
						   <th width="15%" ><b>'.$rowDailyvisite->enTotal.'</b></th>
						   <th width="15%" ><b>'.$sqlCurrentMonth->enTotalMonth.'</b></th>
						   <th width="15%" ><b>'.$sqlFinalcialyear->enYearTotal.'</b></th>
						</tr>
						</table>';
						
						$availableData .='</td>
						   
						</tr>
					</table><br><br>
       ';						
						
						$availableData .='<table style="postion:absolute;margin-top:-160px;margin-left:300px;">
						<tr>
						<td width="30%"><img  src="../../uploaded_files/shop/'.$rowShop->image.'"></td>
						</tr></table>';
						               				//print_r($order_data);
							
	$availableData .= '<div style="page-break-inside:avoid;"><table class="table" style="margin:0px !important;text-align:Left;" width="800">
						<tr>
						  <td>
							<b>Discussion Summary </b>
						 	</td>
						   
						</tr>
					</table>
       <table class="table" border="1" style="border:1px solid:red;">
						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">
						   <th width="5%" style="color:#000;"><b>S:No</b></th>
						   <th width="15%" style="color:#000;"><b>Company Visited</b></th>
						   <th width="15%" style="color:#000;text-align:center;"><b>Contact Person</b></th>
						   <th width="25%" style="color:#000;text-align:center;"><b>Contact  No </b></th>						   
						   <th style="color:#000;text-align:center;"><b>'.ucwords('discussion summary').'</b></th>
						   						
						</tr>';
						
			foreach($order_data1 as $room_idfromarr=>$order_data){	
					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($order_data['customer'])."'",''); 
		  			$resultContact = $db->fetch_object2($resContact);
                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;
					$mobile	=	$resultContact->mobile;
					if($mobile	==''){
						$mobile	='-';
						}
					
		$availableData .= '<tr align="middle" style="border:1px;" >
						   <td width="5%" >'.$counter++.'</td>

						   <td width="15%" >'.ucwords(selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$order_data['company']."'")).'</td>	
						   <td width="15%" style="text-align:center;">'.ucwords($NAme).'</td>
						   <td width="25%" style="text-align:center;">'.$mobile.'</td>						   
						   <td style="text-align:center;">'.$order_data['discussion_summary'].'</td>
						   						
						</tr>';
						
						
						}
						
						
						
						
	$availableData .= '</table></div>';
				
/*Discussion Summary -End*/

		
	/*Feed Back Summary -Start*/
	$availableData .= '<div style="page-break-inside:avoid;"><table class="table" style="margin:0px !important;text-align:Left;" width="800">
						<tr>
						  <td>
							<b>Feed Back Summary </b>
						 	</td>
						   
						</tr>
					</table>
       ';
	$availableData .= '<table class="table" border="1" style="border:1px solid:red;">
						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">
						   <th width="5%" style="color:#000;"><b>S:No</b></th>
						   <th width="15%" style="color:#000;"><b>Hotel Name</b></th>
						   <th width="15%" style="color:#000;text-align:center;"><b>Date</b></th>
						   <th width="25%" style="color:#000;text-align:center;"><b>Status </b></th>
						   
						   <th style="color:#000;text-align:center;"><b>FeedBack Summary					</b></th>
						   						
						</tr>';
	$sqlDailyvisit = executeSql(" SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' AND DATE(`".TBL_DAILYVISIT."`.`date_created`)  = '".date('Y-m-d',strtotime($CurrentDate))."' and id_user	= '".addslashes($hotelcheckarr)."'");
	 
	 //$rowRatePlanExisting = $db->fetch_object2($sqlDailyvisit);
	 $feedCount=1;
	 while($rowDailyvisite = $db->fetch_object2($sqlDailyvisit)){
		
		$resState = executeSql("SELECT * from `".TBL_DAILYVISIT_FEEDBACK."` where status='1' and  Visit_id='".addslashes($rowDailyvisite->id)."'");

if(num_rows($resState) > 0){

		while($row = $db->fetch_object2($resState)){
			
				
		$feedback_summary	=	selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($ShopID)."' AND `visit_id` = '".addslashes($rowDailyvisite->id)."'  AND `details_id` = '".$row->id."'");
					  				
	$availableData .= '
						<tr align="middle" style="border:1px">
						   <td width="5%" ><b>'.$feedCount++.'</b></td>
						   <td width="15%" >'.ucwords(selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'")).'</td>
						   <td width="15%" style="text-align:center;">'.date('d-M-Y',strtotime($row->dated)).'</td>
						   <td width="25%" style="text-align:center;">Open</td>
						   
						   <td style="text-align:center;">'.$feedback_summary.'</td>
						   						
						</tr>';						
		}
}
						
	 }
						
						
						
	$availableData .= '</table></div>';	
/*Feed Back Summary -End*/	
		
		$availableData .= '<div style="page-break-inside:avoid;"><table class="table" style="margin:0px !important;text-align:Left;" width="800">
						<tr>
						  <td>
							<b>CONVEYANCE </b>
						 	</td>
						   
						</tr>
					</table>
       ';

$availableData .= '<table class="table" border="1" >
						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">
						   <th width="4%" style="color:#000;"><b>S:No</b></th>
						   <th width="8%" style="color:#000;"><b>Date</b></th>
						   <th width="10%" style="color:#000;text-align:center;"><b>From</b></th>
						   <th width="10%" style="color:#000;text-align:center;"><b>To </b></th>   
						   <th width="15%" style="color:#000;text-align:center;"><b>Company Visited</b></th>
						   <th width="5%" style="color:#000;"><b>Kms Run</b></th>
						   <th width="5%" style="color:#000;"><b>Rate/Km</b></th>
						   <th width="5%" style="color:#000;"><b>Parking</b></th>
						   <th width="5%" style="color:#000;"><b>Total</b></th>
						   <th width="5%" style="color:#000;"><b>Approval Status</b></th>
						   
						   						
						</tr>';					
						
	/*================================CONVEYANCE START==================================================================================*/
$sql1 = " SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' AND DATE(`".TBL_DAILYVISIT."`.`date_created`)  = '".date('Y-m-d',strtotime($CurrentDate))."' and id_user	= '".addslashes($hotelcheckarr)."'";

	$db->query($sql1);

	 $numRows= $db->num_rows();

	//$pagging = new pagingClass($sql,$setpage);

	//$db->query($pagging->getQuery());

	$total = $db->num_rows();
if($total > 0){$counter = 1;
												
				  while($row2 = $db->fetch_object()){
					  
					  $TotalSum	 +=	$row2->Total;
					  $availableData .= '<tr>
						                       
                    <td>'.$counter++.'</td>
                    <td>'.dateformat_date($row2->dated).'</td>';
                    
                  
					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row2->id_contacts)."'",''); 
		  			$resultContact = $db->fetch_object2($resContact);
                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;
                    if($row2->conveyance_approved==1){
						$conApprove = "Approved";
					}
					else{
						$conApprove = "Not Approved";
					}
					
                    $availableData .= '<td >'.ucfirst($row2->StatFrom).'</td>';
					                    $availableData .= '<td >'.ucfirst($row2->StatTo).'</td>
                    
                    
                    
                    <td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row2->id_company."'").'</td>
					<td style=" text-align:center;">'.$row2->KmsRun.'</td>
					<td style=" text-align:center;">'.$row2->RateKm.'</td>
					<td style=" text-align:center;">'.$row2->Parking.'</td>
					<td style=" text-align:center;">'.$row2->Total.'</td>
					<td style=" text-align:center;">'.$conApprove.'</td>
					</tr>'; 
					
				  }
}					
	$availableData .= '
						<tr>
						<td  colspan="7"></td>
						<td  style="text-align:center; border:1px solid;font-size:16px; " width="15%"><b>Grand Total :</b></td>
						  <td   style=" text-align:center;border:1px solid;font-size:16px; " width="10%" style="font-size:16px;">
							<b>'.$TotalSum.' </b>
						 	</td>
						 	<td ></td>
						   
						</tr></table></div>
					
       ';
       
	   
$availableData .= '</div>';	
							
				$TotalSum=0;	$DayVisitcount=0;	
				}
				
				

				}
					

/*================================CONVEYANCE END==================================================================================*/   
echo   $availableData;
//die;
		$dompdf = new DOMPDF();
		$dompdf->set_paper('letter', 'landscape');
		$dompdf->load_html($availableData);
		$dompdf->render();
		
		$font = Font_Metrics::get_font("helvetica", "bold");
		$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
		
		$dompdf->output();
		$output = $dompdf->output();
		$SaleReport	=	'SalesReport'.$ShopID.'_'.$CurrentDate;
		$pj = $_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/cron-pdf/".$SaleReport.".pdf";
		file_put_contents($pj,$output);
		
		fclose($pj);
		
		 unset($rowShop);
		 $availableData='';
		 $datawisearrayFinal = '';	
		 $datawisearrayFinal2	='';	
		 $datewise_array = '';
	}
}
/*DSR PDF END ------------------------------------------------------------------------------*/



	
	
	


$AutoMailSql  		=  executeSQl("SELECT * FROM `".TBL_AUTOEMAIL_MASTER."` WHERE name='DSR' and status=1  ");
while($RowAutoMail = $db->fetch_object2($AutoMailSql)){

	$resShop  		=  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE `id` = '".addslashes($RowAutoMail->id_shop)."' ");
	$rowShop = $db->fetch_object2($resShop);
	$ShopID	=	$rowShop->id;
	
	$CurrentDate	= date('Y-m-d');
$sql  = " SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($ShopID)."' ";
$sql .= " AND DATE(`".TBL_DAILYVISIT."`.`date_created`)  BETWEEN '".date('Y-m-d',strtotime($CurrentDate))."' And '".date('Y-m-d',strtotime($CurrentDate))."'";
$sql .= "order by dated desc ";

	$db->query($sql);	 
	 $total = $db->num_rows();
	if($total > 0){
		
		
		$EmailArray	=	explode(',',$RowAutoMail->email);
		foreach($EmailArray	as $ListSentEmail){	
			$EmailID	=	$ListSentEmail;	
			
			
			$corpse 	= $rowShop->name;
			$mail 		= new PHPMailer;
			$mail->isMail();
			$mail->IsHTML(true);
			$mail->From=$EmailID;
			$mail->FromName='Roomstatushub';
			$mail->AddAddress($EmailID);
			
			$pj = $_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/cron-pdf/SalesReport".$ShopID."_".$CurrentDate.".pdf";
			//echo $pj;
			//echo "<br>".is_readable($pj) ? 'The file is readable' : 'The file is NOT readable';
			$mail->Subject = $rowShop->name;
			$mail->AddAttachment($pj);
			$mail->AddReplyTo($EmailID);
	
			$mail->Body=$corpse;
			if (!$mail->Send())
					echo "Error Sending: ".$mail->ErrorInfo;
			unset($mail);
		}
	}
}
		
?>