<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');

	// ----------cate---------
	
	
	$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
	
	if($_REQUEST['search_name'] != ''){
		$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}
	if($_REQUEST['hotelId'] != ''){
		
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.")";
	}
	if($_REQUEST['booking_status'] != ''){
		$booking_status_arr = implode(',',$_REQUEST['booking_status']);
		$cond .= " AND `".TBL_ORDERS."`.`booking_status` in (".$booking_status_arr.") ";
	}
	if($_REQUEST['company_id'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";
	}
	if($_REQUEST['guest'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";
	}
	if($_REQUEST['payment_status'] != ''){
		$payment_status_arr = implode(',',$_REQUEST['payment_status']);
		$cond .= " AND `".TBL_ORDERS."`.`payment_status` in (".$payment_status_arr.") ";
	}
	//checkin_radio
	
if($_REQUEST['reservation_date'] != ''){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);	
		$reservation_date= explode(" to ",$_REQUEST['reservation_date']);
		$checkin = $reservation_date['0'];
		$checkout = $reservation_date['1'];
		//$cond .= " AND `".TBL_ORDERS."`.`checkin` = '".date('Y-m-d',strtotime($checkin))."' and `".TBL_ORDERS."`.`checkout` = '".date('Y-m-d',strtotime($checkout))."'";
		if(strtotime($checkin)!=strtotime($checkout)){
			$tillcheckout = date ("Y-m-d", strtotime("-1 day", strtotime($checkout)));
			$cond .= " AND `".TBL_ORDERS."`.`checkin` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		}else{
			$cond .= " AND `".TBL_ORDERS."`.`checkin`='".date('Y-m-d',strtotime($checkin))."'";
		}
		
		$datewise_array = array();
		$checkinDate = date('Y-m-d',strtotime($checkin));
		
		 $checkoutDate = date('Y-m-d',strtotime($checkout));
		while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	

			$datewise_array[] = $checkinDate;
			$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
					
		}
		
		
}	


	
	
	if($_REQUEST['id_executive'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_executive` = '".addslashes($_REQUEST['id_executive'])."'";		
		
	}
	
	
	
	$start_date	=	date('Y-m-d',strtotime($_REQUEST['start_date']));
	$end_date	=	date('Y-m-d',strtotime($_REQUEST['end_date']));
	$pace_date	=	date('Y-m-d',strtotime($_REQUEST['pace_date']));
	
	
	$start_date_budget	=	date('d-m-Y',strtotime($_REQUEST['start_date']));
	$end_date_budget	=	date('d-m-Y',strtotime($_REQUEST['end_date']));
	
	
	

	$date=date_create($start_date);
	date_add($date,date_interval_create_from_date_string("-1 year"));
	$LYstart_date	=	date_format($date,"Y-m-d");
	
	
	$date_end=date_create($end_date);
	date_add($date_end,date_interval_create_from_date_string("-1 year"));
	$LYend_date	=	date_format($date_end,"Y-m-d");


	$LYstart_date	=date('Y-m-d',strtotime($LYstart_date));
	$LYend_date	=	date('Y-m-d',strtotime($LYend_date));

$ts1 = strtotime($start_date);
$ts2 = strtotime($end_date);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

	  	
	  
	  
	 echo  $sql	="SELECT `fs_orders`.id_order,`fs_order_detail`.room_id ,`fs_order_detail`.id_order_detail ,`fs_order_detail`.`dated` ,`fs_orders`.booking_status,`fs_users`.id as fs_user_id,`fs_users`.name as name_executive, `fs_order_detail`.hotel_id,`fs_order_detail`.room_quantity,


(SELECT SUM(`fs_budget_master`.`qty`) FROM `fs_budget_master` WHERE `fs_users`.id=`fs_budget_master`.id_user   AND `fs_order_detail`.hotel_id=`fs_budget_master`.id_hotel AND ( `fs_budget_master`.month between '".$start_date."' and '".$end_date."')) as budget_qty,


(SELECT SUM(`fs_budget_master`.`month_value`) FROM `fs_budget_master` WHERE `fs_users`.id=`fs_budget_master`.id_user   AND `fs_order_detail`.hotel_id=`fs_budget_master`.id_hotel AND ( `fs_budget_master`.month between '".$start_date."' and '".$end_date."')) as budget_value


 ,sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$LYstart_date."' and '".$LYend_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LY_ConfirmandTend`
 
 
,sum(case when (`booking_status` = '1' || `booking_status` = '2') and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `ConfirmandTend`


 ,sum(case when `booking_status` = '3' and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `booking_status` = '4' and ( ( `fs_order_detail` .dated between '".$start_date."' and '".$end_date."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled` 


			  FROM 
					`fs_users` left JOIN `fs_orders` ON `fs_users`.id=`fs_orders`.id_executive                      
		RIGHT JOIN
	  				`fs_order_detail` 
				ON
					 fs_orders.id_order=fs_order_detail.id_order 
			 WHERE 
			 		`fs_orders`.`id_shop` = '2'  AND `fs_orders`.`booking_status` in (1,2,3,4)
			group by 
			  		`fs_order_detail`.hotel_id,`fs_orders`.id_executive        
			ORDER BY 
					`fs_users`.id ASC
		  ";
		
		
	 
	
if($_POST['Download'] == 'Download'){
	error_reporting(1);
	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
	$rowShop = $db->fetch_object2($resShop);


	$db->query($sql);
	$numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	

	$datawisearrayFinal = array();			
	if($total > 0){
		
		$cntr_order= 0;
		
	}
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Akhil")
								 ->setLastModifiedBy("Akhil")
								 ->setTitle("Date wise Booking Report")
								 ->setSubject("Date wise Booking Report")
								 ->setDescription("Date wise Booking Report")
								 ->setKeywords("Date wise Booking Report")
								 ->setCategory("Report");



	
		// Add some data

	
	if($total > 0){$counter = 1;
	
	
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('C8', 'ROOM NIGHTS')
	->setCellValue('H8', 'ARR')
	->setCellValue('L8', 'TOTAL REVENUE')
	->setCellValue('P8', '% CONTRIBUTION')
	->setCellValue('A2', 'EXECUTIVEWISE PRODUCTIVITY REPORT')
	->setCellValue('B4', 'Period '.date("d-m-Y",strtotime($start_date)).' to '.date("d-m-Y",strtotime($end_date)));
	
	
		
	$objPHPExcel->getActiveSheet()->mergeCells('C8:G8');
	$objPHPExcel->getActiveSheet()->mergeCells('H8:K8');
	$objPHPExcel->getActiveSheet()->mergeCells('L8:O8');
	$objPHPExcel->getActiveSheet()->mergeCells('P8:Q8');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:Q2');
	$objPHPExcel->getActiveSheet()->mergeCells('B4:Q4');	
	$objPHPExcel->getActiveSheet()->mergeCells('E9:F9');
	$head_hotel_row = 10;

		//foreach($datawisearrayFinal as $dateCheckin=>$dateData){
			$head_cntr_column = "A";$head_hotel_column = "A";
				
			
			/*$objPHPExcel->getActiveSheet()->getStyle('B9')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->mergeCells('B12:B1');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
			*/
			
			
			
				
				$objPHPExcel->setActiveSheetIndex(0)
				
				->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'UNIT NAME')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Budget')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'This Year')				
				->setCellValue($head_cntr_column++.$head_hotel_row, 'V2B')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'GOLY %')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Budget')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'This Year')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'GOLY %')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'Budget')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'This Year')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'GOLY %')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'ROOM NIGHTS')
				->setCellValue($head_cntr_column++.$head_hotel_row, 'REVENUE');
				
function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

cellColor('H8:O8', 'F28A8C');


$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 15,
        'name'  => 'Verdana'
    ));

$styleArray_1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));



$styleArray_2 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));




$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleArray_1);
 $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_2);
 $objPHPExcel->getActiveSheet()->getStyle('C8:G8')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('H8:K8')->applyFromArray($styleArray_2);
 $objPHPExcel->getActiveSheet()->getStyle('H8:K8')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('L8:O8')->applyFromArray($styleArray_2);
 $objPHPExcel->getActiveSheet()->getStyle('L8:O8')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('P8:Q8')->applyFromArray($styleArray_2);
 $objPHPExcel->getActiveSheet()->getStyle('P8:Q8')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);



$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('C11:G11')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
	
 $styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '000'),
		),
	),
);	
$objPHPExcel->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('H8:K8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('L8:O8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('P8:Q8')->applyFromArray($styleThinBlackBorderOutline);




	
$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(33);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(7);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(9);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(8);	

$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(10);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('P')->setWidth(15);				 			 

				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":G".$head_hotel_row)->applyFromArray($styleThinBlackBorderOutline);
 $objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$head_hotel_row++;
					
					
						
	
	$connew	=$head_hotel_row;
	$Serialno=1;
	while($row = $db->fetch_object()){
	
	
	
	$head_order_data1 = "A";
	$head_order_data = "A"; 
  
  
if($row->fs_user_id==$OrderUserID){
	
	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++);	

	}else{   
	
	$Serialno=1;
	
	cellColor($head_order_data . $connew, 'F28A8C');
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data . $connew, 'EXECUTIVE NAME : '.$row->name_executive);


	$objPHPExcel->getActiveSheet()->mergeCells($head_order_data . $connew.':B' . $connew);
	$objPHPExcel->getActiveSheet()->getStyle($head_order_data . $connew.':B' . $connew++)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++);

	}
	
	
	




$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'"))
->setCellValue($head_order_data++ . $connew, $row->LY_ConfirmandTend)
->setCellValue($head_order_data++ . $connew, $row->budget_qty)
->setCellValue($head_order_data++ . $connew, $row->ConfirmandTend)
->setCellValue($head_order_data++ .   $connew, $row->ConfirmandTend-$row->LY_ConfirmandTend)

->setCellValue($head_order_data . $connew, $row->Cancelled);

	
	
	$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$connew.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);

$OrderUserID	=	$row->fs_user_id;


$connew++;				}
						
				$objPHPExcel->getActiveSheet()	
				->setCellValue('B'.$connew,  'Total' )		
					->setCellValue('C'.$connew,  '=SUM(C8:C'.($connew -1).')' )					
					->setCellValue('D'.$connew,  '=SUM(D8:D'.($connew -1).')' )					
					->setCellValue('E'.$connew,  '=SUM(E8:E'.($connew -1).')' )
					->setCellValue('F'.$connew,  '=SUM(F8:F'.($connew -1).')' )
					->setCellValue('G'.$connew,  '=SUM(G8:G'.($connew -1).')' );					
					
		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':G'.$connew)->getFont()->setBold(true);				
		 $objPHPExcel->getActiveSheet()->getStyle('B'.$connew)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
);	

$styleArray2 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('argb' => '000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':G'.$connew)->applyFromArray($styleArray2);
 $objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':G'.$connew)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	
			//}
	}
	$objPHPExcel->getActiveSheet()->setTitle('Pace Report');

								
	//$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="pace_report.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
 
}

if($_POST['Search'] == 'Search'){
	

	
	$db->query($sql);
	$numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	
	
	

}


?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Pace Report Manager
        <small>Pace Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pace Report Manager</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        <div class="box-header with-border">
          <h3 class="box-title">Pace Report &nbsp;</small> </h3>
		  <!--<div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="booknow.php?type=N" >Book Now</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php /*?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
							  
							  </ul>
							</div>-->
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
		  <div class="form-group col-sm-4">
                   
                    <div class="form-group">
                  <label for="start_date">Checkin Date from</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
					
            <div class="col-md-4">
              <div class="form-group">
               
					
				
                  <label for="end_date">Checkin Date To</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="end_date" name="end_date" value="<?php if($_POST) echo $_POST['end_date'];elseif($row->end_date) echo stripslashes(date('d-m-Y',strtotime($row->end_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_end_date;?>
                </div>
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->  
			
		  
		  
			<div class="col-md-4">
              <div class="form-group">
                  <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="pace_date" value="<?php if($_POST) echo $_POST['pace_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
              <!-- /.form-group -->
            </div>
			
			
			
			
			
          <!-- /.row -->
        </div>
		
			
			
		
					
				
			
				
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
       <input name="Search" type="submit" class="btn btn-primary" value="Search" /> <input name="Download" type="submit" class="btn btn-primary" value="Download" />
        </div>
		</form>		
      </div>
	  <style>
	  #example2 tbody tr td, #example2 tbody tr th{padding:2px;}
	  </style>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <!--<h3 class="box-title">Pace Report List</h3>-->
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                
                </thead>
                <tbody>
				<?php 		
				


				if($total > 0){$counter = 1;
				
				?>
				<tr><th colspan=5>Pace Report On : <?php echo dateformat_date($pace_date)?></th><td colspan=1></td></tr>
				
				
				<tr>
                  <th>Hotel Name</th>
				  <th>Last Year</th>
				  <th>Confirm</th>
				  
                  
				  <th>Waitlisted</th>
				  <th>Cancelled</th>
				  <th>Variance Vs LY</th> 
				    



                </tr>
				<?php
				while($row = $db->fetch_object()){
						
						
						
						
						?>

							<tr>
								<td><?php echo $row->HotelName;?></td>
                                <td><?php echo $row->LY_ConfirmandTend; ?></td>
								<td><?php echo $row->ConfirmandTend;?></td>
								<td><?php echo $row->Waitlisted;?></td>								
								<td><?php echo $row->Cancelled;?></td>
								<td><?php echo $row->ConfirmandTend-$row->LY_ConfirmandTend;?></td>
								
								
							
							</tr/>

						<?php
				}
				?>
				
				
					
				<?php
				
				?>
			   
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
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
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                   
<?php include_once("includes/footer.php")?>  