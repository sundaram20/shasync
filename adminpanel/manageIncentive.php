<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');
/////////////////////////////////////////////////////////////////////////////////////
//debugData($_REQUEST);
//////////////////////////////////////////////////////////////////
$assignUserEnquiryId=array();
$assignUserSql = "SELECT * FROM ".TBL_INCENTIVE_DETAILS." WHERE id_user='".$_SESSION['userId']."' or `id_forward_for_approval`='".$_SESSION['userId']."'  GROUP by id_incentive  ";
$assignUserQuery= mysqli_query($connNew,$assignUserSql);
while($assignUserResult=mysqli_fetch_object($assignUserQuery)){
array_push($assignUserEnquiryId,$assignUserResult->id_incentive);
}
$assignUserEnquiryId= implode(',',$assignUserEnquiryId);
//////////////////////////////////////////////////////////////////
$sql = " SELECT  DISTINCT A.* FROM `".TBL_INCENTIVE."` A WHERE A.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
$display_lead_summary=selectColumn('fs_user_levels','lead_summary_approval','WHERE id="'.$_SESSION['userLevel'].'" ');
if($_SESSION['userLevel']!='1'){
	
if($_SESSION['userLevel']!='1' && $display_lead_summary!='1' && !isset($_REQUEST['drilled_team'])){
	
	//$sql .= " AND (A.id_user='".$_SESSION['userId']."' )  ";
	$sql .= " AND A.id IN(".$assignUserEnquiryId.")  ";
}
elseif($_REQUEST['checkin_radio']==2){
	
	$sql .= " AND A.id_user IN (".$_REQUEST['drilled_team'].")";	
}//
else{
	if($_SESSION['unit_user']!=2){
		$sql .= " AND A.assign_user_id IN (".$_REQUEST['drilled_team'].")";	}
	else{
		$sql .= " AND A.hotel_id IN (".$_SESSION['hotel_access'].")"; 
		}
}
}

 if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==1){
	if($_REQUEST['reservation_date'] != ''){
		//list($from_book,$to_book) = split(" to ",$_REQUEST['booking_date']);		
$booking_date= explode(" to ",$_REQUEST['reservation_date']);
	$from_book = $booking_date['0'];
	$to_book = $booking_date['1'];
			$sql .= " AND DATE(A.checkin) >='".date('Y-m-d',strtotime($from_book))."' AND DATE(A.checkout) <= '".date('Y-m-d',strtotime($to_book))."' ";	
		$fromPrint = $from_book;
		$toPrint = $to_book;
	}
}

if($_REQUEST['search_payment_status'] != ''){
	$sql .= " AND `payment_status` = '".addslashes($_REQUEST['search_payment_status'])."'";
}
if($_REQUEST['lead_status'] != ''){
	$sql .= " AND A.`current_status` = '".$_REQUEST['lead_status']."' ";	
	}
if($_REQUEST['user_id'] != ''){
	$sql .= " AND A.`id_user` = '".addslashes($_REQUEST['user_id'])."'";
}
 if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==2){
	if($_REQUEST['booking_date'] != ''){
		//list($from_book,$to_book) = split(" to ",$_REQUEST['booking_date']);		
$booking_date= explode(" to ",$_REQUEST['booking_date']);
	$from_book = $booking_date['0'];
	$to_book = $booking_date['1'];
			$sql .= " AND DATE(A.date_created) >='".date('Y-m-d',strtotime($from_book))."' AND DATE(A.date_created) <= '".date('Y-m-d',strtotime($to_book))."' ";	
		$fromPrint = $from_book;
		$toPrint = $to_book;
	}
}
if($_REQUEST['id_hotel'] !=""){
  $sql.=" AND A.hotel_id=".$_REQUEST['id_hotel']." ";
}
if($_REQUEST['sourcehotel_id'] !="")
  $sql.=" AND A.sourcehotel_id=".$_REQUEST['sourcehotel_id']." ";
$sql .= "order by id desc ";
//echo $sql;
//if($_POST['Search'] == 'Search'){
	$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
if($_REQUEST['Download']=='Download'){
	
	if($_REQUEST['checkin_radio']==2){
		//list($from_book,$to_book) = split(" to ",$_REQUEST['booking_date']);
		$booking_date= explode(" to ",$_REQUEST['booking_date']);
	$from_book = $booking_date['0'];
	$to_book = $booking_date['1'];
		$CreationOrBookingDate	=	'Generation Date From '.date('d M-Y',strtotime($from_book)).' To '.date('d M-Y',strtotime($to_book));
		}
	
	/*if($_REQUEST['checkin_radio']==1){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);
		$reservation_date= explode(" to ",$_REQUEST['reservation_date']);
	$checkin = $reservation_date['0'];
	$checkout = $reservation_date['1'];	
		$CreationOrBookingDate	=	'Checkin and Checkout Date From '.date('d M-Y',strtotime($checkin)).' To '.date('d M-Y',strtotime($checkout));
		}*/
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	function cellColor($cells,$color){
	    global $objPHPExcel;
	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
             'rgb' => $color
	       )
	    ));
	}
	error_reporting(1);
	$shop_id	=	$_SESSION['shop'];
	$EnqSql 	 = 	mysqli_query($conn,$sql);
		if($EnqSql){
		$numRows = mysqli_num_rows($EnqSql);
		}
		//		die;
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Shafeer")
									 ->setLastModifiedBy("Shafeer")
									 ->setTitle("Conveyance Report")
									 ->setSubject("Conveyance Report")
									 ->setDescription("Conveyance Report")
									 ->setKeywords("Conveyance Report")
									 ->setCategory("Report");
		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
		$objDrawing->setName('Logo');       //set name to image
		$objDrawing->setDescription('Logo'); //set description to image
		$logo = selectColumn('fs_shop','image'," WHERE  id=".$shop_id." ");
		
		if(!isset($cron)){
			$signature = "../uploaded_files/shop/".$logo.""; 
		}
		else{
			$signature = "/home/admingcs/public_html/sync/uploaded_files/shop/".$logo.""; 
		}
		
		if(file_exists($signature)){
		$objDrawing->setPath($signature);
		$objDrawing->setOffsetX(25);                       //setOffsetX works properly
		$objDrawing->setOffsetY(10);                       //setOffsetY works properly
		$objDrawing->setCoordinates('G1');        //set image to cell
		 
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		}  //save
		if($numRows > 0){
		$counter = 1;
		if(!isset($cron)){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A6', 'Lead Summary Report   '.$CreationOrBookingDate);
		}
		else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A6', 'Lead Summary Report '.date('M-Y',strtotime($checkin)).' To '.date('M-Y',strtotime($checkout)));
		}	
				 	 				 	 
		
		$objPHPExcel->getActiveSheet()->mergeCells('A6:O6');
		$objPHPExcel->getActiveSheet()->mergeCells('A7:O7');
		//$objPHPExcel->getActiveSheet()->mergeCells('M7:O7');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A7', 'Lead Summary Details')
			->setCellValue('M7', 'Sales Lead Award Summary Details');
		$head_hotel_row = 8;
		$head_cntr_column = "A";$head_hotel_column = "A";
		$objPHPExcel->setActiveSheetIndex(0)
					 	 					
//
			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Date')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel Name')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source-Hotel Name')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Guest Name')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Check In')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Check Out')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'No of Rooms')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Total Room Nights')			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Revenue Generated')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Lead closed by')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Remarks')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'SLA Last Remarks')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'SLA Status');
			/*->setCellValue($head_cntr_column++.$head_hotel_row, 'Incentive Approved')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source Incentive')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source Hotel Incentive')
		 	->setCellValue($head_cntr_column++.$head_hotel_row, 'Incentive Remarks')		
	
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source Hotels Bank Detail')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Payment Status');
			*/
	$styleArray = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => 'FFFFFF'),
	        'size'  => 14,
			'text-transform' => 'uppercase',
	        'name'  => 'Calibri'
	    ));
	$styleArray_1 = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '000'),
	        'size'  => 10,
			'text-transform'=>'uppercase'
	       
	    ));
  
cellColor('A6:P6','254061');
cellColor('A7:P7','a0cde8');

	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
	 $objPHPExcel->getActiveSheet()->getStyle('A6:P6')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('A7:P7')->applyFromArray($styleArray_1);
	 $objPHPExcel->getActiveSheet()->getStyle('A7:P7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('A8:P8')->applyFromArray($styleArray_1);
	 $objPHPExcel->getActiveSheet()->getStyle('A8:P8')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	
	
	$objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	
		
	 $styleThinBlackBorderOutline = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000'),
			),
		),
	);	
	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);		
	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()
	    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	    
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(22);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(22);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(25);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(15);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(18);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(18);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(20);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('O')->setWidth(20);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('P')->setWidth(20);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('Q')->setWidth(20);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('R')->setWidth(20);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('S')->setWidth(25);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('T')->setWidth(15);
	
	$head_hotel_row++;
	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
						
				
		$Serialno=1;
		$connew = 9;
		$head_cntr_column = "A";$head_hotel_column = "A";
		
		while($row = mysqli_fetch_object($EnqSql)){
		
		
			$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$row->id."' ");
			
			$FollowupSql = executeSql("SELECT B.*,B.lead_status AS F_STATUS,B.details as lastFollowupDesc,B.followup_close_type_id,B.dated as nextlead_date from `".TBL_DAILY_ENQUERY_DETAILS."` As B where   id = '".$max_id."' ");
			
			$FollowupSqlRow = $db->fetch_assoc2($FollowupSql);
			
			
			
			$head_order_data1 = "A";
			
			$head_order_data = "A";       
		
		
		$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
		$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
		$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	
	
	    $mergeCityrow	=	$connew;
		//$SCount	=	$Serialno++;
	           
		
                              
	$Incentive_status	=	$row->current_status;
	if($Incentive_status==1){
	$Incentive_status='Verified By GM - S & M';
	}elseif($Incentive_status==2){
	$Incentive_status='Not Approved';		
	}elseif($Incentive_status==3){
	$Incentive_status='Verified By Hotel';		
	}else{
	$Incentive_status='Pending For Approval';
	}
	
	    
		$max_id=selectColumn(TBL_INCENTIVE_DETAILS,'MAX(id)',"WHERE  id_incentive = '".$row->id."' ");
		
		$FollowupSql = executeSql("SELECT B.* from `".TBL_INCENTIVE_DETAILS."` As B where   id = '".$max_id."' ");
		
		$FollowupSqlRow = $db->fetch_assoc2($FollowupSql); 
		//echo '<pre>';print_r($FollowupSqlRow);
		
		$enqueryIdContact	= selectColumn(TBL_DAILY_ENQUERY,'id_contact'," WHERE `id` = '".$row->id_enquiry."'");
		$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($enqueryIdContact)."'",''); 
		
		$resultContact = $db->fetch_object2($resContact);
		
		$contactNameMobile	=	''.$resultContact->first_name.' '.$resultContact->last_name.' <br>'.$resultContact->mobile;
		
		$enqueryIdCompany	= selectColumn(TBL_DAILY_ENQUERY,'id_company'," WHERE `id` = '".$row->id_enquiry."'");
		
		$LeadFollowupDesc	= selectColumn(TBL_DAILY_ENQUERY,'details'," WHERE `id` = '".$row->id_enquiry."'");
		
		$created_by	= selectColumn(TBL_DAILY_ENQUERY,'created_by'," WHERE `id` = '".$row->id_enquiry."'");
		
		$assign_user_id	= selectColumn(TBL_DAILY_ENQUERY,'assign_user_id'," WHERE `id` = '".$row->id_enquiry."'");
		//$approved_amount	= selectColumn(TBL_DAILY_ENQUERY,'approved_amount'," WHERE `id` = '".$row->id_enquiry."'");
		
		$EnqDetailsmax_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$row->id_enquiry."' ");
		$enquiry_close_summary	= selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `id` = '".$EnqDetailsmax_id."'");			
		
		
		if($row->current_status==3){						
		if($row->payment_status==2){  $Fstatus	=	'Paid'; } else{ $Fstatus	= 'Pending';}
		
		}else{							
		$Fstatus	= '-';
		}
		
		$hotel_percentage	= selectColumn('fs_incentive_participate_hotel','hotel_percentage'," WHERE `hotel_id` = '".$row->hotel_id."' AND id_shop='".addslashes($_SESSION['shop'])."' AND status=1");	
		$executive_percentage	= selectColumn('fs_incentive_participate_hotel','executive_percentage'," WHERE `hotel_id` = '".$row->hotel_id."' AND id_shop='".addslashes($_SESSION['shop'])."' AND status=1");				
		
		$hotel_percentage= ($row->approved_amount*$hotel_percentage)/100;
		$executive_percentage= ($row->approved_amount*$executive_percentage)/100;
		
		$hotel_access	=selectColumn(TBL_USERS,'hotel_access'," WHERE `id` = '".$created_by."'");
		
		//$CreatedUserhotel_access=			selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE  id IN (".$list->hotel_access.")   AND id_shop='".addslashes($_SESSION['shop'])."'");
		
		$HotelAccessSql = executeSql("SELECT * from `".TBL_HOTELS."`  where  id IN ('".$hotel_access."') AND id_shop='".addslashes($_SESSION['shop'])."'");
		$CreatedUserhotel_access=array();
		$CreatedUserBankdetails=array();
		while($HotelAccessSqlRow = $db->fetch_assoc2($HotelAccessSql)){
			array_push($CreatedUserhotel_access,$HotelAccessSqlRow['name'].'-'.$HotelAccessSqlRow['city']);
			array_push($CreatedUserBankdetails,$HotelAccessSqlRow['bank_detail']);
			//$CreatedUserhotel_access[]	   = $HotelAccessSqlRow['name'];
			//$CreatedUserBankdetails[]		= $HotelAccessSqlRow['bank_detail'];
			}
		$Createdaccess= implode(',',$CreatedUserhotel_access);
		$CreatedUserBankdetails= implode(',',$CreatedUserBankdetails);
		
		$daysNew =  abs((strtotime($row->checkin) - strtotime($row->checkout))/ 86400 );
		if($daysNew == '0'){
			$no_of_days = '1';
		}else {
			$no_of_days = $daysNew;
		}
							
$objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(false);
$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':P'.$connew)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++)
->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->date_created)))
->setCellValue($head_order_data++ . $connew, ($row->hotel_id!=0?selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE `id` = '".$row->hotel_id."'"):"Direct"))
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$created_by."'"))
->setCellValue($head_order_data++ . $connew, $Createdaccess)
->setCellValue($head_order_data++ . $connew,($enqueryIdCompany!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$enqueryIdCompany."'"):"Direct"))
->setCellValue($head_order_data++ . $connew,$row->guest_name)
->setCellValue($head_order_data++ . $connew,date('d M Y',strtotime($row->checkin)))
->setCellValue($head_order_data++ . $connew,date('d M Y',strtotime($row->checkout)))
->setCellValue($head_order_data++ . $connew,$row->no_room)
->setCellValue($head_order_data++ . $connew,$no_of_days*$row->no_room)
->setCellValue($head_order_data++ . $connew,$row->approved_amount)
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$assign_user_id."'"))
->setCellValue($head_order_data++ . $connew, $enquiry_close_summary)
->setCellValue($head_order_data++ . $connew, $FollowupSqlRow['remarks']?$FollowupSqlRow['remarks']:' - ')

->setCellValue($head_order_data++ . $connew, $Incentive_status);
/*->setCellValue($head_order_data++ . $connew,$row->approved_amount)
->setCellValue($head_order_data++ . $connew,$executive_percentage)
->setCellValue($head_order_data++ . $connew,$hotel_percentage)
->setCellValue($head_order_data++ . $connew,$FollowupSqlRow['remarks']?$FollowupSqlRow['remarks']:' - ')
->setCellValue($head_order_data++ . $connew, $CreatedUserBankdetails?$CreatedUserBankdetails:'-')
->setCellValue($head_order_data++ . $connew, $Fstatus);*/
   
$objPHPExcel->getActiveSheet()->getStyle('A6:P6')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A7:P7')->applyFromArray($styleThinBlackBorderOutline);
	$connew++;	
		
 
 
		
		//$UserCityReAssin  =$UserCity2;
		//$Serialno =$EndSerialno++;
	}
		$forTotal = 'C';
		$totalArray = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '1e51bf'),
	        'size'  => 12,
	        'name'  => 'Verdana'
	    ));
				
	}//exit;
		$objPHPExcel->getActiveSheet()->setTitle('Incentive Summary Report');
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
/*$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);	
		
	*/		
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setTop(0.50);
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setRight(0.10);
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setLeft(0.15);
$objPHPExcel->getActiveSheet()
    ->getPageMargins()->setBottom(1);	
$objPHPExcel->getActiveSheet()
    ->getPageSetup()
    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()
    ->getPageSetup()
    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	
	$fileName = 'Sales_Lead_Award_Summary_Report '.date('d_M_Y');
		ob_end_clean();
		if(!isset($cron)){
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
	
	}
}
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Sales Lead Award Manager<small>Sales Lead Award Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Sales Lead Award</li>
    </ol>
  </section>
  
  <!-- Main content -->
  
  <section class="content">
    <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">
        <div class="form-group has-error" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (
            <?=$numRows;?>
            ) &nbsp;</small> </h3>
          
          <!-- <a title="Add Lead" class="pull-right btn btn-success" href="editEnquiry.php" style="color:#fff;font-weight:bold;">&nbsp;SEND LEAD</a>
-->
          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->
            
            <ul class="dropdown-menu" role="menu">
            </ul>
          </div>
        </div>
        
        <!-- /.box-header -->
        
        <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
          <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Hotel</label>
                <?php $companyDropDown = '<select class="form-control select2" name="id_hotel" >
											    <option value="">Select Hotel</option>';
											  $resCat = selectSql(TBL_HOTELS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name!='' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_hotel'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>
              </div>
            </div>
            <div class="form-group col-sm-4">
              <label for="hotel_id" >Source Hotel</label>
              <?php 
				$categoryDropDown = '<select name="sourcehotel_id" id="sourcehotel_id" class="form-control select2">
									 					  <option value="">Select Hotel</option>';
	  $resCat = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['sourcehotel_id'] == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
              <span id="hotelError"></span> </div>
            <!-- /.col -->
            <div class="col-md-4">
              <div class="form-group">
                <label>Source</label>
                <?php $levelDropDown = '<select class="form-control select2" name="user_id">
											    <option value="">Select user</option>';
											 if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_USERS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_USERS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if($_REQUEST['user_id']== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$levelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $levelDropDown .= '</select>';
												  ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <?php 
					if($_REQUEST['lead_status'] == '1'){
							$selected1 = 'selected="selected"';
					}elseif($_REQUEST['lead_status'] == '2'){
							$selected2 = 'selected="selected"';
					}elseif($_REQUEST['lead_status'] == '3'){
							$selected3 = 'selected="selected"';
					}elseif($_REQUEST['lead_status'] == '0'){
							$selected0 = 'selected="selected"';
					}
				  echo $statusDropDown = '<select class="form-control select2" name="lead_status"> <option value="">All </option>
				  <option '.$selected1.' value="1">Verified By GM - S & M</option>
				  <option '.$selected2.' value="2">Not Approved</option>
				  <option '.$selected3.' value="3">Verified By Hotel</option>
				  <option '.$selected0.' value="0">Pending For Approval</option>
				  </select>';?>
              </div>
              
              <!-- /.form-group --> 
              
            </div>
            <div class="form-group col-sm-4">
              <label for="booking_date">
                <input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{echo 'checked="checked"';}?>/>
                &nbsp;Claim Date  : From - To</label>
              <div class="input-group">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Enter booking date" id="booking_date" name="booking_date" value="<?php if($_REQUEST) echo $_REQUEST['booking_date'];?>">
              </div>
              
              <!-- /.input group --> 
              
            </div>
            <div class="form-group col-sm-4">
              <label for="reservation_date">
                <input type="radio" name="checkin_radio" value="1" <?php if($_REQUEST['checkin_radio']=='1'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='2'  ){}?>/>
                &nbsp; Checkin - Checkout Date </label>
              <div class="input-group">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
              </div>
              
              <!-- /.input group --> 
              
              <span id="reservation_dateError"></span> </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Payment Status</label>
                <?php 
					if($_REQUEST['search_payment_status'] == '1'){
							$selectedpayment1 = 'selected="selected"';
					}elseif($_REQUEST['search_payment_status'] == '2'){
							$selectedpayment2 = 'selected="selected"';
					}
					
				  echo $statusDropDown = '<select class="form-control select2" name="search_payment_status"> 
				  <option value="">All </option>
				  <option '.$selectedpayment1.' value="1">Pending</option>
				  <option '.$selectedpayment2.' value="2">Paid</option>
				 
				  </select>';?>
              </div>
              <!-- /.row --> 
              
            </div>
          </div>
          
          <!-- /.box-body -->
          
          <div class="box-footer">
            <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;"  />
            &nbsp;&nbsp;&nbsp;
            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" style="margin-left:10px;float:left;" />
            <a title="Clear" class="pull-left btn btn-success" href="manageIncentive.php" style="margin-left:10px;color:#fff;font-weight:bold; float:left;">&nbsp;Clear</a> </div>
        </form>
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Lead List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            
            <!-- /.box-header -->
            
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th >Inc ID.&nbsp;</th>
                    <th> Claim Date </th>
                    <th>Source Hotel Name </th>
                    <th>Source Executive </th>
                    <th> Lead Closed By</th>
                    <th> Last Updated </th>
                    <th>Handled By </th>
                    <th> Hotel Name </th>
                    <th> Company Name </th>
                    <th>Last Remarks </th>
                    <th> Status</th>
                    <?php /*?> <th> Payment Status</th><?php */?>
                    <th > Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
				  
							 				
				if($total > 0){$counter = 1;
				
				$Expand = 1;
				  while($row = $db->fetch_object()){
					  
			$Incentive_status	=	$row->current_status;
			if($Incentive_status==1){
					$Incentive_status='Verified By GM - S & M';
				}elseif($Incentive_status==2){
					$Incentive_status='Not Approved';		
				}elseif($Incentive_status==3){
					$Incentive_status='Verified By Hotel';		
				}else{
					$Incentive_status='Pending For Approval';
					}
					 
					$max_id=selectColumn(TBL_INCENTIVE_DETAILS,'MAX(id)',"WHERE  id_incentive = '".$row->id."' ");
					$FollowupSql = executeSql("SELECT B.* from `".TBL_INCENTIVE_DETAILS."` As B where   id = '".$max_id."' ");
					$FollowupSqlRow = $db->fetch_assoc2($FollowupSql); 
					  
					  
//B.lead_status AS F_STATUS,B.details as lastFollowupDesc,B.followup_close_type_id,B.dated as nextlead_date
					  
					  $Expand;
		$created_by	= selectColumn(TBL_DAILY_ENQUERY,'created_by'," WHERE `id` = '".$row->id_enquiry."'");
		$hotel_access	=selectColumn(TBL_USERS,'hotel_access'," WHERE `id` = '".$created_by."'");
		
		//$CreatedUserhotel_access=			selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE  id IN (".$list->hotel_access.")   AND id_shop='".addslashes($_SESSION['shop'])."'");
		
		$HotelAccessSql = executeSql("SELECT * from `".TBL_HOTELS."`  where  id IN ('".$hotel_access."') AND id_shop='".addslashes($_SESSION['shop'])."'");
		$CreatedUserhotel_access=array();
		$CreatedUserBankdetails=array();
		while($HotelAccessSqlRow = $db->fetch_assoc2($HotelAccessSql)){
			array_push($CreatedUserhotel_access,$HotelAccessSqlRow['name'].'-'.$HotelAccessSqlRow['city']);
			array_push($CreatedUserBankdetails,$HotelAccessSqlRow['bank_detail']);
			//$CreatedUserhotel_access[]	   = $HotelAccessSqlRow['name'];
			//$CreatedUserBankdetails[]		= $HotelAccessSqlRow['bank_detail'];
			}
		$SourceHotelNameCreated= implode(',',$CreatedUserhotel_access);
 
					  ?>
                  <div data-role="header">
                
                <tr> 
                  
                  <!-- <td>
                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>-->
                  <td><?php echo $row->id;   ?></td>
                  <td><?php echo date('d M Y',strtotime($row->date_created));   ?></td>
                  <td><?php echo $SourceHotelNameCreated;?></td>
                  <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$created_by."'"); ?></td>
                  <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'");  ?></td>
                  <td><?php echo date('d M Y',strtotime($FollowupSqlRow['dated']));   ?></td>
                  <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_currently_with."'");  ?></td>
                  <?php
					
					$enqueryIdContact	= selectColumn(TBL_DAILY_ENQUERY,'id_contact'," WHERE `id` = '".$row->id_enquiry."'");
					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($enqueryIdContact)."'",''); 
			
					$resultContact = $db->fetch_object2($resContact);
			
					$contactNameMobile	=	''.$resultContact->first_name.' '.$resultContact->last_name.' <br>'.$resultContact->mobile;
					
					$enqueryIdCompany	= selectColumn(TBL_DAILY_ENQUERY,'id_company'," WHERE `id` = '".$row->id_enquiry."'");
								?>
                  <td><?php echo ($row->hotel_id!=0?selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE `id` = '".$row->hotel_id."'"):"Direct");  ?></td>
                  <td><?php echo ($enqueryIdCompany!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$enqueryIdCompany."'"):"Direct").' - '.$contactNameMobile;  ?></td>
                  <td ><?php  echo $FollowupSqlRow['remarks']?$FollowupSqlRow['remarks']:' - '; ?>
                  <td ><?php echo $Incentive_status; ?></td>
                  <?php /*?>  <td ><?php  if($row->current_status==3){
						
						 if($row->payment_status==2){ echo 'Paid'; } else{ echo 'Pending';}
						 
						}else{
							
							echo '-';
							}?></td><?php */?>
                  <td>&nbsp;
                    <?php 
?>
                    <a href="editEnquiry.php?action=edit&eId=<?=encryptor('encrypt',$row->id_enquiry)?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>
                    <?php if($row->current_status==3){?>
                    <?php /*?> <a  href="javascript:void(0);" id="viewbtn" onclick="OpenViewPopup(<?php echo $row->id; ?>);" title="Incentive Payment"><i class="fa fa-credit-card" aria-hidden="true"></i></a><?php */?>
                    <?php }?></td>
                </tr>
                <?php $Expand++;
				  
				  	}?>
                <tr>
                  <td align="right" colspan="12"><?php  echo $pagging->getLinks();?></td>
                </tr>
                <?php }else {?>
                <tr>
                  <td height="200" align="center" colspan="12">---- No Record Found ---- </td>
                </tr>
                <?php }?>
                  </tbody>
                
              </table>
            </div>
          </form>
          
          <!-- /.box-body --> 
          
        </div>
        
        <!-- /.box --> 
        
      </div>
      
      <!-- /.col --> 
      
    </div>
    
    <!-- /.row --> 
    
  </section>
  
  <!-- /.content --> 
  
</div>
<div id="viewincPopUp" class="well" style="display:none; background-color:#fff;">
  <div id="EditClaimIncentiveForm">
    <div class="box-header with-border">
      <h3 class="box-title">Sales Lead Award Payment </h3>
      <div class="box-tools pull-right">
        <button type="button" class="viewincPopUp_close btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> </div>
    <div id="cars2" class="desc" >
      <form id="paymentPopUpForm" class="paymentPopUpForm" data-parsley-validate autocomplete="off">
        <input type="hidden" name="id_incentive" id="id_incentive" value="">
        <div id="DisplayIncentiveCheckBox"></div>
        <div class="form-group">
          <div class="form-group">
            <label for="pkg_title" style="float:left;">Payment Status</label>
            <select name="payment_status"  id="payment_status" class="form-control input-sm" data-parsley-required>
              <option value="">Select Payment Status</option>
              <option  value="1">Pending</option>
              <option value="2">Paid</option>
            </select>
          </div>
          <div class="form-group">
            <label for="pkg_title" style="float:left;">Remarks</label>
            <textarea   name="remarks" id="remarks" class="form-control" placeholder="remarks"  data-parsley-required automcomplete="off"></textarea>
          </div>
          <br/>
          <div class="form-group col-sm-12" style="float:left;">
            <button class="btn btn-primary" onclick="updatePaymentStatusform();" type="button">Save</button>
            &nbsp;
            <button class="viewincPopUp_close btn btn-default">Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
  <div id="rateUpdateData"></div>
  <button class="my_popup_close btn btn-default pull-right">Close</button>
</div>
<script>
  
  function updatePaymentStatusform(){	
	
		
		
		var form=$("#paymentPopUpForm");
		if(form.parsley().validate()){
			$('.loading').show(); 
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxIncentivePaymentUpdate.php',
		   data: form.serialize(), 
		   success: function (result) {
			   
			   $('#viewincPopUp').popup('hide');
			   $( ".my_popup_open" ).click();			
				$( "#rateUpdateData" ).html(result);
		  /*if(result!=''){
		    $('#followup_close_summary').val('');
			$('#close_type').val('');
			$('#close_status').val('');
			$('#ColseSummaryPopUp').popup('hide');
			$( ".my_popup_open" ).click();	
		   $( "#FollowUpNextUpdate" ).html(result);
			 
			  }*/
			},
		 
		});
		return false;
		}
	}
function OpenViewPopup(id_incentive){	
					$('#viewincPopUp').popup('show');	
					$('#id_incentive').val(id_incentive);
	}
  
</script>
<?php include_once("includes/footer.php")?>
