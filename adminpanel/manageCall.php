<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');



/////////////////////////////////////////////////////////////////////////////////////



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



$lead_status	=	$_REQUEST['lead_status'];

//////////////////////////////////////////////////////////////////
$assignUserEnquiryId=array();
$assignUserSql = "SELECT * FROM ".TBL_DAILY_ENQUERY_DETAILS." WHERE id_user='".$_SESSION['userId']."' or `assign_user_id`='".$_SESSION['userId']."'  GROUP by enquiry_id  ";
$assignUserQuery= mysqli_query($connNew,$assignUserSql);

while($assignUserResult=mysqli_fetch_object($assignUserQuery)){
array_push($assignUserEnquiryId,$assignUserResult->enquiry_id);
}
$assignUserEnquiryId= implode(',',$assignUserEnquiryId);
//////////////////////////////////////////////////////////////////


$sql = " SELECT  DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A WHERE A.`id_shop` = '".addslashes($_SESSION['shop'])."' ";

$display_lead_summary=selectColumn('fs_user_levels','lead_summary_approval','WHERE id="'.$_SESSION['userLevel'].'" ');
if($_SESSION['userLevel']!='1'){
	
if($_SESSION['userLevel']!='1' && $display_lead_summary!='1' && !isset($_REQUEST['drilled_team'])){
	
	//$sql .= " AND (A.id_user='".$_SESSION['userId']."' )  ";
	$sql .= " AND A.id IN(".$assignUserEnquiryId.")  ";

}
elseif($_REQUEST['checkin_radio']==2){
	
	$sql .= " AND A.id_user IN (".$_REQUEST['drilled_team'].")";	

}
else{
	if($_SESSION['unit_user']!=2){
		$sql .= " AND A.assign_user_id IN (".$_REQUEST['drilled_team'].")";	}
	else{
		$sql .= " AND A.hotel_id IN (".$_SESSION['hotel_access'].")"; 
		}
}

}

if($lead_status != ''){

	//$sql .= " AND B.`lead_status` = '".$lead_status."'";

}



if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==1){	

	if($_REQUEST['reservation_date'] != ''){

		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);
		
		$reservation_date= explode(" to ",$_REQUEST['reservation_date']);
	$checkin = $reservation_date['0'];
	$checkout = $reservation_date['1'];	

		$sql .= " AND DATE(A.follow_up_date) >='".date('Y-m-d',strtotime($checkin))."' AND DATE(A.follow_up_date) <= '".date('Y-m-d',strtotime($checkout))."' ";

		$fromPrint = $checkin;

		$toPrint = $checkout;

	}

}else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==2){

	if($_REQUEST['booking_date'] != ''){

		//list($from_book,$to_book) = split(" to ",$_REQUEST['booking_date']);	
$booking_date= explode(" to ",$_REQUEST['booking_date']);
	$from_book = $booking_date['0'];
	$to_book = $booking_date['1'];
		

			$sql .= " AND DATE(A.created_date) >='".date('Y-m-d',strtotime($from_book))."' AND DATE(A.created_date) <= '".date('Y-m-d',strtotime($to_book))."' ";	

		

		$fromPrint = $from_book;

		$toPrint = $to_book;

	}

}



if($_REQUEST['id_hotel'] !="")

  $sql.=" AND A.hotel_id=".$_REQUEST['id_hotel']." ";



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

				 	 				 	 



		



		$objPHPExcel->getActiveSheet()->mergeCells('A6:M6');

		$head_hotel_row = 7;

		$head_cntr_column = "A";$head_hotel_column = "A";

		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Generation Date')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Source')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Updated on')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Handled By')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel Name')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Lead Description')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Remarks')			

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Next Follow Up')
			

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Close Type')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Revenue')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Status');

			

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

  

cellColor('A6:M6','254061');

cellColor('A7:M7','75923c');

	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	 $objPHPExcel->getActiveSheet()->getStyle('A6:M6')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->applyFromArray($styleArray_1);

	 $objPHPExcel->getActiveSheet()->getStyle('A7:M7')->getAlignment()->applyFromArray(

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

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(15);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(22);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(22);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(25);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(30);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(28);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(18);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(18);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(7);	

	

	$head_hotel_row++;

	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

						

				

		$Serialno=1;

		$connew = 8;

		$head_cntr_column = "A";$head_hotel_column = "A";

		

		while($row = mysqli_fetch_object($EnqSql)){

		

		

		$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$row->id."' ");

					$FollowupSql = executeSql("SELECT B.*,B.lead_status AS F_STATUS,B.details as lastFollowupDesc,B.followup_close_type_id,B.dated as nextlead_date from `".TBL_DAILY_ENQUERY_DETAILS."` As B where   id = '".$max_id."' ");

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

                    

                    

                   

                    

                    

                    

                    

                    

                    

 $objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(false);

$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->setActiveSheetIndex(0)

->setCellValue($head_order_data++ . $connew, $Serialno++)

->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->created_date)))

->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'"))

->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($FollowupSqlRow['created_date'])))

->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->assign_user_id."'"))

->setCellValue($head_order_data++ . $connew, ($row->hotel_id!=0?selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE `id` = '".$row->hotel_id."'"):"Direct"))

->setCellValue($head_order_data++ . $connew,($row->id_company!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"):"Direct"))

->setCellValue($head_order_data++ . $connew,$row->details)

->setCellValue($head_order_data++ . $connew,$LeadFollowupDesc)





->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->follow_up_date)))



->setCellValue($head_order_data++ . $connew,$FollowupCloseTypeRemarks)

->setCellValue($head_order_data++ . $connew, $FollowupSqlRow['revenue'])



->setCellValue($head_order_data++ . $connew, $lead_status); 







   

$objPHPExcel->getActiveSheet()->getStyle('A6:M6')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->applyFromArray($styleThinBlackBorderOutline);



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

              

              <div class="col-md-5">

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

              

              <!-- /.col -->

              

            <div class="col-md-5">



              <div class="form-group">



                <label>Status</label>				



				<?php 



					if($_REQUEST['status'] == '1'){



							$selected1 = 'selected="selected"';



					}elseif($_REQUEST['status'] == '0'){



							$selected0 = 'selected="selected"';



					}



				  echo $statusDropDown = '<select class="form-control select2" name="lead_status"> <option value="">Both</option>



				  <option '.$selected1.' value="1">Open</option>



				  <option '.$selected0.' value="0">Close</option>



				  </select>';?>



              </div>



              <!-- /.form-group -->



            </div>

            

            

            <div class="form-group col-sm-5">







                                <label for="booking_date"><input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{echo 'checked="checked"';}?>/>&nbsp;Generation Date  : From - To</label>







                                <div class="input-group">







                                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>







                                 <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Enter booking date" id="booking_date" name="booking_date" value="<?php if($_REQUEST) echo $_REQUEST['booking_date'];?>">







                                </div>







                                <!-- /.input group -->







                                </div>

                                

                                <div class="form-group col-sm-5">

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

<!--<th width="10%">S.No.&nbsp;</th>-->

<th> Generation Date </th>

<th>	Source</th>

<th>	Last Updated </th>	

<th>Handled By </th>

<th>	Hotel Name </th>

<th>	Company Name 	</th>

<th>Lead Description </th>

<th>Last Remarks </th>

<th>Next Follow Up </th>

<th>	Status</th>



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

				  while($row = $db->fetch_object()){

					  

					 

					$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$row->id."' ");

					$FollowupSql = executeSql("SELECT B.*,B.lead_status AS F_STATUS,B.details as lastFollowupDesc,B.followup_close_type_id,B.dated as nextlead_date from `".TBL_DAILY_ENQUERY_DETAILS."` As B where   id = '".$max_id."' ");

					$FollowupSqlRow = $db->fetch_assoc2($FollowupSql); 

					  

					  

					  

					  $Expand;

					  ?>

                  <div data-role="header">

                  <tr>

                   <!-- <td>

                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>-->

                    

                    <td><?php echo date('d M Y',strtotime($row->created_date));   ?></td>

                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'");  ?></td>

                    <td><?php echo date('d M Y',strtotime($FollowupSqlRow['created_date']));   ?></td>

                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->assign_user_id."'");  ?></td>

                    

                    <?php

					

		$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row->id_contact)."'",''); 

		$resultContact = $db->fetch_object2($resContact);

		$contactNameMobile	=	''.$resultContact->first_name.' '.$resultContact->last_name.' <br>'.$resultContact->mobile;

					?>

                    

                    

                    <td><?php echo ($row->hotel_id!=0?selectColumn(TBL_HOTELS,'CONCAT(name,"- ",city)'," WHERE `id` = '".$row->hotel_id."'"):"Direct");  ?></td>

                    <td><?php echo ($row->id_company!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"):"Direct").' - '.$contactNameMobile;  ?></td>

                    <td><?php echo $row->details;   ?></td>

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

						 $FollowupCloseTypeRemarks	=	selectColumn(TBL_CLOSING_MASTER,'name'," WHERE `id` = '".$FollowupSqlRow['followup_close_type_id']."'");

					   echo $LeadFollowupDesc= $FollowupCloseTypeRemarks.' - '.selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");

						 }

						// echo selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");}  ?></td>

                    

                    

                   

                    

                    <td ><?php echo date('d M Y',strtotime($row->follow_up_date));   ?></td>

                    <td ><?php echo $lead_status; ?></td>

                               

                

                    

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

                    <td align="right" colspan="12"><?php  echo $pagging->getLinks();?>

                    </td>

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

