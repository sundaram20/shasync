<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');



/////////////////////////////////////////////////////////////////////////////////////
// Set default date range for the last 7 days
$default_from_date = date('Y-m-d', strtotime('-7 days'));
$default_to_date = date('Y-m-d');
$default_booking_date = $default_from_date . ' to ' . $default_to_date;


//debugData($_REQUEST);


if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

	addslashes(encryptor('decrypt',$_REQUEST['delId']));

	

	checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'delete');

	

	executeSql("DELETE from `".TBL_DAILY_ENQUERY_DETAILS."` where type='4' and enquiry_id='".addslashes(encryptor('decrypt',$_REQUEST['delId']))."' ");

    executeSql("DELETE from `".TBL_DAILY_CALENDER."` where type='4' and visit_id='".addslashes(encryptor('decrypt',$_REQUEST['delId']))."' ");

	$delSql  = "DELETE from `".TBL_DAILY_ENQUERY."` where type='4' and id='".addslashes(encryptor('decrypt',$_REQUEST['delId']))."' ";



	

	

	if(executeSql($delSql)){		

		$err = 0;

		$_SESSION['successMsg'] = 'One Lead  has been deleted sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = 'Unable to delete Lead ';

	}

}



//$lead_status	=	$_REQUEST['lead_status'];

//////////////////////////////////////////////////////////////////
//$assignUserEnquiryId=array();
//$assignUserSql = "SELECT * FROM ".TBL_DAILY_ENQUERY_DETAILS." WHERE id_user='".$_SESSION['userId']."' or //`assign_user_id`='".$_SESSION['userId']."'  GROUP by enquiry_id  ";
//$assignUserQuery= mysqli_query($connNew,$assignUserSql);

//while($assignUserResult=mysqli_fetch_object($assignUserQuery)){
//array_push($assignUserEnquiryId,$assignUserResult->enquiry_id);
//}
//$assignUserEnquiryId= implode(',',$assignUserEnquiryId);
//////////////////////////////////////////////////////////////////


//$sql = "SELECT DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A LEFT JOIN `".TBL_DAILY_ENQUERY_DETAILS."` AS B ON A.id=B.enquiry_id WHERE A.`id_shop` = '".addslashes($_SESSION['shop'])."' AND A.type!=8";

$sql = "SELECT DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A 
        LEFT JOIN `".TBL_DAILY_ENQUERY_DETAILS."` AS B 
        ON B.id = (SELECT MAX(id) FROM `".TBL_DAILY_ENQUERY_DETAILS."` WHERE enquiry_id = A.id)
        WHERE A.`id_shop` = '".addslashes($_SESSION['shop'])."' AND A.type!=8";


//$display_lead_summary = selectColumn('fs_user_levels', 'lead_summary_approval', "WHERE id='".$_SESSION['userLevel']."'");




//if ($_SESSION['userLevel'] != '1') {
  //  if ($display_lead_summary != '1' && !isset($_REQUEST['drilled_team'])) {
    //    if (!empty($assignUserEnquiryId)) {
      //      $sql .= " AND A.id IN(".$assignUserEnquiryId.")";
       // } else {
         //   $sql .= " AND 1=0";
     //   }
   // } elseif (isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio'] == 2) {
    //    if (!empty($_REQUEST['drilled_team'])) {
     //       $sql .= " AND A.id_user IN (".$_REQUEST['drilled_team'].")";
    //    } else {
    //        $sql .= " AND 1=0";
    //    }
  //  } else {
       // if ($_SESSION['unit_user'] != 2) {
     //       if (!empty($_REQUEST['drilled_team'])) {
      //          $sql .= " AND A.assign_user_id IN (".$_REQUEST['drilled_team'].")";
     //       } else {
          //      $sql .= " AND 1=0";
          //  }
      //  } else {
       //     if (!empty($_SESSION['hotel_access'])) {
      //          $sql .= " AND A.hotel_id IN (".$_SESSION['hotel_access'].")";
        //    } else {
        //       $sql .= " AND 1=0";
         //   }
       // }
  //  }
//}

// Apply lead status filter
/*if (!empty($_REQUEST['lead_status'])) {
    $sql .= " AND B.`lead_status` = '".$_REQUEST['lead_status']."'";
}*/

if (isset($_REQUEST['lead_status']) && $_REQUEST['lead_status'] !== '') {
    $sql .= " AND B.`lead_status` = '".$_REQUEST['lead_status']."'";
}

// Apply default or user-selected date range for booking date
if (!isset($_REQUEST['checkin_radio']) || $_REQUEST['checkin_radio'] == 2) {
    // Use default date range if no booking_date is provided
    $booking_date = !empty($_REQUEST['booking_date']) ? $_REQUEST['booking_date'] : $default_booking_date;
    $booking_date = explode(" to ", $booking_date);
    $from_book = $booking_date[0];
    $to_book = $booking_date[1];
    
    $sql .= " AND DATE(A.dated) >= '".date('Y-m-d', strtotime($from_book))."' AND DATE(A.dated) <= '".date('Y-m-d', strtotime($to_book))."'";
    $fromPrint = $from_book;
    $toPrint = $to_book;
} elseif ($_REQUEST['checkin_radio'] == 1 && !empty($_REQUEST['reservation_date'])) {
    $reservation_date = explode(" to ", $_REQUEST['reservation_date']);
    $checkin = $reservation_date[0];
    $checkout = $reservation_date[1];
    
    $sql .= " AND DATE(A.follow_up_date) >= '".date('Y-m-d', strtotime($checkin))."' AND DATE(A.follow_up_date) <= '".date('Y-m-d', strtotime($checkout))."'";
    $fromPrint = $checkin;
    $toPrint = $checkout;
}

// Apply additional filters
if (!empty($_REQUEST['id_hotel'])) {
    $sql .= " AND A.hotel_id=".$_REQUEST['id_hotel'];
}
if (!empty($_REQUEST['id_created_by'])) {
    $sql .= " AND A.created_by=".$_REQUEST['id_created_by'];
}
if (!empty($_REQUEST['search_name'])) {
    $sql .= " AND A.id_company=".$_REQUEST['search_name'];
}
if (!empty($_REQUEST['id_contact'])) {
    $sql .= " AND A.id_contact=".$_REQUEST['id_contact'];
}

if (!empty($_REQUEST['id_mst_lead_source'])) {
    $leadSourceIds = array_filter($_REQUEST['id_mst_lead_source']); // remove empty values
    if(!empty($leadSourceIds)){
        $leadSourceIds = array_map('intval', $leadSourceIds); // sanitize
        $sql .= " AND A.id_mst_lead_source IN (".implode(',', $leadSourceIds).")";
    }
}

/*if (!empty($_REQUEST['id_mst_lead_source'])) {
    $sql .= " AND A.id_mst_lead_source=".$_REQUEST['id_mst_lead_source'];
}*/

if (!empty($_REQUEST['id_open_type']) && !empty($_REQUEST['id_close_type'])) {
    // Both selected — use OR
    $sql .= " AND (B.`followup_open_type_id` = '".$_REQUEST['id_open_type']."' 
              OR B.`followup_close_type_id` = '".$_REQUEST['id_close_type']."')";
} else if (!empty($_REQUEST['id_open_type'])) {
    // Only open type selected
    $sql .= " AND B.`followup_open_type_id` = '".$_REQUEST['id_open_type']."'";
} else if (!empty($_REQUEST['id_close_type'])) {
    // Only close type selected
    $sql .= " AND B.`followup_close_type_id` = '".$_REQUEST['id_close_type']."'";
}

if (!empty($_REQUEST['id_customer'])) {
    $sql .= " AND A.id_contact=".$_REQUEST['id_customer'];
}

$sql .= " ORDER BY id DESC";

// Debugging: Output the SQL query (remove in production)
//echo $sql;

// Execute query and handle pagination
$db->query($sql);
$numRows = $db->num_rows();
$pagging = new pagingClass($sql, $setpage);
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

	

	if($_REQUEST['checkin_radio']==1){

		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);	
		
		$reservation_date= explode(" to ",$_REQUEST['reservation_date']);
	$checkin = $reservation_date['0'];
	$checkout = $reservation_date['1'];

		$CreationOrBookingDate	=	'FollowUp Date From '.date('d M-Y',strtotime($checkin)).' To '.date('d M-Y',strtotime($checkout));

		}

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

		 $EnqSql = mysqli_query($conn,$sql);

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

				 	 				 	 



		



		$objPHPExcel->getActiveSheet()->mergeCells('A6:T6');

		$head_hotel_row = 7;

		$head_cntr_column = "A";$head_hotel_column = "A";

		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Generation Date')

            ->setCellValue($head_cntr_column++.$head_hotel_row, 'Time')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Updated on')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Handled By')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel Name')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Phone')
			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Email')
			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Lead Source')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Lead Description')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Remarks')			

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Next Follow Up')
			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Open Type')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Close Type')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Revenue')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Commission')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Status')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'IP Address')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'GCLID');

			

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

  

cellColor('A6:U6','254061');

cellColor('A7:U7','75923c');

	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	 $objPHPExcel->getActiveSheet()->getStyle('A6:T6')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7:T7')->applyFromArray($styleArray_1);

	 $objPHPExcel->getActiveSheet()->getStyle('A7:T7')->getAlignment()->applyFromArray(

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

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	

	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

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

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(10);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(22);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(22);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(25);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(30);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(28);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(28);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(18);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(18);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(18);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('O')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('P')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('Q')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('R')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('S')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('T')->setWidth(18);
    $objPHPExcel->getActiveSheet(1)->getColumnDimension('U')->setWidth(18);

	

	$head_hotel_row++;

	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

						

				

		$Serialno=1;

		$connew = 8;

		$head_cntr_column = "A";$head_hotel_column = "A";

		

		while($row = mysqli_fetch_object($EnqSql)){

		

	

		$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$row->id."' ");

					$FollowupSql = executeSql("SELECT B.*,B.lead_status AS F_STATUS,B.details as lastFollowupDesc,B.followup_close_type_id,B.followup_open_type_id,B.dated as nextlead_date from `".TBL_DAILY_ENQUERY_DETAILS."` As B where   id = '".$max_id."'  ");



					//echo $FollowupSql;die;

					$FollowupSqlRow = $db->fetch_assoc2($FollowupSql);

					

		$head_order_data1 = "A";

		$head_order_data = "A";       





		

		

		$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)

	);

		$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY)

	);

		$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

		$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);



	

	

	    $mergeCityrow	=	$connew;



		//$SCount	=	$Serialno++;

	



                    

                    

                    

		$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row->id_contacts)."'",''); 

		$resultContact = $db->fetch_object2($resContact);

		$NAme	=	$resultContact->first_name.' '.$resultContact->last_name;

					                    

                    

                   

                      if($FollowupSqlRow['F_STATUS']==1){

                         $lead_status='Open';

                       } 

                       else if($FollowupSqlRow['F_STATUS']==''){

                        $lead_status='';

                       }

                       else{

                        $lead_status='Close';

                       }



                       if($row->status==1){

                        $status='Active';

                       }

                       else{

                        $status='Inactive';

                       }



                    

                       if($FollowupSqlRow['F_STATUS']==1){

						   $LeadFollowupDesc= $FollowupSqlRow['lastFollowupDesc'];

						   $FollowupCloseTypeRemarks	='-';

						}else{ 

						

					$FollowupCloseTypeRemarks	=	selectColumn(TBL_CLOSING_MASTER,'name'," WHERE `id` = '".$FollowupSqlRow['followup_close_type_id']."'");

					$LeadFollowupDesc= selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");

					   }  

                    

                    

                   

                    

                    

                    
$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row->id_contact)."'",''); 

		$resultContact = $db->fetch_object2($resContact);

		$contactNameMobile	=	'- '.$resultContact->first_name.' '.$resultContact->last_name.' '.($resultContact->mobile!=''?'/ '.$resultContact->mobile:'');
			$full_name = ''.$resultContact->first_name.' '.$resultContact->last_name.'';
			$email=$resultContact->email?? '-';
           $mob=$resultContact->mobile?? '-';         

                    

                    

 $objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(false);

$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':U'.$connew)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->setActiveSheetIndex(0)

->setCellValue($head_order_data++ . $connew, $Serialno++)

->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->dated)))
->setCellValue($head_order_data++ . $connew, date('h:i A', strtotime($row->dated)))

->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'"))

->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($FollowupSqlRow['created_date'])))

->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->assign_user_id."'"))

->setCellValue($head_order_data++ . $connew, ($row->hotel_id!=0?selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'"):"Direct"))

->setCellValue($head_order_data++ . $connew,($row->id_company!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"):"Direct").'- '.$full_name)
	
	->setCellValue($head_order_data++ . $connew,$mob)
	
	->setCellValue($head_order_data++ . $connew,$email)
	
->setCellValue($head_order_data++ . $connew,($row->id_mst_lead_source!=''?selectColumn(TBL_LEAD_SOURCE_MASTER,'name'," WHERE `id` = '".$row->id_mst_lead_source."'"):"-"))
->setCellValue($head_order_data++ . $connew,$row->details)

->setCellValue($head_order_data++ . $connew,$LeadFollowupDesc)





->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->follow_up_date)))

->setCellValue($head_order_data++ . $connew, 
    ($FollowupSqlRow['F_STATUS']==1 ? 
        selectColumn(TBL_OPEN_MASTER,'name'," WHERE `id` = '".$FollowupSqlRow['followup_open_type_id']."'") 
        : '-'))

->setCellValue($head_order_data++ . $connew,$FollowupCloseTypeRemarks)

->setCellValue($head_order_data++ . $connew, $FollowupSqlRow['revenue'])
->setCellValue($head_order_data++ . $connew, $FollowupSqlRow['commission'])



->setCellValue($head_order_data++ . $connew, $lead_status)
->setCellValue($head_order_data++ . $connew, (!empty($row->ip_address) ? $row->ip_address : '-'))
->setCellValue($head_order_data++ . $connew, (!empty($row->gclid) ? $row->gclid : '-'));







   

$objPHPExcel->getActiveSheet()->getStyle('A6:T6')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A7:T7')->applyFromArray($styleThinBlackBorderOutline);



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

		$objPHPExcel->getActiveSheet()->setTitle('Lead Summary Report');

		

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

	

	$fileName = 'Lead_Summary_Report '.date('d_M_Y');

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

<style>
/* Fix multi-select height growth misaligning other filters */
.select2-container--default .select2-selection--multiple {
    min-height: 34px;
    max-height: 80px;
    overflow-y: auto;
}

.select2-container {
    width: 100% !important;
}

.box-body .row {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
}

.box-body .row .col-md-4,
.box-body .row .col-sm-4 {
    display: flex;
    flex-direction: column;
}

.box-body .row .col-md-4 .form-group,
.box-body .row .col-sm-4 .form-group {
    flex: 1;
}
	
	.wrapped-cell {
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.wrapped-cell:hover {
    overflow: visible;
    white-space: normal;
    word-break: break-all;
    background: #f8f9fa; /* Light highlight on hover */
    position: relative;
    z-index: 10;
}
}
	
</style>

<?php include_once("includes/left.php")?>
<div id="viewincPopUp" class="well" style="display:none; background-color:#fff;">
<div id="EditClaimIncentiveForm"></div>
</div>
<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Lead Manager<small>Lead Master</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Lead</li>

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

            <a title="Add Lead" class="pull-right btn btn-success" href="editEnquiry.php" style="color:#fff;font-weight:bold;">&nbsp;SEND LEAD</a>

          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>

            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->

            <ul class="dropdown-menu" role="menu">

              <?php /*?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_RATE;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>

								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_RATE;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>

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
                  <?php $hotelDropDown = '<select class="form-control select2" name="id_hotel" >

											    <option value="">Select Hotel</option>';

											  $resCat = selectSql(TBL_HOTELS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name!='' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['id_hotel'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

												}

											  }

											 	echo $hotelDropDown .= '</select>';

											  ?>

                </div>

              </div>

         
              <!-- /.col -->

              <div class="col-md-4">

                <div class="form-group">

                  <label>Source</label>

                  <?php $sourceDropDown = '<select class="form-control select2" name="id_created_by" >

											    <option value="">Select Source</option>';
                    //<?php //echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'");  

											  $resCat2 = selectSql(TBL_USERS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name!='' ",' ORDER BY `name`');
											  //echo $resCat2;

											  if($db->num_rows2($resCat2)){

											  	while($resultCat2 = $db->fetch_object2($resCat2)){

													if($_REQUEST['id_created_by'] == $resultCat2->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$sourceDropDown .= '<option '.$selected.' value="'.$resultCat2->id.'">'.ucfirst($resultCat2->name).'</option>';

												}

											  }

											 	echo $sourceDropDown .= '</select>';

											  ?>

                </div>

              </div>

              

              <!-- /.col -->
              
             <div class="col-md-4">
                <div class="form-group">
                  <label>Company</label>
                    <select class="form-control select2 itemName" name="search_name" id="search_name"   >

                  </select>
                 </div> 
              </div>
              <!-- /.col -->

            
              <!-- /.col -->
              
             <div class="col-md-4">
                <div class="form-group">
                  <label>Contact</label>
                 <select class="form-control select2 customerName" name="id_customer" id="id_customer"   >
                  </select>

                      </div>
              </div>
              <!-- /.col -->
				
				<div class="col-md-4">
    <div class="form-group">
        <label>Lead Source</label>
        <?php 
        $leadSourceDropDown = '<select class="form-control select2" name="id_mst_lead_source[]" multiple="multiple" style="width:100%;">
            <option value="">Select Lead Source</option>';
        $resLeadSource = selectSql(TBL_LEAD_SOURCE_MASTER, "where status=1 and id_shop='".addslashes($_SESSION['shop'])."'", ' ORDER BY `name`');
        if($db->num_rows2($resLeadSource)){
            while($resultLeadSource = $db->fetch_object2($resLeadSource)){
                // Check if this option is in the selected array
                $selected = (is_array($_REQUEST['id_mst_lead_source']) && in_array($resultLeadSource->id, $_REQUEST['id_mst_lead_source'])) ? 'selected="selected"' : '';
                $leadSourceDropDown .= '<option '.$selected.' value="'.$resultLeadSource->id.'">'.ucfirst($resultLeadSource->name).'</option>';
            }
        }
        echo $leadSourceDropDown .= '</select>';
        ?>
    </div>
</div>
				
				
				<!--<div class="col-md-4">
    <div class="form-group">
        <label>Lead Source</label>
        <?php 
        $leadSourceDropDown = '<select class="form-control select2" name="id_mst_lead_source">
            <option value="">Select Lead Source</option>';
        $resLeadSource = selectSql(TBL_LEAD_SOURCE_MASTER, "where status=1 and id_shop='".addslashes($_SESSION['shop'])."'", ' ORDER BY `name`');
        if($db->num_rows2($resLeadSource)){
            while($resultLeadSource = $db->fetch_object2($resLeadSource)){
                $selected = ($_REQUEST['id_mst_lead_source'] == $resultLeadSource->id) ? 'selected="selected"' : '';
                $leadSourceDropDown .= '<option '.$selected.' value="'.$resultLeadSource->id.'">'.ucfirst($resultLeadSource->name).'</option>';
            }
        }
        echo $leadSourceDropDown .= '</select>';
        ?>
    </div>
</div>-->
				
				<!-- Open Type Filter -->
<div class="col-md-4">
    <div class="form-group">
        <label>Open Type</label>
        <?php 
        $openTypeDropDown = '<select class="form-control select2" name="id_open_type">
            <option value="">Select Open Type</option>';
        $resOpenType = selectSql(TBL_OPEN_MASTER, "where status=1 and id_shop='".addslashes($_SESSION['shop'])."'", ' ORDER BY `name`');
        if($db->num_rows2($resOpenType)){
            while($resultOpenType = $db->fetch_object2($resOpenType)){
                $selected = ($_REQUEST['id_open_type'] == $resultOpenType->id) ? 'selected="selected"' : '';
                $openTypeDropDown .= '<option '.$selected.' value="'.$resultOpenType->id.'">'.ucfirst($resultOpenType->name).'</option>';
            }
        }
        echo $openTypeDropDown .= '</select>';
        ?>
    </div>
</div>

<!-- Close Type Filter -->
<div class="col-md-4">
    <div class="form-group">
        <label>Close Type</label>
        <?php 
        $closeTypeDropDown = '<select class="form-control select2" name="id_close_type">
            <option value="">Select Close Type</option>';
        $resCloseType = selectSql(TBL_CLOSING_MASTER, "where status=1 and id_shop='".addslashes($_SESSION['shop'])."'", ' ORDER BY `name`');
        if($db->num_rows2($resCloseType)){
            while($resultCloseType = $db->fetch_object2($resCloseType)){
                $selected = ($_REQUEST['id_close_type'] == $resultCloseType->id) ? 'selected="selected"' : '';
                $closeTypeDropDown .= '<option '.$selected.' value="'.$resultCloseType->id.'">'.ucfirst($resultCloseType->name).'</option>';
            }
        }
        echo $closeTypeDropDown .= '</select>';
        ?>
    </div>
</div>
				
				
				


            <div class="col-md-4">



              <div class="form-group">



                <label>Status</label>				



				<?php

					if($_REQUEST['lead_status'] == '1'){
							$selected1 = 'selected="selected"';
					}elseif($_REQUEST['lead_status'] == '0'){
							$selected0 = 'selected="selected"';
					}
				  echo $statusDropDown = '<select class="form-control select2" name="lead_status"> <option value="">Both</option>
				  <option '.$selected1.' value="1">Open</option>
				  <option '.$selected0.' value="0">Close</option>
				  </select>';?>
              </div>
              <!-- /.form-group -->



            </div>

            

            

            <div class="form-group col-sm-4">


                                <label for="booking_date"><input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{echo 'checked="checked"';}?>/>&nbsp;Generation Date  : From - To</label>







                                <div class="input-group">







                                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>







                                <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Enter booking date" id="booking_date" name="booking_date" data-parsley-required value="<?php if(isset($_REQUEST['booking_date']) && $_REQUEST['booking_date']!=''){echo $_REQUEST['booking_date'];}
                   else{ echo date('d-m-Y',strtotime('-7 days')).' to '.date('d-m-Y'); }?>">




                                </div>







                                <!-- /.input group -->







                                </div>

                                

                                <div class="form-group col-sm-4">

                    <label for="reservation_date"><input type="radio" name="checkin_radio" value="1" <?php if($_REQUEST['checkin_radio']=='1'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='2'  ){}?>/>&nbsp;FollowUp Date : From - To </label>







                    <div class="input-group">







                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>







                      <input type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">







					   







                    </div>







                    <!-- /.input group -->







                    <span id="reservation_dateError"></span> </div>

            

            

              <!-- /.row -->

            </div>

          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;"  /> &nbsp;&nbsp;&nbsp;

            

            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" style="margin-left:10px;float:left;" />

          <a title="Clear" class="pull-left btn btn-success" href="manageEnquiry.php" style="margin-left:10px;color:#fff;font-weight:bold; float:left;">&nbsp;Clear</a>   

          </div>

        </form>

      

 

       

 



  

        <div class="box">

          <div class="box-header">

            <h3 class="box-title">Lead List( <?php 
				if($_REQUEST['checkin_radio'] == 2){
					echo $_REQUEST['booking_date'];
				}elseif($_REQUEST['checkin_radio'] == 1){
					echo $_REQUEST['reservation_date'];
				}else{ echo date('d-m-Y',strtotime('-7 days')).' to '.date('d-m-Y'); }?> )</h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr>

<!--<th width="10%">S.No.&nbsp;</th>-->

<th> Generation Date & Time </th>

<th>	Source</th>

<th>	Last Updated </th>	

<th>Handled By </th>

<th>	Hotel Name </th>
					  
					  <th>Lead Source</th>

<th>	Company Name 	</th>
					  
<th style="display: none;"> Email </th>

<th>Lead Description </th>
					  
					  <th>Type</th>

<th>Last Remarks </th>

<th>Next Follow Up </th>

<th>	Status</th>

<th>IP Address</th>
<th>GCLID</th>

<th >	Action</th>





<!--<th> Generation Date </th>

<th>	Cerated By</th>

<th>	latest action Date</th>	

<th>Currently with</th>

<th>	lead for Hotel</th>

<th>	Company Name	</th>

<th>Description</th>

<th>	Follow up Date</th>

<th>	Follow upStatus</th>

<th>	Close Remarks</th>

<th >	Action</th>-->

              

		            

                    

                    <!--<th width="10%">

                      S.No.&nbsp;</th>

                    

                    <th>Company Name</th>

                    <th>Lead For Hotel</th>

                    <th>Lead Given By</th>

                    <th>Lead Given To</th>

                    <th>Description</th>

                    <th>Close Remark</th>

                    <th>Date</th>

                    <th>Follow up status</th> 

                    <th>status</th> 

                    <th>Action</th>-->

                  </tr>

                </thead>

                <tbody>

                  <?php 

				  

							 				

				if($total > 0){$counter = 1;

				

				$Expand = 1;

				if($_REQUEST['lead_status']!=''){

                   //	$lead_status=" AND lead_status='".$_REQUEST["lead_status"]."' ";

                   }

				  while($row = $db->fetch_object()){

					  

					 

					$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$row->id."' ");

					$FollowupSql = executeSql("SELECT B.*,B.lead_status AS F_STATUS,B.details as lastFollowupDesc,B.followup_close_type_id,B.dated as nextlead_date from `".TBL_DAILY_ENQUERY_DETAILS."` As B where   id = '".$max_id."'   ");

					//echo $FollowupSql;

					$FollowupSqlRow = $db->fetch_assoc2($FollowupSql); 

					  

					  

					  

					  $Expand;

					  ?>

                  <div data-role="header">

                  <tr>

                   <!-- <td>

                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>-->

                    

                    <td><?php echo date('d M Y H:i', strtotime($row->dated)); ?></td>

                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'");  ?></td>

                    <td><?php echo date('d M Y',strtotime($FollowupSqlRow['created_date']));   ?></td>

                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->assign_user_id."'");  ?></td>

                    

                    <?php

					

		$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row->id_contact)."'",''); 

		$resultContact = $db->fetch_object2($resContact);

		$contactNameMobile	=	''.$resultContact->first_name.' '.$resultContact->last_name.' <br>'.$resultContact->mobile;
		
					  $email = $resultContact->email;
					  
					?>

                    

                    

                    <td><?php echo ($row->hotel_id!=0?selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE `id` = '".$row->hotel_id."'"):"Direct");  ?></td>
					  
					  <td><?php echo ($row->id_mst_lead_source != '' ? selectColumn(TBL_LEAD_SOURCE_MASTER,'name'," WHERE `id` = '".$row->id_mst_lead_source."'") : '-'); ?></td>

                    <td><?php echo ($row->id_company!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"):"Direct").' - '.$contactNameMobile;  ?></td>
					  
					  <td style="display:none;"><?php echo $email; ?></td>

                    <td><?php echo $row->details;   ?></td>
					  
					  <td>
					  <?php 
if($FollowupSqlRow['F_STATUS']==1){
    $type = selectColumn(TBL_OPEN_MASTER,'name'," WHERE `id` = '".$FollowupSqlRow['followup_open_type_id']."'");
    if(!empty($type)){
        echo '<span class="label label-primary">'.$type.'</span>';
    } else {
        echo '-';
    }
} else {
    $type = selectColumn(TBL_CLOSING_MASTER,'name'," WHERE `id` = '".$FollowupSqlRow['followup_close_type_id']."'");
    if(!empty($type)){
        echo '<span class="label label-danger">'.$type.'</span>';
    } else {
        echo '-';
    }
}
?>
					  </td>

                    <?php

					

                      //$lead_status=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'lead_status'," WHERE `enquiry_id` = '".$row->id."'");

                      if($FollowupSqlRow['F_STATUS']==1){
                         $lead_status='Open';
                       } 

                       else if($FollowupSqlRow['F_STATUS']==''){
                        $lead_status='';
                       }
                       else{
                        $lead_status='Close';
                       }


                       if($row->status==1){
                        $status='Active';
                       }
                       else{
                        $status='Inactive';
                       }



                    ?>

                     <td ><?php   if($FollowupSqlRow['F_STATUS']==1){
						
						 echo $FollowupSqlRow['lastFollowupDesc'];
		
						}else{ 

					   echo $LeadFollowupDesc= selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");

						 }

						// echo selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");}  ?></td>

                    

                    

                   

                    

                    <td ><?php echo date('d M Y',strtotime($row->follow_up_date));   ?></td>

                    <td ><?php echo $lead_status; ?></td>
<td><?php echo !empty($row->ip_address) ? htmlspecialchars($row->ip_address) : '-'; ?></td>

					  <!--<td class="wrapped-cell" title="<?php echo htmlspecialchars($row->gclid); ?>"><?php echo !empty($row->gclid) ? htmlspecialchars($row->gclid) : '-'; ?></td>-->
					  
					  <td style="max-width: 150px;">
    <?php if (!empty($row->gclid)): ?>
        <span class="gclid-text" style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
            <?php echo htmlspecialchars($row->gclid); ?>
        </span>
        <a href="javascript:void(0);" onclick="toggleGclid(this)" style="font-size: 10px; color: #007bff; cursor: pointer;">Read More</a>
    <?php else: ?>
        -
    <?php endif; ?>
</td>
                               

                

                    

                        <td>

                        &nbsp;                     
<?php 
?>
                        <a href="editEnquiry.php?action=edit&eId=<?=encryptor('encrypt',$row->id)?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>
                       
                       
                        <?php 
						
						
			 $incentive_module_approved	=selectColumn(TBL_SHOP,'incentive_module_approved'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
			if($incentive_module_approved	==1){
			
			
						
						
						$incentiveSql = executeSql("SELECT * from `".TBL_INCENTIVE."` where  id_enquiry = '".$row->id."' ");
						if(num_rows($incentiveSql) > 0){
							$incentiveSqlRow = $db->fetch_assoc2($incentiveSql);

						 $IncentiveData= "'".$incentiveSqlRow['id']."','".date('d M Y',strtotime($row->created_date))."','".$row->hotel_id."'";
						
						?>
 						                   
					<a  href="javascript:void(0);" id="viewbtn" onclick="OpenViewPopup(<?php echo $IncentiveData; ?>);"><img style="width:30px;height:30px;" src="icon/cashpay.png"></a>

                       <?php } }?>

                 <?php if($_SESSION['userLevel']=='1'){?>

                        &nbsp;	

                        <a href="javascript:void(0)" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageEnquiry.php?delId=<?=encryptor('encrypt',$row->id)?>&action=delete&page=<?=$_REQUEST['page']?>';}" title="Delete"><i class="fa fa-remove" ></i></a> 

                        

                        

                <?php } ?>

                        </td>

                  </tr>

                

                 

                 

                 

                  <?php $Expand++;

				  

				  	}?>

                  <tr>

                    <td align="right" colspan="14"><?php  echo $pagging->getLinks();?>

                    </td>

                  </tr>

                  <?php }else {?>

                  <tr>

                    <td height="200" align="center" colspan="14">---- No Record Found ---- </td>

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

  

  <div id="duplicate" class="well" style="display:none;">

    

  </div>
  
<script>
function OpenViewPopup(id_incentive,enquiryDate,id_hotel_md){

	

	/*var enquiryDate		=	$("#enquiryDate").val();
	var id_hotel_md	=	$("#id_hotel_md").val();
	
			
		*/
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxIncentiveViewForm.php',
				data: 'selectType=view&id_hotel_md='+id_hotel_md+'&enquiryDate='+enquiryDate+'&id_incentive='+id_incentive, 
				success: function (result) {
					$('#viewincPopUp').popup('show');
					$('#EditClaimIncentiveForm').html(result);	
					
				}
		});	
					
			
		
	
	}

  
</script>




  <?php include_once("includes/footer.php")?>

<script>
	
	

  //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
     $('.itemName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCompanyName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================

	  //Customer AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
     $('.customerName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCustomerName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //Customer AUTO COMPLETE END==================================================================
	
	function toggleGclid(btn) {
    var textSpan = btn.previousElementSibling;
    if (textSpan.style.whiteSpace === "nowrap") {
        // Expand
        textSpan.style.whiteSpace = "normal";
        textSpan.style.wordBreak = "break-all";
        btn.innerHTML = "Read Less";
    } else {
        // Collapse
        textSpan.style.whiteSpace = "nowrap";
        btn.innerHTML = "Read More";
    }
}
	</script>